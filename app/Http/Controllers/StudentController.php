<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\Question;
use App\Models\Curriculum;
use App\Models\AiQuestion;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function dashboard()
    {
        // Show student dashboard with available quizzes/tests
        $quizzes = Quiz::all();
        return view('student.dashboard', compact('quizzes'));
    }

    public function startTest($quizId)
    {
        // Load quiz questions for student to answer
        $quiz = Quiz::findOrFail($quizId);
        $questions = $quiz->questions()->with('answers')->get();

        return view('student.test', compact('quiz', 'questions'));
    }

    public function submitTest(Request $request, $quizId)
    {
        // Validate and save student's answers, compute score
        $user = auth()->user();
        $answers = $request->input('answers'); // e.g. ['question_id' => 'answer_id']

        foreach ($answers as $questionId => $answerId) {
            Result::create([
                'user_id' => $user->id,
                'question_id' => $questionId,
                'quiz_id' => $quizId,
                'answer_id' => $answerId,
            ]);
        }

        // Redirect to results page or dashboard
        return redirect()->route('student.results', ['quizId' => $quizId]);
    }

    public function results($quizId)
    {
        // Show student's results for a quiz
        $user = auth()->user();
        $results = Result::where('user_id', $user->id)
            ->where('quiz_id', $quizId)
            ->with(['question', 'answer'])
            ->get();

        return view('student.results', compact('results'));
    }

    // Ai questions
    public function showAvailableQuizzes()
    {
        $studentId = auth()->id();

        /*$curriculums = DB::table('quiz_user')
            ->where('user_id', $studentId)
            ->join('curriculums', 'quiz_user.quiz_id', '=', 'curriculums.id')
            ->select('curriculums.*', 'quiz_user.status')
            ->get();*/

            $curriculums = DB::table('quiz_user')
            ->join('curriculums', 'quiz_user.quiz_id', '=', 'curriculums.id')
            ->select('curriculums.*', 'quiz_user.status')
            ->where('quiz_user.user_id', auth()->id()) // ✅ fixed here
            ->get();

        return view('student.index', compact('curriculums'));
    }

    public function takeQuiz(Curriculum $curriculum)
    {
        $studentId = auth()->id();

        // Check if the student is authorized to take this quiz
        $quiz = DB::table('quiz_user')
            ->where('quiz_id', $curriculum->id)
            ->where('user_id', $studentId)
            ->first();

        if (!$quiz) {
            return redirect()->route('student.quizzes')->with('error', 'Unauthorized access to quiz.');
        }

        $questions = AiQuestion::where('curriculum_id', $curriculum->id)->get();

        return view('student.quizzes.take', compact('curriculum', 'questions'));
    }
}
