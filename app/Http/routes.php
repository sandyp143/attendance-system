<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('register');

});

Route::any('login', 'LoginController@login');
Route::get('/check', 'AttendanceController@setAttendance');
Route::get('/attendance/{member}', 'AttendanceController@getAttendance');
Route::get('/register','LoginController@register');
Route::get('/auth/dashboard', function () {
    return view('dashboard');

});



