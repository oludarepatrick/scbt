@extends('backend.layouts.master')

    @section('title','create quiz')
    
    @section('content')

    <div class="span9">
        <div class="content">

        @if(Session::has('message'))

            <div class="alert alert-success">{{Session::get('message')}}</div>
        @endif

        <form action="{{route('quiz.update',[$quiz->id])}}" method="POST">@csrf
        {{method_field('PUT')}}
            <div class="module">
                <div class="module-head">
                        <h3>Create Quiz</h3>
                </div>
                <div class="module-body">
                    <div class="control-group">
                    <lable class="control-label" for="name">Quiz name</label>
                    <div class="controls">
                        <input type="text" name="name" class="span6" placeholder="name of a quiz" value="{{$quiz->name}}">
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
                        <textarea name="description" class="span6" placeholder="description of a quiz">{{$quiz->description}}</textarea>
                    </div>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message}}</strong>
                    </span>
                    
                    </div>
                    @enderror
                    <div class="control-group">
                    <lable class="control-label" for="minutes">Minutes</label>
                    <div class="controls">
                        <input type="text" name="minutes" class="span2" placeholder="minute" value="{{$quiz->minutes}}">
                    </div>
                    @error('minutes')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message}}</strong>
                    </span>
                    @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Class</label>
                        <select name="class_id" class="filter form-control @error('class_id') is-invalid @enderror span6" value="{{$quiz->class_id}}" id="classId">
                            <option value="{{ !empty($quiz->class_id)?$quiz->class_id:'' }}">{{ !empty($quiz->class_id)?$quiz->class_id:'Select Class' }}</option>
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
                        <select name="arm" class="filter form-control @error('arm') is-invalid @enderror span6" value="{{$quiz->arm}}" onChange="showSubjects(this.value)" id="armId">
                            <option value="{{ !empty($quiz->arm)?$quiz->arm:'' }}">{{ !empty($quiz->arm)?$quiz->arm:'Select Arm' }}</option>
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
                            <option value="{{ !empty($quiz->subject_id)?$quiz->subject_id:'' }}">{{ !empty($quiz->subject_id)?$quiz->subject_id:'Select Subject' }}</option>
                            
                        </select>
                        @error('subject_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quiz Status</label>
                        <select name="status" class="filter form-control @error('status') is-invalid @enderror span6" id="status">
                            <option value="{{ $quiz->status }}">{{ ($quiz->status==1)?'Active':'In-Active' }}</option>
                            
                            <?php 
                                $sssTf=array(1=>"Active", 0=>"In-Active");
                                foreach($sssTf as $k=>$v)
                                {
                                    if($k !=$quiz->status)
                                    {
                                       echo "<option value='".$k."'>".$v."</option>"; 
                                    }
                                }
                            ?>
                            
                        </select>
                        @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    
                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" class="btn btn-info">Update</button> 
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