@extends('backend.layouts.master')

    @section('title','create question')
    
    @section('content')
    <div class="span9">
        <div class="content">
        @if(Session::has('message'))

            <div class="alert alert-success">{{Session::get('message')}}</div>
        @endif
        <div class="module">
                <div class="module-head">
                        <h3>All Question</h3>
                        </div>
                <div class="module-body">
                    <p><h3 class="heading">{!!$question->question!!}</h3></p>
                    @if(!empty($question->mfile_ext))
                        <?php 
                            
                            list($txt,$ext)=explode(".", $question->mfile_ext);
                            $ext=strtolower($ext);
                            
                        ?>
                        @if($ext=="jpg" || $ext=="jpeg" || $ext=='png')
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
                    <div class="module-body table">
                        <table class="table table-message">
                            <tbody>
                                @foreach($question->answers as $key=>$answer)
                                <tr class="read">
                                    <td class="cell-author hidden-phone hidden-tablet">
                                    {{$key+1}}.{{$answer->answer}}
                                        @if($answer->is_correct)
                                        <span class="badge badge-success pull-right">correct</b></span>
                                        @endif
                                    </td>
                                        
                                </tr>
@endforeach
</tbody>
</table>
</div>
<div class="module-foot">
  <a href="{{route('question.edit',[$question->id])}}">
    <button class="btn btn-primary">Edit</button></a>

    <form id="delete-form{{$question->id}}" method="POST" action="{{route('question.destroy',[$question->id])}}">@csrf
        {{method_field('DELETE')}}
    </form>
    <a href="" onclick="if(confirm('Do you want to delete?')){
        event.preventDefault();
        document.getElementById('delete-form{{$question->id}}').submit()
     }else{
        event.preventDefault();
        }

        ">
      <input type="submit" value="Delete" class="btn btn-danger">
    </a>
    

   <a href="{{route('question.index')}}"> <button class="btn btn-inverse pull-right">Back</button></a>
        </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>



@endsection