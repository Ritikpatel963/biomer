@extends('layout.layout')

@php
    $title = 'Homepage Editor';
    $subTitle = 'Manage homepage banner and content blocks';
@endphp

@section('content')

<div class="admin-shell">
    <div class="admin-page-card">
        <div class="admin-page-card__header">
            <div>
                <span class="admin-page-card__eyebrow">Homepage Control</span>
                <p class="admin-page-card__desc">Manage the homepage banner slider, section content, and Instagram video reviews from one full-width editor.</p>
            </div>
            <div class="admin-page-card__actions">
                <a href="{{ route('dashboard.site-settings.edit') }}" class="btn btn-outline-secondary">Site Settings</a>
                <a href="{{ route('dashboard.google-analytics.edit') }}" class="btn btn-outline-secondary">Google Analytics</a>
            </div>
        </div>

        <div class="admin-toolbar-tabs">
            <a href="{{ route('dashboard.homepage-editor.edit') }}" class="admin-toolbar-tabs__link active">Homepage Editor</a>
            <a href="{{ route('dashboard.site-settings.edit') }}" class="admin-toolbar-tabs__link">Site Settings</a>
            <a href="{{ route('dashboard.google-analytics.edit') }}" class="admin-toolbar-tabs__link">Google Analytics</a>
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

        <form action="{{ route('dashboard.homepage-editor.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('dashboard.settings.partials.homepage-editor-fields', ['homepageConfig' => $homepageConfig])

            <div class="d-flex justify-content-between align-items-center gap-3 mt-4 flex-wrap">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Back</a>
                <button type="submit" class="btn btn-primary">
                    
                    Save Homepage
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
