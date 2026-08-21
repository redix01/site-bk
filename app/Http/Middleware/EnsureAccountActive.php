<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks account-changing actions (transfers, deposits, withdrawals, savings,
 * investing, profile/security changes) for suspended or locked accounts. The
 * user can still sign in and view their dashboard — they just can't act on it.
 */
class EnsureAccountActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->isActive() && !$user->isAdmin()) {
            $message = $user->isSuspended()
                ? 'Your account is suspended. Please contact support to resolve this before continuing.'
                : 'Your account is locked. Please contact support to resolve this before continuing.';

            return back()->with('error', $message);
        }

        return $next($request);
    }
}
