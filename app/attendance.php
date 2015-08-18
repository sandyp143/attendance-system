<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\DateInvalidException as DateInvalidException;
use App\Exceptions\DateRequiredException as DateRequiredException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Month;
use Exception;

class Attendance extends Model
{

    protected $table = 'attendances';

    protected $fillable = ['date', 'check_in', 'check_out', 'member_id','month_id'];
    protected $appends = ['working_hours'];




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

        $in = strtotime($this->check_in);
        $out = strtotime($this->check_out);
        $seconds= round(abs($out - $in));

        $minutes = floor($seconds / 60);
        $seconds %= 60;

        $hours = floor($minutes /60);
        $minutes %= 60;
        $sum =[$hours,$minutes,$seconds];
        return implode(":",$sum);

           }


    function scopeForMember($query, $memberId) {
        return $query->whereMemberId($memberId);
    }


    function scopeDateBetween($query, $startDate,$endDate) {
         return $query->whereRaw("date BETWEEN '$startDate' AND '$endDate' ");

    }

    function scopeForUniqueCheck($query,$memberId,$date)
    {
        return $query->where('date','=',$date)->whereMemberId($memberId);
    }


//    function scopeForAllMonth($query){
//
//        return $query->select(DB::raw('month_id, count(id) as days_count GROUP BY month_id'));
//    }

    function make($data)
    {

        $month = Month::ForMonth( 'January')->first();

        $config['month_id'] = $month->id;

        $config['member_id'] =Session::get('memberId');
        $config['date'] = $data['date'];

        if (array_key_exists('check_in', $data)) {



            $config['check_in']=$data['check_in'];
            $attendance=Attendance::ForUniqueCheck( $config['member_id'],$config['date'])->first();
            if(empty($attendance)) {
                Attendance::create($config);
                         }
            else{
                throw new Exception('You Already have check In for the day');

            }

        }


        if (array_key_exists('check_out', $data)) {

            $config['check_out']=$data['check_out'];

//        foreach($data as  $key => $value)
//        {
//            $this->is_empty($value);
//        }
//        $this->is_valid($this->checkInFlag);
            $attendance=Attendance::ForUniqueCheck( $config['member_id'],$config['date'])->first();


            if(empty($attendance)){


                throw new Exception('You Havent check in yet');
            }
            if($attendance->check_out === '00:00:00') {

                $attendance->update($data);

            }
            else{

                throw new Exception('You Already have check out for the day');
            }
        }




    }

//    function is_empty($data){
//        if ($data == '' ||$data == null)
//        { throw new DateRequiredException("Date is Required");
//
//        }






}
