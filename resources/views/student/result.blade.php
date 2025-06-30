@extends('layouts.app')
@section('title', 'Quiz Result')

@section('content')
<div class="span9">
    <div class="content">
        <div class="module">
            <div class="module-head">
                <h3>AI Quiz Result</h3>
            </div>

    <div class="module-body">
        <div class="mb-3">
            @if ($curriculum)
            <p><strong>Student:</strong> {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</p>
            <p><strong>Subject:</strong> {{ $curriculum->subject }}</p>
            <p><strong>Class:</strong> {{ $curriculum->class }}</p>
            @else
            <p class="text-danger">Curriculum information not available.</p>
            @endif
            <p><strong>Total Questions:</strong> {{ $totalQuestions }}</p>
            <p><strong>Correct Answers:</strong> {{ $correctAnswers }}</p>
            @if ($totalQuestions > 0)
                <p><strong>Score:</strong> {{ round(($correctAnswers / $totalQuestions) * 100, 2) }}%</p>
            @else
                <p><strong>Score:</strong> Not available (No questions answered)</p>
            @endif  
        </div>
    </div>

    <hr>
    <div class="module-body">
    <div class="mb-3">
    <h4 class="mt-4">Question Breakdown</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Your Answer</th>
                <th>Correct Answer</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($answers as $index => $answer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{!! strip_tags($answer->question->question) !!}</td>
                    <td>{{ $answer->answer }}</td>
                    <td>{{ $answer->question->correct_answer }}</td>
                    <td>
                        @if ($answer->answer == $answer->question->correct_answer)
                            <span class="badge bg-success">Correct</span>
                        @else
                            <span class="badge bg-danger">Wrong</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>
</div>
</div>
@endsection
