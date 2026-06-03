@extends('layout.layout')

@php
    $title = 'Edit Tag';
    $subTitle = 'Tags';
@endphp

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-1">Edit Tag</h5>
            <p class="text-secondary-light mb-0">Update tag details for {{ $tag->name }}.</p>
        </div>
        <a href="{{ route('dashboard.tags.index') }}" class="btn btn-outline-secondary btn-sm">Back to Tags</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3 mb-0">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body">
        <form action="{{ route('dashboard.tags.update', $tag) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tag Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $tag->name) }}" placeholder="e.g. Cotton, Summer, Sale" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Slug</label>
                    <input type="text" name="slug" id="slugInput" class="form-control @error('slug') is-invalid @enderror"
                        value="{{ old('slug', $tag->slug) }}" placeholder="e.g. cotton-summer-sale">
                    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Auto-generated from name. Lowercase letters, numbers and hyphens only.</small>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('dashboard.tags.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Tag</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.querySelector('input[name="name"]');
    const slugInput = document.getElementById('slugInput');

    nameInput.addEventListener('input', function () {
        if (!slugInput.dataset.manual) {
            slugInput.value = this.value.toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-');
        }
    });

    slugInput.addEventListener('input', function () {
        this.dataset.manual = 'true';
    });
});
</script>
@endpush
