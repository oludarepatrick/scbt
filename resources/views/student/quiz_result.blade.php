@extends('layouts.app')
<style>
@media print {
    .no-print {
        display: none !important;
    }
}
</style>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <h3 class="mb-4">Quiz Result</h3>

            <div class="card mb-4">
                <div class="card-body">
                    <h5>Subject: {{ $quiz->curriculum->subject }}</h5>
                    <p>Class: {{ $quiz->curriculum->class }}</p>
                    <p>Date Taken: {{ $quiz->created_at->format('F j, Y g:i A') }}</p>
                </div>
            </div>

            <div class="alert alert-primary">
                <h5>Performance Summary</h5>
                <ul class="mb-0">
                    <li><strong>Correct Answers:</strong> {{ $correctAnswers }} / {{ $totalQuestions }}</li>
                    <li><strong>Score:</strong> {{ $scorePercentage }}%</li>
                    <li><strong>Remark:</strong> <span class="text-info">{{ $remark }}</span></li>
                </ul>
            </div>


            @foreach ($answers as $answer)
                @php
                    $question = $answer->question;
                    $isCorrect = $answer->answer_option === $question->correct_option;
                @endphp

                <div class="card mb-3 border-{{ $isCorrect ? 'success' : 'danger' }}">
                    <div class="card-body">
                        <h5>{{ strip_tags(html_entity_decode($question->question)) }}</h5>

                        @php
                            $options = [
                                'A' => $question->option_a,
                                'B' => $question->option_b,
                                'C' => $question->option_c,
                                'D' => $question->option_d,
                            ];
                        @endphp

                        @foreach ($options as $key => $value)
                            @if ($value)
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="radio" disabled
                                        {{ $key === $answer->answer_option ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label {{ $key === $question->correct_option ? 'text-success' : '' }}">
                                        {{ $key }}. {{ $value }}
                                        @if($key === $question->correct_option)
                                            <span class="badge bg-success">Correct Answer</span>
                                        @endif
                                        @if($key === $answer->answer_option && $key !== $question->correct_option)
                                            <span class="badge bg-danger">Your Choice</span>
                                        @endif
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="no-print">
                <a href="{{ route('ai.dashboard') }}" class="btn btn-primary mt-3">Back to Dashboard</a>

                <button class="btn btn-outline-dark mt-3" onclick="window.print()">
                    Print / Save as PDF
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
