@foreach ($questions as $question)
    <input type="hidden" name="question_ids[]" value="{{ $question->id }}">

    <div class="card mb-3 question-block" data-question-id="{{ $question->id }}">
        <div class="card-body">
            {{-- Question Number and Text --}}
            <h5 class="mb-3">
                <strong>Q{{ $loop->iteration + ($page - 1) * 3 }}:</strong> {{ strip_tags(html_entity_decode($question->question_text)) }}
            </h5>

            @php
                $options = [
                    'A' => $question->option_a,
                    'B' => $question->option_b,
                    'C' => $question->option_c,
                    'D' => $question->option_d,
                    'E' => $question->option_e, // optional E
                ];
            @endphp

            <div class="list-group">
                @foreach ($options as $key => $option)
                    @if($option)
                        <label class="list-group-item list-group-item-action">
                            <input class="form-check-input me-2" type="radio"
                                name="answers[{{ $question->id }}]"
                                value="{{ $key }}"
                                id="q{{ $question->id }}_{{ $key }}"
                                @if(isset($studentAnswers[$question->id]) && $studentAnswers[$question->id] === $key) checked @endif
                            >
                            <strong>{{ $key }}.</strong> {{ $option }}
                        </label>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endforeach

@if($questions->isEmpty())
    <div class="alert alert-warning text-center">
        No questions available for this quiz.
    </div>
@endif

