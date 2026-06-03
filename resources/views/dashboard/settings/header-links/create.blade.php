@extends('layout.layout')

@php
    $title    = 'Add Header Link';
    $subTitle = 'Create a new navigation menu item';
@endphp

@section('content')

  {{-- Breadcrumb --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h6 class="mb-0">+ Add Header Link</h6>
      <small class="text-muted">Create a new navigation menu item</small>
    </div>
    <a href="{{ route('dashboard.header-links.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card">
    <div class="card-body p-4">
      <form action="{{ route('dashboard.header-links.store') }}" method="POST">
        @csrf

        <div class="row g-3 mb-3">

          <div class="col-md-6">
            <label class="form-label fw-semibold">Label <span class="text-danger">*</span></label>
            <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
                   value="{{ old('label') }}" placeholder="e.g., Home, About, Products" required>
            @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">URL <span class="text-danger">*</span></label>
            <input type="text" name="url" class="form-control @error('url') is-invalid @enderror"
                   value="{{ old('url') }}" placeholder="e.g., /, /about, /products" required>
            @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Icon</label>
            <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
                   value="{{ old('icon') }}" placeholder="e.g., lucide:home, ri-home-line">
            @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Position <span class="text-danger">*</span></label>
            <input type="number" name="position" class="form-control @error('position') is-invalid @enderror"
                   value="{{ old('position', 0) }}" required>
            @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <small class="text-muted d-block mt-1">Lower numbers appear first</small>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Open Link</label>
            <select name="target" class="form-select @error('target') is-invalid @enderror">
              <option value="_self" {{ old('target') == '_self' ? 'selected' : '' }}>In same tab</option>
              <option value="_blank" {{ old('target') == '_blank' ? 'selected' : '' }}>In new tab</option>
            </select>
            @error('target')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Status</label>
            <div class="form-check form-switch mt-2">
              <input type="hidden" name="is_active" value="0">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                {{ old('is_active', true) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Active</label>
            </div>
          </div>

        </div>

        <div class="d-flex gap-2 justify-content-between">
          <a href="{{ route('dashboard.header-links.index') }}" class="btn btn-outline-secondary">Cancel</a>
          <button type="submit" class="btn btn-primary">
             Create Link
          </button>
        </div>

      </form>
    </div>
  </div>

@endsection

