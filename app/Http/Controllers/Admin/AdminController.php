<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            return view('admin.login');
        }

        return view('admin.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Validate and authenticate admin credentials.
     */
    public function auth(Request $request)
    {
        //this is for admin authentication
        $email = $request->email;
        $password = $request->password;

        $admin = Admin::where('email', $email)->first();

        if ($admin) {
            if (Hash::check($password, $admin->password)) {

                $request->session()->put('ADMIN_LOGIN', true);
                $request->session()->put('ADMIN_ID', $admin->id);

                // This store the intended url and go where wanted to go
                return redirect()->intended('admin/dashboard')
                    ->with('success', 'You are logged in successfully');
                // return redirect('admin/dashboard')
                //     ->with('success', 'You are logged in successfully');
            } else {
                return redirect('admin')
                    ->with('error', 'Incorrect password');
            }
        }
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
