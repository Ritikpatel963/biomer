@extends('layout.layout')

@php
    $title = 'Edit Page';
    $subTitle = 'Pages';
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="mb-1">Edit Page</h5>
        <small class="text-muted">{{ $page->title }} / {{ $page->slug }}</small>
    </div>
    <a href="{{ route('dashboard.pages.index') }}" class="btn btn-outline-secondary btn-sm">Back to Pages</a>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('dashboard.pages.update', $page) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card basic-data-table mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Page Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Page Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $page->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">URL Slug</label>
                            <input type="text" disabled class="form-control" value="{{ $page->slug }}">
                            <small class="text-muted d-block mt-1">Page URL: <code>{{ config('app.url') }}/{{ $page->slug }}</code></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card basic-data-table mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">SEO Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Meta Title <span class="text-muted">(50-60 chars)</span></label>
                            <input type="text" name="meta_title" id="meta_title" maxlength="255"
                                class="form-control @error('meta_title') is-invalid @enderror"
                                value="{{ old('meta_title', $page->meta_title) }}" placeholder="E.g., About Our Solutions | Bharat Biomer"
                                data-page-edit-counter-input data-counter-target="meta_title_count">
                            <small class="text-muted d-block mt-1"><span id="meta_title_count" data-page-edit-counter>0</span>/255 chars</small>
                            @error('meta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Meta Description <span class="text-muted">(150-160 chars)</span></label>
                            <textarea name="meta_description" id="meta_description" rows="3" maxlength="500"
                                class="form-control @error('meta_description') is-invalid @enderror"
                                placeholder="Summary for search results..."
                                data-page-edit-counter-input data-counter-target="meta_description_count">{{ old('meta_description', $page->meta_description) }}</textarea>
                            <small class="text-muted d-block mt-1"><span id="meta_description_count" data-page-edit-counter>0</span>/500 chars</small>
                            @error('meta_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Meta Keywords</label>
                            <textarea name="meta_keyword" id="meta_keyword" rows="3" maxlength="500"
                                class="form-control @error('meta_keyword') is-invalid @enderror"
                                placeholder="comma-separated keywords..."
                                data-page-edit-counter-input data-counter-target="meta_keyword_count">{{ old('meta_keyword', $page->meta_keyword) }}</textarea>
                            <small class="text-muted d-block mt-1"><span id="meta_keyword_count" data-page-edit-counter>0</span>/500 chars</small>
                            @error('meta_keyword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card basic-data-table mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Settings</h5>
                </div>
                <div class="card-body">
                    <input type="hidden" name="status" value="0">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                            {{ old('status', $page->status) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Publish this page</label>
                    </div>
                    <small class="text-muted d-block mt-2">{{ old('status', $page->status) ? 'Published' : 'Draft (not visible)' }}</small>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('dashboard.pages.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
