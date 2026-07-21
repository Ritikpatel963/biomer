@extends('layout.frontlayout')
@section('title', 'My Account – Bharat Biomer')

@section('content')
<div class="container my-4 my-account-page">
    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-lg-3">
            @include('components.customer-sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-lg-9">
            <h1 class="ma__page-title">My Account</h1>
            <p class="ma__page-subtitle">Manage your account settings and preferences</p>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="ma__card">
                <div class="ma__header">
                    <div class="ma__title">Account Information</div>
                    <div class="ma__subtitle">Your personal details and account status</div>
                </div>

                <div class="ma__body">
                    <div class="ma__section">
                        <h3 class="ma__section-title">Profile Details</h3>
                        <div class="ma__profile-info">
                            <div class="ma__info-row">
                                <span class="ma__info-label">Full Name:</span>
                                <span class="ma__info-value">{{ $customer->name }}</span>
                            </div>
                            <div class="ma__info-row">
                                <span class="ma__info-label">Email Address:</span>
                                <span class="ma__info-value">{{ $customer->email }}</span>
                            </div>
                            <div class="ma__info-row">
                                <span class="ma__info-label">Phone Number:</span>
                                <span class="ma__info-value">{{ $customer->phone ?? 'Not provided' }}</span>
                            </div>
                            <div class="ma__info-row">
                                <span class="ma__info-label">Member Since:</span>
                                <span class="ma__info-value">{{ $customer->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="ma__info-row">
                                <span class="ma__info-label">Account Status:</span>
                                <span class="ma__info-value">
                                    <span class="ma__status-active">Active</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="ma__alert">
                        <span class="ma__alert-icon">✓</span>
                        <span class="ma__alert-text">You can now easily update your account information below.</span>
                    </div>

                    <div class="ma__actions">
                        <a href="{{ route('customer.account.edit') }}" class="ma__btn ma__btn--primary">
                            Edit Account
                        </a>
                        <a href="{{ route('customer.dashboard') }}" class="ma__btn ma__btn--outline">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
