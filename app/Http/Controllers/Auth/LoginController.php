<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return inertia('Auth/Login');
    }

    /**
     * Handle user login with email and password.
     * This is for regular users only. Admins should use /admin/login
     */
    public function login(Request $request)
    {
        $this->enforceBotProtection($request);

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if user exists and is admin before attempting login
        $user = User::where('email', $request->email)->first();
        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.login')->with('info', 'Please sign in using the admin login.');
        }

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            // Double check - admins should not use this route
            if ($user->isAdmin()) {
                Auth::logout();
                return redirect()->route('admin.login')->with('info', 'Please sign in using the admin login.');
            }

            // Record successful login
            LoginHistory::recordSuccess($user, $request);
            
            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
