<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\{Student,ClassDivision,Subject,Session,Classes,SchoolInfo};

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        
        $getInfo = SchoolInfo::where('status', 'ACTIVE')->get(['session','term'])->first();
        
        //dd($getInfo);
        $active_session=$getInfo->session;
        $active_term=$getInfo->term;
        
        $quizzes =Quiz::where('sessions', $active_session)
            ->where('terms', $active_term)
            ->get();
         
        return view('backend.quiz.index',compact('quizzes'));
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

        return view('backend.quiz.create', $data);
        //return redirect()->route('food.index')->with('message','Food Info Updated Successfully');
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
        
        $getInfo = SchoolInfo::where('status', 'Active')->get(['session','term'])->first();
        $data['sessions']=$getInfo->session;
        $data['terms']=$getInfo->term;
        //dd();
        $quiz = (new Quiz)->storeQuiz($data);
        return redirect()->back()->with('message','Quiz Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['classes']=Classes::all();
        $data['arms']=ClassDivision::all();

        $data['quiz'] = (new Quiz)->getQuizById($id);
        
    

        return view('backend.quiz.edit',$data);
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
        
        $quiz = (new Quiz)->updateQuiz($data,$id);
        
        return redirect(route('quiz.index'))->with('message','Quiz Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        (new Quiz)->deleteQuiz($id);
        return redirect(route('quiz.index'))->with('message','Quiz Deleted Successfully!');

       /* $quiz = Quiz::find($id);
        $quiz->delete();
        return redirect(route('quiz.index'))->with('message','Quiz Updated Successfully!');*/
    }

    public function question($id){
        $quizzes = Quiz::with('questions')->where('id',$id)->get();
        return view('backend.quiz.question',compact('quizzes'));
    }

    public function validateForm($request){
        return $this->validate($request,[
            'name'=>'required|string',
            'description'=>'required|min:3|max:500',
            'minutes'=>'required|integer',
            'class_id' => 'required',
            'subject_id' => 'required',
            'arm' => 'required',
            'status'=>'required'
        ]);
    }
    public function showSubjects(Request $request)
    {
        $classId=$request->cId;
        $armId=$request->armId;
        //echo $classId;
        $data['subjects']=Subject::where('class', $classId)->get(['subject']);
        //var_dump($data['subjects']); exit();
        return view('backend.quiz.display-subjects', $data);
    }
}
