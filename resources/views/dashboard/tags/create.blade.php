{{-- resources/views/dashboard/tags/create.blade.php --}}
@extends('layout.layout')

@section('title', 'Add Tag')

@section('content')
<div class="container-fluid py-4">
  <div class="row justify-content-center">
    <div class="col-lg-5">

      <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('dashboard.tags.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
        <h2 class="mb-0 fw-bold">Add New Tag</h2>
      </div>

      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <form action="{{ route('dashboard.tags.store') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label class="form-label fw-semibold">Tag Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" placeholder="e.g. bhoomi star benefits" minlength="2" maxlength="100" required
                data-tag-create-name>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              <small class="text-muted">Preview: <span id="tagPreview" data-tag-create-preview style="background:#e8f5ed;color:#2d7a45;border:1px solid #a8d5b5;border-radius:20px;padding:.1rem .6rem;font-size:.8rem;"></span></small>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold">Slug
                <small class="text-muted fw-normal">(auto-generated)</small>
              </label>
              <input type="text" name="slug" id="slugField" class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug') }}" placeholder="e.g. bhoomi-star-benefits" maxlength="100" pattern="[A-Za-z0-9_-]+"
                data-tag-create-slug>
              @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-success btn-lg">💾 Save Tag</button>
            </div>
          </form>
        </div>
      </div>

      {{-- Bulk add section --}}
      <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-light fw-semibold"> Bulk Add Tags</div>
        <div class="card-body">
          <form action="{{ route('dashboard.tags.bulkStore') }}" method="POST">
            @csrf
            <label class="form-label">Enter tags (one per line or comma-separated)</label>
            <textarea name="bulk_tags" class="form-control" rows="5" maxlength="3000" required
              placeholder="agrochemical online&#10;best farming product&#10;bhoomi star, organic farming"></textarea>
            <button type="submit" class="btn btn-outline-success mt-2 w-100">Add All Tags</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
