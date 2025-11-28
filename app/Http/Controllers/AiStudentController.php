<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Curriculum;
use DB;
use App\Models\QuizUser;
use App\Models\Quiz;
use App\Models\AiQuestion;
use App\Models\StudentAnswer;
use App\Models\TestSession;
use Barryvdh\DomPDF\Facade\Pdf;

class AiStudentController extends Controller
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

    /*public function dashboard()
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
            $quiz->quiz_id = $assignment->id;  // <-- Add this
        }
    
            return $quiz;
        });
    
        return view('student.index', compact('quizzes'));
    }*/

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
            $quiz->time_left = $assignment->time_left ?? ($quiz->minutes * 60);
            $quiz->quiz_user_id = $assignment->id; // preserve for actions
            return $quiz;
        }
    
        return null;
    })->filter(); // <-- remove nulls
    

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
    $questions = AiQuestion::where('curriculum_id', $curriculum->id)
        ->skip(($page - 1) * $perPage)
        ->take($perPage + 1)
        ->get();

    $hasMore = $questions->count() > $perPage;
    $questions = $questions->take($perPage);

    //$studentAnswers = StudentAnswer::where('user_id', $userId)->where('test_session_id', $quizUser->id)->pluck('answer_option', 'question_id');
    $studentAnswers = StudentAnswer::where('test_session_id', $quizUser->id)->where('user_id', $quizUser->user_id)->pluck('answer_option', 'question_id');
    $secondsLeft = $quizUser->time_left ?? ($quiz->minutes * 60);

    return view('student.start', compact(
        'quiz', 'curriculum', 'questions',
        'page', 'hasMore', 'studentAnswers',
        'quizUser', 'secondsLeft'
    ));
    //return view('student.start', compact('quiz', 'curriculum', 'questions', 'page', 'hasMore', 'studentAnswers', 'quizUser'));
}


    public function submit(Request $request, QuizUser $quizUser)
    {
        $request->validate([
            'question_id' => 'required|exists:ai_questions,id',
            'answer' => 'required|string',
        ]);

        $questionId = $request->question_id;
        $answer = $request->answer;

        // Save or update the student's answer
        StudentAnswer::updateOrCreate([
            'quiz_id' => $quizUser->quiz_id,
            'test_session_id' => $quizUser->id, // new addition
            'user_id' => $quizUser->user_id,
            'question_id' => $questionId
        ], [
            'answer_option' => $answer,
            'question_type' => 'ai'
        ]);

        // Count how many questions have been answered by this student for this quiz
        $answeredCount = StudentAnswer::where('quiz_id', $quizUser->quiz_id)
            ->where('user_id', $quizUser->user_id)
            ->count();

        // Total number of AI questions in this curriculum/quiz
        $totalQuestions = AiQuestion::where('curriculum_id', $quizUser->quiz_id)->count();

        // If all questions are answered, redirect to finish page
        if ($answeredCount >= $totalQuestions) {
            return redirect()->route('quiz.finish', $quizUser->id);
        }

        // Get next unanswered question
        $nextQuestion = AiQuestion::where('curriculum_id', $quizUser->quiz_id)
            ->whereNotIn('id', StudentAnswer::where('quiz_id', $quizUser->quiz_id)
                ->where('user_id', $quizUser->user_id)
                ->pluck('question_id'))
            ->first();

        // Redirect to quiz start page (or you can pass $nextQuestion if needed)
        return redirect()->route('quiz.start', [$quizUser->quiz_id]);
    }


    public function finish(Request $request, QuizUser $quizUser)
{
    if ($quizUser->status != 2) {
        $quizUser->update([
            'status' => 2,
            'ended_at' => now(),
            'time_left' => 0,
        ]);
    }

    if ($request->ajax() || $request->isMethod('post')) {
        return response()->json([
            'success' => true,
            'redirect' => route('quiz.result', $quizUser->id)
        ]);
    }

    return redirect()->route('quiz.result', $quizUser->id);
}

    


public function next(Request $request, $quiz_id)
{
    $userId = auth()->id();
    $page = $request->input('page', 1);
    $perPage = 3;

    // Fetch QuizUser row for this student
    $quizUser = QuizUser::where('quiz_id', $quiz_id)
        ->where('user_id', $userId)
        ->firstOrFail();

    // Save answers
    $answers = $request->input('answers', []);
    foreach ($answers as $questionId => $answerOption) {
        StudentAnswer::updateOrCreate([
            'test_session_id' => $quizUser->id,
            'user_id'         => $quizUser->user_id,
            'question_id'     => $questionId
        ], [
            'quiz_id'        => $quizUser->quiz_id,
            'answer_option'  => $answerOption,
            'question_type'  => 'ai'
        ]);
    }
    

    // Save remaining time if sent
    if ($request->filled('time_left')) {
        $quizUser->time_left = (int) $request->input('time_left');
        $quizUser->save();
    }

    $curriculum = $quizUser->quiz->curriculum;

    // Fetch batch of questions
    $questions = $curriculum->aiQuestions()
        ->skip(($page - 1) * $perPage)
        ->take($perPage + 1)
        ->get();

    $hasMore = $questions->count() > $perPage;
    $questions = $questions->take($perPage);

    // Load previous answers for this student
    //$studentAnswers = StudentAnswer::where('quiz_id', $quizUser->id)->pluck('answer_option', 'question_id');
    $studentAnswers = StudentAnswer::where('test_session_id', $quizUser->id)->where('user_id', $quizUser->user_id)->pluck('answer_option', 'question_id');


    $html = view('student.partials.quiz_batch', compact(
        'questions', 'studentAnswers', 'page', 'hasMore', 'quizUser'
    ))->render();

    return response()->json([
        'success' => true,
        'html' => $html
    ]);
}





// Working but not saving time left
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

        // Fetch QuizUser row
        $quizUser = QuizUser::with('curriculum', 'quiz')
            ->where('quiz_id', $quizUserId)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Save answers
        foreach ($answers as $questionId => $answerOption) {
            StudentAnswer::updateOrCreate([
                'test_session_id' => $quizUser->id,
                'user_id'         => $quizUser->user_id,
                'question_id'     => $questionId
            ], [
                'quiz_id'        => $quizUser->quiz_id,
                'answer_option'  => $answerOption,
                'question_type'  => 'ai'
            ]);

        }

        // Save remaining time if sent
        if ($request->filled('time_left')) {
            $quizUser->time_left = (int) $request->input('time_left');
            $quizUser->save();
        }

        $curriculum = $quizUser->quiz->curriculum;

        // Fetch batch of questions
        $questions = $curriculum->aiQuestions()
            ->skip(($page - 1) * $perPage)
            ->take($perPage + 1)
            ->get();

        $hasMore = $questions->count() > $perPage;
        $questions = $questions->take($perPage);

        // Load previous answers
        //$studentAnswers = StudentAnswer::where('quiz_id', $quizUser->id)->pluck('answer_option', 'question_id');
        $studentAnswers = StudentAnswer::where('test_session_id', $quizUser->id)->where('user_id', $quizUser->user_id)->pluck('answer_option', 'question_id');


        $html = view('student.partials.quiz_batch', compact(
            'questions', 'studentAnswers', 'page', 'hasMore', 'quizUser'
        ))->render();

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


       



    /*public function saveTime2(Request $request, $id) // here, $id is quiz_users.id
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
    }*/

    public function result($quizId)
{
    // Load quiz with curriculum and validate ownership
    $quiz = QuizUser::with('curriculum')
        ->where('id', $quizId)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    // Load student answers together with the actual AI questions
    $answers = StudentAnswer::with(['question' => function ($q) {
        $q->select('id', 'correct_option', 'option_a', 'option_b', 'option_c', 'option_d', 'question_text');
    }])
    ->where('quiz_id', $quizId)
    ->where('user_id', auth()->id())
    ->get();

    // Total number of answered questions
    $totalQuestions = $answers->count();

    // Correct answers based on comparing correct_option vs answer_option
    $correctAnswers = $answers->filter(function ($answer) {
        return strtoupper($answer->answer_option) === strtoupper(optional($answer->question)->correct_option);
    })->count();

    // Score percentage
    $scorePercentage = $totalQuestions > 0
        ? round(($correctAnswers / $totalQuestions) * 100, 2)
        : 0;

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

    return view('student.quiz_result', compact(
        'quiz', 'answers', 'correctAnswers', 'totalQuestions', 'scorePercentage', 'remark'
    ));
}



    /*public function result2($quizId)
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
    }*/

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

    public function saveTime(Request $request)
    {
        $userId = auth()->id();
        $quizUserId = $request->input('quiz_user_id');
        $timeLeft = (int) $request->input('time_left');

        $quizUser = QuizUser::where('id', $quizUserId)
            ->where('user_id', $userId)
            ->first();

        if (!$quizUser) {
            return response()->json(['success' => false, 'message' => 'Session not found']);
        }

        $quizUser->time_left = $timeLeft;
        $quizUser->save();

        return response()->json(['success' => true]);
    }



}


