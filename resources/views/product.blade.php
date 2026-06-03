@extends('layout.frontlayout')
@section('title', 'Shop – Bharat Biomer Products')

@section('content')

  <style>
.shop__card {
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid #e8f0e4;
  height: 100%;
  display: flex;
  flex-direction: column;
}
.shop__card:hover {
  box-shadow: none;
}
.shop__img-wrap {
  position: relative;
  background: #f4faf0;
  padding: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 200px;
}
.shop__img {
  max-height: 160px;
  width: auto;
  object-fit: contain;
}
.shop__badge {
  position: absolute;
  top: 12px;
  right: 12px;
  font-size: 0.72rem;
  font-weight: 600;
  padding: 4px 12px;
  border-radius: 20px;
  letter-spacing: 0.3px;
}
.shop__badge--available {
  background: #e8f5ed;
  color: #2d7a45;
  border: 1px solid #a8d5b5;
}
.shop__badge--soon {
  background: #fff8e1;
  color: #b45309;
  border: 1px solid #fcd34d;
}

/* ✅ Heart Wishlist Button */
.shop__wishlist-btn {
  position: absolute;
  top: 12px;
  left: 12px;
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: #fff;
  border: 1px solid #e8f0e4;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 1rem;
  transition: all 0.2s;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.shop__wishlist-btn:hover {
  background: #fdecea;
  border-color: #f5a9a4;
  transform: none;
}
.shop__wishlist-btn.wishlisted {
  background: #fdecea;
  border-color: #e74c3c;
}

.shop__body {
  padding: 1.25rem;
  display: flex;
  flex-direction: column;
  flex: 1;
}
.shop__meta {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-bottom: 0.6rem;
}
.shop__name {
  font-size: 1.05rem;
  font-weight: 700;
  color: #1a2e1a;
  margin-bottom: 0.4rem;
  line-height: 1.3;
}
.shop__desc {
  font-size: 0.85rem;
  color: #6b7c6b;
  margin-bottom: 0.75rem;
  line-height: 1.5;
}
.shop__price-row {
  display: flex;
  align-items: baseline;
  gap: 6px;
  margin-bottom: 0.25rem;
}
.shop__price-label { font-size: 0.75rem; color: #9aab9a; }
.shop__price { font-size: 1.3rem; font-weight: 800; color: #2d7a45; }
.shop__variants { font-size: 0.78rem; color: #9aab9a; margin-bottom: 1rem; }
.shop__variation-row {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 0.9rem;
}
.shop__variation-btn {
  border: 1px solid #d1e7d5;
  background: #f4faf0;
  color: #2d7a45;
  border-radius: 999px;
  padding: 6px 12px;
  font-size: 0.8rem;
  cursor: pointer;
  transition: all 0.2s ease;
}
.shop__variation-btn:hover {
  background: #eaf5e8;
}
.shop__variation-btn--active {
  background: #2d7a45;
  color: #fff;
  border-color: #2d7a45;
}
.shop__actions {
  display: flex;
  gap: 10px;
  margin-top: auto;
  padding-top: 0.75rem;
  border-top: 1px solid #f0f5ee;
}
.shop__btn {
  flex: 1;
  padding: 0.5rem 0.75rem;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  border: none;
  text-align: center;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}
.shop__btn--primary { background: #2d7a45; color: #fff; }
.shop__btn--primary:hover { background: #245e36; }
.shop__btn--primary.shop__btn--added { background: #4caf72; }
.shop__btn--outline {
  background: transparent;
  color: #2d7a45;
  border: 1.5px solid #2d7a45;
}
.shop__btn--outline:hover { background: #f0faf4; }
.shop__btn--disabled { background: #f0f0f0; color: #aaa; cursor: not-allowed; }
  </style>

  {{-- Additional Styles for Filters --}}
  <style>
  .shop__filters-card {
    background: #fff;
    border: 1px solid #e8f0e4;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  }

  .shop__search-row {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
    align-items: center;
    flex-wrap: wrap;
  }

  .shop__search-group {
    position: relative;
    flex: 1;
    min-width: 250px;
  }

  .shop__search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9aab9a;
    font-size: 1.1rem;
  }

  .shop__search-input {
    width: 100%;
    padding: 10px 12px 10px 40px;
    border: 1.5px solid #c8e6c9;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: border-color 0.2s;
  }

  .shop__search-input:focus {
    outline: none;
    border-color: #2d7a45;
  }

  .shop__search-btn, .shop__clear-btn, .shop__apply-btn {
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
  }

  .shop__search-btn {
    background: #2d7a45;
    color: #fff;
  }

  .shop__search-btn:hover {
    background: #245e36;
  }

  .shop__clear-btn {
    background: #f0f0f0;
    color: #666;
  }

  .shop__clear-btn:hover {
    background: #e0e0e0;
  }

  .shop__filters-row {
    display: flex;
    gap: 16px;
    align-items: end;
    flex-wrap: wrap;
  }

  .shop__filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 150px;
  }

  .shop__filter-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #2d7a45;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .shop__filter-select, .shop__price-input {
    padding: 8px 12px;
    border: 1.5px solid #c8e6c9;
    border-radius: 6px;
    font-size: 0.85rem;
    transition: border-color 0.2s;
  }

  .shop__filter-select:focus, .shop__price-input:focus {
    outline: none;
    border-color: #2d7a45;
  }

  .shop__price-range {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .shop__price-input {
    width: 80px;
  }

  .shop__price-separator {
    color: #9aab9a;
    font-weight: 600;
  }

  .shop__filter-actions {
    margin-left: auto;
  }

  .shop__apply-btn {
    background: #2d7a45;
    color: #fff;
    padding: 10px 20px;
  }

  .shop__apply-btn:hover {
    background: #245e36;
  }

  .shop__results-info {
    background: #e8f5ed;
    border: 1px solid #a8d5b5;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 0.9rem;
    color: #2d7a45;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  @media (max-width: 768px) {
    .shop__search-row {
      flex-direction: column;
      align-items: stretch;
    }

    .shop__filters-row {
      flex-direction: column;
    }

    .shop__filter-group {
      width: 100%;
    }

    .shop__filter-actions {
      margin-left: 0;
      margin-top: 12px;
    }
  }
  </style>

  <section class="prodh__section">
    <div class="container">
      <div class="row">
        <div class="col-12 col-lg-8">
          <div class="prodh__badge mb-3">
            <img src="assets/images/flask-icon.svg" alt="flask" class="prodh__badge-icon"/>
            <span class="prodh__badge-text">Our Product Range</span>
          </div>
          <h1 class="prodh__heading">Shop Bio-Stimulants & Agri Solutions</h1>
          <p class="prodh__desc">Choose from our range of scientifically developed formulations crafted for modern farming. Trusted by farmers across India.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="avan__section">
    <div class="container">

      <div class="row">
        <div class="col-12">
          <div class="avan__header">
            <div class="avan__header-top">
              <span class="avan__check">✓</span>
              <h3 class="avan__header-title">All Products</h3>
            </div>
            <p class="avan__header-desc">Proven formulations ready for field application</p>
          </div>
        </div>
      </div>

      {{-- Search and Filter Bar --}}
      <div class="row mb-4">
        <div class="col-12">
          <div class="shop__filters-card">
            <form method="GET" action="{{ route('products.index') }}" id="filterForm">

              {{-- Search Bar --}}
              <div class="shop__search-row">
                <div class="shop__search-group">
                  <i class="ri-search-line shop__search-icon"></i>
                  <input type="text" name="search" value="{{ request('search') }}"
                         class="shop__search-input" placeholder="Search products...">
                </div>
                <button type="submit" class="shop__search-btn">
                  <i class="ri-search-line"></i> Search
                </button>
                <a href="{{ route('products.index') }}" class="shop__clear-btn">
                  <i class="ri-close-line"></i> Clear
                </a>
              </div>

              {{-- Filters Row --}}
              <div class="shop__filters-row">
                <div class="shop__filter-group">
                  <label class="shop__filter-label">Category</label>
                  <select name="category" class="shop__filter-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                      <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="shop__filter-group">
                  <label class="shop__filter-label">Brand</label>
                  <select name="brand" class="shop__filter-select">
                    <option value="">All Brands</option>
                    @foreach($brands as $br)
                      <option value="{{ $br->id }}" {{ request('brand') == $br->id ? 'selected' : '' }}>
                        {{ $br->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="shop__filter-group">
                  <label class="shop__filter-label">Price Range</label>
                  <div class="shop__price-range">
                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                           class="shop__price-input" placeholder="Min ₹" min="0">
                    <span class="shop__price-separator">-</span>
                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                           class="shop__price-input" placeholder="Max ₹" min="0">
                  </div>
                </div>

                <div class="shop__filter-group">
                  <label class="shop__filter-label">Sort By</label>
                  <select name="sort" class="shop__filter-select">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                  </select>
                </div>

                <div class="shop__filter-actions">
                  <button type="submit" class="shop__apply-btn">
                    <i class="ri-filter-line"></i> Apply Filters
                  </button>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>

      {{-- Results Info --}}
      @if(request()->hasAny(['search', 'category', 'brand', 'min_price', 'max_price']))
        <div class="row mb-3">
          <div class="col-12">
            <div class="shop__results-info">
              <i class="ri-information-line"></i>
              Showing {{ $products->count() }} of {{ $products->total() }} products
              @if(request('search'))
                for "<strong>{{ request('search') }}</strong>"
              @endif
            </div>
          </div>
        </div>
      @endif

      <div class="row g-4">

        @php
          // ✅ Get wishlist product IDs for logged in customer
          $wishlistIds = [];
          if (Auth::guard('customer')->check()) {
              $wishlistIds = Auth::guard('customer')->user()
                  ->wishlists()->pluck('product_id')->toArray();
          }
        @endphp

        @forelse($products as $product)
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="shop__card">

            <div class="shop__img-wrap">
              @if($product->featured_image)
                <img src="{{ Storage::url($product->featured_image) }}"
                     alt="{{ $product->name }}" class="shop__img">
              @else
                <img src="assets/images/product-bottle.svg"
                     alt="{{ $product->name }}" class="shop__img">
              @endif

              {{-- Status Badge --}}
              @if($product->status === 'active')
                <span class="shop__badge shop__badge--available">Available</span>
              @else
                <span class="shop__badge shop__badge--soon">Coming Soon</span>
              @endif

              {{-- ✅ Heart Wishlist Button --}}
              @auth('customer')
              @php $isWishlisted = in_array($product->id, $wishlistIds); @endphp
              <button class="shop__wishlist-btn wishlist-toggle {{ $isWishlisted ? 'wishlisted' : '' }}"
                      data-id="{{ $product->id }}"
                      title="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}">
                  {{ $isWishlisted ? '❤️' : '🤍' }}
              </button>
              @endauth

              {{-- ✅ Not logged in — redirect to login --}}
              @guest('customer')
              <a href="{{ route('customer.login') }}"
                 class="shop__wishlist-btn"
                 title="Login to add to wishlist">
                  🤍
              </a>
              @endguest

            </div>

            <div class="shop__body">

              <div class="shop__meta">
                @if($product->category)
                  <span class="avan__tag">{{ $product->category->name }}</span>
                @endif
                @if($product->brand)
                  <span class="avan__tag">{{ $product->brand->name }}</span>
                @endif
              </div>

              <h4 class="shop__name">{{ $product->name }}</h4>

              @if($product->short_description)
                <p class="shop__desc">{{ Str::limit($product->short_description, 80) }}</p>
              @endif

              @if($product->variations->count())
                <div class="shop__price-row">
                  <span class="shop__price-label">Price</span>
                  <span class="shop__price">₹{{ number_format($product->base_price, 2) }}</span>
                  <span class="shop__price-label shop__price-unit">/ {{ $product->unit ?? 'unit' }}</span>
                </div>
                <div class="shop__variation-row">
                  @foreach($product->variations->where('is_active', true) as $var)
                    <button type="button"
                            class="shop__variation-btn"
                            data-product-id="{{ $product->id }}"
                            data-variation-id="{{ $var->id }}"
                            data-price="{{ $var->price }}"
                            data-unit="{{ $var->unit ?? $product->unit }}"
                            data-name="{{ $var->attribute_value }}">
                      {{ $var->attribute_value }}
                    </button>
                  @endforeach
                </div>
                <p class="shop__variants">{{ $product->variations->count() }} pack size(s) available</p>
              @else
                <div class="shop__price-row">
                  <span class="shop__price">₹{{ number_format($product->base_price, 2) }}</span>
                  <span class="shop__price-label" style="margin-left: 8px;">/ {{ $product->unit ?? 'unit' }}</span>
                </div>
              @endif

              <div class="shop__actions">
                <a href="{{ route('products.show', $product->slug ?? $product->id) }}"
                   class="shop__btn shop__btn--outline">
                  View Details
                </a>
                @if($product->status === 'active')
                  <button class="shop__btn shop__btn--primary add-to-cart"
                          data-id="{{ $product->id }}"
                          data-name="{{ $product->name }}">
                    Add to Cart
                  </button>
                @else
                  <button class="shop__btn shop__btn--disabled" disabled>
                    Coming Soon
                  </button>
                @endif
              </div>

            </div>
          </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
          <img src="assets/images/flask-icon.svg" alt="" style="width:60px;opacity:.3;margin-bottom:1rem;">
          <p class="text-muted">No products available at the moment. Check back soon!</p>
        </div>
        @endforelse

      </div>

      @if($products->hasPages())
        <div class="row mt-5">
          <div class="col-12 d-flex justify-content-center">
            {{ $products->links() }}
          </div>
        </div>
      @endif

    </div>
  </section>

  <section class="ppip__section">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="ppip__header-top">
            <img src="assets/images/clock-icon.svg" alt="clock" class="ppip__header-icon"/>
            <h3 class="ppip__header-title">More Coming Soon</h3>
          </div>
          <p class="ppip__header-desc">Next-generation solutions under active development</p>
        </div>
      </div>
      <div class="row g-4 mt-2">
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ppip__card">
            <span class="ppip__badge">Coming Soon</span>
            <div class="ppip__icon-wrap">
              <img src="assets/images/fertilizer-icon.svg" alt="Smart Fertilizers" class="ppip__icon"/>
            </div>
            <h4 class="ppip__card-title">Smart Fertilizers</h4>
            <p class="ppip__card-desc">Intelligent nutrient delivery with controlled release</p>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ppip__card">
            <span class="ppip__badge">Coming Soon</span>
            <div class="ppip__icon-wrap">
              <img src="assets/images/consortia-icon.svg" alt="Microbial Consortia" class="ppip__icon"/>
            </div>
            <h4 class="ppip__card-title">Microbial Consortia</h4>
            <p class="ppip__card-desc">Advanced multi-strain formulations for soil health</p>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ppip__card">
            <span class="ppip__badge">Coming Soon</span>
            <div class="ppip__icon-wrap">
              <img src="assets/images/biopolymer-icon.svg" alt="Biopolymer Inputs" class="ppip__icon"/>
            </div>
            <h4 class="ppip__card-title">Biopolymer Inputs</h4>
            <p class="ppip__card-desc">Sustainable polymer-based agri enhancement</p>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ppip__card">
            <span class="ppip__badge">Coming Soon</span>
            <div class="ppip__icon-wrap">
              <img src="assets/images/climate-icon.svg" alt="Climate-Resilient" class="ppip__icon"/>
            </div>
            <h4 class="ppip__card-title">Climate-Resilient</h4>
            <p class="ppip__card-desc">Formulations for extreme weather stress</p>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection

@push('scripts')
<script>
  // ── Add to Cart ──────────────────────────────────────────────────────
  document.querySelectorAll('.shop__variation-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const card = this.closest('.shop__card');
      if (!card) return;

      card.querySelectorAll('.shop__variation-btn').forEach(b => b.classList.remove('shop__variation-btn--active'));
      this.classList.add('shop__variation-btn--active');

      const priceEl = card.querySelector('.shop__price');
      const unitEl  = card.querySelector('.shop__price-unit');
      if (priceEl) {
        priceEl.textContent = '₹' + parseFloat(this.dataset.price).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      }
      if (unitEl) {
        unitEl.textContent = '/ ' + this.dataset.unit;
      }

      const addBtn = card.querySelector('.add-to-cart');
      if (addBtn) {
        addBtn.dataset.variationId = this.dataset.variationId;
      }
    });
  });

  function updateGlobalCartBadge(count) {
    if (count > 0) {
      document.querySelectorAll('.bb-cart-badge').forEach(badge => {
        badge.textContent = count;
      });

      document.querySelectorAll('.bb-cart-icon').forEach(icon => {
        if (!icon.querySelector('.bb-cart-badge')) {
          const badge = document.createElement('span');
          badge.className = 'bb-cart-badge';
          badge.textContent = count;
          icon.appendChild(badge);
        }
      });
    } else {
      document.querySelectorAll('.bb-cart-badge').forEach(badge => badge.remove());
    }
  }

  document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', function () {
      const id   = this.dataset.id;
      const name = this.dataset.name;

      fetch('/cart/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: id, quantity: 1, variation_id: this.dataset.variationId || null })
      })
      .then(r => r.json())
      .then(d => {
        if (d.success) {
          if (d.cart_count !== undefined) {
            updateGlobalCartBadge(d.cart_count);
          }
          this.textContent = '✓ Added!';
          this.classList.add('shop__btn--added');
          setTimeout(() => {
            this.textContent = 'Add to Cart';
            this.classList.remove('shop__btn--added');
          }, 2000);
        }
      })
      .catch(() => alert('Could not add to cart. Please try again.'));
    });
  });

  // ── Wishlist Toggle ──────────────────────────────────────────────────
  document.querySelectorAll('.wishlist-toggle').forEach(btn => {
    btn.addEventListener('click', function () {
      const productId = this.dataset.id;
      const self      = this;

      fetch('{{ route("wishlist.toggle") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: productId })
      })
      .then(r => r.json())
      .then(d => {
        if (d.success) {
          self.textContent = d.wishlisted ? '❤️' : '🤍';
          self.title       = d.wishlisted ? 'Remove from wishlist' : 'Add to wishlist';
          d.wishlisted
            ? self.classList.add('wishlisted')
            : self.classList.remove('wishlisted');

          // ✅ Update navbar wishlist count
          const badge = document.getElementById('wishlist-count');
          if (badge) {
            badge.textContent = d.count;
            badge.style.display = d.count > 0 ? 'flex' : 'none';
          }
        }
      })
      .catch(() => alert('Could not update wishlist. Please try again.'));
    });
  });

  // ── Filter Enhancements ──────────────────────────────────────────────
  // Auto-submit on select change for better UX
  document.querySelectorAll('.shop__filter-select').forEach(select => {
    select.addEventListener('change', () => {
      document.getElementById('filterForm').submit();
    });
  });

  // Clear filters functionality
  document.querySelector('.shop__clear-btn').addEventListener('click', (e) => {
    e.preventDefault();
    // Reset all form fields
    document.querySelectorAll('#filterForm input, #filterForm select').forEach(field => {
      if (field.type === 'number') {
        field.value = '';
      } else {
        field.selectedIndex = 0;
      }
    });
    // Submit the form to clear filters
    document.getElementById('filterForm').submit();
  });

  // Search on Enter key
  document.querySelector('.shop__search-input').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      document.getElementById('filterForm').submit();
    }
  });
</script>
@endpush
