<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Organization;
use App\User;
use App\Member;
use Exception;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;


class LoginController extends Controller{

    protected $organization;
    protected $user;
    public function __construct(Organization $organization , User $user) {
        $this->organization = $organization;
        $this->user = $user;
    }

	public function login(Request $request){


        if ($request->isMethod('post'))
        {
            $validator = $this->getLoginValidator();
            if ($validator->passes()) {
                $credentials = $this->getLoginCredentials();

                if (Auth::attempt($credentials)) {
                    //return Redirect::route("user/profile");//
                    $user = Auth::user();
                    $member=$user->member()->first();

                    Session::put('memberId',$member->id);
                    return Redirect::to("auth/dashboard")->withFlashMessage( 'welcome '.$user->first_name);
                    ;
                }

                return Redirect::back()->withErrors(array("error" => array("email/password invalid") ))->withInput();
            }
            else
            {
                return Redirect::back()->withErrors($validator)->withInput();

            }
        }

        return View("/login");
    }



    protected function getLoginValidator()
    {
        return Validator::make(Input::all(), array(
            "email" => "required",
            "password" => "required"
        ));
    }


    protected function getLoginCredentials()
    {
        return array( "email" => Input::get("email"),"password" =>Input::get("password") );
    }





    public function register()
    {

        try {

            $input = Input::all();

            $organizationName = !empty($input['organization_name'])?$input['organization_name']:null;

            $this->organization->checkOrganization( $organizationName);
            $this->user->register($input);


            return redirect::to('/login')->withFlashMessage('Registration Successfull. Please Login with Credentials');



        } catch (Exception  $e) {
            return $e->getMessage();


            return redirect::to('/dashboard')->withFlashMessage($e->getMessage());

        }


    }

		
}


		



