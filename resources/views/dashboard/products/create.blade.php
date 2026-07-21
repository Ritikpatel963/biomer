@extends('layout.layout')

@php
    $title = isset($product) ? 'Edit Product' : 'Product Add';
    $subTitle = 'Products';
@endphp

@section('content')
    <div class="product-editor">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form
            action="{{ isset($product) ? route('dashboard.products.update', $product) : route('dashboard.products.store') }}"
            method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif

            <div class="row">

                {{-- LEFT COLUMN --}}
                <div class="col-lg-8 product-editor__main">

                    {{-- Basic Info --}}
                    <div class="card mb-4">
                        <div class="card-header">Basic Information</div>
                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-8">
                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $product->name ?? '') }}"
                                        placeholder="e.g. Bhoomi Star" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">SKU</label>
                                    <input type="text" name="sku"
                                        class="form-control @error('sku') is-invalid @enderror"
                                        value="{{ old('sku', $product->sku ?? '') }}"
                                        placeholder="Optional">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">- Select Category -</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Brand</label>
                                    <select name="brand_id" class="form-select">
                                        <option value="">- Select Brand -</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">Technical Content</label>
                                    <input type="text" name="technical_content" class="form-control"
                                        value="{{ old('technical_content', $product->technical_content ?? '') }}"
                                        placeholder="e.g. BIO SEA WEED EXTRACT">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required>
                                        @foreach (['active' => 'Active', 'inactive' => 'Inactive', 'draft' => 'Draft'] as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ old('status', $product->status ?? 'active') === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Short Description</label>
                                    <textarea name="short_description" class="form-control" rows="2"
                                        placeholder="One-line summary shown in listings...">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Full Description</label>
                                    <textarea name="description" class="form-control tinymce-editor" rows="6"
                                        placeholder="Detailed product description...">{{ old('description', $product->description ?? '') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">YouTube / Video URL</label>
                                    <input type="url" name="video_url"
                                        class="form-control @error('video_url') is-invalid @enderror"
                                        value="{{ old('video_url', $product->video_url ?? '') }}"
                                        placeholder="https://youtube.com/shorts/...">
                                    @error('video_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- PRODUCT DATA / VARIATIONS --}}
                    @include('dashboard.products.partials.variation-builder')

                    {{-- SEO --}}
                    <div class="card mb-4">
                        <div class="card-header">SEO Settings</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Meta Title <span class="text-muted">(50-60 chars)</span></label>
                                    <input type="text" name="meta_title" class="form-control"
                                        value="{{ old('meta_title', $product->meta_title ?? '') }}"
                                        placeholder="e.g., Premium Organic Bhoomi Star | Bharat Biomer"
                                        maxlength="60">
                                    <small class="text-muted">Used in search results and browser tabs</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Meta Description <span class="text-muted">(150-160 chars)</span></label>
                                    <textarea name="meta_description" class="form-control" rows="3"
                                        placeholder="e.g., Discover premium Bhoomi Star for better soil health. Natural ingredients, proven results..."
                                        maxlength="160">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                                    <small class="text-muted">Appears below title in search results</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Meta Keywords <span class="text-muted">(comma-separated)</span></label>
                                    <textarea name="meta_keyword" class="form-control" rows="2"
                                        placeholder="e.g., organic fertilizer, soil enhancement, bhoomi star, bio products, agriculture">
{{ old('meta_keyword', $product->meta_keyword ?? '') }}</textarea>
                                    <small class="text-muted">Separate keywords with commas. Not displayed but helps search engines.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- FAQs --}}
                    <div class="card mb-4" id="faqCard">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Frequently Asked Questions</span>
                            <button type="button" class="btn btn-sm btn-outline-success" id="addFaqRowBtn">+ Add FAQ</button>
                        </div>
                        <div class="card-body">
                            <div id="faqRows">
                                @if(isset($product) && $product->faqs->count())
                                    {{-- Edit mode: show existing FAQs as AJAX-managed items --}}
                                    @foreach($product->faqs as $faq)
                                    <div class="faq-item border rounded p-3 mb-3 position-relative" data-faq-id="{{ $faq->id }}">
                                        <div class="d-flex gap-2 position-absolute top-0 end-0 m-2">
                                            <button type="button" class="btn btn-xs btn-outline-secondary faq-edit-btn" style="font-size:.7rem;padding:2px 8px;">Edit</button>
                                            <button type="button" class="btn btn-xs btn-outline-danger faq-delete-btn" style="font-size:.7rem;padding:2px 8px;">✕</button>
                                        </div>
                                        <div class="faq-view">
                                            <p class="fw-semibold mb-1 faq-q-text">{{ $faq->question }}</p>
                                            <p class="text-muted mb-0 faq-a-text" style="font-size:.9rem;">{{ $faq->answer }}</p>
                                        </div>
                                        <div class="faq-edit-form d-none">
                                            <div class="mb-2"><label class="form-label fw-semibold">Question</label>
                                                <input type="text" class="form-control faq-q-input" value="{{ $faq->question }}"></div>
                                            <div class="mb-2"><label class="form-label fw-semibold">Answer</label>
                                                <textarea class="form-control faq-a-input" rows="2">{{ $faq->answer }}</textarea></div>
                                            <button type="button" class="btn btn-sm btn-success faq-save-btn">Save</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary faq-cancel-btn">Cancel</button>
                                        </div>
                                    </div>
                                    @endforeach
                                @elseif(old('faqs'))
                                    {{-- Create mode: flash-back rows --}}
                                    @foreach(old('faqs') as $i => $faqOld)
                                    <div class="faq-row border rounded p-3 mb-3 position-relative">
                                        <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 remove-faq-row">✕</button>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold">Question</label>
                                            <input type="text" name="faqs[{{ $i }}][question]" class="form-control" value="{{ $faqOld['question'] ?? '' }}" placeholder="e.g. What is the shelf life?">
                                        </div>
                                        <div>
                                            <label class="form-label fw-semibold">Answer</label>
                                            <textarea name="faqs[{{ $i }}][answer]" class="form-control" rows="2">{{ $faqOld['answer'] ?? '' }}</textarea>
                                        </div>
                                        <input type="hidden" name="faqs[{{ $i }}][sort_order]" value="{{ $i }}">
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <p class="text-muted small mb-0" id="faqEmptyMsg">No FAQs added yet. Click "Add FAQ" to start.</p>
                            {{-- New FAQ form for edit mode (AJAX) --}}
                            @if(isset($product))
                            <div id="newFaqForm" class="border rounded p-3 mt-3 d-none">
                                <div class="mb-2"><label class="form-label fw-semibold">Question</label>
                                    <input type="text" id="newFaqQ" class="form-control" placeholder="e.g. What is the shelf life?"></div>
                                <div class="mb-2"><label class="form-label fw-semibold">Answer</label>
                                    <textarea id="newFaqA" class="form-control" rows="2" placeholder="Write the answer here..."></textarea></div>
                                <button type="button" id="saveNewFaqBtn" class="btn btn-sm btn-success">Add FAQ</button>
                                <button type="button" id="cancelNewFaqBtn" class="btn btn-sm btn-outline-secondary ms-2">Cancel</button>
                            </div>
                            @endif
                        </div>
                    </div>


                </div>{{-- /col-lg-8 --}}


                {{-- RIGHT COLUMN --}}
                <div class="col-lg-4 product-editor__sidebar">

                    {{-- Pricing --}}
                    <div class="card mb-4">
                        <div class="card-header">Pricing</div>
                        <div class="card-body">
                            <label class="form-label">Base / Starting Price (INR) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="base_price"
                                class="form-control @error('base_price') is-invalid @enderror"
                                value="{{ old('base_price', $product->base_price ?? '') }}"
                                min="0" step="0.01" placeholder="500.00" required>
                            @error('base_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Each variation has its own price set above.</small>

                            <label class="form-label mt-3">Default Unit <span class="text-danger">*</span></label>
                            <input type="text" name="unit"
                                class="form-control @error('unit') is-invalid @enderror"
                                value="{{ old('unit', $product->unit ?? 'kg') }}"
                                placeholder="e.g. kg, liter, piece, ton, etc" required>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Example: kg, liter, piece, ton, box, etc. Each variation can have its own unit.</small>

                            <label class="form-label mt-3">Shipping Charge (INR)</label>
                            <input type="number" name="shipping_charge"
                                class="form-control @error('shipping_charge') is-invalid @enderror"
                                value="{{ old('shipping_charge', $product->shipping_charge ?? 0) }}"
                                min="0" step="0.01" placeholder="0.00">
                            @error('shipping_charge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Leave as 0 for free shipping, or enter the shipping charge amount.</small>

                            <label class="form-label mt-3">Tax Rate (%) (GST/VAT)</label>
                            <input type="number" name="tax_rate"
                                class="form-control @error('tax_rate') is-invalid @enderror"
                                value="{{ old('tax_rate', $product->tax_rate ?? 0) }}"
                                min="0" max="100" step="0.01" placeholder="0.00">
                            @error('tax_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">e.g., 5% GST, 18% VAT. Leave as 0 for no tax.</small>

                            <div class="border-top mt-4 pt-4">
                                <input type="hidden" name="manage_stock" value="0">
                                <label class="form-check d-flex align-items-center gap-2 p-0 mb-3">
                                    <input type="checkbox" name="manage_stock" value="1" class="form-check-input"
                                        {{ old('manage_stock', $product->manage_stock ?? true) ? 'checked' : '' }}>
                                    <span>Manage stock for this product</span>
                                </label>

                                <label class="form-label">Stock Quantity</label>
                                <input type="number" name="stock_quantity"
                                    class="form-control @error('stock_quantity') is-invalid @enderror"
                                    value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}"
                                    min="0" step="1" placeholder="0">
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">For variable products, each variation stock is managed in Product Data.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Featured Image --}}
                    <div class="card mb-4">
                        <div class="card-header">Featured Image</div>
                        <div class="card-body">
                            @if (isset($product) && $product->featured_image)
                                <div class="featured-img-wrap" id="featuredExistingWrap">
                                    <img src="{{ Storage::url($product->featured_image) }}"
                                        class="img-fluid rounded"
                                        style="max-height:180px;object-fit:cover;width:100%;">
                                    <button type="button" class="del-img"
                                        data-featured-image-delete
                                        data-product-id="{{ $product->id }}"
                                        title="Remove">x</button>
                                </div>
                            @endif
                            <input type="file" name="featured_image" id="featuredImageInput" class="form-control" accept="image/*"
                                data-featured-image-input data-preview-target="featuredPreview">
                            <div class="featured-img-wrap mt-2" id="featuredPreviewWrap" style="display:none;">
                                <img id="featuredPreview" class="img-fluid rounded"
                                    style="max-height:180px;object-fit:cover;width:100%;">
                                <button type="button" class="del-img" data-featured-preview-clear title="Remove">x</button>
                            </div>
                        </div>
                    </div>

                    {{-- Gallery --}}
                    <div class="card mb-4">
                        <div class="card-header">Gallery Images</div>
                        <div class="card-body">
                            @if (isset($product) && $product->images->isNotEmpty())
                                <div class="img-preview-grid mb-2" id="existingGallery">
                                    @foreach ($product->images as $img)
                                        <div class="existing-img" id="existingImg_{{ $img->id }}">
                                            <img src="{{ Storage::url($img->image_path) }}">
                                            <button type="button" class="del-img"
                                                data-gallery-image-delete
                                                data-image-id="{{ $img->id }}"
                                                title="Remove">x</button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple
                                data-gallery-input>
                            <div id="galleryPreviews" class="img-preview-grid"></div>
                            <small class="text-muted">Select multiple images (Ctrl+click).</small>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="card mb-4">
                        <div class="card-header">Tags</div>
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" id="tagInput" class="form-control"
                                    placeholder="Type a tag and press Enter"
                                    data-product-tag-input>
                                <button type="button" class="btn btn-outline-secondary"
                                    data-product-tag-add>Add</button>
                            </div>
                            <div class="tag-pills mt-2" id="tagPills" data-product-tag-pills></div>
                            <div id="tagInputsContainer"></div>

                            @if (isset($product))
                                <div data-product-existing-tags="{{ e($product->tags->pluck('name')->toJson()) }}"></div>
                            @endif

                            <div class="mt-3">
                                <small class="text-muted d-block mb-1">Suggestions:</small>
                                <div style="display:flex;flex-wrap:wrap;gap:.3rem;">
                                    @foreach ($tags->take(20) as $t)
                                        <span class="badge bg-light text-dark border"
                                            style="cursor:pointer;font-size:.75rem;"
                                            data-product-tag-suggestion
                                            data-tag-name="{{ $t->name }}">{{ $t->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card mb-4">
                        <div class="card-body d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                 {{ isset($product) ? 'Update Product' : 'Save Product' }}
                            </button>
                            <a href="{{ route('dashboard.products.index') }}"
                                class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>

                </div>{{-- /col-lg-4 --}}
            </div>{{-- /row --}}
        </form>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    const isEditMode = {{ isset($product) ? 'true' : 'false' }};
    const rows       = document.getElementById('faqRows');
    const emptyMsg   = document.getElementById('faqEmptyMsg');

    function updateEmpty() {
        if (!emptyMsg) return;
        const hasItems = rows.querySelectorAll('.faq-row, .faq-item').length > 0;
        emptyMsg.style.display = hasItems ? 'none' : '';
    }

    // ── CREATE MODE: form inputs ──────────────────────────────────────────
    if (!isEditMode) {
        let faqIndex = {{ old('faqs') ? count(old('faqs')) : 0 }};

        document.getElementById('addFaqRowBtn').addEventListener('click', function () {
            const i = faqIndex++;
            const div = document.createElement('div');
            div.className = 'faq-row border rounded p-3 mb-3 position-relative';
            div.innerHTML = `
                <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 remove-faq-row">✕</button>
                <div class="mb-2">
                    <label class="form-label fw-semibold">Question</label>
                    <input type="text" name="faqs[${i}][question]" class="form-control" placeholder="e.g. What is the shelf life?">
                </div>
                <div>
                    <label class="form-label fw-semibold">Answer</label>
                    <textarea name="faqs[${i}][answer]" class="form-control" rows="2" placeholder="Write the answer here..."></textarea>
                </div>
                <input type="hidden" name="faqs[${i}][sort_order]" value="${i}">
            `;
            rows.appendChild(div);
            updateEmpty();
            div.querySelector('input').focus();
        });

        rows.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-faq-row')) {
                e.target.closest('.faq-row').remove();
                updateEmpty();
            }
        });

    // ── EDIT MODE: AJAX ───────────────────────────────────────────────────
    } else {
        const csrf       = document.querySelector('meta[name="csrf-token"]').content;
        const storeUrl   = '{{ isset($product) ? route("dashboard.products.faqs.store", $product) : "#" }}';
        const updateBase = '{{ url("dashboard/products/faqs") }}/';
        const newForm    = document.getElementById('newFaqForm');

        function buildFaqItem(faq) {
            const div = document.createElement('div');
            div.className = 'faq-item border rounded p-3 mb-3 position-relative';
            div.dataset.faqId = faq.id;
            div.innerHTML = `
                <div class="d-flex gap-2 position-absolute top-0 end-0 m-2">
                    <button type="button" class="btn btn-xs btn-outline-secondary faq-edit-btn" style="font-size:.7rem;padding:2px 8px;">Edit</button>
                    <button type="button" class="btn btn-xs btn-outline-danger faq-delete-btn" style="font-size:.7rem;padding:2px 8px;">✕</button>
                </div>
                <div class="faq-view">
                    <p class="fw-semibold mb-1 faq-q-text">${faq.question}</p>
                    <p class="text-muted mb-0 faq-a-text" style="font-size:.9rem;">${faq.answer}</p>
                </div>
                <div class="faq-edit-form d-none">
                    <div class="mb-2"><label class="form-label fw-semibold">Question</label>
                        <input type="text" class="form-control faq-q-input" value="${faq.question}"></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Answer</label>
                        <textarea class="form-control faq-a-input" rows="2">${faq.answer}</textarea></div>
                    <button type="button" class="btn btn-sm btn-success faq-save-btn">Save</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary faq-cancel-btn">Cancel</button>
                </div>
            `;
            return div;
        }

        document.getElementById('addFaqRowBtn').addEventListener('click', () => {
            newForm.classList.remove('d-none');
            document.getElementById('newFaqQ').focus();
        });
        document.getElementById('cancelNewFaqBtn').addEventListener('click', () => newForm.classList.add('d-none'));
        document.getElementById('saveNewFaqBtn').addEventListener('click', async () => {
            const q = document.getElementById('newFaqQ').value.trim();
            const a = document.getElementById('newFaqA').value.trim();
            if (!q || !a) return alert('Question and answer are required.');
            const res = await fetch(storeUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify({ question: q, answer: a, sort_order: rows.querySelectorAll('.faq-item').length })
            });
            if (!res.ok) return alert('Failed to save FAQ.');
            const data = await res.json();
            rows.appendChild(buildFaqItem(data.faq));
            document.getElementById('newFaqQ').value = '';
            document.getElementById('newFaqA').value = '';
            newForm.classList.add('d-none');
            updateEmpty();
        });

        rows.addEventListener('click', async function (e) {
            const item = e.target.closest('.faq-item');
            if (!item) return;
            const id = item.dataset.faqId;

            if (e.target.classList.contains('faq-edit-btn')) {
                item.querySelector('.faq-view').classList.add('d-none');
                item.querySelector('.faq-edit-form').classList.remove('d-none');
            }
            if (e.target.classList.contains('faq-cancel-btn')) {
                item.querySelector('.faq-view').classList.remove('d-none');
                item.querySelector('.faq-edit-form').classList.add('d-none');
            }
            if (e.target.classList.contains('faq-save-btn')) {
                const q = item.querySelector('.faq-q-input').value.trim();
                const a = item.querySelector('.faq-a-input').value.trim();
                if (!q || !a) return alert('Question and answer required.');
                const res = await fetch(updateBase + id, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: JSON.stringify({ question: q, answer: a })
                });
                if (!res.ok) return alert('Failed to update FAQ.');
                item.querySelector('.faq-q-text').textContent = q;
                item.querySelector('.faq-a-text').textContent = a;
                item.querySelector('.faq-view').classList.remove('d-none');
                item.querySelector('.faq-edit-form').classList.add('d-none');
            }
            if (e.target.classList.contains('faq-delete-btn')) {
                if (!confirm('Delete this FAQ?')) return;
                const res = await fetch(updateBase + id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                });
                if (!res.ok) return alert('Failed to delete FAQ.');
                item.remove();
                updateEmpty();
            }
        });
    }

    updateEmpty();
})();
</script>
@endpush
