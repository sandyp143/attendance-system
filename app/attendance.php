<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\DateInvalidException as DateInvalidException;
use App\Exceptions\DateRequiredException as DateRequiredException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Month;

class Attendance extends Model
{

    protected $table = 'attendances';

    protected $fillable = ['date', 'check_in', 'check_out', 'member_id'];


    public static $checkInFlag = 0;

    function member()
    {
        return $this->belongsTo('App\User', 'member_id');
    }


    function setDateAttribute($date)
    {
        $this->attributes['date'] = date('Y-m-d', strtotime($date));
    }

    function setCheckInAttribute($time)
    {
        $this->attributes['check_in'] = date('h:i:s', strtotime($time));
    }

    function setCheckOutAttribute($time)
    {
        $this->attributes['check_out'] = date('h:i:s', strtotime($time));
    }

    function getWorkingHoursAttribute()
    {

        $diff = date(" H:i:s", $this->check_in - $this->check_out);
        return $diff;

    }


    function scopeForMember($query, $memberId) {
        return $query->whereMemberId($memberId);
    }


    function scopeDateBetween($query, $startDate,$endDate) {
         return $query->whereRaw("date BETWEEN '$startDate' AND '$endDate' ");
    }

    function scopeForAllMonth($query){

        return $query->select(DB::raw('month_id, count(id) as days_count GROUP BY month_id'));
    }

    function create($data)
    {

        if (array_key_exists('check_in', $data)) {
            $this->checkInFlag = 1;

            $month = Month::where('name', $data['month'])->first();
            $config[month_id] = $month->id;
            $config['member_id'] = Session::get(memberId);
            $config['date'] = $data['date'];
        }

//        foreach($data as  $key => $value)
//        {
//            $this->is_empty($value);
//        }
        $this->is_valid($this->checkInFlag);

        $Attendance = Attendance::firstOrCreate($config);
        $Attendance->update($data);

    }

//    function is_empty($data){
//        if ($data == '' ||$data == null)
//        { throw new DateRequiredException("Date is Required");
//
//        }


    function is_valid($checkInFlag)
    {

        if ($checkInFlag === 0) {
            throw new DateInvalidException("You should check in before check out");

        }

    }



}
