{{-- resources/views/dashboard/products/edit.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Edit Product';
    $subTitle = 'Edit Product';
@endphp



@section('content')

  {{-- Breadcrumb --}}
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      
      <h5 class="mb-1">Edit Product</h5>
      <small class="text-muted">{{ $product->name }}</small>
    </div>
    <a href="{{ route('dashboard.products.index') }}" class="btn btn-outline-secondary btn-sm">Back to Products</a>
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

  <form action="{{ route('dashboard.products.update', $product) }}"
        method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">

      {{-- ── LEFT COLUMN ─────────────────────────────────────────────── --}}
      <div class="col-lg-8">

        {{-- Basic Info --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">Basic Information</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">

              <div class="col-12">
                <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $product->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">SKU <span class="text-danger">*</span></label>
                <input type="text" name="sku"
                       class="form-control @error('sku') is-invalid @enderror"
                       value="{{ old('sku', $product->sku) }}" required>
                @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Base Price <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">Rs.</span>
                  <input type="number" name="base_price" step="0.01" min="0"
                         class="form-control @error('base_price') is-invalid @enderror"
                         value="{{ old('base_price', $product->base_price) }}" required>
                  @error('base_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Shipping Charge</label>
                <div class="input-group">
                  <span class="input-group-text">Rs.</span>
                  <input type="number" name="shipping_charge" step="0.01" min="0"
                         class="form-control @error('shipping_charge') is-invalid @enderror"
                         value="{{ old('shipping_charge', $product->shipping_charge ?? 0) }}">
                  @error('shipping_charge')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <small class="text-muted d-block mt-1">0 = Free shipping</small>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Tax Rate (%)</label>
                <div class="input-group">
                  <input type="number" name="tax_rate" step="0.01" min="0" max="100"
                         class="form-control @error('tax_rate') is-invalid @enderror"
                         value="{{ old('tax_rate', $product->tax_rate ?? 0) }}">
                  <span class="input-group-text">%</span>
                  @error('tax_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <small class="text-muted d-block mt-1">GST/VAT rate (0 = no tax)</small>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Unit <span class="text-danger">*</span></label>
                <input type="text" name="unit"
                       class="form-control @error('unit') is-invalid @enderror"
                       value="{{ old('unit', $product->unit ?? 'kg') }}"
                       placeholder="e.g. kg, liter, piece, ton, box" required>
                @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted d-block mt-1">Unit of measurement for this product</small>
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Technical Content</label>
                <input type="text" name="technical_content"
                       class="form-control @error('technical_content') is-invalid @enderror"
                       value="{{ old('technical_content', $product->technical_content) }}"
                       placeholder="e.g. 100% Cotton, GSM 180">
                @error('technical_content')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Short Description</label>
                <textarea name="short_description" rows="2"
                          class="form-control @error('short_description') is-invalid @enderror"
                          placeholder="Brief summary shown in listings...">{{ old('short_description', $product->short_description) }}</textarea>
                @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Full Description</label>
                <textarea name="description" rows="5"
                          class="form-control tinymce-editor @error('description') is-invalid @enderror"
                          placeholder="Detailed product description...">{{ old('description', $product->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

            </div>
          </div>
        </div>

        {{-- SEO --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0"> SEO Settings</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label fw-semibold">Meta Title <span class="text-muted">(50-60 chars)</span></label>
                <input type="text" name="meta_title"
                       class="form-control @error('meta_title') is-invalid @enderror"
                       value="{{ old('meta_title', $product->meta_title) }}"
                       placeholder="e.g., Premium Organic Bhoomi Star | Bharat Biomer"
                       maxlength="60">
                <small class="text-muted d-block mt-1">Used in search results and browser tabs</small>
                @error('meta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold">Meta Description <span class="text-muted">(150-160 chars)</span></label>
                <textarea name="meta_description" rows="3"
                          class="form-control @error('meta_description') is-invalid @enderror"
                          placeholder="e.g., Discover premium Bhoomi Star for better soil health..."
                          maxlength="160">{{ old('meta_description', $product->meta_description) }}</textarea>
                <small class="text-muted d-block mt-1">Appears below title in search results</small>
                @error('meta_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold">Meta Keywords <span class="text-muted">(comma-separated)</span></label>
                <textarea name="meta_keyword" rows="2"
                          class="form-control @error('meta_keyword') is-invalid @enderror"
                          placeholder="e.g., organic fertilizer, soil enhancement, bhoomi star, bio products">{{ old('meta_keyword', $product->meta_keyword) }}</textarea>
                <small class="text-muted d-block mt-1">Separate keywords with commas. Not displayed but helps search engines.</small>
                @error('meta_keyword')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>
        </div>

        {{-- Gallery Images --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0"> Gallery Images</h5>
          </div>
          <div class="card-body">

            @if($product->images->count())
              <div class="row g-2 mb-3" id="existing-gallery">
                @foreach($product->images as $img)
                <div class="col-auto" id="gallery-item-{{ $img->id }}">
                  <div class="position-relative">
                    <img src="{{ Storage::url($img->image_path) }}"
                         class="radius-8 border"
                         style="width:80px;height:80px;object-fit:cover;"
                         alt="">
                    <button type="button"
                            class="btn btn-danger btn-sm position-absolute top-0 end-0 delete-gallery-img"
                            data-product-edit-gallery-delete
                            data-image-id="{{ $img->id }}"
                            data-delete-url="{{ route('dashboard.products.destroyImage', $img) }}"
                            style="font-size:.65rem;padding:1px 5px;line-height:1.4;">✕</button>
                  </div>
                </div>
                @endforeach
              </div>
            @else
              <p class="text-muted text-sm mb-3">No gallery images yet.</p>
            @endif

            <label class="form-label fw-semibold">Add More Images</label>
            <input type="file" name="gallery[]" multiple accept="image/*"
                   class="form-control @error('gallery.*') is-invalid @enderror">
            <small class="text-muted">Max 2MB each. JPG, PNG, WEBP.</small>
            @error('gallery.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

          </div>
        </div>

      </div>

        {{-- FAQ Management --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Frequently Asked Questions</h5>
            <button type="button" class="btn btn-sm btn-outline-success" id="addFaqBtn">+ Add FAQ</button>
          </div>
          <div class="card-body">
            <div id="faqList">
              @forelse($product->faqs as $faq)
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
                  <div class="mb-2">
                    <label class="form-label fw-semibold">Question</label>
                    <input type="text" class="form-control faq-q-input" value="{{ $faq->question }}">
                  </div>
                  <div class="mb-2">
                    <label class="form-label fw-semibold">Answer</label>
                    <textarea class="form-control faq-a-input" rows="2">{{ $faq->answer }}</textarea>
                  </div>
                  <button type="button" class="btn btn-sm btn-success faq-save-btn">Save</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary faq-cancel-btn">Cancel</button>
                </div>
              </div>
              @empty
              <p class="text-muted small" id="faqEmptyMsg">No FAQs yet. Click "Add FAQ" to create one.</p>
              @endforelse
              @if($product->faqs->isNotEmpty())
              <p class="text-muted small d-none" id="faqEmptyMsg">No FAQs yet. Click "Add FAQ" to create one.</p>
              @endif
            </div>

            {{-- New FAQ form (hidden by default) --}}
            <div id="newFaqForm" class="border rounded p-3 d-none">
              <div class="mb-2">
                <label class="form-label fw-semibold">Question</label>
                <input type="text" id="newFaqQ" class="form-control" placeholder="e.g. What is the shelf life?">
              </div>
              <div class="mb-2">
                <label class="form-label fw-semibold">Answer</label>
                <textarea id="newFaqA" class="form-control" rows="2" placeholder="Write the answer here..."></textarea>
              </div>
              <button type="button" id="saveNewFaqBtn" class="btn btn-sm btn-success">Add FAQ</button>
              <button type="button" id="cancelNewFaqBtn" class="btn btn-sm btn-outline-secondary ms-2">Cancel</button>
            </div>
          </div>
        </div>

      </div>


      <div class="col-lg-4">

        {{-- Status & Classify --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0"> Status & Classify</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">

              <div class="col-12">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                  <option value="active"   {{ old('status', $product->status) === 'active'   ? 'selected' : '' }}>Active</option>
                  <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                  <option value="draft"    {{ old('status', $product->status) === 'draft'    ? 'selected' : '' }}>Draft</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Brand</label>
                <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                  <option value="">Select Brand</option>
                  @foreach($brands as $brand)
                    <option value="{{ $brand->id }}"
                      {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                      {{ $brand->name }}
                    </option>
                  @endforeach
                </select>
                @error('brand_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Category</label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                  <option value="">Select Category</option>
                  @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                      {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                      {{ $cat->name }}
                    </option>
                  @endforeach
                </select>
                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

            </div>
          </div>
        </div>

        {{-- Featured Image --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0"> Featured Image</h5>
          </div>
          <div class="card-body text-center">

            @if($product->featured_image)
              <img src="{{ Storage::url($product->featured_image) }}"
                   class="img-fluid radius-8 border mb-3"
                   style="max-height:160px;object-fit:cover;"
                   id="featured-preview"
                   data-product-edit-featured-preview>
            @else
              <div class="bg-neutral-200 radius-8 d-flex align-items-center justify-content-center mb-3"
                   style="height:120px;" id="featured-placeholder"
                   data-product-edit-featured-placeholder>
                <span class="text-muted text-sm">No image uploaded</span>
              </div>
              <img src="" class="img-fluid radius-8 border mb-3 d-none"
                   style="max-height:160px;object-fit:cover;" id="featured-preview"
                   data-product-edit-featured-preview>
            @endif

            <input type="file" name="featured_image" id="featured_image_input"
                   accept="image/*"
                   class="form-control form-control-sm @error('featured_image') is-invalid @enderror"
                   data-product-edit-featured-input>
            <small class="text-muted">Max 2MB. Leave blank to keep current.</small>
            @error('featured_image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

          </div>
        </div>

        {{-- Video URL --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0"> Video URL</h5>
          </div>
          <div class="card-body">
            <input type="url" name="video_url"
                   class="form-control @error('video_url') is-invalid @enderror"
                   value="{{ old('video_url', $product->video_url) }}"
                   placeholder="https://youtube.com/...">
            @error('video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">
            Update Product
          </button>
          <a href="{{ route('dashboard.products.index') }}" class="btn btn-outline-secondary">
            Cancel
          </a>
        </div>

      </div>

    </div>{{-- end .row --}}
  </form>

@endsection

@push('scripts')
<script>
(function () {
    const csrf    = document.querySelector('meta[name="csrf-token"]').content;
    const storeUrl  = '{{ route("dashboard.products.faqs.store", $product) }}';
    const updateBase = '{{ url("dashboard/products/faqs") }}/';
    const deleteBase = '{{ url("dashboard/products/faqs") }}/';
    const faqList    = document.getElementById('faqList');
    const emptyMsg   = document.getElementById('faqEmptyMsg');
    const newForm    = document.getElementById('newFaqForm');

    function updateEmpty() {
        if (!emptyMsg) return;
        emptyMsg.classList.toggle('d-none', faqList.querySelectorAll('.faq-item').length > 0);
    }

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

    // Add FAQ
    document.getElementById('addFaqBtn').addEventListener('click', () => {
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
            body: JSON.stringify({ question: q, answer: a, sort_order: faqList.querySelectorAll('.faq-item').length })
        });
        if (!res.ok) return alert('Failed to save FAQ.');
        const data = await res.json();
        faqList.appendChild(buildFaqItem(data.faq));
        document.getElementById('newFaqQ').value = '';
        document.getElementById('newFaqA').value = '';
        newForm.classList.add('d-none');
        updateEmpty();
    });

    // Edit & Delete (event delegation)
    faqList.addEventListener('click', async function (e) {
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
            const res = await fetch(deleteBase + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            });
            if (!res.ok) return alert('Failed to delete FAQ.');
            item.remove();
            updateEmpty();
        }
    });

    updateEmpty();
})();
</script>
@endpush
