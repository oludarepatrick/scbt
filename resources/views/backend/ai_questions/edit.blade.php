@extends('backend.layouts.master')

@section('title','Edit AI Question')

@section('content')
<div class="span9">
    <div class="content">
        <form action="{{ route('ai_questions.update', $question->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="module">
                <div class="module-head">
                    <h3>Edit Question</h3>
                </div>
                <div class="module-body">
                    <div class="mb-3">
                        <label class="control-label">Question</label>
                        <input type="text" name="question" class="form-control span6" value="{{ $question->question }}">
                        @error('question')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="control-label">Options (comma separated)</label>
                        <input type="text" name="options[]" class="form-control span6" value="{{ implode(',', json_decode($question->options, true)) }}">
                        @error('options')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="control-label">Correct Answer</label>
                        <input type="text" name="correct_answer" class="form-control span6" value="{{ $question->correct_answer }}">
                        @error('correct_answer')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" class="btn btn-primary">Update Question</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection