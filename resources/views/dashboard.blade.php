<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Untitled Document</title>
    <!-- Latest compiled and minified CSS -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script></head>

<body>


<div class="container"
<div class="page-header">
    <p class="navbar-text navbar-right">Signed in as <a href="#" class="navbar-link">{{Auth::user()->first_name}}</a></p>

    <h1>Your Dashboard </h1>
</div>
<div class="jumbotron">

    <h1>Take Your Attendance</h1>

    @if (Session::has('flash_message'))
        <div class="alert alert-danger">


            {{ Session::get('flash_message') }}

        </div>
    @endif


   <button type="button" class="btn btn-primary btn-lg" onclick="window.location='{{ URL::to('/check?mode=checkIn') }}'"> Check In</button>
   <button type="button" class="btn btn-primary btn-lg" onclick="window.location='{{ URL::to('/check?mode=checkOut') }}'"> Check Out</button>

</div>
</div>
</body>
</html>
