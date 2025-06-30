@extends('backend.layouts.master')

@section('title', 'Saved AI Questions')

@section('content')
<div class="span9">
    <div class="content">

        <div class="module">
            <div class="module-head">
                <h3>Saved AI Questions</h3>
            </div>
            <div class="module-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($questions->count() > 0)
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Question</th>
                                <th>Options</th>
                                <th>Correct Answer</th>
                                <th>Time (mins)</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $index => $q)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $q->class }}</td>
                                <td>{{ $q->subject }}</td>
                                <td>{{ $q->question }}</td>
                                <td>
                                    A: {{ $q->option_a }}<br>
                                    B: {{ $q->option_b }}<br>
                                    C: {{ $q->option_c }}<br>
                                    D: {{ $q->option_d }}
                                </td>
                                <td>{{ strtoupper($q->correct_answer) }}</td>
                                <td>{{ $q->time_minutes }}</td>
                                <td>{{ $q->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="pagination">
                        {{ $questions->links() }}
                    </div>
                @else
                    <p>No AI-generated questions found yet.</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
