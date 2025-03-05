@extends('backend.layouts.master')

    @section('title','create quiz')
    
    @section('content')
    
    
    <div class="span9">
        <div class="content">
            @if(Session::has('message'))
                <div class="alert alert-success">{{Session::get('message')}}</div>
            @endif

            <!--<form  method="POST" id="search">-->
            
                <div class="module">
                    <div class="module-head">
                            <h3>Re-Assign Exam</h3>
                    </div>

                    <div class="module-body">
                        <div class="table-responsive">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                
                                <div class="mb-3">
                                    <label class="form-label">Arm (Optional)</label>
                                    <select name="arm" class="filter form-control @error('arm') is-invalid @enderror span6" id="armId">
                                        <option value="optional">-Select Arm (Optional)-</option>
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
                        
                                <div class="mb-3">
                                    <label class="form-label">Select Class</label>
                                    <select name="classname" class="filter form-control @error('classname') is-invalid @enderror span6" onChange="displaySubject(this.value)" id="classId">
                                        <option>Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{$class->class}}">{{$class->class}}</option>
                                        @endforeach
                                    </select>
                                    @error('classname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                
                                
                                <div class="mb-3">
                                    <label class="form-label">Quiz Title</label>
                                    <select name="quiz" class="filter form-control @error('quiz') is-invalid @enderror span6" id="quiz">
                                        <option>Select Quiz</option>
                                        
                                    </select>
                                    @error('quiz')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                
                            </div>
                            <div class="col-sm-6 col-md-6">
                                
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button type="submit" class="btn btn-success" id='btn_ajax' onClick="doThis()">Load Students</button>
                            </div>
                        </div>
                        
                        <div class="col-sm-6 col-md-6 data" id="dataM">
                                        
                        </div>
                    </div>
                </div>
            <!--</form>-->
            <!--<script src="{{asset('js/jquery.form.js')}}"></script>-->
            
            
        </div>
    </div>
    
@endsection
<script>
    //sends data to server, via POST, and displays the received answer
    var customURL = "<?= asset('images/loader.gif'); ?>";

    function displaySubject(value)
    {
        //var classId=document.getElementById('classId').value;
        var armId=document.getElementById('armId').value;

        var see_resp = document.getElementById('quiz');
        var req = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');  // XMLHttpRequest object

        var data ='_token={{csrf_token()}}&armId='+armId+'&cId='+value;

        req.open('POST', 'loadsquizes', true); // set the request

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

    function doThis()
    {
        var classId=document.getElementById('classId').value;
        var armId=document.getElementById('armId').value;
        var quizId=document.getElementById('quiz').value;

        var see_resp = document.getElementById('dataM');
        var req = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');  // XMLHttpRequest object

        //pairs index=value with data to be sent to server (including csrf_token)
        //var data ='_token={{csrf_token()}}&cId='+classId+'&armId'+armId;
        var data ='_token={{csrf_token()}}&armId='+armId+'&cId='+classId+'&quizId='+quizId;

        
        req.open('POST', 'loadstudreasign', true); // set the request

        //adds header for POST request
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        req.send(data); //sends data

        // If the response is successfully received, will be added in #see_resp
        req.onreadystatechange = function()
        {
            if(req.readyState ==4 && req.status==200)
            {
                //alert(req.responseText); //just for debug
                see_resp.innerHTML = req.responseText;
            }
            else{
                see_resp.innerHTML ="<img src='"+customURL+"'> <b> Please wait,Loading... </b>";
            }
            
        }
    }
</script>