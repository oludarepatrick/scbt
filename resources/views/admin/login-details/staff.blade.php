@extends('backend.layouts.master')

@section('title', 'Staff Login Details')

@section('content')
<div class="span9">
    <div class="content">
        @if(Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif

        <div class="module">
            <div class="module-head">
                <h3>Staff Login Details</h3>
            </div>
            <div class="module-body">
                <!-- Search Form -->
                <form action="{{ route('admin.login-details.staff') }}" method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Search by Name or Email" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary mt-2">Search</button>
                </form>

                <!-- Staff Table -->
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staff as $key => $user)
                        <tr>
                            <td>{{ $staff->firstItem() + $key }}</td>
                            <td>{{ $user->firstname }}</td>
                            <td>{{ $user->lastname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->visible_password }}</td> <!-- Consider securing passwords -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination Links -->
                <div class="pagination">
                    {{ $staff->appends(['search' => request('search')])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
