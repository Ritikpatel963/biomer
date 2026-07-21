@extends('layout.layout')

@php
    $title = 'Create Page';
@endphp



@section('content')
    <div class="container-fluid py-4 page-create-page">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following errors:</strong>
                <ul class="page-create-error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('dashboard.pages.store') }}" method="POST" class="form-card">
            @csrf

            <!-- Basic Information -->
            <div class="form-section">
                <h5 class="form-section-title">📖 Page Information</h5>

                <div class="form-group">
                    <label for="title">Page Title <span class="page-create-required">*</span></label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}" placeholder="E.g., About Us, Contact, Our Technology" required
                        data-page-create-title>
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <small>This is the main title of your page. Must be unique.</small>
                </div>

                <div class="form-group">
                    <label for="slug">URL Slug <span class="page-create-required">*</span></label>
                    <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror"
                        value="{{ old('slug') }}" placeholder="e.g., about-us, contact-us" required
                        data-page-create-slug>
                    @error('slug')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <small>URL-friendly version of the title. Use hyphens for spaces.</small>
                </div>

                <div class="form-group">
                    <label for="content">Page Content <span class="page-create-required">*</span></label>
                    <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror"
                        placeholder="Write your page content here..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <small>Main content of the page (HTML supported)</small>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="status" name="status" class="form-check-input" value="1"
                        {{ old('status') ? 'checked' : '' }}>
                    <label for="status" class="page-create-status-label">Publish this page</label>
                </div>
                <small class="page-create-draft-note">Uncheck to save as draft</small>
            </div>

            <!-- SEO Settings -->
            <div class="form-section">
                <h5 class="form-section-title"> SEO Settings</h5>

                <div class="seo-note">
                    <strong>📌 SEO Tips:</strong> These fields help search engines understand your page and improve how it appears
                    in search results.
                </div>

                <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror"
                        value="{{ old('meta_title') }}" placeholder="E.g., About Our Biotech Solutions | Bharat Biomer" maxlength="255"
                        data-page-create-counter-input data-counter-target="meta_title_count" data-counter-max="255">
                    <div class="char-count" id="meta_title_count" data-page-create-counter>0 / 255</div>
                    @error('meta_title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <small>Appears in browser tabs and search results (50-60 chars ideal)</small>
                </div>

                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" class="form-control page-create-seo-textarea @error('meta_description') is-invalid @enderror"
                        placeholder="Summarize your page content (150-160 characters)" maxlength="500"
                        data-page-create-counter-input data-counter-target="meta_description_count" data-counter-max="500">{{ old('meta_description') }}</textarea>
                    <div class="char-count" id="meta_description_count" data-page-create-counter>0 / 500</div>
                    @error('meta_description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <small>Shows below page title in search results (150-160 chars ideal)</small>
                </div>

                <div class="form-group">
                    <label for="meta_keyword">Meta Keywords</label>
                    <textarea id="meta_keyword" name="meta_keyword" class="form-control page-create-seo-textarea @error('meta_keyword') is-invalid @enderror"
                        placeholder="comma-separated keywords" maxlength="500"
                        data-page-create-counter-input data-counter-target="meta_keyword_count" data-counter-max="500">{{ old('meta_keyword') }}</textarea>
                    <div class="char-count" id="meta_keyword_count" data-page-create-counter>0 / 500</div>
                    @error('meta_keyword')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <small>Keywords related to your page content (separate with commas)</small>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Create Page</button>
                <a href="{{ route('dashboard.pages.index') }}" class="btn btn-secondary">✕ Cancel</a>
            </div>
        </form>

    </div>
@endsection
