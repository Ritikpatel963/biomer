{{-- resources/views/dashboard/variations/create.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Add Variation';
    $subTitle = 'Add Variation';
@endphp

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9 col-12">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h6 class="fw-bold mb-0">Add Variation</h6>
                <small class="text-muted">Product: <strong>{{ $product->name }}</strong></small>
            </div>
            <a href="{{ route('dashboard.products.variations.index', $product) }}"
               class="btn btn-outline-secondary btn-sm">
                Back
            </a>
        </div>

        {{-- Error Alert --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Card --}}
        <div class="card basic-data-table">
            <div class="card-header">
                <h5 class="card-title mb-0">Variation Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.products.variations.store', $product) }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        @if($globalAttributes->count())
                            <div class="col-12">
                                <div class="border radius-8 p-3">
                                    <label class="form-label fw-semibold">Use Global Attribute</label>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <select id="globalAttributeSelect" class="form-select">
                                                <option value="">Choose attribute</option>
                                                @foreach($globalAttributes as $attribute)
                                                    <option value="{{ $attribute->name }}" data-values="{{ e(json_encode($attribute->values ?? [])) }}">{{ $attribute->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <select id="globalAttributeValueSelect" class="form-select">
                                                <option value="">Choose value</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Attribute Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Attribute Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="attribute_name" id="attributeNameInput"
                                   class="form-control @error('attribute_name') is-invalid @enderror"
                                   value="{{ old('attribute_name', 'Pack') }}"
                                   placeholder="e.g. Pack, Size, Weight" required>
                            @error('attribute_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Attribute Value --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Attribute Value <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="attribute_value" id="attributeValueInput"
                                   class="form-control @error('attribute_value') is-invalid @enderror"
                                   value="{{ old('attribute_value') }}"
                                   placeholder="e.g. 5 KG, 10 KG, 20 KG" required>
                            @error('attribute_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SKU --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                SKU <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="sku"
                                   class="form-control @error('sku') is-invalid @enderror"
                                   value="{{ old('sku') }}"
                                   placeholder="e.g. PROD-5KG" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Price --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input type="number" name="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}"
                                       min="0" step="0.01" placeholder="500.00" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Weight --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Weight</label>
                            <div class="input-group">
                                <input type="number" name="weight"
                                       class="form-control @error('weight') is-invalid @enderror"
                                       value="{{ old('weight') }}"
                                       min="0" step="0.01" placeholder="0.00">
                                <span class="input-group-text">kg</span>
                            </div>
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Stock Quantity --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Stock Quantity <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="stock_quantity"
                                   class="form-control @error('stock_quantity') is-invalid @enderror"
                                   value="{{ old('stock_quantity', 0) }}"
                                   min="0" required>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Image --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Variation Image</label>
                            <input type="file" name="image"
                                   class="form-control @error('image') is-invalid @enderror"
                                   accept="image/*"
                                   onchange="previewImg(this)">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="imgPreview" src="" alt=""
                                 class="radius-8 border mt-2 d-none"
                                 style="width:80px;height:80px;object-fit:cover;">
                        </div>

                        {{-- Active Toggle --}}
                        <div class="col-12">
                            <x-toggle-switch name="is_active" id="isActive" :checked="old('is_active', true)" label="Active (visible to customers)" />
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault" {{ old('is_default') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="isDefault">Set as default variation</label>
                            </div>
                            <small class="text-secondary-light">Only one variation can be default for this product.</small>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit"
                                class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                            
                            Save Variation
                        </button>
                        <a href="{{ route('dashboard.products.variations.index', $product) }}"
                           class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    const globalAttributeSelect = document.getElementById('globalAttributeSelect');
    const globalAttributeValueSelect = document.getElementById('globalAttributeValueSelect');
    const attributeNameInput = document.getElementById('attributeNameInput');
    const attributeValueInput = document.getElementById('attributeValueInput');

    globalAttributeSelect?.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const values = JSON.parse(selected.dataset.values || '[]');
        attributeNameInput.value = this.value || attributeNameInput.value;
        globalAttributeValueSelect.innerHTML = '<option value="">Choose value</option>';
        values.forEach(value => {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = value;
            globalAttributeValueSelect.appendChild(option);
        });
    });

    globalAttributeValueSelect?.addEventListener('change', function () {
        attributeValueInput.value = this.value || attributeValueInput.value;
    });

    function previewImg(input) {
        const prev = document.getElementById('imgPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                prev.src = e.target.result;
                prev.classList.remove('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
