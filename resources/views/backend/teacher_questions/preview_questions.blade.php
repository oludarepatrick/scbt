@extends('backend.layouts.master')

@section('title', 'Preview AI Questions')

@section('content')
<style>
    #miniEditor { width: 100%; }
    .editor-toolbar button {
        border: 1px solid #ccc;
        background: white;
        padding: 5px 10px;
        cursor: pointer;
        margin-right: 5px;
        border-radius: 4px;
    }
    .editor-area {
        border: 1px solid #ccc;
        min-height: 150px;
        padding: 10px;
        margin-top: 10px;
        background: #fff;
    }
    .editor-area img {
        max-width: 100%;
    }
</style>
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
                <h3>Preview & Edit AI Generated Questions</h3>
            </div>

            <div class="module-body">
                <ol>
                    @foreach ($questions as $q)
                    <li class="mb-4 p-3 border rounded question-block" data-id="{{ $q->id }}">
                        <form class="update-form">
                            @csrf
                            <div class="form-group">
                                <label><strong>Question:</strong></label>

                                <!-- Hidden textarea -->
                                <textarea name="question"
                                        class="form-control hidden-textarea"
                                        style="width:500px; display:none;">
                                    {{ strip_tags(html_entity_decode($q->question_text)) }}
                                </textarea>

                                <!-- Toolbar -->
                                <div class="editor-toolbar" style="margin-bottom:8px;">
                                    <!-- Basic -->
                                    <button type="button" data-cmd="bold">Bold</button>
                                    <button type="button" data-cmd="italic">Italic</button>
                                    <button type="button" data-cmd="underline">Underline</button>
                                    <button type="button" data-cmd="subscript">Sub</button>
                                    <button type="button" data-cmd="superscript">Super</button>

                                    <!-- Alignment -->
                                    <button type="button" data-cmd="justifyLeft">Left</button>
                                    <button type="button" data-cmd="justifyCenter">Center</button>
                                    <button type="button" data-cmd="justifyRight">Right</button>
                                    <button type="button" data-cmd="justifyFull">Justify</button>

                                    <!-- Lists -->
                                    <button type="button" data-cmd="insertUnorderedList">• Bullet</button>
                                    <button type="button" data-cmd="insertOrderedList">1. Number</button>

                                    <!-- Colors -->
                                    <button type="button" class="textColorBtn">Text Color</button>
                                    <button type="button" class="bgColorBtn">Highlight</button>

                                    <!-- Inserts -->
                                    <button type="button" data-cmd="insertHorizontalRule">HR</button>
                                    <button type="button" class="insertTableBtn">Table</button>
                                    <button type="button" class="insertCodeBtn">Code</button>
                                    <button type="button" class="insertQuoteBtn">Quote</button>

                                    <!-- Media -->
                                    <button type="button" class="insertImageBtn">Image</button>
                                    <button type="button" class="insertMathBtn">Math</button>

                                    <!-- Clean -->
                                    <button type="button" data-cmd="removeFormat">Clean</button>
                                </div>

                                <!-- Editor -->
                                <div class="editor-area"
                                    contenteditable="true"
                                    style="border:1px solid #ccc; padding:10px; min-height:150px; border-radius:4px;">
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <label>A)</label>
                                <input type="text" name="option_a" class="form-control" value="{{ $q->option_a }}">
                            </div>
                            <div class="form-group mt-2">
                                <label>B)</label>
                                <input type="text" name="option_b" class="form-control" value="{{ $q->option_b }}">
                            </div>
                            <div class="form-group mt-2">
                                <label>C)</label>
                                <input type="text" name="option_c" class="form-control" value="{{ $q->option_c }}">
                            </div>
                            <div class="form-group mt-2">
                                <label>D)</label>
                                <input type="text" name="option_d" class="form-control" value="{{ $q->option_d }}">
                            </div>
                            
                            <div class="form-group mt-2">
                                <label><strong>Correct Answer:</strong></label>
                                <select name="correct_option" class="form-control">
                                    @foreach (['A', 'B', 'C', 'D'] as $option)
                                        <option value="{{ $option }}" {{ $q->correct_option == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <table>
                                <tr><td>
                            <div class="form-group mt-2">
                            <button type="button" class="btn btn-primary btn-sm update-btn mt-3">Update</button></td>
                        </form>

                        <!-- Delete still uses regular form --><td>
                        <form action="{{ route('ai_questions.destroy', $q->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Delete this question?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button></td>
                        </form></tr></table>
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

<script>
document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".question-block").forEach(block => {

        const editor = block.querySelector(".editor-area");
        const textarea = block.querySelector(".hidden-textarea");

        // Load initial question HTML
        editor.innerHTML = textarea.value;

        // Basic formatting buttons
        block.querySelectorAll(".editor-toolbar button[data-cmd]").forEach(btn => {
            btn.addEventListener("click", () => {
                document.execCommand(btn.dataset.cmd, false, null);
            });
        });

        // Text color
        block.querySelector(".textColorBtn").addEventListener("click", () => {
            let color = prompt("Enter text color (name or hex):", "#000000");
            if (color) document.execCommand("foreColor", false, color);
        });

        // Background highlight
        block.querySelector(".bgColorBtn").addEventListener("click", () => {
            let color = prompt("Enter highlight color:", "yellow");
            if (color) document.execCommand("backColor", false, color);
        });

        // Insert Image
        block.querySelector(".insertImageBtn").addEventListener("click", () => {
            let url = prompt("Enter Image URL:");
            if (url) document.execCommand("insertImage", false, url);
        });

        // Insert Math
        block.querySelector(".insertMathBtn").addEventListener("click", () => {
            let formula = prompt("Enter LaTeX:");
            if (formula) {
                let html = `<span class='math'>\\(${formula}\\)</span>`;
                document.execCommand("insertHTML", false, html);

                if (window.MathJax) MathJax.typesetPromise();
            }
        });

        // Insert Table
        block.querySelector(".insertTableBtn").addEventListener("click", () => {
            let tableHtml =
                `<table border="1" cellpadding="4" style="border-collapse:collapse;">
                    <tr><td>Cell 1</td><td>Cell 2</td></tr>
                    <tr><td>Cell 3</td><td>Cell 4</td></tr>
                </table>`;
            document.execCommand("insertHTML", false, tableHtml);
        });

        // Insert Code Block
        block.querySelector(".insertCodeBtn").addEventListener("click", () => {
            let code = prompt("Enter code:");
            if (code) {
                let html = `<pre style="background:#eee; padding:6px;">${code}</pre>`;
                document.execCommand("insertHTML", false, html);
            }
        });

        // Insert Quote
        block.querySelector(".insertQuoteBtn").addEventListener("click", () => {
            let text = prompt("Quote text:");
            if (text) {
                let html = `<blockquote style='border-left:3px solid #999; padding-left:8px;'>${text}</blockquote>`;
                document.execCommand("insertHTML", false, html);
            }
        });

        // Sync editor → textarea before saving
        block.querySelector(".update-btn").addEventListener("click", () => {
            textarea.value = editor.innerHTML;
        });

    });
});
</script>


<!-- ✅ AJAX Script -->
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

            // Convert options into array format
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

@endsection
