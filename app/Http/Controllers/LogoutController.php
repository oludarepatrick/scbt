<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function perform()
    {
        //$userType=auth()->user()->occupation;
        Session::flush();
        Auth::logout();

        
        //local
        //return redirect()->intended('http://127.0.0.1:8000/login');
        //Live
        return redirect()->intended('http://yffscbt.schooldrive.com.ng/ai-login');
        
        // if($userType=="Student")
        // {
        //     return redirect()->intended(qLink.'/login');
        //     //return redirect()->intended('https://grafton.schooldrive.com.ng/index.php/student/dashboard');
        // }
        // else{
        //     return redirect()->intended('http://34.74.15.13/login');
        //     //return redirect()->intended('https://grafton.schooldrive.com.ng/index.php/staff/dashboard');
        // }
    }
}
