@if (count($students) > 0)
    <h3 align='center' style='color:darkblue'>Class Name: {{ $classId }}, Arm: {{ $armId }} </h3>
    <form class="card" method="POST" action="{{ route('re-assigning') }}">
        @csrf
        <input type="hidden" name="classId" value="<?= $classId; ?>" />
        <input type="hidden" name="armId" value="<?= $armId; ?>" />
        <input type="hidden" name="quizId" value="<?= $quizId; ?>" />
        <table border='1' align='center' width="1000px">
            <tr>
                
                <td colspan="{{ ($armId=='optional')?6:5 }}" align='center'>
                    Click <input type='submit' class='btn btn-primary btn-sm' value='Re-Assign Students' onClick="return confirm('are you sure you want to proceed?'); "> to proceed
                </td>
            </tr>
            <tr>
                <th>S/N</th><th>Student's ID</th><th>Name In Full</th><th>Gender</th>
                <?php if($armId=='optional'){ ?>
                    <th>Arm</th>
                <?php } ?>
                <th>Action</th>
            </tr>
            @php $i=0; @endphp
            @foreach($students as $stud)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $stud->student_id }}</td>
                    <td>{{ $stud->surname." ".$stud->firstname." ".$stud->othername }}</td>
                    <td>{{ $stud->sex }}</td>
                    <?php if($armId=='optional'){ ?>
                        <td>{{ $stud->class_division }}</td>
                    <?php } ?>
                    <td><input type='checkbox' checked value="{{  $stud->sn }}" name='mystud[]'></td>
                </tr>
            @endforeach
        </table>
    </form>
@else
    <h3 align='center' style='color:red'>No Record Found</h3>
@endif

<?php //foreach($students as $stud){ echo $stud->student_id; } ?>