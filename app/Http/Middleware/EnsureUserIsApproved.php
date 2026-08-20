<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * Defense in depth alongside the login-time redirect/block in
     * AuthController::authenticate() - catches a pending/rejected user
     * hitting a role-gated route directly with an existing session (e.g.
     * a session that started before a Dean rejected them).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user->isRejected()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        }

        if ($user->isPending()) {
            return redirect()->route('pending-approval');
        }

        return $next($request);
    }
}
