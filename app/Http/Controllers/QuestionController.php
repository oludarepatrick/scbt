<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\{Question,SchoolInfo};
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getInfo = SchoolInfo::where('status', 'Active')->get(['session','term'])->first();
        $active_session=$getInfo->session;
        $active_term=$getInfo->term;
        
        
        //$questions = (new Question)->getQuestions();
        $questions = Question::join('quizzes', 'quizzes.id', '=', 'questions.quiz_id')
            ->where('quizzes.sessions', $active_session)
            ->where('quizzes.terms', $active_term)
            ->orderBy('questions.created_at', 'DESC')
            ->select(['questions.id','questions.mfile_ext','questions.quiz_id','questions.question'])
            ->paginate(10);

        //dd($questions);
        return view('backend.question.index',compact('questions'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $getInfo = SchoolInfo::where('status', 'Active')->get(['session','term'])->first();
        $active_session=$getInfo->session;
        $active_term=$getInfo->term;
        
        $quizes =Quiz::where('sessions', $active_session)
            ->where('terms', $active_term)
            ->get();
        
        //dd($quizes);
        
        return view('backend.question.create',compact('quizes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $data = $this->validateForm($request);
        if($request->has('mfile'))
        {
            $extF=$request->mfile->extension();
            if($extF=="jpeg" || $extF=="jpg" || $extF=="png")
            {
                $locatn = 'uploads/images';  
            }
            else if($extF=="mp3" || $extF=="mp4")
            {
                $locatn = 'uploads/'.(($extF=="mp3")?'audios':'videos');
            }
            else if($extF=="pdf")
            {
                $locatn = 'uploads/files';  
            }
            else{
                return redirect()->route('question.create')->with('message','Invalid file extension!');
                exit(); 
            }
            $upld = $locatn.'/'.time().'__.'.$request->mfile->extension();  
            $request->mfile->move(public_path($locatn), $upld);
            $data['mfile_ext']=$upld;
        }

        $question = (new Question)->storeQuestion($data);
        $Answer = (new Answer)->storeAnswer($data,$question);

        return redirect()->route('question.create')->with('message','Question created successfully!');
        //old version
        /*
        $question = (new Question)->storeQuestion($data);
        $Answer = (new Answer)->storeAnswer($data,$question);
        return redirect()->route('question.create')->with('message','Question created successfully!');
        */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = (new Question)->getQuestionById($id);
        //dd($question);
        return view('backend.question.show',compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$question = (new Question)->findQuestion($id);
        $question = Question::where('id',$id)->get()->first();
        //dd($question);
        return view('backend.question.edit',compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->validateForm($request);
        if($request->has('mfile'))
        {
            $extF=$request->mfile->extension();
            if($extF=="jpeg" || $extF=="jpg" || $extF=="png")
            {
                $locatn = 'uploads/images';  
            }
            else if($extF=="mp3" || $extF=="mp4")
            {
                $locatn = 'uploads/'.(($extF=="mp3")?'audios':'videos');
            }
            else if($extF=="pdf")
            {
                $locatn = 'uploads/files';  
            }
            else{
                return redirect()->route('question.show')->with('message','Invalid file extension!');
                exit(); 
            }
            $upld = $locatn.'/'.time().'__.'.$request->mfile->extension();  
            $request->mfile->move(public_path($locatn), $upld);
            $request['mfile_ext']=$upld;
        }
        else{
            $request['mfile_ext']=$request->old_file;
        }
        
        //dd($request);
        $question = (new Question)->updateQuestion($id,$request);
        $answer = (new Answer)->updateAnswer($request,$question);
        return redirect()->route('question.show',$id)->with('message','Question Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        (new Answer)->deleteAnswer($id);
        (new Question)->deleteQuestion($id);
        return redirect()->route('question.index')->with('message','Question Deleted Successfully!');
    }

    public function validateForm($request){
        return $this->validate($request,[
            'quiz'=>'required',
            'question'=>'required|min:1',
            'options'=>'bail|required|array|min:1',
            'options.*'=>'bail|required|string|distinct',
            'correct_answer'=>'required'
        ]);
    }
    
    public function showQuestion(Request $request)
    {
        $quizId = $request->quizId;
        
       $noB=Question::where('quiz_id', $quizId)->count();
       
       print ($noB+1);
    }
}
