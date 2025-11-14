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
                            <h3>Assign Exam</h3>
                    </div>

                    <div class="module-body">
                        <div class="table-responsive">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                
                                
                        
                                <div class="mb-3">
                                    <label class="form-label">Select Class</label>
                                    <select name="classname" class="filter form-control @error('classname') is-invalid @enderror span6" onChange="displaySubject(this.value)" id="classId">
                                        <option>Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{$class->name}}">{{$class->name}}</option>
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
    
<script>
document.addEventListener("DOMContentLoaded", function() {

    var customURL = "{{ asset('images/loader.gif') }}";

    window.displaySubject = function(value) {
        var quizDropdown = document.getElementById('quiz');
        quizDropdown.innerHTML = "<option>Loading...</option>";

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "{{ url('exam/loadsquizes') }}", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                quizDropdown.innerHTML = xhr.responseText;
            }
        };
        xhr.send('_token={{ csrf_token() }}&cId=' + value);
    }

    window.doThis = function() {
        var classId = document.getElementById('classId').value;
        var quizId = document.getElementById('quiz').value;
        var dataDiv = document.getElementById('dataM');

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "{{ url('exam/loadstud') }}", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                dataDiv.innerHTML = xhr.responseText;
            } else {
                dataDiv.innerHTML = "<img src='" + customURL + "'> <b>Loading...</b>";
            }
        };
        xhr.send('_token={{ csrf_token() }}&cId=' + classId + '&quizId=' + quizId);
    }

});
</script>
@endsection