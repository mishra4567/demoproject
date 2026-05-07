<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\AdminEmailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use function Symfony\Component\Clock\now;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->session()->has('ADMIN_LOGIN')) {
            return redirect('admin/dashboard');
        } else {
            return view('admin.auth.login');
        }

        return view('admin.auth.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Login Process for admin roles users.
     * Validate and authenticate admin credentials.
     */
    public function auth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
        ]);
        //this is for admin authentication
        $email = $request->email;
        $password = $request->password;

        $admin = Admin::where('email', $email)->first();
        // Email not found
        if (!$admin) {
            return redirect('admin')
                ->with('error', 'Email not found')
                ->withInput();
        }
        // Check password
        if (!Hash::check($password, $admin->password)) {
            return redirect('admin')
                ->with('error', 'Incorrect password')
                ->withInput();
        }
        // Email not verified
        if (is_null($admin->email_verified_at)) {
            return redirect('admin')
                ->with('error', 'Please verify your email before logging in')
                ->withInput();
        }
        // Not approved by super admin
        if ($admin->admin_appr != 1) {
            return redirect('admin')
                ->with('error', 'Your account is pending approval by the super admin')
                ->withInput();
        }
        // Account suspended and inactive
        if ($admin->status != 1) {
            return redirect('admin')
                ->with('error', 'Your account is suspended. Please contact support.')
                ->withInput();
        }

        // put admin data in session
        $request->session()->put('ADMIN_LOGIN', true);
        $request->session()->put('ADMIN_ID', $admin->id);
        $request->session()->put('ADMIN_ROLE', $admin->admin_role);
        $request->session()->put('ADMIN_NAME', $admin->name);

        $request->session()->put('ADMIN_IS_SUPER', $admin->is_super_admin);

        return redirect()->intended('admin/dashboard')
            ->with('success', 'Welcome back, ' . $admin->name . '!');
    }
    // This method shows the registration form for new admin users.
    public function register(Request $request)
    {
        return view('admin.auth.register');
    }
    // This method processes the registration form submission, creates a new admin user, and sends a verification email.
    public function registerProcess(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:administrator,manager,editor,reviewer',
        ], [
            'email.unique' => 'This email is already registered',
            'password.confirmed' => 'The password confirmation does not match',
            'role.in' => 'Please select a valid role',
        ]);
        // Generate verification token
        $token = Str::random(64);

        $admin = Admin::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'admin_role' => $request->role,
            'status' => 0,
            'admin_appr' => 0,
            'is_super_admin' => 0,
            'email_verification_token' => $token,
            'email_verified_at'        => null,
        ]);
        // Send verification email
        AdminEmailService::send('verify', $admin, [
            'verification_link' => route('admin.verify.email', $token),
        ]);
        return redirect()->back()
            ->with('success', 'Registration successful! Please check your email to verify your account. After verification, wait for super admin approval.')
            ->withInput();
    }
    // This method handles email verification when the user clicks the link in the verification email.
    public function verifyEmail(Request $request, $token)
    {
        $admin = Admin::where('email_verification_token', $token)->first();
        if (!$admin) {
            return redirect()->route('admin.index')
                ->with('error', 'Invalid verification token');
        }
        if (!is_null($admin->email_verified_at)) {
            return redirect()->route('admin.index')
                ->with('info', 'Your email is already verified. Please wait for super admin approval.');
        }
        $admin->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);
        return redirect()->route('admin.index')
            ->with('success', 'Email verified successfully! Please wait for super admin approval before logging in.');
    }

    /**
     * Display the specified resource.
     */

    // public function updatepassword()
    // {
    //     // update admin password
    //     $admin = Admin::find(0);

    //     if (!$admin) {
    //         return "Admin not found";
    //     }

    //     $admin->password = Hash::make('newpassword');
    //     $admin->save();

    //     return "Password updated successfully";
    // }

    /**
     * Status        admin_appr         Meaning
     *  0              0                  Pending - just registered
     *  0              0                  Email not verified
     *  1              1                  Approved - can login
     *  0              2                  Rejected
     *  2              any                Suspended
     */
    /**
     *  Actions send emails automatically:
     *  Action             Email sent
     *  Approve             approved — with user ID, role, login URL
     *  Reject              rejected — with reason
     *  Suspend             suspended — with reason
     *  Reactivate          No email
     */

    public function settings()
    {
        $profile = DB::table('admins')
            ->where(function ($query) {
                $query->where('is_super_admin', 0) // ← exclude super admin
                    ->orWhereNull('is_super_admin');
            })
            ->orderByRaw("
            CASE status
                WHEN 0 THEN 1
                WHEN 1 THEN 2
                WHEN 2 THEN 3
                ELSE 4
            END
        ")
            ->get();

        return view('admin.settings.settings', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    // ─── Approve / Reject / Suspend / Reactivate ──────────
    public function profileupdate(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,suspend,reactivate',
            'reason' => 'required_if:action,reject|required_if:action,suspend|nullable|string|max:500',
        ], [
            'action.required'    => 'Please select an action.',
            'reason.required_if' => 'Reason is required for reject and suspend.',
        ]);

        $admin = Admin::findOrFail($id);

        // Block super admin
        if ($admin->is_super_admin == 1) {
            return redirect()->back()
                ->with('error', 'Cannot modify super admin account.');
        }

        // Block self suspend
        if ($request->action === 'suspend' && $admin->id == session('ADMIN_ID')) {
            return redirect()->back()
                ->with('error', 'You cannot suspend your own account.');
        }

        switch ($request->action) {

            case 'approve':
                if (is_null($admin->email_verified_at)) {
                    return redirect()->back()
                        ->with('error', 'Cannot approve — user has not verified their email yet.');
                }
                $newPassword = Str::random(10);
                $admin->update([
                    'admin_appr' => 1,
                    'status' => 1,
                    'password' => Hash::make($newPassword)
                ]);
                AdminEmailService::send('approved', $admin, [
                    'role'      => $admin->admin_role,
                    'username' => $admin->name,
                    'password' => $newPassword,
                    'login_url' => route('admin.index'),
                ]);
                $message = $admin->name . ' has been approved.';
                break;

            case 'reject':
                $admin->update(['admin_appr' => 2, 'status' => 0]);
                AdminEmailService::send('rejected', $admin, [
                    'reason' => $request->reason,
                ]);
                $message = $admin->name . ' has been rejected.';
                break;

            case 'suspend':
                $admin->update(['status' => 2]);
                AdminEmailService::send('suspended', $admin, [
                    'reason' => $request->reason,
                ]);
                $message = $admin->name . ' has been suspended.';
                break;

            case 'reactivate':
                // Generate a temporary password
                $newPassword = Str::random(10);
                $admin->update([
                    'admin_appr' => 1,
                    'status' => 1,
                    'password' => Hash::make($newPassword)
                ]);
                // $admin->save();
                AdminEmailService::send('approved', $admin, [
                    'reason' => 'Your account has been reactivated.',
                    'role'      => $admin->admin_role,
                    'username' => $admin->name,
                    'password' => $newPassword,
                    'login_url' => route('admin.index'),
                ]);
                $message = $admin->name . ' has been reactivated.';
                break;

            default:
                return redirect()->back()->with('error', 'Invalid action.');
        }

        return redirect()->back()->with('success', $message);
    }


    /**
     * Chenge Password Form for admin user.
     */
    public function changePasswordForm()
    {
        return view('admin.settings.change_password');
    }
    // ─── Update password ───────────────────────────────────
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.confirmed' => 'New password confirmation does not match.',
        ]);

        $admin = Admin::findOrFail(session('ADMIN_ID'));

        // ✅ Check current password
        if (!Hash::check($request->current_password, $admin->password)) {
            return redirect()->back()
                ->with('error', 'Current password is incorrect.')
                ->withInput();
        }

        // ✅ Check new password is not same as current
        if (Hash::check($request->password, $admin->password)) {
            return redirect()->back()
                ->with('error', 'New password cannot be the same as current password.')
                ->withInput();
        }

        // ✅ Update password
        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.change.password')
            ->with('success', 'Password changed successfully. Please use your new password next time you login.');
    }

    /**
     * Logout the admin user.
     */
    public function logout(Request $request)
    {
        $request->session()->forget('ADMIN_LOGIN');
        $request->session()->forget('ADMIN_ID');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin')->with('success', 'Logged out successfully');
    }
    /**
     * Forgot password form for admin user.
     */
    public function forgotPassword()
    {
        return view('admin.auth.forgot_password');
    }
    public function forgotPasswordSend(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $admin = Admin::where('email', $request->email)->first();
        // Always show success even if email not found (security)
        if (!$admin) {
            return redirect()->back()
                ->with('success', 'If this email exists, a reset link has been sent.');
        }
        // Generate token
        $token = Str::random(64);
        $admin->update([
            'password_reset_token'      => $token,
            'password_reset_expires_at' => Carbon::now()->addMinutes(60),
        ]);
        // Send email
        AdminEmailService::send('reset_password', $admin, [
            'reset_url' => route('admin.reset.password.form', $token),
            'expiry'    => '60 minutes',
        ]);
        return redirect()->back()
            ->with('success', 'Password reset link sent! Please check your email.');
    }
    public function resetPasswordForm(Request $request, string $token)
    {
        $admin = Admin::where('password_reset_token', $token)->first();
        // Invalid token
        if (!$admin) {
            return redirect()->route('admin.forgot.password')
                ->with('error', 'Invalid or expired reset link. Please request a new one.');
        }
        // Expired token
        if (Carbon::now()->isAfter($admin->password_reset_expires_at)) {
            $admin->update([
                'password_reset_token'      => null,
                'password_reset_expires_at' => null,
            ]);
            return redirect()->route('admin.forgot.password')
                ->with('error', 'This reset link has expired. Please request a new one.');
        }
        return view('admin.auth.reset_password', compact('token'));
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
        ], [
            'password.min'      => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex'    => 'Password must contain uppercase, lowercase, number and special character (@$!%*#?&).',
        ]);
        $admin = Admin::where('password_reset_token', $request->token)->first();
        // Invalid token
        if (!$admin) {
            return redirect()->route('admin.forgot.password')
                ->with('error', 'Invalid or expired reset link.');
        }
        // Expired
        if (Carbon::now()->isAfter($admin->password_reset_expires_at)) {
            $admin->update([
                'password_reset_token'      => null,
                'password_reset_expires_at' => null,
            ]);
            return redirect()->route('admin.forgot.password')
                ->with('error', 'Reset link expired. Please request a new one.');
        }
        // ✅ Update password and clear token
        $admin->update([
            'password'                  => Hash::make($request->password),
            'password_reset_token'      => null,
            'password_reset_expires_at' => null,
        ]);
        return redirect()->route('admin.index')
            ->with('success', 'Password reset successfully! You can now login with your new password.');
    }
    /**
     * Forgot password form for admin user End.
     */
}
