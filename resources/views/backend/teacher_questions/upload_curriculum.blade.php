@extends('backend.layouts.master')

@section('title', 'Upload Curriculum & Scheme of Work')

@section('content')

<div class="span9">
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teacher.curriculum.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="module">
                <div class="module-head">
                    <h3>Upload Curriculum & Scheme of Work to Generate Questions</h3>
                </div>
                <div class="module-body">

                    <div class="control-group mb-3">
                        <label class="control-label" for="name">Question Name <span class="text-danger">*</span></label>
                        <div class="controls">
                            <input type="text" name="name" id="name" class="form-control span6" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="control-label">Select Subject</label>
                        <select name="subject" class="form-control span6">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject }}">{{ $subject }}</option>
                            @endforeach
                        </select>
                        @error('subject')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="control-label">Select Class</label>
                        <select name="class" class="form-control span6">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class }}">{{ $class }}</option>
                            @endforeach
                        </select>
                        @error('class')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="control-group mb-3">
                        <label class="control-label" for="time">Set Time <span class="text-danger">*</span></label>
                        <div class="controls">
                            <input type="text" name="time" id="time" class="form-control span6" value="{{ old('time') }}" required>
                        </div>
                    </div>

                    <div class="control-group mb-3">
                        <label class="control-label" for="curriculum_file">Upload Curriculum File (PDF or Word)</label>
                        <div class="controls">
                            <input type="file" name="curriculum_file" id="curriculum_file" class="form-control span6" accept=".pdf,.doc,.docx">
                            <small class="form-text text-muted">You can upload a PDF or Word document.</small>
                        </div>
                    </div>

                    <div class="control-group mb-3">
                        <label class="control-label" for="curriculum_text">Or Paste Curriculum Text</label>
                        <div class="controls">
                            <textarea name="curriculum_text" id="curriculum_text" class="form-control span6" rows="6" placeholder="Paste curriculum content here...">{{ old('curriculum_text') }}</textarea>
                            <small class="form-text text-muted">If you upload a file, this field is optional.</small>
                        </div>
                    </div>

                    <div class="control-group mb-3">
                        <label class="control-label" for="scheme_of_work">Scheme of Work (Optional)</label>
                        <div class="controls">
                            <textarea name="scheme_of_work" id="scheme_of_work" class="form-control span6" rows="4" placeholder="Add scheme of work here...">{{ old('scheme_of_work') }}</textarea>
                        </div>
                    </div>

                    <div class="control-group mb-3">
                        <label class="control-label" for="lesson_note">Lesson Note (Optional)</label>
                        <div class="controls">
                            <textarea name="lesson_note" id="lesson_note" class="form-control span6" rows="4" placeholder="Add lesson note here...">{{ old('lesson_note') }}</textarea>
                        </div>
                    </div>

                    <div class="control-group text-center mt-3">
                        <button type="submit" class="btn btn-primary">Upload Curriculum</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection
