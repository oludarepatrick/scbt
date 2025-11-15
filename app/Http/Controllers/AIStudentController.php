<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Curriculum;
use DB;
use App\Models\QuizUser;
use App\Models\Quiz;
use App\Models\AIQuestion;
use App\Models\StudentAnswer;
use Barryvdh\DomPDF\Facade\Pdf;

class AIStudentController extends Controller
{
    public function showLogin()
    {
        return view('student.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('ai.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

   public function dashboard()
{
    $student = Auth::user();

    // Get assigned quizzes with relationships
    $assignments = QuizUser::where('user_id', $student->id)
        ->with(['quiz.curriculum.aiQuestions'])
        ->get();

    // Map to quizzes
    $quizzes = $assignments->map(function ($assignment) {
    $quiz = $assignment->quiz;

    if ($quiz) {
        $quiz->status = $assignment->status;
        $quiz->time_left = $assignment->time_left;
        $quiz->quiz_user_id = $assignment->id;  // <-- Add this
    }

        return $quiz;
    });

    return view('student.index', compact('quizzes'));
}



    public function showAvailableQuizzes()
    {
        $student = Auth::user();

        $quizzes = DB::table('quiz_users')
            ->join('curriculums', 'quiz_users.quiz_id', '=', 'curriculums.id')
            ->where('quiz_users.user_id', $student->id)
            ->select('curriculums.*', 'quiz_user.status')
            ->get();

        return view('student.quizzes', compact('quizzes'));
    }


    public function viewResult($quizId)
{
    $studentId = auth()->id();

    $answers = StudentAnswer::with('question')
        ->where('user_id', $quizId)
        ->where('user_id', $studentId)
        ->get();

    $correctAnswers = $answers->filter(function ($ans) {
        return $ans->answer == $ans->question->correct_answer;
    })->count();

    $totalQuestions = $answers->count();

    // Safer curriculum loading
    $firstAnswer = $answers->first();
    $curriculum = $firstAnswer && $firstAnswer->question
        ? Curriculum::find($firstAnswer->question->curriculum_id)
        : null;

    $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : null;

    return view('student.result', compact('answers', 'correctAnswers', 'totalQuestions', 'curriculum', 'score'));
}



    public function start($quiz_id, Request $request)
{
    $userId = auth()->id();
    $page = $request->input('page', 1);
    $perPage = 3;

    // Fetch the quiz
    $quiz = Quiz::findOrFail($quiz_id);

    // Fetch the quiz_user row for this student
    $quizUser = QuizUser::where('quiz_id', $quiz->id)
        ->where('user_id', $userId)
        ->firstOrFail();

    // Block only completed quizzes
    if ($quizUser->status == 2) {
        return redirect()->route('ai.dashboard')->with('error', 'Invalid or completed quiz.');
    }

    // Mark as started
    if ($quizUser->status == 0) {
        $quizUser->update([
            'status' => 1,
            'started_at' => now(),
        ]);
    }

    // Initialize time_left if null
    if (is_null($quizUser->time_left)) {
        $quizUser->update(['time_left' => $quiz->minutes ?? 900]);
    }

    // Load curriculum
    $curriculum = Curriculum::findOrFail($quiz->curriculum_id);

    // Load questions for this curriculum
    $questions = AIQuestion::where('curriculum_id', $curriculum->id)
        ->skip(($page - 1) * $perPage)
        ->take($perPage + 1)
        ->get();

    $hasMore = $questions->count() > $perPage;
    $questions = $questions->take($perPage);

    $studentAnswers = StudentAnswer::where('user_id', $userId)
        ->where('test_session_id', $quizUser->id)
        ->pluck('answer_option', 'question_id');

    return view('student.start', compact('quiz', 'curriculum', 'questions', 'page', 'hasMore', 'studentAnswers', 'quizUser'));
}





    public function submit(Request $request, QuizUser $quizUser)
    {
        $request->validate([
            'question_id' => 'required|exists:ai_questions,id',
            'answer' => 'required|string',
        ]);

        $questionId = $request->question_id;
        $answer = $request->answer;

        StudentAnswer::updateOrCreate(
            [
                'quiz_user_id' => $quizUser->id,
                'question_id' => $questionId,
            ],
            [
                'answer' => $answer,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Redirect to next question or finish page if no more questions
        $answeredCount = StudentAnswer::where('quiz_user_id', $quizUser->id)->count();
        $totalQuestions = AIQuestion::where('curriculum_id', $quizUser->quiz_id)->count();

        if ($answeredCount >= $totalQuestions) {
            return redirect()->route('quiz.finish', $quizUser->id);
        }

        // Get next unanswered question
        $nextQuestion = AIQuestion::where('curriculum_id', $quizUser->quiz_id)
            ->whereNotIn('id', StudentAnswer::where('quiz_user_id', $quizUser->id)->pluck('question_id'))
            ->first();

        return redirect()->route('quiz.start', [$quizUser->quiz_id]);
    }

    public function finish(QuizUser $quizUser)
    {
        $quizUser->status = 2; // Mark as completed
        $quizUser->updated_at = now();
        $quizUser->save();

        return redirect()->route('quiz.result', $quizUser->id);
    }


public function next(Request $request, $quiz_id)
{
    $userId = auth()->id();
    $page = $request->input('page', 1);
    $perPage = 3;
    $testSessionId = $request->input('test_session_id');

    // Save answers
    $answers = $request->input('answers', []);
    foreach ($answers as $questionId => $answerOption) {
        StudentAnswer::updateOrCreate(
            [
                'quiz_id' => $quiz_id,
                'user_id' => $userId,
                'question_id' => $questionId,
                'test_session_id' => $testSessionId
            ],
            [
                'answer_option' => $answerOption,
                'question_type' => 'ai' // fill with correct type
            
            ]
        );
    }

    $quiz = QuizUser::where('quiz_id', $quiz_id)
        ->where('user_id', $userId)
        ->with('curriculum')
        ->first();

    if (!$quiz || !$quiz->curriculum) {
        return response()->json([
            'success' => false,
            'message' => 'Quiz session not found.'
        ]);
    }

    $curriculum = $quiz->curriculum;

    $questions = $curriculum->aiQuestions()
        ->skip(($page - 1) * $perPage)
        ->take($perPage + 1)
        ->get();

    $hasMore = $questions->count() > $perPage;
    $questions = $questions->take($perPage);

    $studentAnswers = $quiz->studentAnswers
        ->pluck('answer_option', 'question_id');

    $html = view('student.partials.quiz_batch', compact('questions', 'studentAnswers', 'page', 'hasMore', 'quiz'))->render();

    return response()->json([
        'success' => true,
        'html' => $html
    ]);
}




// Working but not saving time left
    /*public function nextAjax(Request $request, $quizUserId)
    {
        try {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Bad request'], 400);
        }

        $userId = auth()->id();
        $page = $request->input('page', 1);
        $perPage = 3;
        $answers = $request->input('answers', []);
        $testSessionId = $request->input('test_session_id');

        foreach ($answers as $questionId => $answerOption) {
            StudentAnswer::updateOrCreate(
                [
                    'quiz_id' => $quizUserId,
                    'user_id' => $userId,
                    'question_id' => $questionId,
                    'test_session_id' => $testSessionId
                ],
                [
                    'answer_option' => $answerOption,
                    'question_type' => 'ai' // fill with correct type
                ]
            );
        }

        $quiz = QuizUser::with('curriculum')->where('id', $quizUserId)->where('user_id', $userId)->first();
        $curriculum = $quiz->curriculum;

        $questions = $curriculum->aiQuestions()
            ->skip(($page - 1) * $perPage)
            ->take($perPage + 1)
            ->get();

        $hasMore = $questions->count() > $perPage;
        $questions = $questions->take($perPage);

        $studentAnswers = $quiz->studentAnswers()
            ->where('user_id', $userId)
            ->pluck('answer_option', 'question_id');

        $html = view('student.partials.quiz_batch', compact('questions', 'studentAnswers', 'page', 'hasMore', 'quiz'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'nextPage' => $page + 1,
            'hasMore' => $hasMore
        ]);

            } catch (\Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }*/

        public function nextAjax(Request $request, $quizUserId)
{
    try {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Bad request'], 400);
        }

        $userId = auth()->id();
        $page = $request->input('page', 1);
        $perPage = 3;
        $answers = $request->input('answers', []);
        $testSessionId = $request->input('test_session_id');

        // ✅ NEW (Save remaining time)
        if ($request->has('time_left')) {
            QuizUser::where('id', $quizUserId)
                ->where('user_id', $userId)
                ->update(['time_left' => $request->input('time_left')]);
        }

        // Save answers
        foreach ($answers as $questionId => $answerOption) {
            StudentAnswer::updateOrCreate(
                [
                    'quiz_id' => $quizUserId,
                    'user_id' => $userId,
                    'question_id' => $questionId,
                    'test_session_id' => $testSessionId
                ],
                [
                    'answer_option' => $answerOption,
                    'question_type' => 'ai'
                ]
            );
        }

        $quiz = QuizUser::with('curriculum')->where('id', $quizUserId)->where('user_id', $userId)->first();
        $curriculum = $quiz->curriculum;

        $questions = $curriculum->aiQuestions()
            ->skip(($page - 1) * $perPage)
            ->take($perPage + 1)
            ->get();

        $hasMore = $questions->count() > $perPage;
        $questions = $questions->take($perPage);

        $studentAnswers = $quiz->studentAnswers()
            ->where('user_id', $userId)
            ->pluck('answer_option', 'question_id');

        $html = view('student.partials.quiz_batch', compact('questions', 'studentAnswers', 'page', 'hasMore', 'quiz'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'nextPage' => $page + 1,
            'hasMore' => $hasMore
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}



    public function saveTime(Request $request, $id) // here, $id is quiz_users.id
    {
        $userId = auth()->id();
        $timeLeft = $request->input('time_left');

        $quiz = QuizUser::where('id', $id)->where('user_id', $userId)->first(); // changed this line

        if (!$quiz) {
            return response()->json(['status' => 'not found'], 404);
        }

        $quiz->time_left = $timeLeft;
        $quiz->save();

        return response()->json(['status' => 'saved']);
    }

    public function result($quizId)
    {
        $quiz = QuizUser::with('curriculum')->where('id', $quizId)->where('user_id', auth()->id())->firstOrFail();

        $answers = StudentAnswer::with('question')
            ->where('quiz_id', $quizId)
            ->where('user_id', auth()->id())
            ->get();

        $totalQuestions = $answers->count();
        $correctAnswers = $answers->filter(function ($answer) {
            return $answer->answer_option === optional($answer->question)->correct_option;
        })->count();

        $scorePercentage = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;

        // Remark logic
        if ($scorePercentage >= 80) {
            $remark = 'Excellent';
        } elseif ($scorePercentage >= 60) {
            $remark = 'Good';
        } elseif ($scorePercentage >= 40) {
            $remark = 'Fair';
        } else {
            $remark = 'Needs Improvement';
        }

        return view('student.quiz_result', compact('quiz', 'answers', 'correctAnswers', 'totalQuestions', 'scorePercentage', 'remark'));
    }

    public function exportResultPdf($quizId)
    {
        $quiz = QuizUser::with('curriculum')->where('id', $quizId)->where('user_id', auth()->id())->firstOrFail();

        $answers = StudentAnswer::with('question')
            ->where('quiz_id', $quizId)
            ->where('user_id', auth()->id())
            ->get();

        $totalQuestions = $answers->count();
        $correctAnswers = $answers->filter(fn($a) => $a->answer_option === optional($a->question)->correct_option)->count();
        $scorePercentage = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;

        $remark = match (true) {
            $scorePercentage >= 80 => 'Excellent',
            $scorePercentage >= 60 => 'Good',
            $scorePercentage >= 40 => 'Fair',
            default => 'Needs Improvement',
        };

        $pdf = Pdf::loadView('student.quiz_result_pdf', compact(
            'quiz', 'answers', 'correctAnswers', 'totalQuestions', 'scorePercentage', 'remark'
        ));

        return $pdf->download("quiz_result_{$quiz->id}.pdf");
    }



}


