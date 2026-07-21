@extends('layout.frontlayout')
@section('title', 'Register – Bharat Biomer')

@section('content')

<x-front-breadcrumb
  badge="Join Us"
  title="Create Your Account"
  description="Register to order, track deliveries and manage your account."
  :icon="asset('assets/images/flask-icon.svg')"
/>

<section class="avan__section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-7 col-lg-5">

        <div class="avan__card" style="padding:36px;">

          <h3 class="avan__product-title" style="margin-bottom:6px;">Create Account</h3>
          <p style="color:#9aab9a;font-size:.88rem;margin-bottom:28px;">
            Already have an account?
            <a href="{{ route('customer.login') }}" style="color:#2d7a45;font-weight:600;">Sign in</a>
          </p>

          <form action="{{ route('customer.register.post') }}" method="POST">
            @csrf

            <div class="auth__field">
              <label class="auth__label">Full Name <span class="auth__required">*</span></label>
              <input type="text" name="name"
                     class="auth__input {{ $errors->has('name') ? 'auth__input--error' : '' }}"
                     value="{{ old('name') }}" placeholder="e.g. Ramesh Patel" minlength="2" maxlength="255" required>
              @error('name')<p class="auth__error">{{ $message }}</p>@enderror
            </div>

            <div class="auth__field">
              <label class="auth__label">Email Address <span class="auth__required">*</span></label>
              <input type="email" name="email"
                     class="auth__input {{ $errors->has('email') ? 'auth__input--error' : '' }}"
                     value="{{ old('email') }}" placeholder="your@email.com" maxlength="255" required>
              @error('email')<p class="auth__error">{{ $message }}</p>@enderror
            </div>

            <div class="auth__field">
              <label class="auth__label">Phone Number</label>
              <input type="tel" name="phone"
                     class="auth__input {{ $errors->has('phone') ? 'auth__input--error' : '' }}"
                     value="{{ old('phone') }}" placeholder="e.g. 9876543210" pattern="[0-9+\-\s()]{7,20}" maxlength="20">
              @error('phone')<p class="auth__error">{{ $message }}</p>@enderror
            </div>

            <div class="auth__field">
              <label class="auth__label">Password <span class="auth__required">*</span></label>
              <input type="password" name="password"
                     class="auth__input {{ $errors->has('password') ? 'auth__input--error' : '' }}"
                     placeholder="8+ characters with letters and numbers" minlength="8" required>
              @error('password')<p class="auth__error">{{ $message }}</p>@enderror
            </div>

            <div class="auth__field">
              <label class="auth__label">Confirm Password <span class="auth__required">*</span></label>
              <input type="password" name="password_confirmation"
                     class="auth__input" placeholder="Repeat your password" required>
            </div>

            <button type="submit" class="auth__btn">Create My Account →</button>
          </form>

          <div class="auth__divider"><span>Already registered?</span></div>
          <a href="{{ route('customer.login') }}" class="auth__btn auth__btn--outline">Sign In</a>

        </div>
      </div>
    </div>
  </div>
</section>

@endsection


