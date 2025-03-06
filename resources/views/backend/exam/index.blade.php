@extends('backend.layouts.master')

    @section('title','Exam Assigned User')
    
    @section('content')
    <div class="span9">
        <div class="content">
        @if(Session::has('message'))

            <div class="alert alert-success">{{Session::get('message')}}</div>
        @endif
        <div class="module">
                <div class="module-head">
                        <h3>All Quiz</h3>
                        </div>
                <div class="module-body">
                <table class="table table-stripped">
                    <thead>
                    <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Quiz</th>
                    <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($quizzes)>0)
                    @foreach($quizzes as $quiz)
                    @foreach($quiz->users as $key=>$user)
                    <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$quiz->name}}</td>
                    <td>
                        <a href="{{route('quiz.question',[$quiz->id])}}"><button class="btn btn-inverse">View Questions</button>
                        </a>
                    </td>
                   <td>
                   <form action="{{route('exam.remove')}}" method="POST">@csrf
                   <input type="hidden" name="user_id" value="{{$user->id}}">
                   <input type="hidden" name="quiz_id" value="{{$quiz->id}}">
                   <button class="btn btn-danger" type="submit">Remove</button>
                   </form>
                   </td>
                  
                   
                       





                    </tr>
                   @endforeach
                   @endforeach


                   @else
                   <td>No User to Display</td>
                   @endif
                    </tbody>
                    </table>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>

@endsection