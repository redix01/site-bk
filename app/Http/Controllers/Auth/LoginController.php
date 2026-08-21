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
     * Single login entry point.
     * Admins are logged in directly and sent to /admin/dashboard.
     * Normal users have credentials validated, receive an OTP via email,
     * and are redirected to the OTP verification page.
     */
    public function login(Request $request)
    {
        $this->enforceBotProtection($request);

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Admin → log in directly, skip OTP
        if ($user->isAdmin()) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        // Normal user → generate OTP, send email, redirect to OTP page
        Auth::logout();

        $otpCode = OtpCode::generateForEmail($request->email);
        Mail::to($request->email)->send(new OtpCodeMail($otpCode->code, $user->name));

        session([
            'otp_user_email' => $request->email,
            'otp_user_name' => $user->name,
            'otp_remember' => $request->boolean('remember'),
        ]);

        return redirect()->route('login.otp')->with([
            'success' => 'A verification code has been sent to your email.',
            'user_name' => $user->name,
        ]);
    }

    /**
     * Show the OTP verification form for normal users.
     */
    public function showOtpForm()
    {
        $email = session('otp_user_email');

        if (!$email) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        return inertia('Auth/OtpVerify', [
            'email' => $email,
            'userName' => session('otp_user_name'),
        ]);
    }

    /**
     * Verify OTP and complete login for normal users.
     */
    public function verifyOtpAndLogin(Request $request)
    {
        $this->enforceBotProtection($request);

        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
        ]);

        $sessionEmail = session('otp_user_email');
        if (!$sessionEmail || $sessionEmail !== $request->email) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

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

        // OTP verified — log the user in
        $user = User::where('email', $request->email)->first();

        Auth::login($user, session('otp_remember', false));
        $request->session()->regenerate();

        // Clean up session data
        session()->forget(['otp_user_email', 'otp_user_name', 'otp_remember']);

        return redirect()->intended('/dashboard');
    }

    /**
     * Resend OTP code for normal users.
     */
    public function resendOtp(Request $request)
    {
        $this->enforceBotProtection($request);

        $sessionEmail = session('otp_user_email');
        if (!$sessionEmail) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $user = User::where('email', $sessionEmail)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Account not found. Please log in again.');
        }

        // Delete old OTPs and generate a new one
        OtpCode::where('email', $sessionEmail)->delete();
        $otpCode = OtpCode::generateForEmail($sessionEmail);
        Mail::to($sessionEmail)->send(new OtpCodeMail($otpCode->code, $user->name));

        return back()->with([
            'success' => 'A new verification code has been sent to your email.',
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
