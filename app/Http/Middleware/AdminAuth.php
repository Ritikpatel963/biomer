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
        $adminEmails = array_map('strtolower', config('admin.emails', []));

        $isAdmin = (bool) ($user->is_admin ?? false)
            || in_array(($user->role ?? null), ['admin', 'super-admin'], true)
            || in_array(($user->type ?? null), ['admin', 'super-admin'], true)
            || in_array(strtolower((string) $user->email), $adminEmails, true);

        if (!$isAdmin) {
            abort(403);
        }

        return $next($request);
    }
}
