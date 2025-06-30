@extends('backend.layouts.master')

@section('title','Generate AI Questions')

@section('content')
<div class="span9">
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('ai_questions.store') }}" method="POST">
            @csrf
            <div class="module">
                <div class="module-head">
                    <h3>Select Curriculum</h3>
                </div>
                <div class="module-body">
                    <div class="mb-3">
                        <label class="control-label">Curriculum</label>
                        <select name="curriculum_id" class="form-control span6">
                            <option value="">Select Curriculum</option>
                            @foreach($curriculums as $curriculum)
                                <option value="{{ $curriculum->id }}">{{ $curriculum->title }}</option>
                            @endforeach
                        </select>
                        @error('curriculum_id')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" class="btn btn-success">Generate Questions</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection