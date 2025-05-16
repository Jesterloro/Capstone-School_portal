<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ================================
    // Show Admin Login Form
    // ================================
    public function showLoginForm()
    {
        return view('login_admin.login');
    }

    // ================================
    // Handle Admin Login
    // ================================
    public function login(Request $request)
    {
        // Validate the login request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Check admin credentials
        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            return redirect()->route('admin.dashboard')->with('success', 'Login successful!');
        }

        // Redirect back if login fails
        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput($request->only('email'));
    }

    // ================================
    // Handle Admin Logout
    // ================================
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }
}
