<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->guest(route('login'))->with('error', 'Please login to access this area.');
        }

        $user = Auth::user();

        // Strict check: Use isAdmin() method for proper type checking
        // This ensures we're checking the boolean value correctly
        if (!$user->isAdmin()) {
            // Log unauthorized access attempt
            Log::warning('Unauthorized admin access attempt', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
            ]);

            abort(403, 'Access denied. Admin privileges required.');
        }

        // Additional security: Check if admin account is active
        // Even admins shouldn't access if their account is suspended or locked
        if (!$user->isActive()) {
            // Log access attempt from inactive admin account
            Log::warning('Inactive admin account access attempt', [
                'user_id' => $user->id,
                'email' => $user->email,
                'status' => $user->status,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Your account is ' . ucfirst($user->status) . '. Please contact support.');
        }

        return $next($request);
    }
}
