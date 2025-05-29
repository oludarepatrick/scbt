@extends('backend.layouts.master')

@section('title','Add Subject to Platform')

@section('content')

<div class="span9">
    <div class="content">
        @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
        @endif

    @if(Session::has('message'))
        <div class="alert alert-success">{{Session::get('message')}}</div>
    @endif

    <form action="{{ route('staffsubj.store') }}" method="POST">@csrf
        <div class="module">
            <div class="module-head">
                <h3>Add Subject</h3>
            </div>
            
            <div class="module-body">
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

                <!-- Class arm set to "A" by default -->
                <input type="hidden" name="class_arm" value="A" />

                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-success">Add Subject</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    </div>
</div>

@endsection
