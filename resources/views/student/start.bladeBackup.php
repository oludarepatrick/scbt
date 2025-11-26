@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <div class="col-md-10">
            <h3 class="mb-4">Quiz: {{ $curriculum->subject }} - {{ $curriculum->class }}</h3>

            @php
                $quizDuration = $curriculum->time ?? 15;
                $totalSeconds = $quizDuration * 60;
                $totalQuestions = $curriculum->aiQuestions()->count();
                $startQuestion = ($page - 1) * 3 + 1;
                $endQuestion = min($startQuestion + 2, $totalQuestions);
            @endphp

            {{-- Timer --}}
            <div id="timer" class="alert alert-info text-center font-weight-bold mb-2">
                Time Left: {{ floor($totalSeconds / 60) }}m {{ $totalSeconds % 60 }}s
            </div>

            {{-- Question progress info --}}
            <div id="questionRange" class="alert alert-secondary text-center">
                Question {{ $startQuestion }} - {{ $endQuestion }} of {{ $totalQuestions }}
            </div>

            <form id="quizForm">
                @csrf
                <input type="hidden" name="page" value="{{ $page }}">
                <input type="hidden" name="test_session_id" value="{{ $quiz->id }}">

                {{-- Questions --}}
                <div id="quiz-container">
                    @include('student.partials.quiz_batch', [
                        'questions' => $questions,
                        'studentAnswers' => $studentAnswers,
                        'page' => $page,
                        'hasMore' => $hasMore,
                        'quiz' => $quiz
                    ])
                </div>

                {{-- Navigation buttons --}}
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" id="prevBtn" class="btn btn-secondary" style="display: {{ $page > 1 ? 'inline-block' : 'none' }};">Previous</button>

                    <button type="button" id="nextBtn" class="btn btn-primary" disabled>Next</button>

                    <a href="{{ route('quiz.finish', $quiz->id) }}" id="submitBtn" class="btn btn-danger" onclick="disableExitProtection()" style="display: none;"
                    onclick="return confirm('Are you sure you want to submit?');">Submit Quiz</a>
                </div>
            </form>
        </div>
    </div>
</div>

@php
    $secondsLeft = isset($quiz->time_left) ? (int) $quiz->time_left : $quizDuration * 60;
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('quizForm');
    const quizContainer = document.getElementById('quiz-container');
    const pageInput = form.querySelector('input[name="page"]');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');

    //-----------------------------------------
    // 1. RESTORE TIME FROM DATABASE
    //-----------------------------------------
    let timeLeft = parseInt("{{ $quizUser->time_left }}");
    let quizUserId = "{{ $quizUser->id }}";
    const timerDisplay = document.getElementById('timer');

    //-----------------------------------------
    // 2. COUNTDOWN + DISPLAY
    //-----------------------------------------
    setInterval(() => {
        if (timeLeft > 0) {
            timeLeft--;
            timerDisplay.innerText =
                `Time Left: ${Math.floor(timeLeft/60)}m ${timeLeft%60}s`;
        } else {
            alert("Time is up! Submitting quiz...");
            document.getElementById('quizForm').submit();
        }
    }, 1000);

    //-----------------------------------------
    // 3. AUTO-SAVE TIME TO SERVER
    //-----------------------------------------
    setInterval(() => {
        fetch("{{ route('quiz.saveTime') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                quiz_user_id: quizUserId,
                time_left: timeLeft
            })
        });
    }, 10000);

    //-----------------------------------------
    // 4. LOAD NEXT/PREV + SAVE TIME
    //-----------------------------------------
    function loadPage(direction) {
        const formData = new FormData(form);
        const currentPage = parseInt(formData.get('page'));
        const newPage = currentPage + direction;
        if (newPage < 1) return;

        formData.set('page', newPage);
        formData.append('time_left', timeLeft);

        fetch("{{ route('quiz.next', $quiz->id) }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                quizContainer.innerHTML = data.html;
                pageInput.value = newPage;

                const total = {{ $curriculum->aiQuestions()->count() }};
                const perPage = 3;
                const start = (newPage - 1) * perPage + 1;
                const end = Math.min(start + perPage - 1, total);

                document.getElementById('questionRange').innerText =
                    `Question ${start} - ${end} of ${total}`;

                nextBtn.style.display = (end < total) ? 'inline-block' : 'none';
                prevBtn.style.display = newPage > 1 ? 'inline-block' : 'none';
                submitBtn.style.display = (end >= total) ? 'inline-block' : 'none';

                nextBtn.disabled = true;

                setTimeout(checkAnswersSelected, 100);
            }
        });
    }

    nextBtn.addEventListener('click', () => loadPage(1));
    prevBtn.addEventListener('click', () => loadPage(-1));

    //-----------------------------------------
    // 5. ENABLE / DISABLE NEXT BUTTON
    //-----------------------------------------
    function checkAnswersSelected() {
        const questions = quizContainer.querySelectorAll('[data-question-id]');
        let allAnswered = true;
        questions.forEach(q => {
            const opts = q.querySelectorAll('input[type="radio"]');
            if (!Array.from(opts).some(o => o.checked)) {
                allAnswered = false;
            }
        });
        nextBtn.disabled = !allAnswered;
    }

    quizContainer.addEventListener('change', checkAnswersSelected);
    checkAnswersSelected();

});
</script>
@endsection
