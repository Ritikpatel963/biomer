@extends('layout.frontlayout')
@section('title', $pageSeo?->meta_title ?: 'Shop – Bharat Biomer Products')
@section('seo_description', $pageSeo?->meta_description ?: 'Shop Bharat Biomer biological farming products for crop health, soil vitality, and better yield.')
@section('seo_keywords', $pageSeo?->meta_keyword ?: '')

@section('body_class', 'shop-page no-product-motion')

@section('content')
  {{-- Additional Styles for Filters --}}
  <x-front-breadcrumb
    badge="Our Product Range"
    title="Shop Bio-Stimulants & Agri Solutions"
    description="Choose from our range of scientifically developed formulations crafted for modern farming. Trusted by farmers across India."
    :icon="asset('assets/images/flask-icon.svg')"
  />

  <section class="avan__section"
           data-no-motion
           data-cart-add-url="{{ route('cart.add') }}"
           data-wishlist-toggle-url="{{ route('wishlist.toggle') }}">
    <div class="container">

      <div class="row">
        <div class="col-12">
          <div class="avan__header">
            <div class="avan__header-top">

              <h3 class="avan__header-title">All Products</h3>
            </div>
            <p class="avan__header-desc">Proven formulations ready for field application</p>
          </div>
        </div>
      </div>

      <div class="row g-4 align-items-start">
        <div class="col-12 col-lg-3 shop__filters-col">
          <div class="shop__filters-backdrop" data-mobile-filter-close></div>
          <div class="shop__filters-card shop__filters-card--sidebar">
            <div class="shop__filters-header">
              <div>
                <span class="shop__filters-kicker">Filters</span>
                <h4 class="shop__filters-title">Refine products</h4>
              </div>
              <a href="{{ route('products.index') }}" class="shop__filters-reset">Reset</a>
              <button type="button"
                      class="shop__filters-close"
                      data-mobile-filter-close
                      aria-label="Close filters">
                <i class="ri-close-line"></i>
              </button>
            </div>
            <form method="GET" action="{{ route('products.index') }}" id="filterForm" data-product-filter-form>

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

                <div class="shop__filter-group shop__filter-group--sort">
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

        <div class="col-12 col-lg-9">
          <div class="shop__mobile-toolbar">
            <div class="shop__mobile-count">
              <strong>{{ $products->total() }} {{ Str::plural('product', $products->total()) }} found</strong>
              <span>Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}</span>
            </div>
            <div class="shop__mobile-controls">
              <select class="shop__mobile-sort" data-mobile-sort aria-label="Sort products">
                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price low to high</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price high to low</option>
              </select>
              <button type="button" class="shop__mobile-filter-btn" data-mobile-filter-open>
                <i class="ri-menu-3-line"></i>
                <span>Filters</span>
              </button>
            </div>
          </div>

          {{-- Results Info --}}
          @if(request()->hasAny(['search', 'category', 'brand', 'min_price', 'max_price']))
            <div class="row mb-3">
              <div class="col-12">
                <!-- <div class="shop__results-info">
                  <i class="ri-information-line"></i>
                  Showing {{ $products->count() }} of {{ $products->total() }} products
                  @if(request('search'))
                    for "<strong>{{ request('search') }}</strong>"
                  @endif
                </div> -->
              </div>
            </div>
          @endif

          <div class="row g-4" data-product-results>

        @php
          // ✅ Get wishlist product IDs for logged in customer
          $wishlistIds = [];
          if (Auth::guard('customer')->check()) {
              $wishlistIds = Auth::guard('customer')->user()
                  ->wishlists()->pluck('product_id')->toArray();
          }
        @endphp

        @forelse($products as $product)
        @php
          $visibleVariations = $product->variations->where('is_active', true);
        @endphp
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="shop__card">

            <div class="shop__img-wrap">
              <a href="{{ route('products.show', $product->slug ?? $product->id) }}"
                 class="shop__image-link"
                 aria-label="View {{ $product->name }}">
                @if($product->featured_image)
                  <img src="{{ Storage::url($product->featured_image) }}"
                       alt="{{ $product->name }}" class="shop__img">
                @else
                  <img src="{{ asset('assets/images/product-bottle.svg') }}"
                       alt="{{ $product->name }}" class="shop__img">
                @endif
              </a>

              {{-- Status Badge --}}
              @if($product->status === 'active')
                <span class="shop__badge shop__badge--available">Available</span>
              @else
                <span class="shop__badge shop__badge--soon">Coming Soon</span>
              @endif

              {{-- ✅ Heart Wishlist Button --}}
              @auth('customer')
              @php $isWishlisted = in_array($product->id, $wishlistIds); @endphp
              <button type="button"
                      class="shop__wishlist-btn wishlist-toggle {{ $isWishlisted ? 'wishlisted' : '' }}"
                      data-id="{{ $product->id }}"
                      title="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}">
                  <i class="{{ $isWishlisted ? 'ri-heart-fill' : 'ri-heart-line' }}" aria-hidden="true"></i>
              </button>
              @endauth

              {{-- ✅ Not logged in — redirect to login --}}
              @guest('customer')
              <a href="{{ route('customer.login') }}"
                 class="shop__wishlist-btn"
                 title="Login to add to wishlist">
                  <i class="ri-heart-line" aria-hidden="true"></i>
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

              @if($visibleVariations->count())
                <div class="shop__price-row">
                  <span class="shop__price-label">Price</span>
                  <span class="shop__price">₹{{ number_format($product->base_price, 2) }}</span>
                  <span class="shop__price-label shop__price-unit">/ {{ $product->unit ?? 'unit' }}</span>
                </div>
                <div class="shop__variation-row">
                  @foreach($visibleVariations as $var)
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
                <p class="shop__variants">{{ $visibleVariations->count() }} pack size(s) available</p>
              @else
                <div class="shop__price-row">
                  <span class="shop__price">₹{{ number_format($product->base_price, 2) }}</span>
                  <span class="shop__price-label shop__price-label--spaced">/ {{ $product->unit ?? 'unit' }}</span>
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
                    <i class="ri-shopping-cart-2-line" aria-hidden="true"></i>
                    <span>Add to Cart</span>
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
          <img src="assets/images/flask-icon.svg" alt="" class="shop__empty-icon">
          <p class="text-muted">No products available at the moment. Check back soon!</p>
        </div>
        @endforelse

          </div>

          <div data-product-pagination>
          @if($products->hasPages())
            <div class="row mt-5">
              <div class="col-12 d-flex justify-content-center">
                {{ $products->links() }}
              </div>
            </div>
          @endif
          </div>
        </div>
      </div>

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
