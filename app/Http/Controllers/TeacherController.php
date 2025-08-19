<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curriculum;
use App\Models\TeacherQuestion;
use App\Models\AIQuestion;
use App\Models\Student;
use App\Models\Subject;
use App\Models\QuizUser;
use Illuminate\Support\Facades\DB;



class TeacherController extends Controller
{
    public function dashboard()
    {
        $curriculums = Curriculum::where('user_id', auth()->id())->get();

        // Fetch the teacher's questions
        //$questions = AIQuestion::where('user_id', auth()->id())->latest()->get();
        $questions = AIQuestion::where('user_id', auth()->id())->latest()->paginate(10);


        return view('backend.teacher_questions.index', compact('curriculums', 'questions'));
    }

    public function uploadCurriculumForm()
    {
        $classes = ['BASIC 1', 'BASIC 2', 'BASIC 3', 'BASIC 4', 'BASIC 5', 'BASIC 6', 'SPECIAL CLASS', 'ENTRANCE', 'GENERAL', 'NURSERY 1', 'NURSERY 2', 'RECEPTION 1', 'RECEPTION 2', 'JAMB', 'WAEC', 'NECO', 'JSS 1', 'JSS 2', 'JSS 3', 'SSS 1', 'SSS 2', 'SSS 3'];
        $subjects = ['MATHEMATICS', 'ENGLISH', 'GEOMETRY', 'LITERATURE', 'SECURITY EDUCATION', 'NATIONAL VALUE', 'BASIC SCIENCE', 'LITERACY', 'NUMERACY', 'BASIC SCIENCE & TECH', 'COMPUTER STUDIES', 'FRENCH', 'PHONICS & DICTION', 'HANDWRITING', 'PREVOCATIONAL STUDIES', 'IGBO LANG', 'CRK', 'YORUBA', 'HISTORY', 'CHEMISTRY', 'PHYSICS', 'BIOLOGY', 'GEOGRAPHY', 'GOVERNMENT', 'ECONOMICS', 'FINANCIAL ACCOUNTING', 'COMMERCE', 'LITERATURE', 'ANIMAL HUSBANDRY'];

        return view('backend.teacher_questions.upload_curriculum', compact('classes', 'subjects'));
    }

        public function uploadCurriculum(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'time' => 'required',
            'subject' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'curriculum_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // max 5MB
            'curriculum_text' => 'nullable|string',
            'scheme_of_work' => 'nullable|string',
            'lesson_note' => 'nullable|string',
        ]);

        // Handle file upload if exists
        $filePath = null;
        if ($request->hasFile('curriculum_file')) {
            $filePath = $request->file('curriculum_file')->store('curriculums', 'public');
        }

        // Save curriculum
        Curriculum::create([
            'user_id' => auth()->id(), // Add this line to resolve the error
            'name' => $request->name,
            'time_left' => $request->time,
            'subject' => $request->subject,
            'class' => $request->class,
            'file_path' => $filePath,
            'content' => $request->curriculum_text ?? '',
            'scheme_of_work' => $request->scheme_of_work ?? '',
            'lesson_note' => $request->lesson_note ?? '',
        ]);

        //return redirect()->route('dashboard')->with('success', 'Curriculum uploaded successfully.');
        return redirect()->back()->with('success', 'AI Question Curriculum Created Successfully');
    }

   //The sets of methods controls the ai generated questions
     public function showQuestionGenerationForm()
    {
        $curriculums = Curriculum::where('user_id', auth()->id())->get();
        return view('teacher_questions.generate', compact('curriculums'));
    }

    public function generateQuestions(Request $request)
    {
        $request->validate([
            'curriculum_id' => 'required|exists:curriculums,id',
            'num_questions' => 'required|integer|min:1',
            'option_type' => 'required|in:a-d,a-e,a-c,true-false',
        ]);

        $curriculum = Curriculum::findOrFail($request->curriculum_id);
        $generatedQuestions = $this->generateAIQuestions($curriculum->content, $request->num_questions, $request->option_type);

        return view('backend.teacher_questions.preview_questions', compact('generatedQuestions', 'curriculum'));
    }

    private function generateAIQuestions(Request $request)
    {
        $request->validate([
        'curriculum_id' => 'required|exists:curriculums,id',
        'question_count' => 'required|integer|min:1|max:50',
        'time_limit' => 'nullable|integer|min:1',
    ]);

    $curriculum = Curriculum::findOrFail($request->curriculum_id);

    // Simulate AI generation (this is where you'll plug in OpenAI or local AI later)
    $questions = [];
    for ($i = 1; $i <= $request->question_count; $i++) {
        $questions[] = [
            'question' => "Sample Question $i based on: " . $curriculum->content,
            'options' => [
                'A' => "Option A for Q$i",
                'B' => "Option B for Q$i",
                'C' => "Option C for Q$i",
                'D' => "Option D for Q$i",
            ],
            'answer' => 'A',
        ];
    }

    // Store temporarily in session for preview/edit
    session([
        'generated_questions' => $questions,
        'curriculum_id' => $curriculum->id,
        'time_limit' => $request->time_limit,
    ]);

    return redirect()->route('questions.preview');
    }
    public function previewGeneratedQuestions()
    {
        $questions = session('generated_questions');
        $timeLimit = session('time_limit');

        if (!$questions) {
            return redirect()->route('questions.generate.form')->with('error', 'No questions to preview.');
        }

        return view('teacher_questions.preview_questions', compact('questions', 'timeLimit'));
    }

    public function previewMathsGeneratedQuestions()
    {
        $questions = session('generated_questions');
        $timeLimit = session('time_limit');

        if (!$questions) {
            return redirect()->route('questions.generate.form')->with('error', 'No questions to preview.');
        }

        return view('teacher_questions.preview_questions_maths', compact('questions', 'timeLimit'));
    }

    public function saveGeneratedQuestions(Request $request)
    {
        $request->validate([
            'questions' => 'required|array',
            'curriculum_id' => 'required|exists:curriculums,id',
        ]);

        foreach ($request->questions as $q) {
            TeacherQuestion::create([
                'user_id' => auth()->id(),
                'curriculum_id' => $request->curriculum_id,
                'question' => $q['question'],
                'options' => json_encode($q['options']),
                'correct_option' => $q['correct_option'],
            ]);
        }

        return redirect()->route('teacher.questions.index')->with('success', 'Questions saved.');
    }

    public function myQuestions()
    {
        $questions = TeacherQuestion::where('user_id', auth()->id())->get();
        return view('backend.teacher_questions.index', compact('questions'));
    }

    public function editQuestion($id)
    {
        $question = TeacherQuestion::findOrFail($id);
        return view('backend.teacher_questions.edit', compact('question'));
    }


    public function updateQuestion(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string',
            'options' => 'required|array',
            'correct_option' => 'required|string',
        ]);

        $question = AIQuestion::findOrFail($id);
        $question->update([
            'question' => $request->question,
            'options' => json_encode($request->options),
            'correct_option' => $request->correct_option,
        ]);

        return redirect()->route('teacher.questions.index')->with('success', 'Question updated.');
    }

    public function deleteQuestion($id)
    {
        $question = AiQuestion::findOrFail($id);
        $question->delete();

        return redirect()->route('teacher_questions.dashboard')->with('success', 'Question deleted successfully.');
    }

    // AI Question List Views
    
    public function aiQuestionList()
    {
         $curriculums = Curriculum::orderBy('created_at', 'desc')->get();

        return view('backend.teacher_questions.index', compact('curriculums'));
    }

    //AI Maths Generated Questions List
    public function aiMathsQuestionList()
    {
         $curriculums = Curriculum::orderBy('created_at', 'desc')->get();

        return view('backend.teacher_questions.index_maths', compact('curriculums'));
    }
    
   public function activateCurriculum(Request $request, Curriculum $curriculum)
{
    $request->validate([
        'time_limit' => 'required|integer|min:1',
    ]);

    $timeLimit = $request->time_limit;
    $now = now();

    // ✅ 1. Update curriculum time_left
    $curriculum->update(['time_left' => $timeLimit]);

    // ✅ 2. Update AI Questions duration for this curriculum
    \App\Models\AIQuestion::where('curriculum_id', $curriculum->id)
        ->update(['duration' => $timeLimit]);

    // ✅ 3. Assign time to students in quiz_user table
    $students = Student::where('status', 'ACTIVE')
                    ->where('class', $curriculum->class)
                    ->get();

    foreach ($students as $student) {
        DB::table('quiz_user')->updateOrInsert(
            ['quiz_id' => $curriculum->id, 'user_id' => $student->student_id],
            ['status' => 0, 'time_left' => $timeLimit, 'updated_at' => $now, 'created_at' => $now]
        );
    }

    return back()->with('success', 'Subject assigned to all students in class ' . $curriculum->class . ' with time limit of ' . $timeLimit . ' minutes.');
}



    public function viewCurriculumQuestions($curriculum_id)
    {
        $curriculum = Curriculum::findOrFail($curriculum_id);

        $questions = AIQuestion::where('curriculum_id', $curriculum_id)
                            ->latest()
                            ->get();

        return view('backend.teacher_questions.show', compact('questions', 'curriculum'));
    }

    // AI Maths Generated. this method loads the question preview page
    public function viewMathsCurriculumQuestions($curriculum_id)
    {
        $curriculum = Curriculum::findOrFail($curriculum_id);

        $questions = AIQuestion::where('curriculum_id', $curriculum_id)
                            ->latest()
                            ->get();

        return view('backend.teacher_questions.show_maths', compact('questions', 'curriculum'));
    }
    
}
