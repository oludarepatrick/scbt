@extends('backend.layouts.master')

@section('title', 'Preview AI Questions')

@section('content')
<div class="span9">
    <div class="content">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="module">
            <div class="module-head">
                <h3>Preview & Edit AI Maths Generated Questions</h3>
            </div>

            <div class="module-body">
                <ol>
                    @foreach ($questions as $q)
                    <li class="mb-4 p-3 border rounded question-block" data-id="{{ $q->id }}">
                        <form class="update-form">
                            @csrf

                            <!-- Question Preview -->
                            <div class="form-group">
                                <label><strong>Real Display Preview :</strong></label>
                                <div class="p-2 border bg-light mb-2">
                                    {{ strip_tags(html_entity_decode(ltrim($q->question, '* '))) }}
                                </div>

                                <label><strong>Edit Question:</strong></label>
                                <textarea name="question" class="form-control question-textarea" style="width:500px;">{{ strip_tags(html_entity_decode(ltrim($q->question, '* '))) }}</textarea>
                            </div>

                            <!-- Option A -->
                            <div class="form-group mt-2">
                                <label>A)</label>
                                <div class="p-1 border mb-1 bg-white">
                                </div>
                                <input type="text" name="option_a" class="form-control" value="{!! $q->option_a !!}">
                                {!! $q->option_a !!}
                            </div>

                            <!-- Option B -->
                            <div class="form-group mt-2">
                                <label>B)</label>
                                <div class="p-1 border mb-1 bg-white">
                                </div>
                                <input type="text" name="option_b" class="form-control" value="{!! $q->option_b !!}">
                            </div>

                            <!-- Option C -->
                            <div class="form-group mt-2">
                                <label>C)</label>
                                <div class="p-1 border mb-1 bg-white">
                                </div>
                                <input type="text" name="option_c" class="form-control" value="{!! $q->option_c !!}">
                            </div>

                            <!-- Option D -->
                            <div class="form-group mt-2">
                                <label>D)</label>
                                <div class="p-1 border mb-1 bg-white">
                                </div>
                                <input type="text" name="option_d" class="form-control" value="{!! $q->option_d !!}">
                            </div>

                            <!-- Correct Option -->
                            <div class="form-group mt-2">
                                <label><strong>Correct Answer:</strong></label>
                                <select name="correct_option" class="form-control">
                                    @foreach (['A', 'B', 'C', 'D'] as $option)
                                        <option value="{!! $option !!}" {{ $q->correct_option == $option ? 'selected' : '' }}>{!! $option !!}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Buttons -->
                            <table>
                                <tr>
                                    <td>
                                        <div class="form-group mt-2">
                                            <button type="button" class="btn btn-primary btn-sm update-btn mt-3">Update</button>
                                        </div>
                                    </td>
                        </form>
                                    <td>
                                        <form action="{{ route('ai_questions.destroy', $q->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Delete this question?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                    </li>
                    @endforeach
                </ol>

                <a href="{{ route('ai_questions.generate') }}" class="btn btn-success mt-3">Generate New</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- ✅ TinyMCE Editor -->
<script src="https://cdn.tiny.cloud/1/5ioc9z9q1p1fzpsdpkyz9aqvmx1dey6a9jgxeedi7n72ugl4/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea.question-textarea',
        menubar: false,
        plugins: 'lists link',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link',
        height: 150
    });
</script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async
  src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<!-- ✅ AJAX Update Handler -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const updateButtons = document.querySelectorAll('.update-btn');
    
    updateButtons.forEach(button => {
        button.addEventListener('click', function () {
            tinymce.triggerSave();
            const parent = button.closest('.question-block');
            const id = parent.getAttribute('data-id');
            const form = parent.querySelector('.update-form');
            const formData = new FormData(form);

            const data = {
                _token: form.querySelector('input[name="_token"]').value,
                question: form.querySelector('textarea[name="question"]').value,
                options: {
                    A: form.querySelector('input[name="option_a"]').value,
                    B: form.querySelector('input[name="option_b"]').value,
                    C: form.querySelector('input[name="option_c"]').value,
                    D: form.querySelector('input[name="option_d"]').value
                },
                correct_option: form.querySelector('select[name="correct_option"]').value
            };

            fetch(`/admin/questions/ai/${id}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    alert('✅ ' + response.message);
                } else {
                    alert('❌ Error: ' + (response.message || 'Unknown error.'));
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                alert('❌ Request failed.');
            });
        });
    });
});
</script>

<!-- ✅ MathJax LaTeX Support -->
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async
  src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (window.MathJax) {
        MathJax.typeset(); // force re-render LaTeX on initial load
    }
});
</script>
@endsection
