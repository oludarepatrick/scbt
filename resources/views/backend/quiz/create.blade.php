@extends('backend.layouts.master')

    @section('title','create quiz')
    
    @section('content')

    <div class="span9">
        <div class="content">

        @if(Session::has('message'))

            <div class="alert alert-success">{{Session::get('message')}}</div>
        @endif

        <form action="{{route('quiz.store')}}" method="POST">@csrf
            <div class="module">
                <div class="module-head">
                    <h3>Create Quiz</h3>
                </div>
                
                <div class="module-body">
                    
                    <div class="mb-3">
                        <lable class="control-label" for="name">Quiz name</label>
                        <div class="form-control">
                            <input type="text" name="name" class="form-control span6" placeholder="name of a quiz" value="{{old('name')}}">
                        </div>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message}}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="control-group">
                    <lable class="control-label" for="description">Description</label>
                    <div class="controls">
                        <textarea name="description" class="span6" placeholder="description of a quiz">{{old('description')}}</textarea>
                    </div>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message}}</strong>
                    </span>
                    
                    </div>
                    @enderror
                    <div class="form-control">
                    <lable class="control-label" for="minutes">Minutes</label>
                    <div class="controls">
                        <input type="text" name="minutes" class="span2" placeholder="minute" value="{{old('minutes')}}">
                    </div>
                    @error('minutes')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message}}</strong>
                    </span>
                    @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Class</label>
                        <select name="class_id" class="filter form-control @error('class_id') is-invalid @enderror span6" id="classId">
                            <option>Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{$class->class}}">{{$class->class}}</option>
                            @endforeach
                        </select>
                        @error('class_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Arm</label>
                        <select name="arm" class="filter form-control @error('arm') is-invalid @enderror span6" onChange="showSubjects(this.value)" id="armId">
                            <option value="">Select Arm</option>
                            @foreach($arms as $arm)
                                <option value="{{$arm->division}}">{{$arm->division}}</option>
                            @endforeach
                        </select>
                        @error('arm')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <span ></span>
                    <div class="mb-3">
                        <label class="form-label">Subject Title</label>
                        <select name="subject_id" class="filter form-control @error('subject_id') is-invalid @enderror span6" id="subjectId">
                            <option value="">Select Subject</option>
                            
                        </select>
                        @error('subject_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <input type="hidden" name="status" value="0" />

                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
<script>
    var customURL = "<?= asset('images/loader.gif'); ?>";
    function showSubjects(value)
    {
        var classId=document.getElementById('classId').value;

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