@extends('layout.layout')

@php
    $title = 'Variations';
    $subTitle = $product->name;
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('dashboard.products.index') }}" class="text-secondary-light text-decoration-none text-sm">Back to Products</a>
        <h5 class="fw-bold mb-1 mt-1">{{ $product->name }}</h5>
        <small class="text-secondary-light">SKU: <code>{{ $product->sku }}</code> | Base Price: Rs. {{ number_format($product->base_price, 2) }}</small>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('dashboard.attributes.index') }}" class="btn btn-outline-secondary btn-sm">Manage Attributes</a>
        <a href="{{ route('dashboard.products.variations.create', $product) }}" class="btn btn-primary btn-sm">Add Manual Variation</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-1">Create Variations From Attribute</h5>
        <p class="text-secondary-light mb-0">Global attributes are created once and available on every product.</p>
    </div>
    <div class="card-body">
        @if($globalAttributes->isEmpty())
            <div class="border radius-8 p-4 text-center">
                <h6 class="fw-semibold mb-1">No global attributes yet</h6>
                <p class="text-secondary-light mb-3">Create attributes like Pack, Size, or Color before generating product variations.</p>
                <a href="{{ route('dashboard.attributes.index') }}" class="btn btn-primary btn-sm">Create Attribute</a>
            </div>
        @else
            <form action="{{ route('dashboard.products.variations.attributes.store', $product) }}" method="POST" id="attributeVariationForm">
                @csrf
                <div class="row g-3">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label fw-semibold">Attribute</label>
                        <select name="global_attribute_id" id="globalAttributeSelect" class="form-select" required>
                            @foreach($globalAttributes as $attribute)
                                <option value="{{ $attribute->id }}" data-values="{{ e(json_encode($attribute->values ?? [])) }}" {{ old('global_attribute_id') == $attribute->id ? 'selected' : '' }}>
                                    {{ $attribute->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label fw-semibold">Default Value</label>
                        <input type="text" name="default_value" id="defaultValueInput" class="form-control" value="{{ old('default_value') }}" placeholder="Optional">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label fw-semibold">Price</label>
                        <input type="number" name="base_price" class="form-control" value="{{ old('base_price', $product->base_price) }}" min="0" step="0.01">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label fw-semibold">Stock</label>
                        <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', 0) }}" min="0">
                    </div>
                    <div class="col-lg-2 col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Generate</button>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Values</label>
                        <div id="attributeValueChoices" class="d-flex flex-wrap gap-2"></div>
                        <small class="text-secondary-light d-block mt-2">Checked values will become product variations. Leave all unchecked to use every value.</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Extra Values For This Product</label>
                        <input type="text" name="custom_values" class="form-control" value="{{ old('custom_values') }}" placeholder="Optional: 25 KG, Trial Pack">
                    </div>
                </div>
            </form>
        @endif

        @if($product->attributes->count())
            <div class="mt-4 pt-3 border-top">
                <p class="text-secondary-light mb-2">Attributes used on this product</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($product->attributes as $attribute)
                        <span class="bg-primary-light text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm">
                            {{ $attribute->name }}: {{ implode(', ', $attribute->values ?? []) }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<div class="card basic-data-table">
    <div class="card-header">
        <h5 class="card-title mb-0">Product Variations</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table admin-data-table mb-0" data-page-length="10" data-no-sort-targets="0,1,8">
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Image</th>
                        <th>SKU</th>
                        <th>Attribute</th>
                        <th>Value</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($variations as $i => $var)
                        <tr>
                            <td>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                @if($var->image_path)
                                    <img src="{{ Storage::url($var->image_path) }}" class="radius-8 border" style="width:44px;height:44px;object-fit:cover;">
                                @else
                                    <div class="radius-8 bg-neutral-200 border" style="width:44px;height:44px;"></div>
                                @endif
                            </td>
                            <td><code>{{ $var->sku }}</code></td>
                            <td class="fw-semibold">{{ $var->attribute_name }}</td>
                            <td>
                                <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">{{ $var->attribute_value }}</span>
                                @if($var->is_default)
                                    <span class="bg-primary-light text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm ms-1">Default</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold">Rs. {{ number_format($var->price, 2) }}</span>
                                @if($var->weight)
                                    <div class="text-sm text-secondary-light">{{ $var->weight }} {{ $var->unit ?: 'kg' }}</div>
                                @endif
                            </td>
                            <td>
                                @if($var->stock_quantity > 10)
                                    <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">{{ $var->stock_quantity }}</span>
                                @elseif($var->stock_quantity > 0)
                                    <span class="bg-warning-focus text-warning-main px-12 py-4 rounded-pill fw-medium text-sm">{{ $var->stock_quantity }}</span>
                                @else
                                    <span class="bg-danger-focus text-danger-main px-12 py-4 rounded-pill fw-medium text-sm">Out</span>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $var->is_active ? 'text-success-main' : 'text-secondary-light' }} fw-semibold">{{ $var->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('dashboard.products.variations.edit', [$product, $var]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('dashboard.products.variations.destroy', [$product, $var]) }}" method="POST" onsubmit="return confirm('Delete this variation?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                No variations yet. Create an attribute above or <a href="{{ route('dashboard.products.variations.create', $product) }}">add one manually.</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const attributeSelect = document.getElementById('globalAttributeSelect');
    const choicesWrap = document.getElementById('attributeValueChoices');
    const defaultValueInput = document.getElementById('defaultValueInput');

    function renderAttributeValues() {
        if (!attributeSelect || !choicesWrap) return;

        const option = attributeSelect.options[attributeSelect.selectedIndex];
        const values = JSON.parse(option.dataset.values || '[]');
        choicesWrap.innerHTML = '';

        if (!values.length) {
            choicesWrap.innerHTML = '<span class="text-secondary-light text-sm">No values found for this attribute.</span>';
            return;
        }

        values.forEach((value, index) => {
            const id = 'attributeValue' + index;
            const label = document.createElement('label');
            label.className = 'border radius-8 px-12 py-8 d-flex align-items-center gap-2 mb-0';
            label.setAttribute('for', id);
            label.innerHTML =
                '<input class="form-check-input mt-0" type="checkbox" name="selected_values[]" value="' + String(value).replace(/"/g, '&quot;') + '" id="' + id + '">' +
                '<span class="fw-medium text-sm">' + value + '</span>';
            choicesWrap.appendChild(label);
        });

        if (!defaultValueInput.value && values[0]) {
            defaultValueInput.value = values[0];
        }
    }

    attributeSelect?.addEventListener('change', function () {
        defaultValueInput.value = '';
        renderAttributeValues();
    });
    renderAttributeValues();
</script>
@endpush
