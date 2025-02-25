@extends('backend.layouts.master')

    @section('title','create quiz')
    
    @section('content')

    <div class="span9">
        <div class="content">

        @if(Session::has('message'))

            <div class="alert alert-success">{{Session::get('message')}}</div>
        @endif
        
        <form action="{{route('question.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="module">
                <div class="module-head">
                        <h3>Create Question</h3>
                </div>

                <div class="module-body">
                    <div class="control-group">
                    <lable class="control-label" for="question">Choose Quiz</label>
                    <div class="controls">
                    <select name="quiz" class="span8" onChange="displayQNo(this.value)" required>
                    <option>Select Quiz</option>
                    @foreach($quizes as $quiz)
                       
                        <option value="{{$quiz->id}}">{{$quiz->name}}</option>
                        @endforeach
                    </select>
                        
                    </div>
                   @error('question')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message}}</strong>
                    </span>
                    @enderror
                    </div>


                    <div class="control-group">
                    <lable class="control-label" for="question" style="font-size: 18px; color:darkblue">Question Name(No. <strong id="qNo"></strong>)</label>
                        <div class="controls">
                        <!--<input type="text" name="question" class="span8" placeholder="question of a quiz" value="{{old('question')}}">-->
                        <textarea id="mceDEMO" name="question" class="span8"></textarea>
                    </div>
                    @error('question')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message}}</strong>
                    </span>
                    @enderror
                    </div>
                    
                    <div class="control-group">
                        <lable class="control-label" for="question">Upload File(if any)</label>
                        <div class="controls">
                            <input type="file" name="mfile" class="span8" placeholder="Upload file" value="{{old('mfile')}}">
                        </div>
                        <strong style='color:red; font-size:13px'>file supported type: jpg,png,mp3,mp4,pdf</strong>
                        @error('mfile')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message}}</strong>
                        </span>
                        @enderror
                    </div>
                   

                    <div class="control-group" style="padding-left:10px">
                    <lable class="control-label" for="options">Options</label>
                    <div class="controls">
                    @for($i=0;$i<4;$i++)
                        @php 
                            if($i==0){ $defltVal="A"; }
                            elseif($i==1){ $defltVal="B"; }
                            elseif($i==2){ $defltVal="C"; }
                            elseif($i==3){ $defltVal="D"; }
                        @endphp
                        <input type="text" name="options[]" class="span6 @error('options') border-red @enderror" placeholder="options{{$i+1}}" value="{{ (!empty(old('options.[$i]'))?old('options.[$i]'):$defltVal) }}" required="">
                        
                        <input type="radio" name="correct_answer" value="{{$i}}">
                        <span>Is correct answer</spam>
                    @endfor
                    </div>
                   @error('options')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message}}</strong>
                    </span>
                    @enderror
                    </div>

                

                    <div class="control-group" style="padding-left:20px">
                        <div class="controls">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
</form>

</div>
</div>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.2/tinymce.min.js"></script>
    <script>
		tinymce.init({
		  selector: '#mceDEMO',
		  width: 754 - 2,
		  height: 372 - 99,
		  plugins: [
			'advlist',
			'autolink',
			'lists',
			'link',
			'image',
			'charmap',
			'print',
			'preview',
			'anchor',
			'searchreplace',
			'visualblocks',
			'code',
			'fullscreen',
			'insertdatetime',
			'media',
			'table',
			'contextmenu',
			'paste',
			'imagetools'
		  ],
		  toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image',
		  
	});
	
	var customURL = "<?= asset('images/loader.gif'); ?>";
    function displayQNo(value)
    {
        var see_resp = document.getElementById('qNo');
        
        var req = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');  // XMLHttpRequest object

        var data ='_token={{csrf_token()}}&quizId='+value;

        req.open('POST', 'loadquestion', true); // set the request

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

@endsection