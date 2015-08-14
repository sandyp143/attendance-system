<?php


namespace App\Http\Controllers;

use App\Month;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Organization;
use App\Attendance;

use App\Exceptions as DateException;

class AttendanceController extends Controller{


    function setAttendance()
    {

        try {

            $input = Input::all();
            $date = strtotime("now");
            if ($input['mode'] === 'checkIn')
            {
                $checkIn = date('h:i:s', $date);
            }

            if ($input['mode'] === 'checkOut')
            {
                $checkOut = date('h:i:s', $date);
            }



            if($checkIn){
                $data['check_in']=$checkIn;
                $data[month]=date('M', $date);
                $data['date'] =date('Y-m-d', $date);
            }

            if($checkOut){
                $data['check_out']=$checkOut;
            }

            $attendance = new Attendance();
            $attendance->create($data);


        } catch (DateException\DateInvalidException $e) {

            Session::flash('Error', $e->getMessage());

            return Redirect::to('/admin/attendance');
            //return Redirect::to('/admin/attendance')->withFlashMessage($a->getMessage());


        }
    }


        function getAttendance($member='')
        {
            $input = Input::all();

            if ($member == null or $member == '') {
                $memberId = Session::get('memberId');

            }
            $memberId = $member;

            if (array_key_exists('date', $input)) {

                $startDate = date("Y-m-d", strtotime($input['date']));
                $endDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($StartDate)) . " +365 day"));
            } else {
                $startDate = date("Y-m-d");
                $endDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($StartDate)) . " -365 day"));
            }

            if (array_key_exists('month', $input)) {

                $month = Month::ForMonth($input['month'])->first();
                $attendance = $month->attendances();
                $total =count($attendance);


            }


        }

    function AverageMonthAttendace($attendance, $month){


    }

}