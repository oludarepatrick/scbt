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
                        <h3>All Questions</h3>
                        </div>
                <div class="module-body">
                <table class="table table-stripped table-responsive" style="width:100%">
                    <thead>
                    <tr>
                    <th>#</th>
                    <th style="width:10%; color:darkblue">Question</th>
                    <th>Quiz</th>
                    <th>Created at</th>
                    <th>View</th>
                    <th>Edit</tH>
                    <th>Delete</th>
                    
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($questions)>0)
                    @foreach($questions as $key=>$question)
                    <tr>
                    <td>{{$key+1}}</td>
                    <td style="width:300px" width="300px">{!!$question->question!!}</td>
                    <td>{{$question->quiz->name}} <br/>
                        @if(!empty($question->mfile_ext))
                            <?php 
                                
                                list($txt,$ext)=explode(".", $question->mfile_ext);
                                $ext=strtolower($ext);
                                
                            ?>
                            
                            @if($ext=="jpg" || $ext=="jpeg"  || $ext=="png")
                            <p><img src='{{ asset($question->mfile_ext) }}' style='width:250px;height:200px'></p>
                            @elseif($ext=="mp3" || $ext=="mp4")
                            <p>
                                <video width="250" height="2000" controls>
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
                    </td>
                    <td>{{date('F y, Y',strtotime($question->created_at))}}</td>
                   
                    <td>
                        <a href="{{route('question.show',[$question->id])}}"><button class="btn btn-info">View</buttom></a>
                    </td>
                  
                    <td>
                   <a href="{{route('question.edit',[$question->id])}}">
                        <button class="btn btn-primary">Edit</button>
                        </a>
                        </td>
                        <td>
                        <form id="delete-form{{$question->id}}" method="POST" action="{{route('question.destroy',[$question->id])}}">@csrf
                        {{method_field('DELETE')}}
                        </form>
                        <a href="#" onclick="if(confirm('Do you want to delete?')){
                            event.preventDefault();
                            document.getElementById('delete-form{{$question->id}}').submit()
                        }else{
                            event.preventDefault();
                        }

                        ">
                        <input type="submit" value="Delete" class="btn btn-danger">
                        </a>

                        </td>





                    </tr>
                   @endforeach

                   @else
                   <td>No Quiz to Display</td>
                   @endif
                    </tbody>
                    </table>
                    <div class="pagination pagination-centered">
                    {{$questions->links()}}
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>

@endsection