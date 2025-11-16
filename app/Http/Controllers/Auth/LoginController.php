<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpCodeMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return inertia('Auth/Login');
    }

    /**
     * Step 1: Validate email and send OTP
     * This is for regular users only. Admins should use /admin/login
     */
    public function sendOtp(Request $request)
    {
        $this->enforceBotProtection($request);

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No account found with this email address.',
            ]);
        }

        // Prevent admins from using the user login flow - redirect them to admin login
        if ($user->isAdmin()) {
            return redirect()->route('admin.login')->with('info', 'Please sign in using the admin login.');
        }

        // Generate and send OTP
        $otpCode = OtpCode::generateForEmail($request->email);
        
        // Send OTP via email
        Mail::to($request->email)->send(new OtpCodeMail($otpCode->code, $user->name));

        // Store user name in session for the next step
        session(['login_user_name' => $user->name, 'login_user_email' => $request->email]);

        return back()->with([
            'success' => 'OTP code sent to your email',
            'user_name' => $user->name,
        ]);
    }

    /**
     * Resend OTP code
     * This is for regular users only. Admins should use /admin/login
     */
    public function resendOtp(Request $request)
    {
        $this->enforceBotProtection($request);

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No account found with this email address.',
            ]);
        }

        // Prevent admins from using the user login flow - redirect them to admin login
        if ($user->isAdmin()) {
            return redirect()->route('admin.login')->with('info', 'Please sign in using the admin login.');
        }

        // Delete any existing OTP codes for this email
        OtpCode::where('email', $request->email)->delete();

        // Generate and send new OTP
        $otpCode = OtpCode::generateForEmail($request->email);
        
        // Send OTP via email
        Mail::to($request->email)->send(new OtpCodeMail($otpCode->code, $user->name));

        return back()->with([
            'success' => 'New OTP code sent to your email',
        ]);
    }

    /**
     * Step 2: Verify OTP and password
     * This is for regular users only. Admins should use /admin/login
     */
    public function verifyOtpAndLogin(Request $request)
    {
        $this->enforceBotProtection($request);

        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No account found with this email address.',
            ]);
        }

        // Prevent admins from using the user login flow - redirect to admin login
        // This is a security measure in case they somehow bypassed the sendOtp check
        if ($user->isAdmin()) {
            Auth::logout();
            return redirect()->route('admin.login')->with('info', 'Please sign in using the admin login.');
        }

        // Verify OTP code - Get the most recent OTP for this email
        $otpCode = OtpCode::where('email', $request->email)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otpCode) {
            throw ValidationException::withMessages([
                'otp_code' => 'No OTP code found. Please request a new code.',
            ]);
        }

        if ($otpCode->isExpired()) {
            throw ValidationException::withMessages([
                'otp_code' => 'OTP code has expired. Please request a new code.',
            ]);
        }

        if ($otpCode->isUsed()) {
            throw ValidationException::withMessages([
                'otp_code' => 'OTP code has already been used. Please request a new code.',
            ]);
        }

        if ($otpCode->hasExceededMaxAttempts()) {
            throw ValidationException::withMessages([
                'otp_code' => 'Too many failed attempts. Please request a new code.',
            ]);
        }

        if (!$otpCode->verify($request->otp_code)) {
            throw ValidationException::withMessages([
                'otp_code' => 'Invalid OTP code. Please try again.',
            ]);
        }

        // Verify password
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            throw ValidationException::withMessages([
                'password' => 'Invalid password.',
            ]);
        }

        $request->session()->regenerate();
        
        // Regular users go to their dashboard
        // Admins should never reach this point due to the check above
        return redirect()->intended('/dashboard');
    }

    /**
     * Legacy login method for backward compatibility
     * Note: This should not be used for new logins. Use the OTP flow instead.
     * Admins should use /admin/login
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
