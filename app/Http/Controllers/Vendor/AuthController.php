<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    //  Register
    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:vendors',
            'password'  => 'required|min:8|confirmed',
            'shop_name' => 'nullable|string|max:255',
            'phone'     => 'nullable|string|max:20',
        ]);

        $vendor = Vendor::create($data);

        Auth::guard('vendor')->login($vendor);

        // Regenerate session
        $request->session()->regenerate();

        // Optional session data
        session([
            'vendor_id'    => $vendor->id,
            'vendor_name'  => $vendor->name,
            'vendor_email' => $vendor->email,
        ]);

        return to_route('vendor.dashboard');
    }
    // ── Login ─────────────────────────────────
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::guard('vendor')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        // Regenerate session
        $request->session()->regenerate();

        // Logged vendor
        $vendor = Auth::guard('vendor')->user();

        // Optional session data
        session([
            'vendor_id'    => $vendor->id,
            'vendor_name'  => $vendor->name,
            'vendor_email' => $vendor->email,
        ]);

        return to_route('vendor.dashboard')
            ->with('success', 'Welcome back, ' . Auth::guard('vendor')->user()->name . '!');;
    }
    // ── Logout ────────────────────────────────
    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();

        // Clear session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return to_route('vendor.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
