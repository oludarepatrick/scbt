@extends('backend.layouts.master')

    @section('title','Update Question')
    
    @section('content')

    <div class="span9">
        <div class="content">

        @if(Session::has('message'))

            <div class="alert alert-success">{{Session::get('message')}}</div>
        @endif

        <form action="{{route('question.update',[$question->id])}}" method="POST" enctype="multipart/form-data">
            @csrf
            {{method_field('PUT')}}
            <div class="module">
                <div class="module-head">
                        <h3>Update Question</h3>
                </div>

                <div class="module-body">
                    <div class="control-group">
                    <lable class="control-label" for="question">Choose Quiz</label>
                    <div class="controls">
                    <select name="quiz" class="span8">
                    <option>Select Quiz</option>
                    @foreach(App\Models\Quiz::all() as $quiz)
                       
                        <option value="{{$quiz->id}}" @if($quiz->id==$question->quiz_id)selected @endif>{{$quiz->name}}</option>
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
                    <lable class="control-label" for="question">Question Name</label>
                    <div class="controls">
                        <!--<input type="text" name="question" class="span8" placeholder="question of a quiz" value="{{$question->question}}">-->
                        <textarea id="mceDEMO" name="question" class="span8">{{$question->question}}</textarea>
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
                    <input type="hidden" value="{{ $question->mfile_ext }}" name="old_file"/>
                    @if(!empty($question->mfile_ext))
                        <?php 
                            
                            list($txt,$ext)=explode(".", $question->mfile_ext);
                            $ext=strtolower($ext);
                            
                        ?>
                        @if($ext=="jpg" || $ext=="jpeg" || $ext=="png")
                        <p><img src='{{ asset($question->mfile_ext) }}' style='width:250px;height:200px'></p>
                        @elseif($ext=="mp3" || $ext=="mp4")
                        <p>
                            <video width="250" height="200" controls>
                                @if($ext=="mp3")
                                    <source src="{{ asset($question->mfile_ext) }}" type="video/mp3">
                                @else 
                                    <source src="{{ asset($question->mfile_ext) }}" type="video/mp4">
                                @endif
                            </video>
                        </p>
                        
                        @elseif($ext=="pdf")
                        <p>{{ asset($question->mfile_ext) }}</p>
                        @endif
                    @endif
                    <div class="control-group">
                    <lable class="control-label" for="options">Options</label>
                    <div class="controls">
                    @foreach($question->answers as $key=>$answer)
                        <input type="text" name="options[]" class="span7" value="{{$answer->answer}}" required=""> 
                        
                        <input type="radio" name="correct_answer" value="{{$key}}" @if($answer->is_correct){{'checked'}} @endif>
                        <span>Is correct answer</spam>
                    @endforeach
                    </div>
                   @error('question')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message}}</strong>
                    </span>
                    @enderror
                    </div>

                

                    <div class="control-group">
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
	
	

</script>

@endsection