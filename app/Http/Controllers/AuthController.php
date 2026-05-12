<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // 🔹 Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // 🔹 Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on role
            if (auth()->user()->is_admin) {
                return redirect('/admin');  // Filament panel
            } else {
                return redirect()->route('user.dashboard');
            }
        }

        return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput(['email' => $request->email]);

    }

    // 🔹 Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // 🔹 Handle register
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // min:6 → min:8
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false,
        ]);

        Auth::login($user);

        // Send verification email
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('success', 'Account created! Please verify your email.');
    }

    // 🔹 Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
                         ->with('success', 'Logged out successfully.');
    }
}
