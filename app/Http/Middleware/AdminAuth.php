<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('signin');
        }

        $user = Auth::guard('web')->user();
        $isAdmin = method_exists($user, 'ensureActiveRoleAssignment')
            && $user->ensureActiveRoleAssignment();

        if (!$isAdmin) {
            abort(403);
        }

        return $next($request);
    }
}
