<?php

namespace App;



use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Exception;
use App\Organization;
use App\Member;

use Illuminate\Support\Facades\Hash;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];





    function member() {

        return $this->hasMany( 'App\Member');
    }

    function register($input){

        foreach($input as $key=>$value){

            $this->checkEmpty($key,$value);
        }


        $user = User::create([
            'first_name' => $input['first_name'], 'last_name' => $input['last_name'], 'email' => $input['email'],
            'password' => Hash::make($input['password'])]);

        $organization = Organization::OfName($input['organization_name'])->first();



        Member::create(['user_id' => $user->id, 'organization_id' => $organization->id]);







    }

    function checkEmpty($key,$value){
        if($value == null || $value =='')
        {
            throw new Exception( $key."  is required");

        }



    }
}
