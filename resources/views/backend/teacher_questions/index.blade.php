@extends('backend.layouts.master')

@section('title', 'AI Curriculum List')

@section('content')

<style>
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content-box {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        position: relative;
    }

    .close-btn {
        position: absolute;
        right: 15px;
        top: 10px;
        color: #aaa;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-btn:hover {
        color: red;
    }
</style>

<div class="span9">
    <div class="content">
        <div class="module">
            <div class="module-head">
                <h3>AI Maths Curriculum Questions</h3>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search...">

            <table class="table table-bordered table-striped" id="curriculumTable">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Class</th>
                        <th>Curriculum Name</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($curriculums as $item)
                        <tr>
                            <td>{{ $item->subject }}</td>
                            <td>{{ $item->class }}</td>
                            <td>{{ $item->name ?? 'Untitled' }}</td>
                            <td>{{ $item->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('teacher.ai_questions.view', $item->id) }}" class="btn btn-info btn-sm">View</a>

                                <!-- Activate Button -->
                                <button class="btn btn-sm btn-success" onclick="openModal({{ $item->id }})">Activate</button>

                                <!-- Modal -->
                                <div id="modal-{{ $item->id }}" class="custom-modal">
                                    <div class="modal-content-box">
                                        <span class="close-btn" onclick="closeModal({{ $item->id }})">&times;</span>
                                        <form action="{{ route('teacher.ai_questions.activate', $item->id) }}" method="POST">
                                            @csrf
                                            <h5>Activate Curriculum</h5>
                                            <p>Set time limit (in minutes):</p>
                                            <input type="number" name="time_limit" min="1" required class="form-control mb-3" placeholder="E.g: 30">
                                            <button type="submit" class="btn btn-primary btn-sm">Activate</button>
                                        </form>
                                    </div>
                                </div>

                                @php
                                    $assignedCount = DB::table('quiz_user')->where('quiz_id', $item->id)->count();
                                    $completedCount = DB::table('quiz_user')->where('quiz_id', $item->id)->where('status', 1)->count();
                                @endphp

                                <span class="badge bg-info text-dark">Assigned: {{ $assignedCount }}</span>
                                <span class="badge bg-success text-light">Completed: {{ $completedCount }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Search filtering
    document.getElementById("searchInput").addEventListener("keyup", function () {
        var filter = this.value.toLowerCase();
        var rows = document.querySelectorAll("#curriculumTable tbody tr");
        rows.forEach(function (row) {
            row.style.display = row.textContent.toLowerCase().includes(filter) ? "" : "none";
        });
    });

    // Open modal
    function openModal(id) {
        document.getElementById('modal-' + id).style.display = 'block';
    }

    // Close modal
    function closeModal(id) {
        document.getElementById('modal-' + id).style.display = 'none';
    }

    // Optional: close modal when clicking outside
    window.addEventListener('click', function(event) {
        document.querySelectorAll('.custom-modal').forEach(function(modal) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>
@endsection
