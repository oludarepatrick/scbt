@extends('backend.layouts.master')

@section('title', 'Student Login Details')

@section('content')
<div class="span9">
    <div class="content">
        @if(Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif

        <div class="module">
            <div class="module-head">
                <h3>Student Login Details</h3>
            </div>
            <div class="module-body">
                <!-- Search Form -->
                <form action="{{ route('admin.login-details.student') }}" method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Search by Name, Class, or Email" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary mt-2">Search</button>
                </form>

                <!-- Student Table -->
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Class</th>
                            <th>Email</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $key => $student)
                        <tr>
                            <td>{{ $students->firstItem() + $key }}</td>
                            <td>{{ $student->firstname }}</td>
                            <td>{{ $student->lastname }}</td>
                            <td>{{ $student->class }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->visible_password }}</td> <!-- Consider securing passwords -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination Links -->
                <div class="pagination">
                    {{ $students->appends(['search' => request('search')])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
