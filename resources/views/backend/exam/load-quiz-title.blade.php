@if(count($quizes))
    <option value="">--select subjects--</option>  
@endif

@forelse ($quizes as $quiz)
    <option value="{{ $quiz->id }}">{{ $quiz->name." (".$quiz->subject_id.")" }}</option>
@empty
    <option value="">No Available Quiz</option> 
@endforelse
