@extends('layout.frontlayout')
@section('title', 'Login – Bharat Biomer')

@section('content')

<section class="prodh__section">
  <div class="container">
    <div class="row">
      <div class="col-12 col-lg-6">
        <div class="prodh__badge mb-3">
          <img src="{{ asset('assets/images/flask-icon.svg') }}" alt="" class="prodh__badge-icon"/>
          <span class="prodh__badge-text">My Account</span>
        </div>
        <h1 class="prodh__heading">Welcome Back</h1>
        <p class="prodh__desc">Login to track your orders and manage your account.</p>
      </div>
    </div>
  </div>
</section>

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
                     value="{{ old('email') }}" placeholder="your@email.com" required>
              @error('email')<p class="auth__error">{{ $message }}</p>@enderror
            </div>

            <div class="auth__field">
              <label class="auth__label">Password <span class="auth__required">*</span></label>
              <input type="password" name="password"
                     class="auth__input {{ $errors->has('password') ? 'auth__input--error' : '' }}"
                     placeholder="Enter your password" required>
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

@push('styles')
<style>
  .auth__alert{padding:12px 16px;border-radius:8px;font-size:.88rem;font-weight:600;}
  .auth__alert--success{background:#e8f5ed;color:#2d7a45;border:1px solid #a8d5b5;}
  .auth__alert--danger{background:#fff0f0;color:#dc3545;border:1px solid #f5c6cb;}
  .auth__field{margin-bottom:18px;}
  .auth__label{display:block;font-size:.85rem;font-weight:600;color:#4a5e4a;margin-bottom:6px;}
  .auth__required{color:#dc3545;}
  .auth__input{width:100%;padding:11px 15px;border:1.5px solid #c8e6c9;border-radius:8px;font-size:.9rem;color:#1a2e1a;background:#fff;outline:none;transition:border-color .2s;font-family:inherit;}
  .auth__input:focus{border-color:#2d7a45;box-shadow:0 0 0 3px rgba(45,122,69,.08);}
  .auth__input--error{border-color:#dc3545;}
  .auth__error{font-size:.78rem;color:#dc3545;margin:4px 0 0;}
  .auth__btn{display:flex;align-items:center;justify-content:center;width:100%;padding:13px;border-radius:10px;font-size:.95rem;font-weight:700;cursor:pointer;border:none;text-decoration:none;background:#2d7a45;color:#fff;transition:background .2s;}
  .auth__btn:hover{background:#245e36;}
  .auth__btn--outline{background:transparent;color:#2d7a45;border:2px solid #2d7a45;}
  .auth__btn--outline:hover{background:#f4faf0;}
  .auth__divider{text-align:center;margin:20px 0;position:relative;}
  .auth__divider::before,.auth__divider::after{content:'';position:absolute;top:50%;width:38%;height:1px;background:#e8f0e4;}
  .auth__divider::before{left:0;}.auth__divider::after{right:0;}
  .auth__divider span{background:#fff;padding:0 10px;color:#9aab9a;font-size:.8rem;}
</style>
@endpush