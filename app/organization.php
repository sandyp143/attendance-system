<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model{

	protected $table = 'organizations';

    protected $fillable = ['name', 'country'];


    function checkOrganizationIsEmpty($name){

        $organization = Oraganization::OfName($name);

        return empty($organization)?true:false;

    }

    public function scopeOfName($query, $name)
    {
        return $query->where('name', $name);
    }


}