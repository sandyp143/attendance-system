<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;

class Organization extends Model{

	protected $table = 'organizations';

    protected $fillable = ['name', 'country'];


    function checkOrganization($name){

        $organization = Organization::OfName($name)->first();
        if(empty($organization)) {

            throw new Exception("Organization doesnt exists");
        }

    }

    public function scopeOfName($query, $name)
    {
        return $query->where('name', $name);
    }


}