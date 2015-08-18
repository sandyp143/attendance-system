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

use Exception;

class AttendanceController extends Controller{

    public static $workHour = 8;
    function setAttendance()
    {

        try {

            $input = Input::all();
            $date = time();
            if ($input['mode'] === 'checkIn')
            {
                $checkIn = date('h:i:s', $date);
                $data['check_in']=$checkIn;

            }


            if ($input['mode'] === 'checkOut')
            {
                $checkOut = date('h:i:s', $date);
                $data['check_out']=$checkOut;
            }

            $data['month']=date('F', $date);
            $data['date'] =date('Y-m-d', $date);

//
//            if($checkIn){
//                $data['check_in']=$checkIn;
//                $data['month']=date('F', $date);
//                $data['date'] =date('Y-m-d', $date);
//            }
//
//            if($checkOut){
//                $data['check_out']=$checkOut;
//            }

            $attendance = new Attendance();
            $attendance->make($data);
            return Redirect::to('/auth/dashboard')->withFlashMessage( $input['mode'].' successful');


        } catch (Exception $e) {





            return Redirect::to('/auth/dashboard')->withFlashMessage($e->getMessage());
            //return Redirect::to('/admin/attendance')->withFlashMessage($a->getMessage());


        }
    }


        function getAttendance($member='')
        {
            $input = Input::all();

            if ($member == null or $member == '') {
                $memberId = Session::get('memberId');

            }else {
                $memberId = $member;
            }

            if (array_key_exists('date', $input)) {

                $year= date("Y", strtotime($input['date']));
                $startDate=$year.'-01-01';
                $endDate = $startDate.'12-31';

            } else {

                $year = date("Y");
                $startDate=$year.'-01-01';
                $endDate = $year.'-12-31';
            }

                $month=array_key_exists('month', $input)?$input['month']:date('F');
                $month = Month::ForMonth($month)->first();

            if(!empty($month)){

                $attendance = $month->attendances()->DateBetween($startDate,$endDate)->ForMember($memberId)->get();
                $attendances= $this->AverageMonthAttendance($attendance ,$month,$year);
                return $attendances;

            }
            else{




                return Redirect::to('/auth/dashboard')->withFlashMessage('No Attendnce for the month');

            }





        }

    function AverageMonthAttendance($attendance, $month, $year){

        for($i=1; $i<$month->days+1;$i++)
        {
            if($i<11) {
                $monthAttendance[$year . '-' . $month->id . '-0' . $i] = [];

            }
            else{
                $monthAttendance[$year . '-' . $month->id . '-' . $i] = [];
            }

            }
            foreach($attendance as $att) {
                $workHour[] =$att->working_hours;
                $monthAttendance[$att->date]=$att;
            }


        $total = count($attendance);
        $result['total'] =$total;
        $result['average']=round( $total / $month->days * 100, 2 );
        $result['month'] = $month->name;
        $result['daysAbsent']= $month->days - $total;
        $result['workingHour']= $this->addWorkHour($workHour);

        $result['attendances']=$monthAttendance;


        return $result;


    }

    function addWorkHour($hour){

        $hours=0;
        $minutes=0;
        $seconds=0;
        foreach($hour as $value){
            $sum= explode(":", $value);
            $hours += $sum[0];
            $minutes += $sum[1];
            $seconds += $sum[2];
        }

        $minutes += floor($seconds / 60);
        $seconds %= 60;

        $hours += floor($minutes /60);
        $minutes %= 60;
        $sum =[$hours,$minutes,$seconds];
        return implode(":",$sum);
    }

}