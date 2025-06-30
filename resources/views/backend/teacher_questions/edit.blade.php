@extends('backend.layouts.master')

@section('title', 'Edit Question')

@section('content')
<div class="span9">
    <div class="content">

        <div class="module">
            <div class="module-head">
                <h3>Edit Question</h3>
            </div>

            <div class="module-body">
                <form action="{{ route('teacher.questions.update', $question->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Question</label>
                        <input type="text" name="question" value="{{ old('question', $question->question) }}" class="form-control span8" required>
                        @error('question')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    @php $options = json_decode($question->options, true); @endphp
                    @foreach($options as $key => $option)
                        <div class="mb-3">
                            <label>Option {{ chr(65 + $key) }}</label>
                            <input type="text" name="options[]" value="{{ old("options.$key", $option) }}" class="form-control span8" required>
                        </div>
                    @endforeach

                    <div class="mb-3">
                        <label>Correct Option (e.g., A, B, C)</label>
                        <input type="text" name="correct_option" value="{{ old('correct_option', $question->correct_option) }}" class="form-control span8" required>
                        @error('correct_option')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Update Question</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
