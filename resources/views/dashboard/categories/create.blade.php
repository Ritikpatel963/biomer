{{-- resources/views/dashboard/categories/create.blade.php --}}
@extends('layout.layout')

@section('title', 'Add Category')

@section('content')
<div class="container-fluid py-4">
  <div class="row justify-content-center">
    <div class="col-lg-6">

      <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('dashboard.categories.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
        <h2 class="mb-0 fw-bold">📂 Add New Category</h2>
      </div>

      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <form action="{{ route('dashboard.categories.store') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" placeholder="e.g. Fertilizer" required
                data-category-slug-source>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Slug
                <small class="text-muted fw-normal">(auto-generated)</small>
              </label>
              <input type="text" name="slug" id="slugField" class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug') }}" placeholder="e.g. fertilizer">
              @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold">Parent Category
                <small class="text-muted fw-normal">(optional)</small>
              </label>
              <select name="parent_id" class="form-select">
                <option value="">— None (Top Level) —</option>
                @foreach($parents as $parent)
                  <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                    {{ $parent->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-success btn-lg">💾 Save Category</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
