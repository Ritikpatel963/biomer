@extends('layout.frontlayout')

@section('title', $blog->meta_title ?: $blog->title . ' - Bharat Biomer')
@section('seo_description', $blog->meta_description ?: Str::limit(strip_tags($blog->description), 160))
@section('seo_keywords', $blog->meta_tags)

@php
    $isLoggedIn = (bool) $customer;
    $reviewCount = $reviews->count();
    $avgRating = $reviewCount ? round($reviews->avg('rating'), 1) : 0;
@endphp

@section('content')
<section class="abth__section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9 text-center">
                <h1 class="abth__heading">{{ $blog->title }}</h1>
                <p class="abth__desc">By {{ $blog->author ?? 'Bharat Biomer' }} • {{ $blog->created_at->format('M d, Y') }} • {{ $blog->reading_time ?? 5 }} min read</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm overflow-hidden mb-4">
                    @if($blog->thumbnail)
                        <img src="{{ $blog->thumbnail_url }}" alt="{{ $blog->title }}" class="img-fluid w-100">
                    @endif
                    <div class="card-body p-4">
                        {!! \App\Services\HtmlSanitizer::clean($blog->description) !!}
                    </div>
                    <div class="px-4 pb-4">
                        <div class="blog-share-bar">
                            <span class="blog-share-bar__label">Share this article</span>
                            <div class="blog-share-bar__links">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="blog-share-bar__link"><i class="ri-facebook-fill"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->title) }}" target="_blank" rel="noopener" class="blog-share-bar__link"><i class="ri-twitter-x-line"></i></a>
                                <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . url()->current()) }}" target="_blank" rel="noopener" class="blog-share-bar__link"><i class="ri-whatsapp-line"></i></a>
                                <button type="button" class="blog-share-bar__link border-0" onclick="navigator.clipboard.writeText('{{ url()->current() }}')">
                                    <i class="ri-share-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="blog-review-card card border-0 shadow-sm p-4 mb-4" id="blog-review-form">
                    <div class="avan__header mb-4">
                        <div class="avan__header-top">
                            <span class="avan__check">★</span>
                            <h3 class="avan__header-title mb-0">Blog Reviews</h3>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-12 col-md-4">
                            <div class="rv__summary-card">
                                <div class="rv__avg-score">{{ number_format($avgRating, 1) }}</div>
                                <div class="rv__stars-row">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="ri-star-{{ $i <= floor($avgRating) ? 'fill rv__star--filled' : 'line' }} rv__star"></i>
                                    @endfor
                                </div>
                                <p class="rv__total-label">{{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</p>

                                @for($star = 5; $star >= 1; $star--)
                                    @php $count = $reviews->where('rating', $star)->count(); @endphp
                                    <div class="rv__bar-row">
                                        <span class="rv__bar-label">{{ $star }} <i class="ri-star-fill rv__star rv__star--filled fs-10"></i></span>
                                        <div class="rv__bar-track">
                                            <div class="rv__bar-fill" style="width:{{ $reviewCount > 0 ? round(($count / $reviewCount) * 100) : 0 }}%"></div>
                                        </div>
                                        <span class="rv__bar-count">{{ $count }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div class="col-12 col-md-8">
                            @if($isLoggedIn && !$alreadyReviewed)
                                <div class="rv__form-card mb-4">
                                    <h5 class="rv__form-title">Write a Review</h5>

                                    <form action="{{ route('frontend.blog.reviews.store', $blog->id) }}" method="POST" class="row g-3">
                                        @csrf
                                        <input type="hidden" name="rating" id="blogRatingInput" value="{{ old('rating') }}">

                                        <div class="col-12">
                                            <div class="rv__rating-section">
                                                <label class="rv__rating-label">Rating <span class="text-danger">*</span></label>
                                                <div class="rv__star-picker mt-2" id="blogStarPicker">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="ri-star-line rv__pick-star" data-value="{{ $i }}" title="Click to rate"></i>
                                                    @endfor
                                                    <span class="rv__pick-label ms-3" id="blogStarLabel">Select rating</span>
                                                </div>
                                                <small class="rv__rating-hint" id="blogRatingHint">Click on stars to rate this blog</small>
                                                @error('rating')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" value="{{ $customer->name }}" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" value="{{ $customer->email }}" readonly>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Comment</label>
                                            <textarea name="comment" class="rv__textarea form-control" rows="4" maxlength="1000" required>{{ old('comment') }}</textarea>
                                            @error('comment')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        {{-- Enable reCAPTCHA again on production.
                                        <div class="col-12">
                                            <label class="form-label d-block">Security Check</label>
                                            @if(config('services.recaptcha.site_key'))
                                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                            @else
                                                <div class="text-danger small">reCAPTCHA is not configured yet. Please add RECAPTCHA_SITE_KEY and RECAPTCHA_SECRET_KEY.</div>
                                            @endif
                                            @error('g-recaptcha-response')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            @error('recaptcha')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        --}}

                                        <div class="col-12">
                                            <button type="submit" class="pd__cta-btn pd__cta-btn--primary" id="blogSubmitReviewBtn" style="width:auto;padding:10px 28px;">
                                                Submit Review
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @elseif($isLoggedIn)
                                <div class="rv__already-msg mb-4">
                                    <i class="ri-checkbox-circle-fill text-success me-2"></i> You have already submitted a review for this blog. Thank you!
                                </div>
                            @else
                                <div class="rv__login-prompt mb-4">
                                    <i class="ri-lock-line me-1"></i>
                                    <a href="{{ route('customer.login', ['redirect' => route('frontend.blog.show', $blog->slug) . '#blog-review-form']) }}" class="text-primary-600 fw-medium">Login</a> to write a review.
                                </div>
                            @endif

                            @forelse($reviews as $review)
                                <div class="rv__item">
                                    <div class="rv__item-header">
                                        <div class="rv__avatar">{{ strtoupper(substr($review->name ?? 'U', 0, 1)) }}</div>
                                        <div>
                                            <p class="rv__name">{{ $review->name }}</p>
                                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="ri-star-{{ $i <= $review->rating ? 'fill' : 'line' }} rv__star rv__star--sm {{ $i <= $review->rating ? 'rv__star--filled' : '' }}"></i>
                                                @endfor
                                                <span class="rv__date ms-2">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="rv__text">{{ $review->comment }}</p>
                                </div>
                            @empty
                                <p class="text-secondary-light">No reviews yet. Be the first to review this blog!</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h4 class="h6 mb-3">Latest Posts</h4>
                    <ul class="list-unstyled mb-0">
                        @foreach($recentBlogs as $post)
                            <li class="mb-3">
                                <a href="{{ route('frontend.blog.show', $post->slug) }}" class="text-dark text-decoration-none">
                                    {{ $post->title }}
                                </a>
                                <p class="text-secondary small mb-0">{{ $post->created_at->format('M d, Y') }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="card border-0 shadow-sm p-4">
                    <h4 class="h6 mb-3">Categories</h4>
                    <ul class="list-unstyled mb-0">
                        @foreach($categories as $category)
                            <li class="py-2 border-bottom">
                                {{ $category->name }} <span class="text-muted">({{ $category->blogs_count }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }

    .blog-review-card {
        overflow: hidden;
    }

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

    .rv__stars-row {
        display: flex;
        justify-content: center;
        gap: 3px;
        margin-bottom: 6px;
    }

    .rv__star {
        font-size: 1.1rem;
        color: #d1d5db;
    }

    .rv__star--filled {
        color: #f59e0b;
    }

    .rv__star--sm {
        font-size: 0.85rem;
    }

    .rv__total-label {
        font-size: 0.82rem;
        color: #6b7280;
        margin-bottom: 16px;
    }

    .rv__bar-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }

    .rv__bar-label {
        font-size: 0.78rem;
        color: #4b5563;
        width: 32px;
        text-align: right;
        white-space: nowrap;
    }

    .rv__bar-track {
        flex: 1;
        height: 7px;
        background: #e5e7eb;
        border-radius: 99px;
        overflow: hidden;
    }

    .rv__bar-fill {
        height: 100%;
        background: #f59e0b;
        border-radius: 99px;
        transition: width .5s ease;
    }

    .rv__bar-count {
        font-size: 0.75rem;
        color: #9ca3af;
        width: 16px;
    }

    .rv__form-card {
        background: #fff;
        border: 1px solid #c8e6c9;
        border-radius: 14px;
        padding: 20px 22px;
    }

    .rv__form-title {
        color: #2d7a45;
        font-weight: 700;
        margin-bottom: 12px;
        font-size: 1rem;
    }

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
        flex-wrap: wrap;
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

    .rv__textarea {
        border: 1.5px solid #c8e6c9;
        border-radius: 10px;
        resize: vertical;
        font-size: 0.9rem;
    }

    .rv__textarea:focus {
        border-color: #2d7a45;
        box-shadow: 0 0 0 3px rgba(45, 122, 69, .12);
        outline: none;
    }

    .rv__item {
        border-bottom: 1px solid #e8f0e4;
        padding: 16px 0;
    }

    .rv__item:last-child {
        border-bottom: none;
    }

    .rv__item-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 8px;
    }

    .rv__avatar {
        width: 42px;
        height: 42px;
        background: #2d7a45;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .rv__name {
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 2px;
        font-size: 0.95rem;
    }

    .rv__date {
        font-size: 0.75rem;
        color: #9ca3af;
    }

    .rv__text {
        font-size: 0.9rem;
        color: #4b5563;
        margin: 0;
        line-height: 1.6;
        padding-left: 54px;
    }

    .rv__login-prompt,
    .rv__already-msg {
        background: #f4faf0;
        border: 1px solid #c8e6c9;
        border-radius: 10px;
        padding: 14px 18px;
        font-size: 0.9rem;
        color: #374151;
    }

    .blog-share-bar {
        border-top: 1px solid #e6efe0;
        padding-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .blog-share-bar__label {
        font-weight: 700;
        color: #21422b;
    }

    .blog-share-bar__links {
        display: flex;
        gap: 10px;
    }

    .blog-share-bar__link {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #edf6e8;
        color: #2d7a45;
        text-decoration: none;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        const pickStars = document.querySelectorAll('#blogStarPicker .rv__pick-star');
        const ratingInput = document.getElementById('blogRatingInput');
        const starLabel = document.getElementById('blogStarLabel');
        const ratingHint = document.getElementById('blogRatingHint');
        const submitBtn = document.getElementById('blogSubmitReviewBtn');
        const starLabels = ['', 'Terrible', 'Poor', 'Average', 'Good', 'Excellent'];
        let selectedRating = parseInt(ratingInput?.value || '0', 10);

        function paintStars(value) {
            pickStars.forEach((star) => {
                const starValue = parseInt(star.dataset.value, 10);
                star.classList.toggle('active', starValue <= value);
                star.classList.toggle('ri-star-fill', starValue <= value);
                star.classList.toggle('ri-star-line', starValue > value);
            });
        }

        if (pickStars.length) {
            paintStars(selectedRating);

            if (selectedRating > 0) {
                starLabel.textContent = starLabels[selectedRating];
                ratingHint.textContent = `Rating selected: ${selectedRating} star${selectedRating !== 1 ? 's' : ''}`;
                ratingHint.style.color = '#2d7a45';
                ratingHint.style.fontWeight = '600';
            }

            pickStars.forEach((star) => {
                star.addEventListener('mouseover', () => {
                    const value = parseInt(star.dataset.value, 10);
                    paintStars(value);
                    starLabel.textContent = starLabels[value];
                    starLabel.style.color = '#f59e0b';
                });

                star.addEventListener('mouseout', () => {
                    paintStars(selectedRating);
                    if (selectedRating === 0) {
                        starLabel.textContent = 'Select rating';
                        starLabel.style.color = '#6b7280';
                    } else {
                        starLabel.textContent = starLabels[selectedRating];
                        starLabel.style.color = '#2d7a45';
                    }
                });

                star.addEventListener('click', (event) => {
                    event.preventDefault();
                    const value = parseInt(star.dataset.value, 10);
                    selectedRating = value;
                    ratingInput.value = value;
                    paintStars(value);
                    starLabel.textContent = starLabels[value];
                    starLabel.style.color = '#2d7a45';
                    ratingHint.textContent = `Rating selected: ${value} star${value !== 1 ? 's' : ''}`;
                    ratingHint.style.color = '#2d7a45';
                    ratingHint.style.fontWeight = '600';
                });
            });
        }

        if (submitBtn) {
            submitBtn.addEventListener('click', function (event) {
                if (!ratingInput.value) {
                    event.preventDefault();
                    starLabel.textContent = 'Please select a rating';
                    starLabel.style.color = '#dc3545';
                    ratingHint.textContent = 'You must select a star rating before submitting';
                    ratingHint.style.color = '#dc3545';
                    ratingHint.style.fontWeight = '600';
                    const starPicker = document.getElementById('blogStarPicker');
                    starPicker.style.animation = 'none';
                    setTimeout(() => {
                        starPicker.style.animation = 'shake 0.3s';
                    }, 10);
                }
            });
        }
    })();
</script>

{{--
    Enable reCAPTCHA again on production.
    @if(config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
--}}
@endpush
