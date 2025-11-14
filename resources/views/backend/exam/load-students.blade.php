@if (count($students) > 0)
    <h3 align='center' style='color:darkblue'>Class Name: {{ $classId }}</h3>
    <form class="card" method="POST" action="{{ route('user.exam') }}">
        @csrf
        <input type="hidden" name="classId" value="{{ $classId }}" />
        <input type="hidden" name="quizId" value="{{ $quizId }}" />

        <table border='1' align='center' width="1000px">
            <tr>
                <td colspan="5" align='center'>
                    Click 
                    <input type='submit' class='btn btn-primary btn-sm' value='Assign Students'
                        onClick="return confirm('Are you sure you want to proceed?');">
                    to proceed
                </td>
            </tr>
            <tr>
                <th>S/N</th>
                <th>Student's ID</th>
                <th>Name In Full</th>
                <th>Class</th>
                <th>Action</th>
            </tr>
            @php $i = 0; @endphp
            @foreach ($students as $stud)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $stud->id }}</td>
                    <td>{{ $stud->firstname . ' ' . $stud->lastname }}</td>
                    <td>{{ $stud->class }}</td>
                    <td><input type='checkbox' checked value="{{ $stud->id }}" name='mystud[]'></td>
                </tr>
            @endforeach
        </table>
    </form>
@else
    <h3 align='center' style='color:red'>No Record Found</h3>
@endif
