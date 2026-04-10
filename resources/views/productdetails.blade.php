@extends('layout.frontlayout')
@section('title', $product->meta_title ?? $product->name . ' – Bharat Biomer')

@push('meta')
  <meta name="description" content="{{ $product->meta_description ?? $product->short_description ?? 'Premium organic products from Bharat Biomer' }}">
  <meta name="keywords" content="{{ $product->meta_keyword ?? $product->name . ', organic, products' }}">
  <meta name="product:category" content="{{ $product->category->name ?? 'Products' }}">
  
  {{-- Open Graph Tags --}}
  <meta property="og:type" content="product">
  <meta property="og:title" content="{{ $product->meta_title ?? $product->name }}">
  <meta property="og:description" content="{{ $product->meta_description ?? $product->short_description ?? 'Premium organic product' }}">
  <meta property="og:url" content="{{ url()->current() }}">
  @if($product->featured_image)
    <meta property="og:image" content="{{ Storage::url($product->featured_image) }}">
  @endif
  
  {{-- Twitter Card Tags --}}
  <meta name="twitter:card" content="product">
  <meta name="twitter:title" content="{{ $product->meta_title ?? $product->name }}">
  <meta name="twitter:description" content="{{ $product->meta_description ?? $product->short_description ?? 'Premium organic product' }}">
  @if($product->featured_image)
    <meta name="twitter:image" content="{{ Storage::url($product->featured_image) }}">
  @endif
  
  {{-- Product Schema.org Structured Data --}}
  <script type="application/ld+json">
  {
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "description": "{{ $product->meta_description ?? $product->short_description ?? $product->description }}",
    "image": "{{ $product->featured_image ? Storage::url($product->featured_image) : asset('assets/images/product-bottle.svg') }}",
    "brand": {
      "@type": "Brand",
      "name": "{{ $product->brand->name ?? 'Bharat Biomer' }}"
    },
    "offers": {
      "@type": "Offer",
      "price": "{{ $product->base_price }}",
      "priceCurrency": "INR",
      "availability": "{{ $product->isInStock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
    },
    "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": "{{ $product->approvedReviews->count() > 0 ? number_format($product->approvedReviews->avg('rating'), 1) : '5' }}",
      "reviewCount": "{{ $product->approvedReviews->count() }}"
    }
  }
  </script>
@endpush

@section('content')
<div class="pd-page">

  <!-- ========================
       SECTION 1: Hero
  ======================== -->
  <section class="prodh__section">
    <div class="container">
      <div class="row">
        <div class="col-12 col-lg-8">

          <div class="prodh__badge mb-3">
            <img src="{{ asset('assets/images/flask-icon.svg') }}" alt="flask" class="prodh__badge-icon"/>
            <span class="prodh__badge-text">
              {{ $product->category->name ?? 'Product Details' }}
            </span>
          </div>

          <h1 class="prodh__heading">{{ $product->name }}</h1>
          <p class="prodh__desc">{{ $product->short_description }}</p>

        </div>
      </div>
    </div>
  </section>

  <!-- ========================
       SECTION 2: Product Detail
  ======================== -->
  <section class="avan__section">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="avan__card">
            <div class="row g-0">

              <!-- ── LEFT: Images ── -->
              <div class="col-12 col-md-5">

                {{-- Main Image --}}
                <div class="pd__img-main-wrap">
                  @if($product->featured_image)
                    <img src="{{ Storage::url($product->featured_image) }}"
                         alt="{{ $product->name }}"
                         class="avan__product-img"
                         id="mainImage">
                  @else
                    <img src="{{ asset('assets/images/product-bottle.svg') }}"
                         alt="{{ $product->name }}"
                         class="avan__product-img"
                         id="mainImage">
                  @endif
                </div>

                {{-- Gallery Thumbnails --}}
                @if($product->images->count())
                <div class="pd__thumbs">
                  @if($product->featured_image)
                    <img src="{{ Storage::url($product->featured_image) }}"
                         class="pd__thumb pd__thumb--active"
                         onclick="changeImage(this, '{{ Storage::url($product->featured_image) }}')">
                  @endif
                  @foreach($product->images as $img)
                    <img src="{{ Storage::url($img->image_path) }}"
                         class="pd__thumb"
                         onclick="changeImage(this, '{{ Storage::url($img->image_path) }}')">
                  @endforeach
                </div>
                @endif

              </div>

              <!-- ── RIGHT: Content ── -->
              <div class="col-12 col-md-7">
                <div class="avan__content">

                  {{-- Brand & Tags --}}
                  <div class="avan__tags-wrap mb-3">
                    @if($product->brand)
                      <span class="avan__tag">{{ $product->brand->name }}</span>
                    @endif
                    @foreach($product->tags as $tag)
                      <span class="avan__tag">{{ $tag->name }}</span>
                    @endforeach
                  </div>

                  <h3 class="avan__product-title">{{ $product->name }}</h3>

                  @if($product->technical_content)
                    <p class="pd__technical">{{ $product->technical_content }}</p>
                  @endif

                  @php
                    $visibleVariations = $product->variations->where('is_active', true);
                    if ($visibleVariations->isEmpty()) {
                        $visibleVariations = $product->variations;
                    }
                  @endphp

                  {{-- ── Price Box ── --}}
                  <div class="pd__price-box">
                    <span class="pd__price-label">Price</span>
                    <div class="pd__price-row">
                      <span class="pd__price" id="displayPrice">
                        ₹{{ number_format($product->base_price, 2) }}
                      </span>
                      <span class="pd__price-unit" id="priceUnit" style="font-size: 0.9rem; color: #7aab7a; margin-left: 4px;">
                        / {{ $product->unit ?? 'unit' }}
                      </span>
                      @if($product->variations->count())
                        <span class="pd__price-note" id="priceNote">Default pack</span>
                      @endif
                    </div>
                  </div>
                  <div class="pd__shipping-note" style="margin-top:0.8rem; font-size:0.9rem; color:#2d7a45;">
                      @if($product->shipping_charge > 0)
                          Shipping: ₹{{ number_format($product->shipping_charge, 2) }} per unit
                      @else
                          Free shipping available
                      @endif
                  </div>

                  {{-- ── Variation Selector ── --}}
                  @if($product->variations->count())
                  <div class="pd__variation-wrap">
                    {{-- Stock indicator --}}
                    @php
                      $defaultStock = $product->stock_quantity;
                    @endphp
                    <p class="pd__stock" id="stockInfo">
                      @if($defaultStock > 10)
                        <span class="pd__stock--in">✓ In Stock ({{ $defaultStock }} available)</span>
                      @elseif($defaultStock > 0)
                        <span class="pd__stock--low">⚠ Low Stock ({{ $defaultStock }} left)</span>
                      @else
                        <span class="pd__stock--out">✕ Out of Stock</span>
                      @endif
                    </p>

                    {{-- Visual Variant Cards --}}
                    <div class="pd__variant-cards-section mt-4">
                      <h5 class="avan__features-heading" style="font-size: 1rem; margin-bottom: 1.2rem;">Choose Your Pack Size</h5>
                      <div class="pd__variant-cards-grid">
                        @foreach($visibleVariations as $var)
                          <div class="pd__variant-card"
                               data-id="{{ $var->id }}"
                               data-price="{{ $var->price }}"
                               data-stock="{{ $var->stock_quantity }}"
                               data-value="{{ $var->attribute_value }}"
                               data-unit="{{ $var->unit ?? $product->unit }}"
                               data-image="{{ $var->image_path ? Storage::url($var->image_path) : '' }}"
                               onclick="selectVariation(this)">
                            @if($var->image_path)
                              <img src="{{ Storage::url($var->image_path) }}" alt="{{ $var->attribute_value }}" class="pd__variant-card-img">
                            @else
                              <img src="{{ asset('assets/images/product-bottle.svg') }}" alt="{{ $var->attribute_value }}" class="pd__variant-card-img">
                            @endif
                            <div class="pd__variant-card-info">
                              <p class="pd__variant-card-title">{{ $var->attribute_value }}</p>
                              <p class="pd__variant-card-price">₹{{ number_format($var->price, 2) }}<span class="pd__variant-card-unit">/ {{ $var->unit ?? $product->unit }}</span></p>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                  @else
                    {{-- No variations --}}
                    <p class="pd__stock">
                      <span class="pd__stock--in">✓ Available</span>
                    </p>
                  @endif

                  {{-- ── Key Features ── --}}
                  @if($product->description)
                  <h5 class="avan__features-heading mt-3">Description</h5>
                  <p class="avan__product-desc">{{ $product->description }}</p>
                  @endif

                  {{-- ── CTA Buttons ── --}}
                  <div class="pd__cta-wrap">
                    <button class="pd__cta-btn pd__cta-btn--primary" id="addToCartBtn"
                            data-product-id="{{ $product->id }}">
                      🛒 Add to Cart
                    </button>
                    <a href="{{ route('products.index') }}" class="pd__cta-btn pd__cta-btn--outline">
                      ← Back to Products
                    </a>
                  </div>

                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================
       SECTION 3: Key Features
  ======================== -->
  @if($product->technical_content || $product->tags->count())
  <section class="avan__section" style="padding-top:0;">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="avan__header">
            <div class="avan__header-top">
              <span class="avan__check">✓</span>
              <h3 class="avan__header-title">Product Details</h3>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3">

        @if($product->technical_content)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="avan__feature-item">
            <div class="avan__feature-icon-wrap">
              <img src="{{ asset('assets/images/dosage-icon.svg') }}" alt="Technical" class="avan__feature-icon"/>
            </div>
            <div>
              <p class="avan__feature-title">Technical Content</p>
              <p class="avan__feature-desc">{{ $product->technical_content }}</p>
            </div>
          </div>
        </div>
        @endif

        @if($product->brand)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="avan__feature-item">
            <div class="avan__feature-icon-wrap">
              <img src="{{ asset('assets/images/compatible-icon.svg') }}" alt="Brand" class="avan__feature-icon"/>
            </div>
            <div>
              <p class="avan__feature-title">Brand</p>
              <p class="avan__feature-desc">{{ $product->brand->name }}</p>
            </div>
          </div>
        </div>
        @endif

        @if($product->category)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="avan__feature-item">
            <div class="avan__feature-icon-wrap">
              <img src="{{ asset('assets/images/multicrop-icon.svg') }}" alt="Category" class="avan__feature-icon"/>
            </div>
            <div>
              <p class="avan__feature-title">Category</p>
              <p class="avan__feature-desc">{{ $product->category->name }}</p>
            </div>
          </div>
        </div>
        @endif

        @if($product->variations->count())
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="avan__feature-item">
            <div class="avan__feature-icon-wrap">
              <img src="{{ asset('assets/images/foliar-icon.svg') }}" alt="Variants" class="avan__feature-icon"/>
            </div>
            <div>
              <p class="avan__feature-title">Pack Sizes</p>
              <p class="avan__feature-desc">{{ $product->variations->count() }} options available</p>
            </div>
          </div>
        </div>
        @endif

      </div>

      {{-- Tags / Crop Suitability --}}
      @if($product->tags->count())
      <div class="row mt-4">
        <div class="col-12">
          <h5 class="avan__features-heading">Tags</h5>
          <div class="avan__tags-wrap">
            @foreach($product->tags as $tag)
              <span class="avan__tag">{{ $tag->name }}</span>
            @endforeach
          </div>
        </div>
      </div>
      @endif

    </div>
  </section>
  @endif

  <!-- ========================
       SECTION 4: Pipeline
  ======================== -->
  <section class="ppip__section">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="ppip__header-top">
            <img src="{{ asset('assets/images/clock-icon.svg') }}" alt="clock" class="ppip__header-icon"/>
            <h3 class="ppip__header-title">More Coming Soon</h3>
          </div>
          <p class="ppip__header-desc">Next-generation solutions under development</p>
        </div>
      </div>
      <div class="row g-4 mt-2">
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ppip__card">
            <span class="ppip__badge">Coming Soon</span>
            <div class="ppip__icon-wrap">
              <img src="{{ asset('assets/images/fertilizer-icon.svg') }}" alt="Smart Fertilizers" class="ppip__icon"/>
            </div>
            <h4 class="ppip__card-title">Smart Fertilizers</h4>
            <p class="ppip__card-desc">Intelligent nutrient delivery with controlled release</p>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ppip__card">
            <span class="ppip__badge">Coming Soon</span>
            <div class="ppip__icon-wrap">
              <img src="{{ asset('assets/images/consortia-icon.svg') }}" alt="Microbial Consortia" class="ppip__icon"/>
            </div>
            <h4 class="ppip__card-title">Microbial Consortia</h4>
            <p class="ppip__card-desc">Advanced multi-strain formulations for soil health</p>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ppip__card">
            <span class="ppip__badge">Coming Soon</span>
            <div class="ppip__icon-wrap">
              <img src="{{ asset('assets/images/biopolymer-icon.svg') }}" alt="Biopolymer" class="ppip__icon"/>
            </div>
            <h4 class="ppip__card-title">Biopolymer Inputs</h4>
            <p class="ppip__card-desc">Sustainable polymer-based agri enhancement</p>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ppip__card">
            <span class="ppip__badge">Coming Soon</span>
            <div class="ppip__icon-wrap">
              <img src="{{ asset('assets/images/climate-icon.svg') }}" alt="Climate" class="ppip__icon"/>
            </div>
            <h4 class="ppip__card-title">Climate-Resilient</h4>
            <p class="ppip__card-desc">Formulations for extreme weather stress</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================
       SECTION 5: Reviews & Ratings
  ======================== -->
  @php
    $approvedReviews = $product->approvedReviews()->with('customer')->latest()->get();
    $avgRating       = $product->avg_rating;
    $reviewCount     = $product->review_count;
    $isLoggedIn      = Auth::guard('customer')->check();
    $alreadyReviewed = $isLoggedIn
        ? $product->reviews()->where('customer_id', Auth::guard('customer')->id())->exists()
        : false;
  @endphp

  <section class="avan__section" style="padding-top:0;">
    <div class="container">

      <div class="avan__header">
        <div class="avan__header-top">
          <span class="avan__check">★</span>
          <h3 class="avan__header-title">Customer Reviews</h3>
        </div>
      </div>

      <div class="row g-4">

        {{-- ── Left: Summary ── --}}
        <div class="col-12 col-md-4">
          <div class="rv__summary-card">
            <div class="rv__avg-score">{{ number_format($avgRating, 1) }}</div>
            <div class="rv__stars-row">
              @for($i = 1; $i <= 5; $i++)
                @if($i <= floor($avgRating))
                  <i class="ri-star-fill rv__star rv__star--filled"></i>
                @elseif($i - $avgRating < 1)
                  <i class="ri-star-half-fill rv__star rv__star--filled"></i>
                @else
                  <i class="ri-star-line rv__star"></i>
                @endif
              @endfor
            </div>
            <p class="rv__total-label">{{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</p>

            {{-- Rating bars --}}
            @for($star = 5; $star >= 1; $star--)
              @php $cnt = $product->approvedReviews()->where('rating', $star)->count(); @endphp
              <div class="rv__bar-row">
                <span class="rv__bar-label">{{ $star }} <i class="ri-star-fill rv__star rv__star--filled fs-10"></i></span>
                <div class="rv__bar-track">
                  <div class="rv__bar-fill" style="width:{{ $reviewCount > 0 ? round(($cnt/$reviewCount)*100) : 0 }}%"></div>
                </div>
                <span class="rv__bar-count">{{ $cnt }}</span>
              </div>
            @endfor
          </div>
        </div>

        {{-- ── Right: Reviews list + form ── --}}
        <div class="col-12 col-md-8">

          {{-- Submit form --}}
          @if($isLoggedIn && !$alreadyReviewed)
          <div class="rv__form-card mb-4" id="reviewFormWrap">
            <h5 class="rv__form-title">Write a Review</h5>
            
            {{-- Star Rating Section --}}
            <div class="rv__rating-section mb-4">
              <label class="rv__rating-label">Rating <span class="text-danger">*</span></label>
              <div class="rv__star-picker mt-2" id="starPicker">
                @for($i = 1; $i <= 5; $i++)
                  <i class="ri-star-line rv__pick-star" data-value="{{ $i }}" id="pickStar{{ $i }}" title="Click to rate"></i>
                @endfor
                <span class="rv__pick-label ms-3" id="starLabel">Select rating</span>
              </div>
              <small class="rv__rating-hint" id="ratingHint">Click on stars to rate this product</small>
            </div>

            <textarea id="reviewText" class="rv__textarea form-control mb-3" rows="3"
                      placeholder="Share your experience with this product (optional)…" maxlength="1000"></textarea>
            <button class="pd__cta-btn pd__cta-btn--primary" id="submitReviewBtn" style="width:auto;padding:10px 28px;">
              Submit Review
            </button>
            <div id="reviewMsg" class="mt-2 fw-medium" style="display:none;"></div>
          </div>
          @elseif($isLoggedIn && $alreadyReviewed)
          <div class="rv__already-msg mb-4">
            <i class="ri-checkbox-circle-fill text-success me-2"></i> You've already reviewed this product. Thank you!
          </div>
          @else
          <div class="rv__login-prompt mb-4">
            <i class="ri-lock-line me-1"></i>
            <a href="{{ route('customer.login') }}" class="text-primary-600 fw-medium">Login</a> to write a review.
          </div>
          @endif

          {{-- Reviews list --}}
          @forelse($approvedReviews as $rev)
          <div class="rv__item">
            <div class="rv__item-header">
              <div class="rv__avatar">{{ strtoupper(substr($rev->customer->name ?? 'U', 0, 1)) }}</div>
              <div>
                <p class="rv__name">{{ $rev->customer->name ?? 'Customer' }}</p>
                <div class="d-flex align-items-center gap-1">
                  @for($i = 1; $i <= 5; $i++)
                    <i class="ri-star-{{ $i <= $rev->rating ? 'fill' : 'line' }} rv__star rv__star--sm {{ $i <= $rev->rating ? 'rv__star--filled' : '' }}"></i>
                  @endfor
                  <span class="rv__date ms-2">{{ $rev->created_at->diffForHumans() }}</span>
                </div>
              </div>
            </div>
            @if($rev->review_text)
            <p class="rv__text">{{ $rev->review_text }}</p>
            @endif
          </div>
          @empty
          <p class="text-secondary-light">No reviews yet. Be the first to review this product!</p>
          @endforelse

        </div>
      </div>
    </div>
</section>

</div>
@endsection


@push('styles')
<style>
  /* ── Animation for validation feedback ────────────── */
  @keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    75% { transform: translateX(4px); }
  }

  /* ── Image Gallery ─────────────────────────── */
  .pd__img-main-wrap {
    position: relative;
    overflow: hidden;
  }
  .pd__thumbs {
    display: flex;
    gap: 10px;
    padding: 12px 16px;
    flex-wrap: wrap;
  }
  .pd__thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e8f0e4;
    cursor: pointer;
    transition: border-color 0.2s;
  }
  .pd__thumb--active,
  .pd__thumb:hover {
    border-color: #2d7a45;
  }

  /* ── Price Box ─────────────────────────────── */
  .pd__price-box {
    background: #f4faf0;
    border: 1px solid #c8e6c9;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 20px;
    display: inline-block;
    min-width: 200px;
  }
  .pd__price-label {
    font-size: 0.78rem;
    color: #7aab7a;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    display: block;
    margin-bottom: 2px;
  }
  .pd__price-row {
    display: flex;
    align-items: baseline;
    gap: 8px;
  }
  .pd__price {
    font-size: 2rem;
    font-weight: 800;
    color: #2d7a45;
    line-height: 1;
  }
  .pd__price-note {
    font-size: 0.78rem;
    color: #9aab9a;
  }

  /* ── Technical label ───────────────────────── */
  .pd__technical {
    font-size: 0.85rem;
    color: #7aab7a;
    margin-bottom: 12px;
    font-style: italic;
  }

  /* ── Variation Buttons ─────────────────────── */
  .pd__variation-wrap {
    margin-bottom: 18px;
  }
  .pd__variation-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 10px;
  }
  .pd__var-btn {
    padding: 8px 20px;
    border-radius: 8px;
    border: 2px solid #c8e6c9;
    background: #fff;
    color: #2d7a45;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .pd__var-btn:hover {
    border-color: #2d7a45;
    background: #f4faf0;
  }
  .pd__var-btn--active {
    border-color: #2d7a45;
    background: #2d7a45;
    color: #fff;
  }

  /* ── Variant Cards Grid ────────────────────── */
  .pd__variant-cards-section {
    padding-top: 1.5rem;
    border-top: 1px solid #e8f0e4;
  }
  .pd__variant-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 12px;
    margin-top: 12px;
  }
  .pd__variant-card {
    background: #fff;
    border: 2px solid #e8f0e4;
    border-radius: 12px;
    padding: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
  }
  .pd__variant-card:hover {
    border-color: #2d7a45;
    box-shadow: 0 4px 12px rgba(45, 122, 69, 0.15);
    transform: translateY(-2px);
  }
  .pd__variant-card-img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 8px;
  }
  .pd__variant-card-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #2d7a45;
    margin-bottom: 4px;
  }
  .pd__variant-card-price {
    font-size: 1.1rem;
    font-weight: 800;
    color: #2d7a45;
    margin-bottom: 4px;
  }
  .pd__variant-card-unit {
    font-size: 0.75rem;
    font-weight: 600;
    color: #7aab7a;
  }
  .pd__variant-card-stock {
    font-size: 0.75rem;
    margin-bottom: 0;
  }

  /* ── Stock ─────────────────────────────────── */
  .pd__stock {
    font-size: 0.85rem;
    margin-bottom: 0;
  }
  .pd__stock--in   { color: #2d7a45; font-weight: 600; }
  .pd__stock--low  { color: #b45309; font-weight: 600; }
  .pd__stock--out  { color: #dc3545; font-weight: 600; }

  /* ── CTA Buttons ───────────────────────────── */
  .pd__cta-wrap {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 24px;
  }
  .pd__cta-btn {
    padding: 12px 28px;
    border-radius: 10px;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s ease;
  }
  .pd__cta-btn--primary {
    background: #2d7a45;
    color: #fff;
    flex: 1;
  }
  .pd__cta-btn--primary:hover { background: #245e36; }
  .pd__cta-btn--outline {
    background: transparent;
    color: #2d7a45;
    border: 2px solid #2d7a45;
  }
  .pd__cta-btn--outline:hover { background: #f4faf0; }

  /* ── Reviews ─────────────────────────────────── */
  .rv__summary-card {
    background: #f4faf0;
    border: 1px solid #c8e6c9;
    border-radius: 16px;
    padding: 24px 20px;
    text-align: center;
  }
  .rv__avg-score {
    font-size: 3.5rem;
    font-weight: 800;
    color: #2d7a45;
    line-height: 1;
    margin-bottom: 8px;
  }
  .rv__stars-row { display: flex; justify-content: center; gap: 3px; margin-bottom: 6px; }
  .rv__star { font-size: 1.1rem; color: #d1d5db; }
  .rv__star--filled { color: #f59e0b; }
  .rv__star--sm { font-size: 0.85rem; }
  .rv__total-label { font-size: 0.82rem; color: #6b7280; margin-bottom: 16px; }
  .rv__bar-row { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
  .rv__bar-label { font-size: 0.78rem; color: #4b5563; width: 32px; text-align: right; white-space: nowrap; }
  .rv__bar-track { flex: 1; height: 7px; background: #e5e7eb; border-radius: 99px; overflow: hidden; }
  .rv__bar-fill { height: 100%; background: #f59e0b; border-radius: 99px; transition: width .5s ease; }
  .rv__bar-count { font-size: 0.75rem; color: #9ca3af; width: 16px; }

  .rv__form-card {
    background: #fff;
    border: 1px solid #c8e6c9;
    border-radius: 14px;
    padding: 20px 22px;
  }
  .rv__form-title { color: #2d7a45; font-weight: 700; margin-bottom: 12px; font-size: 1rem; }
  
  /* ── Rating Section ─────────────────────────────── */
  .rv__rating-section { 
    background: #f9fcf8;
    padding: 16px;
    border-radius: 12px;
    border-left: 4px solid #f59e0b;
  }
  .rv__rating-label { 
    display: block;
    font-size: 0.95rem;
    font-weight: 700;
    color: #2d7a45;
    margin-bottom: 8px;
  }
  
  .rv__star-picker { 
    display: flex; 
    align-items: center; 
    gap: 6px;
  }
  .rv__pick-star { 
    font-size: 2rem;
    color: #d1d5db;
    cursor: pointer;
    transition: color 0.2s, transform 0.15s;
    display: inline-block;
    line-height: 1;
  }
  .rv__pick-star:hover { 
    color: #fbbf24;
    transform: scale(1.2);
  }
  .rv__pick-star.active { 
    color: #f59e0b;
  }
  .rv__pick-label { 
    font-size: 0.95rem;
    font-weight: 600;
    color: #2d7a45;
    min-width: 140px;
  }
  .rv__rating-hint {
    display: block;
    margin-top: 8px;
    color: #6b7280;
    font-size: 0.8rem;
    font-style: italic;
  }
  
  .rv__textarea { border: 1.5px solid #c8e6c9; border-radius: 10px; resize: vertical; font-size: 0.9rem; }
  .rv__textarea:focus { border-color: #2d7a45; box-shadow: 0 0 0 3px rgba(45,122,69,.12); outline: none; }

  .rv__item {
    border-bottom: 1px solid #e8f0e4;
    padding: 16px 0;
  }
  .rv__item:last-child { border-bottom: none; }
  .rv__item-header { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 8px; }
  .rv__avatar {
    width: 42px; height: 42px;
    background: #2d7a45;
    color: #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; font-weight: 700; flex-shrink: 0;
  }
  .rv__name { font-weight: 600; color: #1f2937; margin: 0 0 2px; font-size: 0.95rem; }
  .rv__date { font-size: 0.75rem; color: #9ca3af; }
  .rv__text { font-size: 0.9rem; color: #4b5563; margin: 0; line-height: 1.6; padding-left: 54px; }

  .rv__login-prompt, .rv__already-msg {
    background: #f4faf0;
    border: 1px solid #c8e6c9;
    border-radius: 10px;
    padding: 14px 18px;
    font-size: 0.9rem;
    color: #374151;
  }
</style>
@endpush

@push('scripts')
<script>
  // ── Variation selector ───────────────────────────────────────────────
  function selectVariation(el) {
    const variantId = el.dataset.id || el.dataset.variantId;
    if (!variantId) {
      return;
    }

    document.querySelectorAll('.pd__variant-card').forEach(card => {
      if (card.dataset.variantId == variantId || card.dataset.id == variantId) {
        card.classList.add('pd__variant-card--active');
        card.style.borderColor = '#2d7a45';
        card.style.backgroundColor = '#f9fcf8';
      } else {
        card.classList.remove('pd__variant-card--active');
        card.style.borderColor = '#e8f0e4';
        card.style.backgroundColor = '#fff';
      }
    });

    const price = parseFloat(el.dataset.price).toLocaleString('en-IN', {
      minimumFractionDigits: 2, maximumFractionDigits: 2
    });
    document.getElementById('displayPrice').textContent = '₹' + price;

    const unit = el.dataset.unit || 'unit';
    const priceUnitEl = document.getElementById('priceUnit');
    if (priceUnitEl) {
      priceUnitEl.textContent = '/ ' + unit;
    }

    document.getElementById('priceNote') && (document.getElementById('priceNote').textContent = el.dataset.value);

    const stock = parseInt(el.dataset.stock);
    const stockEl = document.getElementById('stockInfo');
    if (stock > 10) {
      stockEl.innerHTML = `<span class="pd__stock--in">✓ In Stock (${stock} available)</span>`;
    } else if (stock > 0) {
      stockEl.innerHTML = `<span class="pd__stock--low">⚠ Low Stock (${stock} left)</span>`;
    } else {
      stockEl.innerHTML = `<span class="pd__stock--out">✕ Out of Stock</span>`;
    }

    if (el.dataset.image) {
      document.getElementById('mainImage').src = el.dataset.image;
      document.querySelectorAll('.pd__thumb').forEach(t => t.classList.remove('pd__thumb--active'));
    }

    document.getElementById('addToCartBtn').dataset.variationId = variantId;
  }

  // ── Thumbnail gallery ────────────────────────────────────────────────
  function changeImage(thumb, src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.pd__thumb').forEach(t => t.classList.remove('pd__thumb--active'));
    thumb.classList.add('pd__thumb--active');
  }

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

  // ── Add to Cart ──────────────────────────────────────────────────────
  document.getElementById('addToCartBtn').addEventListener('click', function () {
    const productId   = this.dataset.productId;
    const variationId = this.dataset.variationId || null;

    fetch('/cart/add', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ product_id: productId, variation_id: variationId, quantity: 1 })
    })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        const btn = document.getElementById('addToCartBtn');
        btn.textContent = '✓ Added to Cart!';
        btn.style.background = '#4caf72';
        if (d.cart_count !== undefined) {
          updateGlobalCartBadge(d.cart_count);
        }
        setTimeout(() => {
          btn.textContent = '🛒 Add to Cart';
          btn.style.background = '';
        }, 2500);
      }
    })
    .catch(() => alert('Could not add to cart. Please try again.'));
  });

  // ── Star Picker ──────────────────────────────────────────────────────
  const pickStars = document.querySelectorAll('.rv__pick-star');
  let selectedRating = 0;
  const starLabels = ['','⭐ Terrible','⭐⭐ Poor','⭐⭐⭐ Average','⭐⭐⭐⭐ Good','⭐⭐⭐⭐⭐ Excellent'];

  pickStars.forEach(star => {
    star.addEventListener('mouseover', () => {
      const val = parseInt(star.dataset.value);
      pickStars.forEach((s, idx) => {
        const starValue = parseInt(s.dataset.value);
        if (starValue <= val) {
          s.classList.add('active');
          s.classList.remove('ri-star-line');
          s.classList.add('ri-star-fill');
        } else {
          s.classList.remove('active');
          s.classList.remove('ri-star-fill');
          s.classList.add('ri-star-line');
        }
      });
      // Show hover label
      document.getElementById('starLabel').textContent = starLabels[val];
      document.getElementById('starLabel').style.color = '#f59e0b';
    });

    star.addEventListener('mouseout', () => {
      pickStars.forEach(s => {
        const v = parseInt(s.dataset.value);
        s.classList.toggle('active', v <= selectedRating);
        if (v <= selectedRating) {
          s.classList.remove('ri-star-line');
          s.classList.add('ri-star-fill');
        } else {
          s.classList.remove('ri-star-fill');
          s.classList.add('ri-star-line');
        }
      });
      // Reset label if no selection
      if (selectedRating === 0) {
        document.getElementById('starLabel').textContent = 'Select rating';
        document.getElementById('starLabel').style.color = '#6b7280';
      }
    });

    star.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      const val = parseInt(star.dataset.value);
      selectedRating = val;
      
      // Update all stars to show selection
      pickStars.forEach(s => {
        const sVal = parseInt(s.dataset.value);
        if (sVal <= val) {
          s.classList.add('active');
          s.classList.remove('ri-star-line');
          s.classList.add('ri-star-fill');
        } else {
          s.classList.remove('active');
          s.classList.remove('ri-star-fill');
          s.classList.add('ri-star-line');
        }
      });
      
      // Update label with selected value
      document.getElementById('starLabel').textContent = starLabels[val];
      document.getElementById('starLabel').style.color = '#2d7a45';
      
      // Visual feedback for selection
      const ratingHint = document.getElementById('ratingHint');
      if (ratingHint) {
        ratingHint.textContent = `✓ Rating selected: ${val} star${val !== 1 ? 's' : ''}`;
        ratingHint.style.color = '#2d7a45';
        ratingHint.style.fontWeight = '600';
      }
    });
  });

  // Initialize first star display
  if (pickStars.length > 0) {
    pickStars.forEach(s => {
      s.classList.remove('ri-star-fill');
      s.classList.add('ri-star-line');
    });
  }

  // ── Submit review ────────────────────────────────────────────────────
  const submitBtn = document.getElementById('submitReviewBtn');
  if (submitBtn) {
    submitBtn.addEventListener('click', function () {
      if (!selectedRating) {
        document.getElementById('starLabel').textContent = '⚠ Please select a rating!';
        document.getElementById('starLabel').style.color = '#dc3545';
        document.getElementById('ratingHint').textContent = 'You must select a star rating before submitting';
        document.getElementById('ratingHint').style.color = '#dc3545';
        document.getElementById('ratingHint').style.fontWeight = '600';
        // Add shake animation
        const starPicker = document.getElementById('starPicker');
        starPicker.style.animation = 'none';
        setTimeout(() => { starPicker.style.animation = 'shake 0.3s'; }, 10);
        return;
      }
      const text    = document.getElementById('reviewText').value;
      const msgEl   = document.getElementById('reviewMsg');
      submitBtn.disabled = true;
      submitBtn.textContent = 'Submitting…';

      fetch('{{ route("reviews.store", $product) }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ rating: selectedRating, review_text: text })
      })
      .then(r => r.json())
      .then(d => {
        msgEl.style.display = 'block';
        if (d.success) {
          msgEl.style.color = '#2d7a45';
          msgEl.textContent = d.message;
          document.getElementById('reviewFormWrap').style.opacity = '0.6';
          submitBtn.textContent = '✓ Submitted';
        } else {
          msgEl.style.color = '#dc3545';
          msgEl.textContent = d.message;
          submitBtn.disabled = false;
          submitBtn.textContent = 'Submit Review';
        }
      })
      .catch(() => {
        msgEl.style.display = 'block';
        msgEl.style.color = '#dc3545';
        msgEl.textContent = 'Something went wrong. Please try again.';
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit Review';
      });
    });
  }
</script>
@endpush
