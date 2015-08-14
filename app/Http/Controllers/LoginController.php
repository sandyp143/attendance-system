<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Organization;



class LoginController extends Controller{

	public function login(Request $request){

		if ($request->isMethod('post')) {
  


		$rules = array(
		    'email'    => 'required|email', 
		    'password' => 'required|alphaNum'
		);

		
		$validator = Validator::make(Input::all(), $rules);

	
		if ($validator->fails()) {
		    return Redirect::to('login')
		        ->withErrors($validator) 
		        ->withInput(Input::except('password')); 
		} else {


		    $userdata = array(
		        'email'     => Input::get('email'),
		        'password'  => Input::get('password')
		    );

		    
		    if (Auth::attempt($userdata)) {

					return "success";

		    } else {        

		      
		        return Redirect::to('login');

	        }
	    }
	}
	    else{

	        	return View('login');
	        }

    }

    public function register(){

        $input = Input::all();
        $organizationName = $input['organization_name'];

        if(Organization::checkOrganizationIsEmpty){

            Session::flash('Error', 'No such Organization');

            return Redirect::to('register');


        }

        $user =User::create([
            'first_name' => $input['first_name'],'last_name' =>$input['last_name'],'email' =>$input['email'],
            'password'=>Hash::make($input['password'])
        ]);

        $organization= Organization::OfName($input['organization_name']);

        Member::create([ 'user-id'=>$user->id , 'organization-id'=>$organization->id

        ]);

        return redirect::to('login');





    }

		
}


		



