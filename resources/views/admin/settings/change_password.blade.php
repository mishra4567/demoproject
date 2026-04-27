@extends('admin.layout.layout')
@section('page_title', 'Change Password')
@section('settings_select', 'active')
@section('container')

    <div class="section__content section__content--p30">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="title-5 m-b-0">Change Password</h3>
                <a href="{{ route('admin.settings') }}" class="btn btn-secondary btn-sm">
                    ← Back to Settings
                </a>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">

                            <form action="{{ route('admin.change.password.update') }}" method="POST">
                                @csrf

                                {{-- Current Password --}}
                                <div class="mb-3">
                                    <label class="control-label mb-1 fw-semibold">
                                        Current Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="current_password" id="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            placeholder="Enter current password" required>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePassword('current_password')">
                                            <i class="fa fa-eye" id="icon_current_password"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- New Password --}}
                                <div class="mb-3">
                                    <label class="control-label mb-1 fw-semibold">
                                        New Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="new_password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Enter new password" required oninput="checkStrength(this.value)">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePassword('new_password')">
                                            <i class="fa fa-eye" id="icon_new_password"></i>
                                        </button>
                                    </div>
                                    {{-- Password strength --}}
                                    <div class="mt-2">
                                        <div class="progress" style="height:5px;">
                                            <div id="strength_bar" class="progress-bar" style="width:0%;"></div>
                                        </div>
                                        <small id="strength_text" class="text-muted"></small>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Confirm New Password --}}
                                <div class="mb-4">
                                    <label class="control-label mb-1 fw-semibold">
                                        Confirm New Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="confirm_password"
                                            class="form-control" placeholder="Confirm new password" required
                                            oninput="checkMatch()">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePassword('confirm_password')">
                                            <i class="fa fa-eye" id="icon_confirm_password"></i>
                                        </button>
                                    </div>
                                    <small id="match_text" class="mt-1"></small>
                                </div>

                                {{-- Submit --}}
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fa fa-lock me-1"></i> Change Password
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

                    {{-- Password rules --}}
                    <div class="card mt-3 border-0 shadow-sm">
                        <div class="card-body">
                            <p class="fw-semibold mb-2">Password Requirements:</p>
                            <ul class="text-muted small mb-0">
                                <li>Minimum 6 characters</li>
                                <li>Use a mix of letters, numbers and symbols</li>
                                <li>Cannot be the same as current password</li>
                                <li>Must match the confirmation field</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        // Show/hide password
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById('icon_' + fieldId);

            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'fa fa-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'fa fa-eye';
            }
        }

        // Password strength checker
        function checkStrength(password) {
            const bar = document.getElementById('strength_bar');
            const text = document.getElementById('strength_text');

            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^A-Za-z0-9]/)) strength++;

            const levels = {
                0: {
                    width: '0%',
                    color: '',
                    label: ''
                },
                1: {
                    width: '25%',
                    color: 'bg-danger',
                    label: 'Weak'
                },
                2: {
                    width: '50%',
                    color: 'bg-warning',
                    label: 'Fair'
                },
                3: {
                    width: '75%',
                    color: 'bg-info',
                    label: 'Good'
                },
                4: {
                    width: '100%',
                    color: 'bg-success',
                    label: 'Strong'
                },
            };

            const level = levels[strength];
            bar.style.width = level.width;
            bar.className = 'progress-bar ' + level.color;
            text.textContent = level.label;
            text.className = 'small ' + (
                strength <= 1 ? 'text-danger' :
                strength == 2 ? 'text-warning' :
                strength == 3 ? 'text-info' : 'text-success'
            );
        }

        // Password match checker
        function checkMatch() {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            const matchText = document.getElementById('match_text');

            if (confirmPass === '') {
                matchText.textContent = '';
                return;
            }

            if (newPass === confirmPass) {
                matchText.textContent = '✅ Passwords match';
                matchText.className = 'small text-success mt-1';
            } else {
                matchText.textContent = '❌ Passwords do not match';
                matchText.className = 'small text-danger mt-1';
            }
        }
    </script>

@endsection
