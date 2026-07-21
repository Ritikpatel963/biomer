<div
    class="card border-0 radius-12 mb-24 wc-product-data"
    id="productVariationBuilder"
    data-product-variation-builder
    data-initial-var-index="{{ isset($product) ? $product->variations->count() : 0 }}"
>
    <div class="card-header bg-base border-bottom py-16 px-24 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0">Product Data</h5>
            <small class="text-secondary-light">Variable product attributes and variations</small>
        </div>
        <span class="badge bg-primary-50 text-primary-600">Variable product</span>
        <input type="hidden" name="is_variant_enabled" id="isVariantEnabledInput" value="1">
        <input type="hidden" name="variant_types" value="[]">
    </div>
    <div class="card-body p-0">
        <div class="wc-data-layout">
            <div class="wc-data-tabs">
                <button type="button" class="wc-data-tab active" data-wc-tab="attributesPanel">
                    <iconify-icon icon="lucide:list-tree"></iconify-icon>
                    Attributes
                </button>
                <button type="button" class="wc-data-tab" data-wc-tab="variationsPanel">
                    <iconify-icon icon="lucide:git-branch"></iconify-icon>
                    Variations
                    <span id="variationCountLabel">{{ isset($product) ? $product->variations->count() : 0 }}</span>
                </button>
            </div>

            <div class="wc-data-content">
                <div class="wc-tab-panel active" id="attributesPanel">
                    <div class="wc-panel-toolbar">
                        <div>
                            <h6 class="mb-1">Attributes</h6>
                            <small class="text-secondary-light">Choose attribute values and mark them for variations.</small>
                        </div>
                        <button type="button" class="btn btn-primary-600 btn-sm" id="goToVariationsBtn">
                            Save Attributes
                        </button>
                    </div>

                    <div class="wc-attribute-list">
                        @forelse($globalAttributes as $attribute)
                            @php
                                $savedValues = isset($product)
                                    ? optional($product->attributes->firstWhere('name', $attribute->name))->values
                                    : null;
                                $selectedValues = old("product_attribute_values.{$attribute->id}", $savedValues ?: ($attribute->values ?? []));
                            @endphp
                            <div class="wc-attribute-item attribute-card is-open" data-attribute-name="{{ $attribute->name }}">
                                <button type="button" class="wc-attribute-heading">
                                    <span>{{ $attribute->name }}</span>
                                    <small>{{ count($attribute->values ?? []) }} values</small>
                                    <iconify-icon icon="lucide:chevron-down"></iconify-icon>
                                </button>
                                <div class="wc-attribute-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Name</label>
                                            <input type="text" class="form-control" value="{{ $attribute->name }}" readonly>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label fw-bold">Value(s)</label>
                                            <div class="wc-value-box attribute-values">
                                                @foreach($attribute->values ?? [] as $value)
                                                    <label class="wc-value-pill">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input variation-value-toggle"
                                                            name="product_attribute_values[{{ $attribute->id }}][]"
                                                            value="{{ $value }}"
                                                            data-attribute-name="{{ $attribute->name }}"
                                                            {{ in_array($value, $selectedValues ?? [], true) ? 'checked' : '' }}
                                                        >
                                                        <span>{{ $value }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex flex-wrap gap-4">
                                            <input type="hidden" name="product_attributes[]" value="{{ $attribute->id }}" class="attribute-hidden-input">
                                            <label class="form-check d-flex align-items-center gap-2 p-0 mb-0">
                                                <input type="checkbox" class="form-check-input variation-attribute-toggle" value="{{ $attribute->id }}" data-attribute-name="{{ $attribute->name }}" checked>
                                                <span>Used for variations</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="wc-empty-variations">
                                Create global product attributes first from Product Attributes.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="wc-tab-panel" id="variationsPanel">
                    <div class="wc-panel-toolbar">
                        <div>
                            <h6 class="mb-1">Variations</h6>
                            <small class="text-secondary-light">Create all variations from selected attribute values.</small>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <select class="form-select form-select-sm wc-action-select" id="variationActionSelect">
                                <option value="generate">Create variations from all attributes</option>
                            </select>
                            <button type="button" class="btn btn-primary-600 btn-sm" id="generateVariationsBtn">Go</button>
                        </div>
                    </div>

                    <div class="wc-defaults-row">
                        <span>Default Form Values:</span>
                        <small class="text-secondary-light">Set a default variation inside any variation row.</small>
                    </div>

                    <div class="wc-variation-list" id="variationsTableBody">
                        <div id="emptyVariationsRow" class="wc-empty-variations {{ isset($product) && $product->variations->isNotEmpty() ? 'd-none' : '' }}">
                            No variations yet. Select attributes, then choose "Create variations from all attributes" and click Go.
                        </div>

                        @if (isset($product) && $product->variations->isNotEmpty())
                            @foreach ($product->variations as $i => $var)
                                @php
                                    $variationAttributes = $var->attributes ?: [$var->attribute_name => $var->attribute_value];
                                    $variationName = collect($variationAttributes)->map(fn($value, $name) => "{$name}: {$value}")->implode(' / ');
                                @endphp
                                <div class="variation-row is-open" id="var_row_{{ $var->id }}">
                                    <div class="wc-variation-heading">
                                        <input type="hidden" name="variations[{{ $i }}][id]" value="{{ $var->id }}">
                                        <input type="hidden" name="variations[{{ $i }}][name]" value="{{ $variationName }}">
                                        @foreach($variationAttributes as $name => $value)
                                            <input type="hidden" name="variations[{{ $i }}][attributes][{{ $name }}]" value="{{ $value }}">
                                        @endforeach
                                        <button type="button" class="wc-variation-title">
                                            <iconify-icon icon="lucide:grip-vertical"></iconify-icon>
                                            <span>#{{ $var->id }} {{ $variationName }}</span>
                                        </button>
                                        <div class="wc-variation-actions">
                                            <span class="badge {{ $var->is_active ? 'bg-success-100 text-success-600' : 'bg-neutral-200 text-secondary-light' }}">{{ $var->is_active ? 'Enabled' : 'Disabled' }}</span>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger-600 remove-variation"
                                                data-existing-variation-remove
                                                data-variation-id="{{ $var->id }}"
                                            >Remove</button>
                                            <button type="button" class="wc-row-toggle" aria-label="Toggle variation">
                                                <iconify-icon icon="lucide:chevron-down"></iconify-icon>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="wc-variation-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">SKU</label>
                                                <input type="text" name="variations[{{ $i }}][sku]" class="form-control" value="{{ $var->sku }}" placeholder="Auto generated if empty">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">MRP / Compare (INR)</label>
                                                <input type="number" step="0.01" min="0" name="variations[{{ $i }}][compare_at_price]" class="form-control" value="{{ $var->compare_at_price }}" placeholder="MRP">
                                                <input type="hidden" name="variations[{{ $i }}][cost_price]" value="{{ $var->cost_price }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">Selling Price (INR)</label>
                                                <input type="number" step="0.01" min="0" name="variations[{{ $i }}][price]" class="form-control" value="{{ $var->price }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">Stock Quantity</label>
                                                <input type="hidden" name="variations[{{ $i }}][track_stock]" value="0">
                                                <input type="hidden" name="variations[{{ $i }}][is_in_stock]" value="0">
                                                <input type="number" min="0" name="variations[{{ $i }}][stock_qty]" class="form-control" value="{{ $var->stock_quantity }}">
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label fw-bold">Variation Settings</label>
                                                <div class="wc-checkbox-grid">
                                                    <input type="hidden" name="variations[{{ $i }}][track_stock]" value="1">
                                                    <input type="hidden" name="variations[{{ $i }}][is_in_stock]" value="1">
                                                    <input type="hidden" name="variations[{{ $i }}][is_active]" value="0">
                                                    <input type="hidden" class="variation-default-hidden" name="variations[{{ $i }}][is_default]" value="{{ $var->is_default ? '1' : '0' }}">
                                                    <label>
                                                        <input class="form-check-input variation-default-radio" type="radio" name="default_variation_row" value="{{ $i }}" {{ $var->is_default ? 'checked' : '' }}>
                                                        Default variation
                                                    </label>
                                                    <label>
                                                        <input class="form-check-input" type="checkbox" name="variations[{{ $i }}][is_active]" value="1" {{ $var->is_active ? 'checked' : '' }}>
                                                        Enabled
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
