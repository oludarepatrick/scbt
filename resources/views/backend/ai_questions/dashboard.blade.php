@extends('backend.layouts.master')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="span9">

    <div class="content">
          @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
        @endif

    @if(Session::has('message'))
        <div class="alert alert-success">{{Session::get('message')}}</div>
    @endif
        <h3>Welcome to the Teacher Dashboard</h3>
        <p>This is your dashboard.</p>
    </div>
</div>
@endsection