<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\{User,Subject,Session,ClassModel,SchoolInfo};

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        
        $getInfo = SchoolInfo::where('status', 1)->get(['session','term'])->first();
        
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
        $data['classes']=ClassModel::all();
        $data['subjects']=Subject::all();

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
    // Validate request
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:quizzes,name',
        'description' => 'required|string',
        'minutes' => 'required|integer|min:1',
        'class_id' => 'required|string',
        'subject_id' => 'required|string',
    ]);

    // Get session and term from active SchoolInfo
    $getInfo = SchoolInfo::where('status', 1)->first(['session', 'term']);

    if (!$getInfo) {
        return back()->with('error', 'No active school info found. Please set session and term first.');
    }

    // Merge session and term into validated data
    $validated['sessions'] = $getInfo->session;
    $validated['terms'] = $getInfo->term;
    $validated['status'] = '1'; // default active

    // Save quiz directly
    Quiz::create($validated);

    return back()->with('message', 'Quiz Created Successfully!');
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
        $data['classes']=ClassModel::all();
        $data['subjects']=Subject::all();

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
    // Validate incoming data
    $data = $this->validateForm($request);

    // Retrieve the current active session and term
    $getInfo = SchoolInfo::where('status', 1)->first(['session', 'term']);
    $data['sessions'] = $getInfo->session ?? null;
    $data['terms'] = $getInfo->term ?? null;

    // Update the quiz record
    $quiz = Quiz::findOrFail($id);
    $quiz->update([
        'name'        => $data['name'],
        'description' => $data['description'],
        'minutes'     => $data['minutes'],
        'sessions'    => $data['sessions'],
        'terms'       => $data['terms'],
        'class_id'    => $data['class_id'],
        'subject_id'  => $data['subject_id'],
    ]);

    return redirect()
        ->route('quiz.index')
        ->with('message', 'Quiz Updated Successfully!');
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
        
        $data['subjects']=Subject::where('class', $classId)->get(['subject']);
      
       return view('backend.quiz.display-subjects', $data);
    }
}
