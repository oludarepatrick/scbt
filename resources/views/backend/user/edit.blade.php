@extends('backend.layouts.master')

    @section('title','Update User')
    
    @section('content')
    <div class="span9">
        <div class="content">
        @if(Session::get('message'))

            <div class="alert alert-success">{{Session::get('message')}}</div>
        @endif
        <div class="module">
                <div class="module-head">
                        <h3>User User</h3>
                        </div>
                <div class="module-body">
            <form action="{{route('user.update', [$user->id])}}" method="POST">@csrf
            {{method_field('PUT')}}

            <div class="module">
                <div class="module-head">
                        <h3>Update User</h3>
                </div>
                <div class="module-body">
                    <div class="control-group">
                    <lable class="control-label" for="name">Full name</label>
                    <div class="controls">
                        <input type="text" name="name" class="span8 @error('name') border-red @enderror" placeholder="name" value="{{$user->name}}">
                    </div>
                   @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message}}</strong>
                    </span>
                    @enderror
                    </div>

                    <div class="control-group">
                    <lable class="control-label" for="password">Password</label>
                    <div class="controls">
                        <input type="text" name="password" class="span8 @error('password') border-red @enderror" placeholder="password" value="{{$user->visible_password}}">
                    </div>
                   @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message}}</strong>
                    </span>
                    @enderror
                    </div>

                    <div class="control-group">
                    <lable class="control-label" for="occupation">Occupation</label>
                    <div class="controls">
                        <input type="text" name="occupation" class="span8 @error('occupation') border-red @enderror" placeholder="occupation" value="{{$user->occupation}}">
                    </div>
                   @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message}}</strong>
                    </span>
                    @enderror
                    </div>

                    <div class="control-group">
                    <lable class="control-label" for="address">Address</label>
                    <div class="controls">
                        <input type="text" name="address" class="span8 @error('address') border-red @enderror" placeholder="address" value="{{$user->address}}">
                    </div>
                   @error('address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message}}</strong>
                    </span>
                    @enderror
                    </div>

                    <div class="control-group">
                    <lable class="control-label" for="phone">Phone</label>
                    <div class="controls">
                        <input type="text" name="phone" class="span8 @error('phone') border-red @enderror" placeholder="phone" value="{{$user->phone}}">
                    </div>
                   @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message}}</strong>
                    </span>
                    @enderror
                    </div>
                
                <div class="control-group">
                    <button type="submit" class="btn btn-success">Update User</button>
                </div>
                </form>
                </div>
                </div>
                </div>
                </div>
                </div>
@endsection