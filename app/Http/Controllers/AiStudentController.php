<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Curriculum;
use DB;
use App\Models\QuizUser;
use App\Models\AIQuestion;
use App\Models\StudentAnswer;
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

    public function dashboard()
    {
        $student = Auth::user();
        //dd($student->id);

        $quizzes = QuizUser::with('curriculum')
                ->where('user_id', auth()->id())
                ->get();

        return view('student.index', compact('quizzes'));
    }

    public function showAvailableQuizzes()
    {
        $student = Auth::user();

        $quizzes = DB::table('quiz_user')
            ->join('curriculums', 'quiz_user.quiz_id', '=', 'curriculums.id')
            ->where('quiz_user.user_id', $student->id)
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
        $page = $request->input('page', 1); // Default to page 1
        $perPage = 3; // Number of questions per page

        $quiz = DB::table('quiz_user')
            ->where('quiz_id', $quiz_id)
            ->where('user_id', $userId)
            ->first();

        if (!$quiz || $quiz->status == 1) {
            return redirect()->route('ai.dashboard')->with('error', 'Invalid or completed quiz.');
        }

        // If time_left is null, set default and update DB
        if (is_null($quiz->time_left)) {
            DB::table('quiz_user')
                ->where('id', $quiz->id)
                ->update(['time_left' => 900]);
            $quiz->time_left = 900;
        }

        $curriculum = Curriculum::findOrFail($quiz_id);

        $questions = AIQuestion::where('curriculum_id', $quiz_id)
            ->skip(($page - 1) * $perPage)
            ->take($perPage + 1) // Fetch one extra to check if more pages exist
            ->get();

        $hasMore = $questions->count() > $perPage;
        $questions = $questions->take($perPage);

        $studentAnswers = StudentAnswer::where('user_id', $userId)
            ->where('test_session_id', $quiz->id)
            ->pluck('answer_option', 'question_id');

        return view('student.start', compact('quiz', 'curriculum', 'questions', 'page', 'hasMore', 'studentAnswers'));
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
                    'answer_option' => $answerOption
                ]
            );
        }

        $quiz = QuizUser::where('quiz_id', $quiz_id)
            ->where('user_id', $userId)
            ->with('curriculum')
            ->first();

        if (!$quiz || !$quiz->curriculum) {
            return redirect()->route('ai.quiz.start', ['id' => $quiz_id])->with('error', 'Quiz session not found.');
        }

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

        return view('student.start', compact('quiz', 'curriculum', 'questions', 'page', 'hasMore', 'studentAnswers'));
    }

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

        foreach ($answers as $questionId => $answerOption) {
            StudentAnswer::updateOrCreate(
                [
                    'quiz_id' => $quizUserId,
                    'user_id' => $userId,
                    'question_id' => $questionId,
                    'test_session_id' => $testSessionId
                ],
                [
                    'answer_option' => $answerOption
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


