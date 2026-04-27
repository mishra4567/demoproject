<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Register</title>

    <!-- Fontfaces CSS-->
    <link href="{{ asset('assets/css/font-face.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('assets/vendor/fontawesome-7.1.0/css/all.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('assets/vendor/mdi-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet"
        media="all">

    <!-- Bootstrap CSS-->
    <link href="{{ asset('assets/vendor/bootstrap-5.3.8.min.css') }}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{ asset('assets/css/aos.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('assets/vendor/css-hamburgers/hamburgers.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('assets/css/swiper-bundle-12.0.3.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('assets/vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css') }}" rel="stylesheet"
        media="all">

    <!-- Main CSS-->
    <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet" media="all">

</head>

<body>
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container-fluid"
                style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f5f5f5;">
                <div class="row w-100" style="max-width: 1000px; margin: auto;">

                    {{-- LEFT — Register Form --}}
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-lg">
                            <div class="card-body p-4">

                                <div class="text-center mb-4">
                                    <a href="#">
                                        <img src="{{ asset('images/icon/logo.png') }}" alt="Logo" height="50">
                                    </a>
                                    <h4 class="mt-3 fw-bold">Create Admin Account</h4>
                                    <p class="text-muted small">Fill in the details to request access</p>
                                </div>

                                <form action="{{ route('admin.register.process') }}" method="post">
                                    @csrf
                                    @include('admin.include.notify')

                                    <div class="form-group mb-3">
                                        <label class="mb-1">Username</label>
                                        <input class="au-input au-input--full" type="text" name="username"
                                            placeholder="Username" value="{{ old('username') }}" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="mb-1">Email Address</label>
                                        <input class="au-input au-input--full" type="email" name="email"
                                            placeholder="Email" value="{{ old('email') }}" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="mb-1">Password</label>
                                        <input class="au-input au-input--full" type="password" name="password"
                                            placeholder="Password" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="mb-1">Confirm Password</label>
                                        <input class="au-input au-input--full" type="password"
                                            name="password_confirmation" placeholder="Confirm Password" required>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="mb-1">Select Role</label>
                                        <select name="role" class="au-input au-input--full" required
                                            style="height: 40px; padding: 0 10px;">
                                            <option value="">-- Select Role --</option>
                                            <option value="administrator"
                                                {{ old('role') == 'administrator' ? 'selected' : '' }}>Administrator
                                            </option>
                                            <option value="manager"
                                                {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                            <option value="editor"
                                                {{ old('role') == 'editor' ? 'selected' : '' }}>Editor</option>
                                            <option value="reviewer"
                                                {{ old('role') == 'reviewer' ? 'selected' : '' }}>Reviewer
                                            </option>
                                        </select>
                                    </div>

                                    <button class="au-btn au-btn--block au-btn--green m-b-20"
                                        type="submit">Register</button>

                                </form>

                                <div class="text-center mt-3">
                                    <p class="text-muted small">
                                        Already have account?
                                        <a href="{{ route('admin.index') }}">Sign In</a>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- RIGHT — Info Section --}}
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="w-100 p-4">

                            <h4 class="fw-bold mb-4" style="color: #333;">
                                <i class="fa fa-info-circle me-2" style="color: #4CAF50;"></i>
                                How It Works
                            </h4>

                            {{-- Step 1 --}}
                            <div class="d-flex align-items-start mb-4">
                                <div class="me-3 flex-shrink-0">
                                    <div
                                        style="
                            width: 42px; height: 42px;
                            background: #4CAF50;
                            border-radius: 50%;
                            display: flex; align-items: center; justify-content: center;
                            color: white; font-weight: bold; font-size: 16px;">
                                        1
                                    </div>
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Details Saved to Database</h6>
                                    <p class="text-muted small mb-0">
                                        Once you submit the form, your login credentials and selected role are securely
                                        saved. Your account status will be set to <strong>Pending</strong> until
                                        reviewed.
                                    </p>
                                </div>
                            </div>

                            {{-- Divider --}}
                            <div
                                style="border-left: 2px dashed #ddd; height: 20px; margin-left: 20px; margin-bottom: 16px;">
                            </div>

                            {{-- Step 2 --}}
                            <div class="d-flex align-items-start mb-4">
                                <div class="me-3 flex-shrink-0">
                                    <div
                                        style="
                            width: 42px; height: 42px;
                            background: #2196F3;
                            border-radius: 50%;
                            display: flex; align-items: center; justify-content: center;
                            color: white; font-weight: bold; font-size: 16px;">
                                        2
                                    </div>
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Admin Reviews & Activates Your Profile</h6>
                                    <p class="text-muted small mb-0">
                                        A super admin will review your registration request. They will verify your role
                                        and either <strong>approve</strong> or <strong>reject</strong> your account from
                                        the admin panel.
                                    </p>
                                </div>
                            </div>

                            {{-- Divider --}}
                            <div
                                style="border-left: 2px dashed #ddd; height: 20px; margin-left: 20px; margin-bottom: 16px;">
                            </div>

                            {{-- Step 3 --}}
                            <div class="d-flex align-items-start mb-4">
                                <div class="me-3 flex-shrink-0">
                                    <div
                                        style="
                            width: 42px; height: 42px;
                            background: #FF9800;
                            border-radius: 50%;
                            display: flex; align-items: center; justify-content: center;
                            color: white; font-weight: bold; font-size: 16px;">
                                        3
                                    </div>
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Receive Email with Login Details</h6>
                                    <p class="text-muted small mb-0">
                                        Once approved, you will receive a confirmation email with your <strong>User
                                            ID</strong>, <strong>password</strong>, <strong>Role</strong>, and a direct login link to access
                                        the admin panel.
                                    </p>
                                </div>
                            </div>

                            {{-- Note --}}
                            <div class="mt-3 p-3 rounded"
                                style="background: #fff8e1; border-left: 4px solid #FF9800;">
                                <p class="small mb-0" style="color: #795548;">
                                    <i class="fa fa-exclamation-triangle me-1" style="color: #FF9800;"></i>
                                    <strong>Note:</strong> Approval may take up to 24 hours. Please do not register
                                    multiple times. Contact support if you face any issues.
                                </p>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap 5 JS-->
    <!-- Jquery JS-->
    <script src="{{ asset('assets/js/vanilla-utils.js') }}"></script>
    <!-- Bootstrap JS-->
    <script src="{{ asset('assets/vendor/bootstrap-5.3.8.bundle.min.js') }}"></script>
    <!-- Vendor JS       -->
    <script src="{{ asset('assets/vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chartjs/chart.umd.js-4.5.1.min.js') }}"></script>

    <!-- Main JS-->
    <script src="{{ asset('assets/js/bootstrap5-init.js') }}"></script>
    <script src="{{ asset('assets/js/main-vanilla.js') }}"></script>
    <script src="{{ asset('assets/js/swiper-bundle-12.0.3.min.js') }}"></script>
    <script src="{{ asset('assets/js/aos.js') }}"></script>
    <script src="{{ asset('assets/js/modern-plugins.js') }}"></script>

</body>

</html>
<!-- end document-->
