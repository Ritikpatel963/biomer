@extends('layout.layout')

@php
    $title = 'Create Page';
@endphp

@push('styles')
    <style>
        :root {
            --green: #2d7a45;
            --green-light: #e8f5ed;
            --border: #dee2e6;
            --radius: 10px;
        }

        .form-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
        }

        .form-section {
            padding: 2rem;
            border-bottom: 1px solid var(--border);
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .form-section-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--green);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: .5rem;
            display: block;
            font-size: .95rem;
        }

        .form-group small {
            color: #999;
            display: block;
            margin-top: .3rem;
            font-size: .85rem;
        }

        .form-control, .form-select {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: .75rem 1rem;
            font-size: .95rem;
            transition: border-color .2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 .2rem rgba(45, 122, 69, .15);
            outline: none;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 150px;
        }

        .char-count {
            text-align: right;
            font-size: .85rem;
            color: #999;
            margin-top: .3rem;
        }

        .char-count.warning {
            color: #ff6b6b;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            padding: 2rem;
            background: #f9f9f9;
        }

        .btn {
            padding: .75rem 2rem;
            border: none;
            border-radius: 7px;
            font-weight: 600;
            font-size: .95rem;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-primary {
            background: var(--green);
            color: #fff;
            transition: all .2s;
        }

        .btn-primary:hover {
            background: #1a5c30;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(45, 122, 69, .3);
        }

        .btn-secondary {
            background: #e9ecef;
            color: #333;
            transition: all .2s;
        }

        .btn-secondary:hover {
            background: #dee2e6;
            transform: translateY(-2px);
        }

        .error-message {
            color: #d32f2f;
            font-size: .85rem;
            margin-top: .3rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
        }

        .seo-note {
            background: var(--green-light);
            border-left: 4px solid var(--green);
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            font-size: .9rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following errors:</strong>
                <ul style="margin: .5rem 0 0; padding-left: 1.5rem;">
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
                    <label for="title">Page Title <span style="color: #d32f2f;">*</span></label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}" placeholder="E.g., About Us, Contact, Our Technology" required>
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <small>This is the main title of your page. Must be unique.</small>
                </div>

                <div class="form-group">
                    <label for="slug">URL Slug <span style="color: #d32f2f;">*</span></label>
                    <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror"
                        value="{{ old('slug') }}" placeholder="e.g., about-us, contact-us" required>
                    @error('slug')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <small>URL-friendly version of the title. Use hyphens for spaces.</small>
                </div>

                <div class="form-group">
                    <label for="content">Page Content <span style="color: #d32f2f;">*</span></label>
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
                    <label for="status" style="margin: 0; font-weight: 600; cursor: pointer;">Publish this page</label>
                </div>
                <small style="display: block; margin-top: .5rem;">Uncheck to save as draft</small>
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
                        value="{{ old('meta_title') }}" placeholder="E.g., About Our Biotech Solutions | Bharat Biomer" maxlength="255">
                    <div class="char-count" id="meta_title_count">0 / 255</div>
                    @error('meta_title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <small>Appears in browser tabs and search results (50-60 chars ideal)</small>
                </div>

                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" class="form-control @error('meta_description') is-invalid @enderror"
                        placeholder="Summarize your page content (150-160 characters)" style="min-height: 80px; max-height: 120px;" maxlength="500">{{ old('meta_description') }}</textarea>
                    <div class="char-count" id="meta_description_count">0 / 500</div>
                    @error('meta_description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <small>Shows below page title in search results (150-160 chars ideal)</small>
                </div>

                <div class="form-group">
                    <label for="meta_keyword">Meta Keywords</label>
                    <textarea id="meta_keyword" name="meta_keyword" class="form-control @error('meta_keyword') is-invalid @enderror"
                        placeholder="comma-separated keywords" style="min-height: 80px; max-height: 120px;" maxlength="500">{{ old('meta_keyword') }}</textarea>
                    <div class="char-count" id="meta_keyword_count">0 / 500</div>
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

    <script>
        // Character counters
        document.getElementById('meta_title').addEventListener('input', function() {
            const count = this.value.length;
            const counter = document.getElementById('meta_title_count');
            counter.textContent = count + ' / 255';
            counter.classList.toggle('warning', count > 255);
        });

        document.getElementById('meta_description').addEventListener('input', function() {
            const count = this.value.length;
            const counter = document.getElementById('meta_description_count');
            counter.textContent = count + ' / 500';
            counter.classList.toggle('warning', count > 500);
        });

        document.getElementById('meta_keyword').addEventListener('input', function() {
            const count = this.value.length;
            const counter = document.getElementById('meta_keyword_count');
            counter.textContent = count + ' / 500';
            counter.classList.toggle('warning', count > 500);
        });

        // Auto-generate slug from title
        document.getElementById('title').addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-+|-+$/g, '');
            document.getElementById('slug').value = slug;
        });

        // Initialize counters on page load
        window.addEventListener('load', function() {
            document.getElementById('meta_title').dispatchEvent(new Event('input'));
            document.getElementById('meta_description').dispatchEvent(new Event('input'));
            document.getElementById('meta_keyword').dispatchEvent(new Event('input'));
        });
    </script>
@endsection
