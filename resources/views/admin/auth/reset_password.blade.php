<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <!-- Fontfaces CSS-->
    <link href="{{ asset('assets/css/font-face.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('assets/vendor/fontawesome-7.1.0/css/all.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('assets/vendor/mdi-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet"
        media="all">
</head>

<body style="background:#f4f6f9; min-height:100vh; display:flex; align-items:center;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="text-center mb-4">
                    <img src="{{ asset('images/icon/logo.png') }}" height="50" alt="Logo">
                </div>

                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-1 text-center">Set New Password</h4>
                        <p class="text-muted text-center small mb-4">
                            Enter and confirm your new password below.
                        </p>

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.reset.password') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            {{-- New Password --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">New Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="new_password" class="form-control"
                                        placeholder="Enter new password" required oninput="checkStrength(this.value)">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePassword('new_password', 'icon_new')">
                                        <i class="fa fa-eye" id="icon_new"></i>
                                    </button>
                                </div>
                                {{-- Strength bar --}}
                                <div class="mt-2">
                                    <div class="progress" style="height:5px;">
                                        <div id="strength_bar" class="progress-bar" style="width:0%;"></div>
                                    </div>
                                    <small id="strength_text" class="text-muted"></small>
                                </div>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="confirm_password"
                                        class="form-control" placeholder="Confirm new password" required
                                        oninput="checkMatch()">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePassword('confirm_password', 'icon_confirm')">
                                        <i class="fa fa-eye" id="icon_confirm"></i>
                                    </button>
                                </div>
                                <small id="match_text" class="mt-1 d-block"></small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fa fa-lock me-1"></i> Reset Password
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

                {{-- Password rules --}}
                <div class="card mt-3 border-0 shadow-sm">
                    <div class="card-body">
                        <p class="fw-semibold mb-2 small">Password Requirements:</p>
                        <ul class="text-muted small mb-0">
                            <li>Minimum <strong>6 characters</strong></li>
                            <li>At least <strong>1 uppercase</strong> letter (A-Z)</li>
                            <li>At least <strong>1 number</strong> (0-9)</li>
                            <li>At least <strong>1 special character</strong> (A-Za-z0-9)</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'fa fa-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'fa fa-eye';
            }
        }

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
                strength <= 2 ? 'text-danger' :
                strength == 3 ? 'text-warning' :
                strength == 4 ? 'text-info' : 'text-success'
            );
        }

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

</body>

</html>
