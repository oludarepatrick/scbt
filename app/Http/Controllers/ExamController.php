<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use App\Models\User;
use App\Models\{Answer,SchoolInfo};
use App\Models\{Student,Subject,Session,ClassModel,QuizUser,UserTimer};
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
        $data['classes']=ClassModel::all();

        
        //dd($data);
        return view('backend.exam.assign2', $data);
    }
    
   public function reAssignForm()
    {
        $data['classes'] = ClassModel::all();

        // Fetch distinct class divisions (arms)
        $data['arms'] = \App\Models\User::select('class_division')
                        ->whereNotNull('class_division')
                        ->distinct()
                        ->get();

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
        $quiz = Quiz::findOrFail($request->quizId);

        foreach ($request->mystud as $stud_id) {
            QuizUser::updateOrCreate(
                [
                    'user_id' => $stud_id,
                    'quiz_id' => $quiz->id
                ],
                [
                    'curriculum_id' => $quiz->curriculum_id, // ADD THIS
                    'time_left' => $quiz->minutes,
                    'status' => '0',
                ]
            );
        }

        return redirect()->back()->with('message', 'Exam successfully assigned!');
    }


    
    public function saveReAssigning(Request $request)
{
    foreach ($request->mystud as $stud_id) {

        $quizTime = Quiz::where('id', $request->quizId)->value('minutes');
        $timer = $quizTime * 60 * 1000;

        DB::table('quiz_users')
            ->where('user_id', $stud_id)
            ->where('quiz_id', $request->quizId)
            ->update([
                'time_left' => $timer,
                'status' => 0, // reset to not started
                'started_at' => null,
                'submitted_at' => null,
                'updated_at' => now(),
            ]);
    }

    return redirect()->back()->with('message', 'Exam successfully Re-Assigned!');
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
    $data['classes']=ClassModel::all();
    
     


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
    if (!empty($request)) {
        $schoolInfo = SchoolInfo::select('session', 'term')->first();

        if (!$schoolInfo) {
            return response("<h3 align='center' style='color:red'>School info not found</h3>");
        }

        $activeSession = $schoolInfo->session;

        $data['students'] = User::where('class', $request->cId)
            ->where('status', '1')
            ->where('category', 'Student')
            ->where('session', $activeSession)
            ->whereNotIn('id', QuizUser::where('quiz_id', $request->quizId)->pluck('user_id'))
            ->get(['id', 'firstname', 'lastname', 'class']);

        $data['classId'] = $request->cId;
        $data['quizId'] = $request->quizId;

        if ($data['students']->isEmpty()) {
            return response("<h3 align='center' style='color:red'>No Record(s) Found</h3>");
        }

        return view('backend.exam.load-students', $data);
    }

    return response("<h3 align='center' style='color:red'>No Record(s) Found</h3>");
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
                $data['students']=User::where('class',$request->cId)
                    ->where('status', '1')
                    ->where('category', 'Student')
                    ->where('session', $activeSes)
                    ->whereIn('id', 
                        QuizUser::where('quiz_id', $request->quizId)->get(['user_id'])
                    )
                    ->get([
                        'id','firstname','lastname', 'class'
                    ]);
            }
            else{
                $data['students']=User::where('class',$request->cId)
                    ->where('status', '1')
                    ->where('category', 'Student')
                    ->where('session', $activeSes)
                    ->whereIn('sn', 
                        QuizUser::where('quiz_id', $request->quizId)->get(['user_id'])
                    )
                    ->get([
                        'id','firstname','lastname','student_id'
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
    $classId = $request->cId;

    if (empty($classId)) {
        return response("<option value=''>Select Class First</option>");
    }

    $quizzes = Quiz::where('class_id', $classId)->get();

    if ($quizzes->isEmpty()) {
        return response("<option value=''>No quiz found for this class</option>");
    }

    $options = "<option value=''>Select Quiz</option>";
    foreach ($quizzes as $quiz) {
        $options .= "<option value='{$quiz->id}'>{$quiz->name}</option>";
    }

    return response($options);
}

public function loadQuizes2(Request $request)
{
    $classId = $request->cId;

    $quizzes = Quiz::where('class_id', $classId)
        ->whereIn('id', Result::select('quiz_id')->distinct()->pluck('quiz_id'))
        ->get();

    if ($quizzes->isEmpty()) {
        return response("<option value=''>No available quizzes</option>");
    }

    $options = "<option value=''>Select Quiz</option>";
    foreach ($quizzes as $quiz) {
        $options .= "<option value='{$quiz->id}'>{$quiz->name}</option>";
    }

    return response($options);
}

    
    public function studResult(Request $request)
    {
        dd($request);
    }
    
    public function showResult(Request $request)
    {
        //ini_set('display_errors', 1);
        $armId=$request->arm;
        $quizId=$request->quiz;
        $classname=$request->classname;
        //dd($request);
        $schlInf=SchoolInfo::get(['id','session','term'])->first();
        $activeSes=$schlInf->session;
        $activeTerm=$schlInf->term;
            
         if($armId!="optional")
            {
                $students=User::where('class',$classname)
                    ->where('class_division',$armId)
                    ->where('status', 'Active')
                    ->where('session', $activeSes)
                    ->whereIn('sn', 
                        QuizUser::select('user_id')->distinct()->where('quiz_id', $quizId)->get(['user_id'])
                    )
                    ->get([
                        'id','firstname','lastname', 'class'
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
                        'id','firstname','lastname', 'class'
                    ])->toArray();
            }
        
        $std_all=array();
        //dd($students);
        if(!empty($students))
        {
        foreach($students as $student)
        {
            $userId=$student['sn'];
            $name=$student['firstname'].' '.$student['lastname'].' '.$student['class'];
            
            $student_id=$student['student_id'];
        
            
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
