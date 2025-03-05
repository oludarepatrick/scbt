@extends('backend.layouts.master')

    @section('title','Quiz Questions')
    
    @section('content')

    <div class="span9">
        <div class="content">
        @foreach($quizzes as $quiz)

        <div class="module">
        <div class="module-head">

        <h3>
            {{$quiz->name}}
            
        </h3>
        </div>

        <div class="module-body">

        <p><h3 class="heading"></h3></p>

        <div class="module-body table">

        @foreach($quiz->questions as $ques)

        <table class="table table-message">
        <tbody>
        <span style='float:right'><a href="{{route('question.edit',[$ques->id])}}" class="btn btn-info btn-sm">Edit Question</a></span>
        <tr class="read">
        
        {!!$ques->question!!}
        
        

        <td class="cell-autho hidden-phone hidden-tablet">
        @if(!empty($ques->mfile_ext))
                        <?php 
                            
                            list($txt,$ext)=explode(".", $ques->mfile_ext);
                            $ext=strtolower($ext);
                            
                        ?>
                        @if($ext=="jpg" || $txt=="jpeg")
                        <p><img src='{{ asset($ques->mfile_ext) }}' style='width:250px;height:200px'></p>
                        @elseif($ext=="mp3" || $ext=="mp4")
                        <p>
                            <video width="250" height="200" controls>
                                @if($ext=="mp3")
                                    <source src="{{ asset($ques->mfile_ext) }}" type="video/mp3">
                                @else 
                                    <source src="{{ asset($ques->mfile_ext) }}" type="video/mp4">
                                @endif
                            </video>
                        </p>
                        
                        @elseif($ext=="pdf")
                        <p>{{ asset($ques->mfile_ext) }}</p>
                        @endif
                    @endif

        @foreach($ques->answers as $answer)
        <p>
        {!!$answer->answer!!}
       @if($answer->is_correct)
        <span class="badge badge-success">
        correct answer
    </span>
    @endif
    </p>
    @endforeach
    </td>
    <tr>
    </tbody>
    </table>
    @endforeach

    <div class="module-foot">
    <td>
    <a href="{{route('quiz.index')}}"><button class="btn btn-inverse pull-center">Back</button></a>
    </td>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    @endforeach
    </div>
    </div>
    
@endsection