@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Available AI Quizzes</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Class</th>
                <th>Time Left</th>
                <th>Date Created</th>
                <th>Status</th>
                <th>Score</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quizzes as $quiz)
                <tr>
                    <td>{{ $quiz->curriculum->subject ?? 'N/A' }}</td>
                    <td>{{ $quiz->curriculum->class ?? 'N/A' }}</td>
                    <td>
                        @if($quiz->time_left)
                            {{ floor($quiz->time_left / 60) }}m {{ $quiz->time_left % 60 }}s
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if ($quiz->curriculum)
                            {{ \Carbon\Carbon::parse($quiz->curriculum->created_at)->format('d M, Y') }}
                        @else
                            N/A
                        @endif
                    </td>

                    {{-- Status Column --}}
                    <td>
                        @if($quiz->status == 0)
                            Not Started
                        @elseif($quiz->status == 1)
                            Continue
                        @elseif($quiz->status == 2)
                            Completed
                        @else
                            Unknown
                        @endif
                    </td>
                    <td>
                        @if($quiz->status == 2)
                            @php
                                // Count correct answers
                                $correct = \DB::table('student_answers')
                                    ->join('ai_questions', 'student_answers.question_id', '=', 'ai_questions.id')
                                    ->where('student_answers.quiz_id', $quiz->id)
                                    ->whereColumn('student_answers.answer_option', 'ai_questions.correct_option')
                                    ->count();

                                // Total questions
                                $total = $quiz->curriculum->aiQuestions()->count();

                                // Calculate percentage
                                $percent = $total > 0 ? round(($correct / $total) * 100, 1) : 0;
                            @endphp

                            {{ $correct }}/{{ $total }} ({{ $percent }}%)
                        @else
                            -
                        @endif
                    </td>

                   {{-- Action Buttons --}}
                <td>
                    @if ($quiz->curriculum)

                        {{-- Start button --}}
                       @if ($quiz->status == 0)
                            <a href="{{ route('ai.quiz.start', ['quizId' => $quiz->id]) }}"
                            onclick="return confirm('Are you sure you want to start this quiz?')"
                            class="btn btn-sm btn-primary">Start</a>

                        @elseif ($quiz->status == 1)
                            <a href="{{ route('ai.quiz.start', ['quizId' => $quiz->id, 'page' => 1]) }}"
                            class="btn btn-sm btn-warning">Continue</a>

                        @elseif ($quiz->status == 2)
                            <a href="{{ route('quiz.result', ['quizUser' => $quiz->quiz_user_id ?? $quiz->id]) }}"
                            class="btn btn-sm btn-success">View Result</a>
                        @endif


                    @else
                        <button class="btn btn-sm btn-secondary" disabled>No Curriculum</button>
                    @endif
                </td>


                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
