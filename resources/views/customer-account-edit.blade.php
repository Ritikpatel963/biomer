@extends('layout.frontlayout')
@section('title', 'Edit Account - Bharat Biomer')

@section('content')
<div class="container my-4">
    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-lg-3">
            @include('components.customer-sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-lg-6">
            <h1 class="cae__page-title">Edit Account</h1>
            <p class="cae__page-subtitle">Update your account information</p>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-3">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('customer.account.update') }}" method="POST" class="cae__form">
                @csrf

                <div class="cae__card">
                    <div class="cae__header">
                        <div class="cae__title">Personal Information</div>
                        <div class="cae__subtitle">Update your personal details below</div>
                    </div>

                    <div class="cae__body">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $customer->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $customer->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted cae__help-text">We'll never share your email.</small>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', $customer->phone) }}"
                                   placeholder="e.g., +91 98765 43210">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="cae__header px-0 pt-0">
                            <div class="cae__title">Change Password</div>
                            <div class="cae__subtitle">Leave these fields blank to keep your current password</div>
                        </div>

                        <div class="form-group">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   id="current_password"
                                   name="current_password"
                                   autocomplete="current-password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password"
                                   class="form-control @error('new_password') is-invalid @enderror"
                                   id="new_password"
                                   name="new_password"
                                   autocomplete="new-password">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted cae__help-text">Use at least 8 characters with one letter and one number.</small>
                        </div>

                        <div class="form-group">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password"
                                   class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                   id="new_password_confirmation"
                                   name="new_password_confirmation"
                                   autocomplete="new-password">
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="cae__actions">
                            <a href="{{ route('customer.account') }}" class="cae__btn cae__btn--outline">Cancel</a>
                            <button type="submit" class="cae__btn cae__btn--primary">Save Changes</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
