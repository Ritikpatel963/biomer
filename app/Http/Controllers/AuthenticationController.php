<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function signIn()
    {
        if (Auth::guard('web')->check()) {
            if (!$this->isAdmin(Auth::guard('web')->user())) {
                Auth::guard('web')->logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();

                return redirect()->route('signin')
                    ->withErrors(['email' => 'This account is not allowed to access the admin panel.']);
            }

            return redirect()->route('index');
        }
        return view('authentication.signin');
    }

    public function loginProcess(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();

            if (!$this->isAdmin($user)) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['email' => 'Unauthorized admin account.'])->onlyInput('email');
            }

            return redirect()->route('index');
        }

        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }

    public function signOut(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('signin');
    }

    private function isAdmin($user): bool
    {
        $adminEmails = array_map('strtolower', config('admin.emails', []));

        return (bool) ($user->is_admin ?? false)
            || in_array(($user->role ?? null), ['admin', 'super-admin'], true)
            || in_array(($user->type ?? null), ['admin', 'super-admin'], true)
            || in_array(strtolower((string) $user->email), $adminEmails, true);
    }
}
