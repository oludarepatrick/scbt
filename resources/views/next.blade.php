@extends('layouts.app')
@section('content')
<next-component></next-component>
<h1>Good mornig</h1>
<a href="{{route('mycom')}}">
                    <button class="btn btn-success">Start Quiz</button>
                    </a>
@endsection