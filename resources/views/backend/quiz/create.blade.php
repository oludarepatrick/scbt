@extends('backend.layouts.master')

    @section('title','create quiz')
    
    @section('content')

    <div class="span9">
        <div class="content">

       @if(Session::has('message'))
    <div class="alert alert-success mt-2">{{ Session::get('message') }}</div>
@endif

@if(Session::has('error'))
    <div class="alert alert-danger mt-2">{{ Session::get('error') }}</div>
@endif

<form action="{{ route('quiz.store') }}" method="POST" class="p-4 bg-light rounded shadow-sm">
    @csrf

    <h4 class="mb-4">📝 Create New Quiz</h4>

    <div class="mb-3">
        <label for="name" class="form-label fw-bold">Quiz Name</label>
        <input type="text" name="name" id="name" class="form-control" placeholder="Enter quiz title" value="{{ old('name') }}">
        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label fw-bold">Description</label>
        <textarea name="description" id="description" rows="3" class="form-control" placeholder="Enter a short description">{{ old('description') }}</textarea>
        @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="minutes" class="form-label fw-bold">Duration (Minutes)</label>
        <input type="number" name="minutes" id="minutes" class="form-control w-25" placeholder="e.g., 30" value="{{ old('minutes') }}">
        @error('minutes') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Select Class</label>
        <select name="class_id" id="classId" class="form-select">
            <option value="">Select Class</option>
            @foreach($classes as $class)
                <option value="{{ $class->name }}" {{ old('class_id') == $class->name ? 'selected' : '' }}>
                    {{ $class->name }}
                </option>
            @endforeach
        </select>
        @error('class_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Select Subject</label>
        <select name="subject_id" id="subjectId" class="form-select">
            <option value="">Select Subject</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->name }}" {{ old('subject_id') == $subject->name ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
            @endforeach
        </select>
        @error('subject_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Create Quiz
        </button>
    </div>
</form>


@endsection
<script>
    var customURL = "<?= asset('images/loader.gif'); ?>";
    function showSubjects(value)
    {
        var classId=document.getElementById('classId').value; 
        //alert(classId);
        var see_resp = document.getElementById('subjectId');
        var req = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');  // XMLHttpRequest object

        var data ='_token={{csrf_token()}}&armId='+value+'&cId='+classId;

        req.open('POST', 'loadsubjects', true); // set the request

        //adds header for POST request
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        req.send(data); //sends data
    
        req.onreadystatechange = function()
        {
            if(req.readyState ==4 && req.status==200)
            {
                see_resp.innerHTML = req.responseText;
            }
            else{
                see_resp.innerHTML ="<img src='"+customURL+"'> <b> Please wait,Loading... </b>";
            }
            
        }
    }
</script>