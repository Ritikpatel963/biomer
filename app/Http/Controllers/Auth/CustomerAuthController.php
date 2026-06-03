<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\CustomerWelcome;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CustomerAuthController extends Controller
{
    // ── Show Login ─────────────────────────────────────────────────────
    public function showLogin()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('products.index');
        }

        $redirect = request('redirect');
        if (is_string($redirect) && str_starts_with($redirect, url('/'))) {
            session()->put('url.intended', $redirect);
        }

        return view('auth.customer-login');
    }

    // ── Login ──────────────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // ✅ Get intended URL but never redirect to admin routes
            $intended = session()->pull('url.intended', '');

            if ($intended && 
                !str_contains($intended, 'authentication') && 
                !str_contains($intended, 'dashboard') &&
                !str_contains($intended, 'signin')) {
                return redirect($intended)
                    ->with('success', 'Welcome back, ' . Auth::guard('customer')->user()->name . '!');
            }

            return redirect()->route('products.index')
                ->with('success', 'Welcome back, ' . Auth::guard('customer')->user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    // ── Show Register ──────────────────────────────────────────────────
    public function showRegister()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('products.index');
        }
        return view('auth.customer-register');
    }

    // ── Register ───────────────────────────────────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:customers,email',
            'phone'                 => 'nullable|string|max:15',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $customer = Customer::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Send welcome email
        try {
            Mail::to($customer->email)->send(new CustomerWelcome($customer));
        } catch (\Exception $e) {
            // Log email error but don't fail registration
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        Auth::guard('customer')->login($customer);

        return redirect()->route('products.index')
            ->with('success', 'Account created! Welcome, ' . $customer->name . '!');
    }

    // ── Logout ─────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->regenerateToken();
        return redirect()->route('customer.login')
            ->with('success', 'You have been logged out.');
    }
}
