@extends('layout.layout')

@php
    $title = 'Edit Footer Link';
    $subTitle = 'Footer Links';
@endphp

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-1">Edit Footer Link</h5>
            <p class="text-secondary-light mb-0">Update footer navigation item: {{ $footerLink->label }}.</p>
        </div>
        <a href="{{ route('dashboard.footer-links.index') }}" class="btn btn-outline-secondary btn-sm">Back to Footer Links</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3 mb-0">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body">
        <form action="{{ route('dashboard.footer-links.update', $footerLink) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Section <span class="text-danger">*</span></label>
                    <input type="text" name="section" class="form-control @error('section') is-invalid @enderror"
                        value="{{ old('section', $footerLink->section) }}" placeholder="e.g., Products, Company, Contact" required>
                    @error('section')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted d-block mt-1">Group links by section in footer.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Position <span class="text-danger">*</span></label>
                    <input type="number" name="position" class="form-control @error('position') is-invalid @enderror"
                        value="{{ old('position', $footerLink->position) }}" required>
                    @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted d-block mt-1">Lower numbers appear first within section.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Label <span class="text-danger">*</span></label>
                    <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
                        value="{{ old('label', $footerLink->label) }}" placeholder="e.g., About Us, Privacy Policy" required>
                    @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">URL <span class="text-danger">*</span></label>
                    <input type="text" name="url" class="form-control @error('url') is-invalid @enderror"
                        value="{{ old('url', $footerLink->url) }}" placeholder="e.g., /, /about, /privacy-policy" required>
                    @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Open Link</label>
                    <select name="target" class="form-select @error('target') is-invalid @enderror">
                        <option value="_self" {{ old('target', $footerLink->target) == '_self' ? 'selected' : '' }}>In same tab</option>
                        <option value="_blank" {{ old('target', $footerLink->target) == '_blank' ? 'selected' : '' }}>In new tab</option>
                    </select>
                    @error('target')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $footerLink->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('dashboard.footer-links.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Link</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
