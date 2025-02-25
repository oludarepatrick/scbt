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
        $userType=auth()->user()->occupation;
        Session::flush();
        Auth::logout();
        
        if($userType=="Student")
        {
            return redirect()->intended('https://grafton.schooldrive.com.ng/index.php/student/dashboard');
        }
        else{
            return redirect()->intended('https://grafton.schooldrive.com.ng/index.php/staff/dashboard');
        }
    }
}
