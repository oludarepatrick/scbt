<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use App\Models\User;
use App\Models\{Answer,SchoolInfo};
use App\Models\{Student,ClassDivision,Subject,Session,Classes,QuizUser,UserTimer};
use DB;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['classes']=Classes::all();
        $data['arms']=ClassDivision::all();

        
        //dd($data);
        return view('backend.exam.assign2', $data);
    }
    
    public function reAssignForm()
    {
        $data['classes']=Classes::all();
        $data['arms']=ClassDivision::all();

        
        return view('backend.exam.re-assign', $data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignExam(Request $request)
    {
        //dd($request->quizId);
        foreach($request->mystud as $ky=>$stud_id)
        {
            //echo $stud_id."<br/>";
            $saveQ=QuizUser::create([
                'user_id'=>$stud_id,
                'quiz_id'=>$request->quizId
            ]);
        }
        //$quiz = (new Quiz)->assignExam($request->all());
        return redirect()->back()->with('message','Exam successfully assigned!');
    }
    
    public function saveReAssigning(Request $request)
    {
        foreach($request->mystud as $ky=>$stud_id)
        {
            //echo $stud_id."<br/>";
            $gtTm=Quiz::where('id',$request->quizId)->get(['minutes'])->first();
            $timer=($gtTm->minutes*60*1000);
            
            /*$updateTimer=UserTimer::where('userId', $stud_id)
            ->where('quizId', $request->quizId)
            ->update([
                't_timer'=>$timer,
                'status'=>0
            ]);*/
            DB::table('user_timer')
                ->where('userId', $stud_id)
                ->where('quizId', $request->quizId)
                ->limit(1)  
                ->update(array('t_timer' => $timer, 'status'=>0));
        }
        
        return redirect()->back()->with('message','Exam successfully Re-Assigned!');   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userExam(Request $request)
    {
        $quizzes = Quiz::get();
        return view('backend.exam.index',compact('quizzes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeExam(Request $request)
    {
        $userId = $request->get('user_id');
        $quizId = $request->get('quiz_id');
        $quiz = Quiz::find($quizId);
        $result = Result::where('quiz_id',$quizId)->where('user_id',$userId)->exists();
        if($result){
            return redirect()->back()->with('message','This quiz is played by user so it cannot be removed!');
        }else{
            $quiz->users()->detach($userId);
            return redirect()->back()->with('message','Exam is no longer assigned to the user!');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getQuizQuestions(Request $request, $quizId)
    {
        //$authUser=auth()->user()->id;
        $authUser=auth()->user()->stud_id;
        //Check if user has been assigned to a particular quiz
        $userId = DB::table('quiz_user')->where('user_id',$authUser)->pluck('quiz_id')->toArray();
        if(!in_array($quizId,$userId)){
            return redirect()->to('/home')->with('error','You are not yet assigned this exam');
        }

        $quiz = Quiz::find($quizId);
        $time = Quiz::where('id',$quizId)->value('minutes');
        $quizQuestions = Question::where('quiz_id',$quizId)->with('answers')->get();
       // dd($quizQuestions);

        $authUserHasPlayedQuiz = Result::where(['user_id'=>$authUser,'quiz_id'=>$quizId])->get();
       // return view('quiz',compact('quiz','time','quizQuestions','authUserHasPlayedQuiz'));
        

        $quiz = Quiz::find($quizId);
        $time = Quiz::where('id',$quizId)->value('minutes');
        $quizQuestions = Question::where('quiz_id',$quizId)->with('answers')->get();
        $authUserHasPlayedQuiz = Result::where(['user_id'=>$authUser,'user_id'=>$quizId])->get();

        $wasCompleted = Result::where('user_id',$authUser)->whereIn('quiz_id',(new Quiz)->hasQuizAttempted())->pluck('quiz_id')->toArray();
        
        if(in_Array($quizId,$wasCompleted)){
            return redirect()->to('/home')->with('error','You have already participated in this exam');
        }

        return view('quiz',compact('quiz','time','quizQuestions','authUserHasPlayedQuiz'));

    }

    public function postQuiz(Request $request){
        $questionId= $request['questionId'];
        $answerId = $request['answerId'];
        $quizId = $request['quizId'];

        $authUser = auth()->user();

        return $userQuestionAnswer = Result::updateOrCreate(
            ['user_id'=> $authUser->stud_id,'quiz_id'=>$quizId, 'question_id'=>$questionId],
            ['answer_id'=>$answerId]);
        
    }

public function viewResult($userId,$quizId){
    $results = Result::where('user_id',$userId)->where('quiz_id',$quizId)->get();
    return view('result-detail',compact('results'));
}

public function result(){
    //$quizzes = Quiz::get();
    $data['classes']=Classes::all();
    $data['arms']=ClassDivision::all();
    
    


    return view('backend.exam.view-result', $data);
}

public function userQuizResult($userId,$quizId){
    $results = Result::where('user_id',$userId)->where('quiz_id',$quizId)->get();
    $totalQuestions = Question::where('quiz_id',$quizId)->count();
    $attemptQuestion = Result::where('quiz_id',$quizId)->where('user_id',$userId)->count();
    $quiz = Quiz::where('id',$quizId)->get();

    $ans=[];
    foreach($results as $answer){
        array_push($ans,$answer->answer_id);
    }
    $userCorrectedAnswer = Answer::whereIn('id',$ans)->where('is_correct',1)->count();
    $userWrongAnswer = $totalQuestions-$userCorrectedAnswer;
    if($attemptQuestion){
        $percentage = ($userCorrectedAnswer/$totalQuestions)*100;
    }else{
        $percentage=0;
    }

    return view('backend.result.result',compact('results','totalQuestions','attemptQuestion','userCorrectedAnswer','userWrongAnswer','percentage','quiz'));
    
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function compo()
    {
        //return view('home');
        return view('next');
    }
    
    public function showStudent(Request $request)
    {
        
        if(!empty($request))
        {
            $schlInf=SchoolInfo::get(['id','session','term'])->first();
            $activeSes=$schlInf->session;
            $activeTerm=$schlInf->term;
            
            if($request->armId!="optional")
            {
                $data['students']=Student::where('class',$request->cId)
                    ->where('class_division',$request->armId)
                    ->where('status', 'Active')
                    ->where('session', $activeSes)
                    ->whereNotIn('sn', 
                        QuizUser::where('quiz_id', $request->quizId)->get(['user_id'])
                    )
                    ->get([
                        'sn','surname','firstname', 'othername','sex','student_id','class_division'
                    ]);
            }
            else{
                $data['students']=Student::where('class',$request->cId)
                    ->where('status', 'Active')
                    ->where('session', $activeSes)
                    ->whereNotIn('sn', 
                        QuizUser::where('quiz_id', $request->quizId)->get(['user_id'])
                    )
                    ->get([
                        'sn','surname','firstname', 'othername','sex','student_id','class_division'
                    ]);
            }
            //echo count($data['students']); exit(); 
            //echo $request->quizId."<br/>".$request->armId."<br/>".$request->cId; exit();
            
            $data['classId']=$request->cId;
            $data['armId']=$request->armId;
            $data['quizId']=$request->quizId;

            return view('backend.exam.load-students',$data);
            //return resposnse()->json(view('backend.exam.load-students',$data));
        }
        else{
            echo "<h3 align='center' style='color:red'>No Record(s) Found</h3>";
        }
        
    }
    
    public function showStudForReassign(Request $request)
    {
       
        if(!empty($request))
        {
            $schlInf=SchoolInfo::get(['id','session','term'])->first();
            $activeSes=$schlInf->session;
            $activeTerm=$schlInf->term;
            
            if($request->armId!="optional")
            {
                $data['students']=Student::where('class',$request->cId)
                    ->where('class_division',$request->armId)
                    ->where('status', 'Active')
                    ->where('session', $activeSes)
                    ->whereIn('sn', 
                        QuizUser::where('quiz_id', $request->quizId)->get(['user_id'])
                    )
                    ->get([
                        'sn','surname','firstname', 'othername','sex','student_id','class_division'
                    ]);
            }
            else{
                $data['students']=Student::where('class',$request->cId)
                    ->where('status', 'Active')
                    ->where('session', $activeSes)
                    ->whereIn('sn', 
                        QuizUser::where('quiz_id', $request->quizId)->get(['user_id'])
                    )
                    ->get([
                        'sn','surname','firstname', 'othername','sex','student_id','class_division'
                    ]);
            }
            //echo count($data['students']); exit(); 
            //echo $request->quizId."<br/>".$request->armId."<br/>".$request->cId; exit();
            
            $data['classId']=$request->cId;
            $data['armId']=$request->armId;
            $data['quizId']=$request->quizId;

            return view('backend.exam.load-students2',$data);
            //return resposnse()->json(view('backend.exam.load-students',$data));
        }
        else{
            echo "<h3 align='center' style='color:red'>No Record(s) Found</h3>";
        }
        
    }

    public function loadQuizes(Request $request)
    {
        //$data['classname']=$request->cId;
        //$data['arm']=$request->armId;
        
        if($request->armId!="optional"){
            $data['quizes']=Quiz::where('class_id', $request->cId)->where('arm', $request->armId)->get();
        }else{
           $data['quizes']=Quiz::where('class_id', $request->cId)->get(); 
        }

        return view('backend.exam.load-quiz-title',$data);
    }
    
    public function loadQuizes2(Request $request)
    {
        //$data['classname']=$request->cId;
        //$data['arm']=$request->armId;
        
        /*if($request->armId!="optional"){
            $data['quizes']=Quiz::where('class_id', $request->cId)
            ->where('arm', $request->armId)
            ->whereIn('id', 
                Result::select('quiz_id')->distinct()->get(['quiz_id'])
            )
            ->get();
        }else{*/
           $data['quizes']=Quiz::where('class_id', $request->cId)
           ->whereIn('id', 
                Result::select('quiz_id')->distinct()->get(['quiz_id'])
            )
           ->get(); 
        //}

        return view('backend.exam.load-quiz-title',$data);
        
        //echo "<option>eeee</option>";
    }
    
    public function studResult(Request $request)
    {
        dd($request);
    }
    
    public function showResult(Request $request)
    {
        $armId=$request->arm;
        $quizId=$request->quiz;
        $classname=$request->classname;
        
        $schlInf=SchoolInfo::get(['id','session','term'])->first();
        $activeSes=$schlInf->session;
        $activeTerm=$schlInf->term;
            
         if($armId!="optional")
            {
                $students=Student::where('class',$classname)
                    ->where('class_division',$armId)
                    ->where('status', 'Active')
                    ->where('session', $activeSes)
                    ->whereIn('sn', 
                        QuizUser::select('user_id')->distinct()->where('quiz_id', $quizId)->get(['user_id'])
                    )
                    ->get([
                        'sn','surname','firstname', 'othername','sex','student_id','class_division'
                    ])->toArray();
            }
            else{
                $students=Student::where('class',$classname)
                    ->where('status', 'Active')
                    ->where('session', $activeSes)
                    ->whereIn('sn', 
                        QuizUser::select('user_id')->distinct()->where('quiz_id', $quizId)->get(['user_id'])
                    )
                    ->get([
                        'sn','surname','firstname', 'othername','sex','student_id','class_division'
                    ])->toArray();
            }
        
        $std_all=array();
        //dd($students);
        if(!empty($students))
        {
        foreach($students as $student)
        {
            $userId=$student['sn'];
            $name=$student['surname'].' '.$student['firstname'].' '.$student['othername'];
            $sex=$student['sex'];
            $student_id=$student['student_id'];
            $class_division=$student['class_division'];
        
            
            $results = Result::where('user_id',$userId)->where('quiz_id',$quizId)->get();
            $totalQuestions = Question::where('quiz_id',$quizId)->count();
            $attemptQuestion = Result::where('quiz_id',$quizId)->where('user_id',$userId)->count();
            $quiz = Quiz::where('id',$quizId)->get();
        
            $ans=[];
            foreach($results as $answer){
                array_push($ans,$answer->answer_id);
            }
            $userCorrectedAnswer = Answer::whereIn('id',$ans)->where('is_correct',1)->count();
            $userWrongAnswer = $totalQuestions-$userCorrectedAnswer;
            if($attemptQuestion){
                $percentage = round(($userCorrectedAnswer/$totalQuestions)*100,2);
            }else{
                $percentage=0;
            }
            
            $getTitlEE=Quiz::where('id',$quizId)->get(['name'])->first();
            $getTitl=$getTitlEE->name;
            
            $std_all[$userId]=array(
                "userId"=>$userId,
                "quizId"=>$quizId,
                "name"=>$name,
                "sex"=>$sex,
                "student_id"=>$student_id,
                "class_division"=>$class_division,
                "class"=>$classname,
                "percentage"=>$percentage,
                "userCorrectedAnswer"=>$userCorrectedAnswer,
                "totalQuestions"=>$totalQuestions
                
            );
            
        };
        
        //dd($std_all);
        return view('backend.exam.dispalyAll',['students'=>$std_all,'classDivision'=>$class_division,'class'=>$classname,'arm'=>$armId, 'quizTitle'=>$getTitl]);
        }
        else{
            echo "<h2>No Available Result<h2>";
        }
        
    }
}
