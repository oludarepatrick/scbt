@extends('backend.layouts.master')

@section('title', 'Manage Subjects')

@section('content')
<div class="span9">
    <div class="content">
    <h4>Manage Subjects</h4>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('subjects.store') }}">
        @csrf
        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control" placeholder="Enter Subject Name" required>
            <button class="btn btn-primary" type="submit">Add</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th><th>Name</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subjects as $key => $subject)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $subject->name }}</td>
                <td>
                    <form method="POST" action="{{ route('subjects.destroy', $subject->id) }}" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this subject?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
