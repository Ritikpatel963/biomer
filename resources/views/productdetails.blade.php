@extends('layout.frontlayout')
@section('title', $product->meta_title ?? $product->name . ' – Bharat Biomer')

@php
  $featuredImageUrl = $product->featured_image ? Storage::url($product->featured_image) : null;
@endphp

@push('meta')
  <meta name="description" content="{{ $product->meta_description ?? $product->short_description ?? 'Premium organic products from Bharat Biomer' }}">
  <meta name="keywords" content="{{ $product->meta_keyword ?? $product->name . ', organic, products' }}">
  <meta name="product:category" content="{{ $product->category->name ?? 'Products' }}">
  
  {{-- Open Graph Tags --}}
  <meta property="og:type" content="product">
  <meta property="og:title" content="{{ $product->meta_title ?? $product->name }}">
  <meta property="og:description" content="{{ $product->meta_description ?? $product->short_description ?? 'Premium organic product' }}">
  <meta property="og:url" content="{{ url()->current() }}">
  @if($featuredImageUrl)
    <meta property="og:image" content="{{ url($featuredImageUrl) }}">
  @endif
  
  {{-- Twitter Card Tags --}}
  <meta name="twitter:card" content="product">
  <meta name="twitter:title" content="{{ $product->meta_title ?? $product->name }}">
  <meta name="twitter:description" content="{{ $product->meta_description ?? $product->short_description ?? 'Premium organic product' }}">
  @if($featuredImageUrl)
    <meta name="twitter:image" content="{{ url($featuredImageUrl) }}">
  @endif
  
  {{-- Product Schema.org Structured Data --}}
  <script type="application/ld+json">
  {
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "description": "{{ $product->meta_description ?? $product->short_description ?? $product->description }}",
    "image": "{{ $featuredImageUrl ? url($featuredImageUrl) : asset('assets/images/product-bottle.svg') }}",
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
<div class="pd-page pd-no-motion" data-no-motion
     data-cart-add-url="{{ route('cart.add') }}"
     data-review-store-url="{{ route('reviews.store', $product) }}">

  <!-- ========================
       SECTION 1: Hero
  ======================== -->
  <x-front-breadcrumb
    :badge="$product->category->name ?? 'Product Details'"
    :title="$product->name"
    :icon="asset('assets/images/flask-icon.svg')"
  />

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
              <div class="col-12 col-md-5 pd__gallery-column">

                {{-- Main Image --}}
                <div class="pd__img-main-wrap">
                  @if($featuredImageUrl)
                    <img src="{{ $featuredImageUrl }}"
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
                  @if($featuredImageUrl)
                    <img src="{{ $featuredImageUrl }}"
                         class="pd__thumb pd__thumb--active"
                         onclick="changeImage(this, '{{ $featuredImageUrl }}')">
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

                  <h1 class="avan__product-title">{{ $product->name }}</h1>

                  @if($product->short_description)
                    <p class="pd__short-desc">{{ $product->short_description }}</p>
                  @endif

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


                  {{-- ── CTA Buttons ── --}}
                  <div class="pd__cta-wrap">
                    <button class="pd__cta-btn pd__cta-btn--primary" id="addToCartBtn"
                            data-product-id="{{ $product->id }}">
                      <i class="ri-shopping-cart-2-line btn-icon" aria-hidden="true"></i>
                      <span>Add to Cart</span>
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
      <div class="avan__header">
        <div class="avan__header-top">
          <svg class="avan__check" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #2d7a45; flex-shrink: 0;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
          <h3 class="avan__header-title">Product Details</h3>
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

  @if($product->description)
  <section class="avan__section" style="padding-top:0;">
    <div class="container">
      <div class="avan__header">
        <div class="avan__header-top">
          <svg class="avan__check" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #2d7a45; flex-shrink: 0;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
          <h2 class="avan__header-title" style="font-size:1.5rem;">Product Description</h2>
        </div>
      </div>
      <div class="avan__product-desc" style="max-width:100%;">
        {!! \App\Services\HtmlSanitizer::clean($product->description) !!}
      </div>
    </div>
  </section>
  @endif

  @if($product->faqs->count())
  <section class="avan__section" style="padding-top:0;">
    <div class="container">
      <div class="avan__header">
        <div class="avan__header-top">
          <svg class="avan__check" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #2d7a45; flex-shrink: 0;"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
          <h2 class="avan__header-title" style="font-size:1.5rem;">Frequently Asked Questions</h2>
        </div>
      </div>
      <div class="pd__faq-list">
        @foreach($product->faqs as $faq)
        <details class="pd__faq-item">
          <summary class="pd__faq-question">{{ $faq->question }}</summary>
          <div class="pd__faq-answer">{{ $faq->answer }}</div>
        </details>
        @endforeach
      </div>
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
                      placeholder="Share your experience with this product" minlength="3" maxlength="1000" required></textarea>
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