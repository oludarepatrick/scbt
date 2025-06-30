<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quiz Result</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; color: #000; }
        h2, h4 { margin-bottom: 5px; }
        .section { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #333; padding: 8px; text-align: left; }
        .summary { background-color: #f0f0f0; padding: 10px; margin-top: 15px; }
    </style>
</head>
<body>

    <h2>Quiz Result Summary</h2>

    <div class="section">
        <strong>Student:</strong> {{ auth()->user()->name }}<br>
        <strong>Subject:</strong> {{ $quiz->curriculum->subject }}<br>
        <strong>Class:</strong> {{ $quiz->curriculum->class }}<br>
        <strong>Date Taken:</strong> {{ $quiz->created_at->format('d M Y, h:i A') }}
    </div>

    <div class="summary">
        <strong>Total Questions:</strong> {{ $totalQuestions }}<br>
        <strong>Correct Answers:</strong> {{ $correctAnswers }}<br>
        <strong>Score:</strong> {{ $scorePercentage }}%<br>
        <strong>Remark:</strong> {{ $remark }}
    </div>

    <h4>Answer Breakdown</h4>
    <table class="table">
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
                    <td>{{ $answer->question->question }}</td>
                    <td>{{ $answer->answer_option }}</td>
                    <td>{{ $answer->question->correct_option }}</td>
                    <td>
                        @if ($answer->answer_option === $answer->question->correct_option)
                            Correct
                        @else
                            Wrong
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 40px; text-align: center;">
        &copy; {{ date('Y') }} SCHOOLDRIVE AI CBT Platform
    </p>

</body>
</html>
