<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
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

                        <h4 class="fw-bold mb-1 text-center">Forgot Password?</h4>
                        <p class="text-muted text-center small mb-4">
                            Enter your registered email and we'll send you a reset link.
                        </p>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('admin.forgot.password.send') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Enter your registered email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa fa-paper-plane me-1"></i>
                                    Send Verification Email
                                </button>
                            </div>

                        </form>

                        <div class="text-center">
                            <a href="{{ route('admin.index') }}" class="text-muted small">
                                ← Back to Login
                            </a>
                        </div>

                    </div>
                </div>

                {{-- Steps info --}}
                <div class="card mt-3 border-0 shadow-sm">
                    <div class="card-body">
                        <p class="fw-semibold mb-2 small">How it works:</p>
                        <div class="d-flex align-items-start mb-2">
                            <span class="badge bg-primary me-2">1</span>
                            <span class="small text-muted">Enter your registered email above</span>
                        </div>
                        <div class="d-flex align-items-start mb-2">
                            <span class="badge bg-primary me-2">2</span>
                            <span class="small text-muted">Check your email for the reset link</span>
                        </div>
                        <div class="d-flex align-items-start mb-2">
                            <span class="badge bg-primary me-2">3</span>
                            <span class="small text-muted">Click the link and set your new password</span>
                        </div>
                        <div class="d-flex align-items-start">
                            <span class="badge bg-primary me-2">4</span>
                            <span class="small text-muted">Login with your new password</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
