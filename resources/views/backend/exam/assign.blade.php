@extends('backend.layouts.master')

    @section('title','create quiz')
    
    @section('content')

    <div class="span9">
        <div class="content">

        @if(Session::has('message'))

            <div class="alert alert-success">{{Session::get('message')}}</div>
        @endif

        <form action="{{route('user.exam')}}" method="POST">@csrf
            <div class="module">
                <div class="module-head">
                        <h3>Create Question</h3>
                </div>

                <div class="module-body">
                    <div class="control-group">
                    <lable class="control-label" for="question">Select Quiz</label>
                    <div class="controls">
                    <select name="quiz_id" class="span8">
                    <option>Select Quiz</option>
                    @foreach(App\Models\Quiz::all() as $quiz)
                       
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
                    <lable class="control-label" for="question">Select User</label>
                    <div class="controls">
                    <select name="user_id" class="span8">
                    <option>Select User</option>
                    @foreach(App\Models\User::where('is_admin','0')->get() as $user)
                       
                        <option value="{{$user->id}}">{{$user->name}}</option>
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
                        <div class="controls">
                            <button type="submit" class="btn btn-success">Submit</button>
</div>
</div>
</form>

</div>
</div>
</div>

@endsection