<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use App\Attendance;


class Month extends Model
{
    protected $table = 'months';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'days'];



    function attendances() {

        return $this->hasMany( 'App\Attendance','month_id');
    }

    function scopeForMonth($query, $month) {
        return $query->whereName($month);
    }
}
