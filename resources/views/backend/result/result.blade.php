@extends('backend.layouts.master')

    @section('title','User Result')
    
    @section('content')
    <div class="span9">
        <div class="content">
        @if(Session::has('message'))

            <div class="alert alert-success">{{Session::has('message')}}</div>
        @endif
        <div class="module">
                <div class="module-head">
                        <h3>User Result</h3>
                        </div>
                <div class="module-body">
                <table class="table table-stripped">
                    <thead>
                    <tr>
                    <th>#</th>
                    <th>Test</th>
                    <th>Total Question</th>
                    <th>Attempt Question</th>
                    <th>Correct Answer</th>
                    <th>Wrong Answer</th>
                    <th>Percentage</th>
                    
                    </tr>
                    </thead>
                    <tbdoy>
                    @foreach($quiz as $q)
                    <tr>
                    <td>1</td>
                    <td>{{$q->name}}</td>
                    <td>{{$totalQuestions}}</td>
                    <td>{{$attemptQuestion}}</td>
                    <td>{{$userCorrectedAnswer}}
                    <td>{{$userWrongAnswer}}</td>
                    <td>{{round($percentage,2)}}</td>
                    </tr>
                    @endforeach

                    </tbody>
                    </table>

                    <table class="table table-stripped">
                    <thead>
                    <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Answer given</th>
                    <th>Result</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($results as $key=>$result)
                    <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$result->question->question}}</td>
                    <td>{{$result->answer->answer}}</td>
                    @if($result->answer->is_correct)
                    <td>Correct</td>

                    @else
                    <td>Wrong</td>
                    @endif
                    </tr>
                    @endforeach
                    </tbody>
                    </table>

                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
@endsection

