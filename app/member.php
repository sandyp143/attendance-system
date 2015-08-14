<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model{

	protected $table = 'members';

    protected $fillable = ['organization_id', 'user_id'];

    function user() {
        return $this->belongsTo('App\User', 'user_id');
    }


    function organization() {
        return $this->belongsTo('App\Organization', 'organization_id');
    }

    function attendance(){

    	return $this->hasMany('App\Attendance');
    }
}