@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-lg border-0 rounded-4" style="background: linear-gradient(135deg, #e3f2fd, #f1f8e9);">
                <div class="card-header text-center text-white fw-bold" style="background: linear-gradient(90deg, #1976d2, #43a047); font-size: 1.3rem;">
                    {{ __('Register') }}
                </div>

                <div class="card-body p-5">
                    <form method="POST" action="{{ route('signup.save') }}">
                        @csrf

                        <div class="row mb-4">
                            <label for="firstname" class="col-md-4 col-form-label text-md-end fw-semibold text-primary">{{ __('First Name') }}</label>
                            <div class="col-md-6">
                                <input id="firstname" type="text" class="form-control rounded-pill @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') }}" required autofocus>
                                @error('firstname')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="lastname" class="col-md-4 col-form-label text-md-end fw-semibold text-primary">{{ __('Last Name') }}</label>
                            <div class="col-md-6">
                                <input id="lastname" type="text" class="form-control rounded-pill @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required>
                                @error('lastname')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="phone" class="col-md-4 col-form-label text-md-end fw-semibold text-primary">{{ __('Phone Number') }}</label>
                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control rounded-pill @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="class" class="col-md-4 col-form-label text-md-end fw-semibold text-primary">{{ __('Class') }}</label>
                            <div class="col-md-6">
                                <select class="form-control rounded-pill @error('class') is-invalid @enderror" name="class" id="class" required>
                                    <option value="">Select Class</option>
                                    <option value="NURSERY 1">NURSERY 1</option>
                                    <option value="NURSERY 2">NURSERY 2</option>
                                    <option value="KG">KG</option>
                                    <option value="GRADE 1">GRADE 1</option>
                                    <option value="GRADE 2">GRADE 2</option>
                                    <option value="GRADE 3">GRADE 3</option>
                                    <option value="GRADE 4">GRADE 4</option>
                                    <option value="GRADE 5">GRADE 5</option>
                                    <option value="JSS 1">JSS 1</option>
                                    <option value="JSS 2">JSS 2</option>
                                    <option value="JSS 3">JSS 3</option>
                                    <option value="SSS 1">SSS 1</option>
                                    <option value="SSS 2">SSS 2</option>
                                    <option value="SSS 3">SSS 3</option>
                                    <option value="SPECIAL">SPECIAL</option>
                                    <option value="ENTRANCE">ENTRANCE</option>
                                </select>
                                @error('class')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="category" class="col-md-4 col-form-label text-md-end fw-semibold text-primary">{{ __('Category') }}</label>
                            <div class="col-md-6">
                                <select class="form-control rounded-pill @error('category') is-invalid @enderror" name="category" id="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Staff">Staff</option>
                                    <option value="Student">Student</option>
                                </select>
                                @error('category')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="session" class="col-md-4 col-form-label text-md-end fw-semibold text-primary">{{ __('Session') }}</label>
                            <div class="col-md-6">
                                <select class="form-control rounded-pill @error('session') is-invalid @enderror" name="session" id="session" required>
                                    <option value="">Select Session</option>
                                    <option value="2025/2026">2025/2026</option>
                                    <option value="2026/2027">2026/2027</option>
                                    <option value="2027/2028">2027/2028</option>
                                    <option value="2028/2029">2028/2029</option>
                                    <option value="2029/2030">2029/2030</option>
                                    <option value="2030/2031">2030/2031</option>
                                </select>
                                @error('session')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="term" class="col-md-4 col-form-label text-md-end fw-semibold text-primary">{{ __('Term') }}</label>
                            <div class="col-md-6">
                                <select class="form-control rounded-pill @error('term') is-invalid @enderror" name="term" id="term" required>
                                    <option value="">Select Term</option>
                                    <option value="First Term">First Term</option>
                                    <option value="Second Term">Second Term</option>
                                    <option value="Third Term">Third Term</option>
                                </select>
                                @error('term')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="email" class="col-md-4 col-form-label text-md-end fw-semibold text-primary">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control rounded-pill @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Password field with eye toggle -->
                        <div class="row mb-4">
                            <label for="password" class="col-md-4 col-form-label text-md-end fw-semibold text-primary">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="password" type="password" class="form-control rounded-start-pill @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Enter password">
                                    <span class="input-group-text rounded-end-pill bg-light" style="cursor:pointer;" id="togglePassword" role="button" tabindex="0" aria-label="Toggle password visibility">
                                        <i class="bi bi-eye text-secondary"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn text-white w-100 py-2 rounded-pill" style="background: linear-gradient(90deg, #1976d2, #43a047); font-weight: 600;">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

{{-- Password toggle script (safe: waits for DOM) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (!toggle || !passwordInput) {
        // If elements aren't present, do nothing (safe fail)
        return;
    }

    const icon = toggle.querySelector('i');

    function togglePasswordVisibility() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            if (icon) {
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash', 'text-success');
            }
        } else {
            passwordInput.type = 'password';
            if (icon) {
                icon.classList.remove('bi-eye-slash', 'text-success');
                icon.classList.add('bi-eye');
            }
        }
    }

    // Click handler
    toggle.addEventListener('click', function (e) {
        e.preventDefault();
        togglePasswordVisibility();
    });

    // Keyboard support (Enter, Space)
    toggle.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar') {
            e.preventDefault();
            togglePasswordVisibility();
        }
    });
});
</script>
@endsection
