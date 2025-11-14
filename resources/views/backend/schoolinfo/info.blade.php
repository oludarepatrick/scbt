@extends('backend.layouts.master')

@section('title', 'Manage Classes')

@section('content')
<div class="span9">
    <div class="content">
    <h4>Manage Classes</h4>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('info.store') }}">
        @csrf
        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control" placeholder="Enter School Name" required>
            
        </div>

        <div class="input-group mb-3">
            <input type="text" name="email" class="form-control" placeholder="Enter Email" required>
            
        </div>
        <div class="input-group mb-3">
            <input type="text" name="phone" class="form-control" placeholder="Enter Phone" required>
            
        </div>
        <div class="input-group mb-3">
            <input type="text" name="term" class="form-control" placeholder="Enter Term" required>
            
        </div>
        <div class="input-group mb-3">
            <input type="text" name="session" class="form-control" placeholder="Enter Session" required>
            
        </div>
        <div class="input-group mb-3">
            <button class="btn btn-primary" type="submit">Add</button>
        </div>
        
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Term</th>
                <th>Session</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($info as $key => $info)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $info->name }}</td>
                <td>{{ $info->email }}</td>
                <td>{{ $info->phone }}</td>
                <td>{{ $info->term }}</td>
                <td>{{ $info->session }}</td>
                <td>
                    <form method="POST" action="{{ route('info.destroy', $info->id) }}" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this Info?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
@endsection
