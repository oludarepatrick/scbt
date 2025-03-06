@if(count($subjects))
    <option value="">--select subjects--</option>  
@endif
@forelse ($subjects as $subject)
    <option value="{{ $subject->subject }}">{{ $subject->subject }}</option>  
@empty
    <option value="">No Available Subjects</option>  
@endforelse
