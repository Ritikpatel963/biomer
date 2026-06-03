@extends('layout.layout')

@php
    $title = 'Google Analytics';
    $subTitle = 'Manage tracking for your website';
@endphp

@section('content')

<div class="admin-shell">
    <div class="admin-page-card">
        <div class="admin-page-card__header">
            <div>
                <span class="admin-page-card__eyebrow">Tracking Setup</span>
                <h2 class="admin-page-card__title">Google Analytics</h2>
                <p class="admin-page-card__desc">Add or update your Google Analytics measurement ID. It is loaded automatically on the frontend when this field is filled.</p>
            </div>
            <div class="admin-page-card__actions">
                <a href="{{ route('dashboard.homepage-editor.edit') }}" class="btn btn-outline-secondary">Homepage Editor</a>
                <a href="{{ route('dashboard.site-settings.edit') }}" class="btn btn-outline-secondary">Site Settings</a>
            </div>
        </div>

        <div class="admin-toolbar-tabs">
            <a href="{{ route('dashboard.homepage-editor.edit') }}" class="admin-toolbar-tabs__link">Homepage Editor</a>
            <a href="{{ route('dashboard.site-settings.edit') }}" class="admin-toolbar-tabs__link">Site Settings</a>
            <a href="{{ route('dashboard.google-analytics.edit') }}" class="admin-toolbar-tabs__link active">Google Analytics</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mt-4">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-4">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('dashboard.google-analytics.update') }}" method="POST">
            @csrf

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <h5 class="card-title mb-0">Measurement ID</h5>
                </div>
                <div class="admin-section-card__body">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-8">
                            <label class="form-label fw-semibold">Google Analytics ID</label>
                            <input type="text" name="google_analytics_id" class="form-control @error('google_analytics_id') is-invalid @enderror"
                                value="{{ old('google_analytics_id', $settings->google_analytics_id ?? '') }}" placeholder="G-XXXXXXXXXX">
                            <small class="text-muted d-block mt-1">Example: <code>G-XXXXXXXXXX</code></small>
                            @error('google_analytics_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-lg-4">
                            <div class="admin-info-chip">
                                <span class="admin-info-chip__label">Current Status</span>
                                <strong>{{ !empty($settings->google_analytics_id) ? 'Connected' : 'Not Connected' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center gap-3 mt-4 flex-wrap">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Back</a>
                <button type="submit" class="btn btn-primary">
                    
                    Save Analytics
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
