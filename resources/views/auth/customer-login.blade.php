@extends('layout.frontlayout')
@section('title', 'Login – Bharat Biomer')

@section('content')

<x-front-breadcrumb
  badge="My Account"
  title="Welcome Back"
  description="Login to track your orders and manage your account."
  :icon="asset('assets/images/flask-icon.svg')"
/>

<section class="avan__section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-7 col-lg-5">

        @if(session('success'))
          <div class="auth__alert auth__alert--success mb-3"><iconify-icon icon="mdi:check-circle-outline" class="icon"></iconify-icon> {{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="auth__alert auth__alert--danger mb-3">⚠ {{ session('error') }}</div>
        @endif

        <div class="avan__card" style="padding:36px;">

          <h3 class="avan__product-title" style="margin-bottom:6px;">Sign In</h3>
          <p style="color:#9aab9a;font-size:.88rem;margin-bottom:28px;">
            Don't have an account?
            <a href="{{ route('customer.register') }}" style="color:#2d7a45;font-weight:600;">Create one</a>
          </p>

          <form action="{{ route('customer.login.post') }}" method="POST">
            @csrf

            <div class="auth__field">
              <label class="auth__label">Email Address <span class="auth__required">*</span></label>
              <input type="email" name="email"
                     class="auth__input {{ $errors->has('email') ? 'auth__input--error' : '' }}"
                     value="{{ old('email') }}" placeholder="your@email.com" maxlength="255" required>
              @error('email')<p class="auth__error">{{ $message }}</p>@enderror
            </div>

            <div class="auth__field">
              <label class="auth__label">Password <span class="auth__required">*</span></label>
              <input type="password" name="password"
                     class="auth__input {{ $errors->has('password') ? 'auth__input--error' : '' }}"
                     placeholder="Enter your password" minlength="6" required>
              @error('password')<p class="auth__error">{{ $message }}</p>@enderror
            </div>

            <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;">
              <input type="checkbox" name="remember" id="remember" style="accent-color:#2d7a45;width:16px;height:16px;">
              <label for="remember" style="font-size:.85rem;color:#6b7c6b;cursor:pointer;">Remember me</label>
            </div>

            <button type="submit" class="auth__btn">Login to My Account →</button>
          </form>

          <div class="auth__divider"><span>New here?</span></div>

          <a href="{{ route('customer.register') }}" class="auth__btn auth__btn--outline">Create Account</a>

        </div>
      </div>
    </div>
  </div>
</section>

@endsection


