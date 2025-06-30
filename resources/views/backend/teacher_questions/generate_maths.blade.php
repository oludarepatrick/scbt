@extends('backend.layouts.master')

@section('title', 'Generate AI Questions')

@section('content')

<div class="span9">
    <div class="content">
        @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
        @endif
        @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
        @endif

    @if(Session::has('message'))
        <div class="alert alert-success">{{Session::get('message')}}</div>
    @endif
    <div class="module">
        <div class="module-head">
                <h3>Generate Questionskk</h3>
            </div>
        <form action="{{ route('ai_questions.generate_maths') }}" method="POST">
            @csrf

            <div class="module-body">
                <div class="mb-3">
                    <label class="control-label">Select Curriculum</label>
                    <select name="curriculum_id" class="form-control span6" required>
                        <option value="">Select Curriculum</option>
                        @foreach ($curriculums as $curriculum)
                            <option value="{{ $curriculum->id }}">{{ $curriculum->class }} - {{ $curriculum->subject }}</option>
                        @endforeach
                    </select>
                    @error('class')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

            <div class="mb-3">
                <label class="control-label">Number of Questions</label>
                <input type="number" name="number" class="form-control" min="1" max="50" required>
            </div>

            
            <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-primary">Generate Questions</button>
                    </div>
            </div>
        </form>
    </div>
</div>
@endsection
