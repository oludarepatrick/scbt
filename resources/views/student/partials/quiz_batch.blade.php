@foreach ($questions as $question)
    <input type="hidden" name="question_ids[]" value="{{ $question->id }}">

    <div class="card mb-3 question-block" data-question-id="{{ $question->id }}">
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

            @foreach ($options as $key => $option)
                @if ($option)
                    <div class="form-check">
                        <input class="form-check-input" type="radio"
                            name="answers[{{ $question->id }}]"
                            value="{{ $key }}"
                            id="q{{ $question->id }}_{{ $key }}"
                            @if(isset($studentAnswers[$question->id]) && $studentAnswers[$question->id] === $key) checked @endif
                        >
                        <label class="form-check-label" for="q{{ $question->id }}_{{ $key }}">
                            {{ $key }}. {{ $option }}
                        </label>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endforeach
