<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\Classes;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        if(auth()->user()->is_admin==1)
        {
            $classes=Classes::all()->toArray();
            //dd($classes);
        
            return view('admin.index');
            exit();
            //return redirect()->route('adminHome');
        }
        $authUser = auth()->user()->stud_id;
        //dd($authUser);
        $assignedQuizId = [];
        $user = DB::table('quiz_user')->where('user_id', $authUser)->get();
        foreach($user as $u){
            array_push($assignedQuizId,$u->quiz_id);
        }
        $quizzes = Quiz::whereIn('id',$assignedQuizId)->get();
        $isExamAssigned = DB::table('quiz_user')->where("user_id",$authUser)->exists();
        $wasQuizCompleted = Result::where('user_id',$authUser)->whereIn('quiz_id',(new Quiz)->hasQuizAttempted())->pluck('quiz_id')->toArray();


        return view('home',compact('quizzes','wasQuizCompleted','isExamAssigned'));
    }
    
    public function adminHome()
    {
        $classes=Classes::all()->toArray();
        //dd($classes);
        
        return view('admin.index');
    }
}
