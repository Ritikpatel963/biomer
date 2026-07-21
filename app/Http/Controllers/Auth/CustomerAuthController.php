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
        if (is_string($redirect) && $this->isSafeRedirectUrl($redirect)) {
            session()->put('url.intended', $redirect);
        }

        return view('auth.customer-login');
    }

    // ── Login ──────────────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->merge([
            'email' => trim((string) $request->input('email', '')),
        ]);

        $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:6',
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
        $request->merge([
            'name' => trim((string) $request->input('name', '')),
            'email' => trim((string) $request->input('email', '')),
            'phone' => trim((string) $request->input('phone', '')),
        ]);

        $validated = $request->validate([
            'name'                  => 'required|string|min:2|max:255',
            'email'                 => 'required|email|max:255|unique:customers,email',
            'phone'                 => ['nullable', 'string', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'password'              => ['required', 'min:8', 'confirmed', 'regex:/^(?=.*[a-zA-Z])(?=.*[0-9])/'],
            'password_confirmation' => 'required',
        ], [
            'password.regex' => 'Password must contain at least one letter and one number.',
            'phone.regex' => 'Please enter a valid phone number.',
        ]);

        $customer = Customer::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
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
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('customer.login')
            ->with('success', 'You have been logged out.');
    }

    private function isSafeRedirectUrl(string $redirect): bool
    {
        $parsedRedirect = parse_url($redirect);

        if ($parsedRedirect === false) {
            return false;
        }

        $redirectHost = $parsedRedirect['host'] ?? null;

        if ($redirectHost === null) {
            return str_starts_with($redirect, '/') && !str_starts_with($redirect, '//');
        }

        return strtolower($redirectHost) === strtolower(parse_url(url('/'), PHP_URL_HOST) ?? '');
    }
}
