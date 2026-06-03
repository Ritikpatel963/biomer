@extends('layout.layout')

@php
    $title = 'Edit Brand';
    $subTitle = 'Brands';
@endphp

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-1">Edit Brand</h5>
            <p class="text-secondary-light mb-0">Update brand details for {{ $brand->name }}.</p>
        </div>
        <a href="{{ route('dashboard.brands.index') }}" class="btn btn-outline-secondary btn-sm">Back to Brands</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3 mb-0">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body">
        <form action="{{ route('dashboard.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Brand Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $brand->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Slug</label>
                    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                        value="{{ old('slug', $brand->slug) }}">
                    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Brand Logo</label>
                    @if($brand->logo)
                        <div class="mb-2">
                            <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}" class="radius-8 border" style="width:100px;height:100px;object-fit:contain;padding:4px;">
                            <div class="text-muted small mt-1">Current logo. Upload a new one to replace it.</div>
                        </div>
                    @endif
                    <input type="file" name="logo" class="form-control" accept="image/*" onchange="previewLogo(this)">
                    <img id="logoPreview" src="" alt="" class="radius-8 border mt-2" style="display:none;width:100px;height:100px;object-fit:contain;padding:4px;">
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('dashboard.brands.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Brand</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewLogo(input) {
    const preview = document.getElementById('logoPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = event => {
            preview.src = event.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
