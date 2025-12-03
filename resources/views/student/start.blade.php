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
                    <div id="bottomTimer"
                        class="text-center mt-4 p-2"
                        style="font-size: 1.8rem; font-weight: bold; color:#b30000;">
                    </div>

                {{-- Navigation buttons --}}
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" id="prevBtn" class="btn btn-secondary" style="display: {{ $page > 1 ? 'inline-block' : 'none' }};">Previous</button>

                    <button type="button" id="nextBtn" class="btn btn-primary" disabled>Next</button>

                    <a href="{{ route('quiz.finish', $quiz->id) }}" id="submitBtn" class="btn btn-danger" style="display: none;"
                    onclick="return confirm('Are you sure you want to submit?');">Submit Quiz</a>
                </div>
            </form>
        </div>
    </div>
</div>

@php
    // Use QuizUser saved time_left if available, otherwise use quiz minutes or curriculum default (minutes -> seconds)
    $defaultMinutes = $quiz->minutes ?? $curriculum->time ?? 15;
    $secondsLeft = isset($quizUser->time_left) && is_numeric($quizUser->time_left)
        ? (int) $quizUser->time_left
        : ($defaultMinutes * 60);
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('quizForm');
    const quizContainer = document.getElementById('quiz-container');
    const pageInput = form.querySelector('input[name="page"]');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    const timerDisplay = document.getElementById('timer');

    // Single authoritative time variable (seconds)
    let timeLeft = {{ $secondsLeft }};
    const quizUserId = "{{ $quizUser->id }}";
    const quizId = "{{ $quiz->id }}"; // used in next route param

    // UI update
    function renderTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerDisplay.innerText = `Time Left: ${minutes}m ${seconds}s`;
    }
    renderTimer();

    // Count down every second
    const countdownInterval = setInterval(() => {
        if (timeLeft > 0) {
            timeLeft--;
            renderTimer();
        } else {
            clearInterval(countdownInterval);
            alert('Time is up! Submitting quiz...');
            // Option: call finish endpoint via AJAX instead of form.submit() to avoid losing CSRF/session state
            //form.submit();

            fetch("{{ route('quiz.finish.post', $quizUser->id) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ })
                })
                .then(res => res.json())
                .then(data => {
                    if (data && data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = "{{ route('ai.dashboard') }}";
                    }
                })
                .catch(() => window.location.href = "{{ route('ai.dashboard') }}");

           

        }
    }, 1000);

    // Auto-save time to server every 10 seconds
    const autosaveInterval = setInterval(() => {
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
        }).catch(err => console.error('Failed saving time', err));
    }, 10000);

    // Remove autosave when leaving (optional)
    window.addEventListener('beforeunload', function () {
        navigator.sendBeacon("{{ route('quiz.saveTime') }}", JSON.stringify({
            quiz_user_id: quizUserId,
            time_left: timeLeft
        }));
    });

    // Page loader (next/prev) - single place that saves time and loads page content
    function loadPage(direction) {
        const formData = new FormData(form);
        const currentPage = parseInt(formData.get('page'));
        const newPage = currentPage + direction;
        if (newPage < 1) return;
        formData.set('page', newPage);

        // append authoritative time_left
        formData.set('time_left', timeLeft);

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

                document.getElementById('questionRange').innerText = `Question ${start} - ${end} of ${total}`;

                nextBtn.style.display = (end < total) ? 'inline-block' : 'none';
                prevBtn.style.display = newPage > 1 ? 'inline-block' : 'none';
                submitBtn.style.display = (end >= total) ? 'inline-block' : 'none';

                nextBtn.disabled = true;
                setTimeout(checkAnswersSelected, 100);
            } else {
                alert("Error loading questions.");
            }
        })
        .catch(err => {
            console.error("AJAX error", err);
            alert("An error occurred.");
        });
    }

    nextBtn.addEventListener('click', function () { loadPage(1); });
    prevBtn.addEventListener('click', function () { loadPage(-1); });

    // check answers selection
    function checkAnswersSelected() {
        const questions = quizContainer.querySelectorAll('[data-question-id]');
        let allAnswered = true;
        questions.forEach(q => {
            const options = q.querySelectorAll('input[type="radio"]');
            const answered = Array.from(options).some(opt => opt.checked);
            if (!answered) allAnswered = false;
        });
        nextBtn.disabled = !allAnswered;
    }

    quizContainer.addEventListener('change', function (e) {
        if (e.target.matches('input[type="radio"]')) {
            checkAnswersSelected();
        }
    });

    checkAnswersSelected();

    //Submit event listener
    submitBtn.addEventListener('click', function (e) {
    e.preventDefault(); // stop direct navigation

    if (!confirm('Are you sure you want to submit your exam?')) return;

    // Save time + answers from the current page before submission
    const formData = new FormData(form);
    formData.set('time_left', timeLeft);

    fetch("{{ route('quiz.next', $quiz->id) }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: formData
    })
    .finally(() => {
        // Now finish exam
        fetch("{{ route('quiz.finish.post', $quizUser->id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
            if (data && data.redirect) {
                window.location.href = data.redirect;
            } else {
                window.location.href = "{{ route('ai.dashboard') }}";
            }
        })
        .catch(() => window.location.href = "{{ route('ai.dashboard') }}");
    });
});

});
</script>
@endsection
