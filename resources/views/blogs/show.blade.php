@extends('layout.frontlayout')

@section('body_class', 'no-product-motion')

@section('title', $blog->meta_title ?: $blog->title . ' - Bharat Biomer')
@section('seo_description', $blog->meta_description ?: Str::limit(strip_tags($blog->description), 160))
@section('seo_keywords', $blog->meta_tags)
@section('canonical_url', $blog->canonical_url ?: route('frontend.blog.show', $blog->slug))
@section('og_type', 'article')
@section('social_image', $blog->thumbnail ? $blog->thumbnail_url : asset('assets/images/og-image.png'))
@section('social_image_alt', $blog->thumbnail_alt_text)

@php
    $isLoggedIn = (bool) $customer;
    $reviewCount = $reviews->count();
    $avgRating = $reviewCount ? round($reviews->avg('rating'), 1) : 0;
    $canonicalUrl = $blog->canonical_url ?: route('frontend.blog.show', $blog->slug);
    $publishedDate = $blog->published_at ?: $blog->created_at;
    $publisherName = $siteSettings?->site_name ?? 'Bharat Biomer';
    $authorName = $blog->author ?: $publisherName;
    $articleText = trim(preg_replace('/\s+/', ' ', strip_tags($blog->description)));

    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $blog->title,
        'description' => $blog->meta_description ?: Str::limit($articleText, 160),
        'image' => [$blog->thumbnail ? $blog->thumbnail_url : asset('assets/images/og-image.png')],
        'datePublished' => $publishedDate->toIso8601String(),
        'dateModified' => $blog->updated_at->toIso8601String(),
        'author' => [
            '@type' => $blog->author ? 'Person' : 'Organization',
            'name' => $authorName,
            'description' => $blog->author_bio,
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => $publisherName,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $siteSettings?->logo_url ?? asset('assets/bharat-biomer/bblogo.webp'),
            ],
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $canonicalUrl,
        ],
        'articleSection' => $blog->category?->name,
        'keywords' => $blog->meta_tags ?: $blog->tags,
        'wordCount' => str_word_count($articleText),
        'inLanguage' => 'en',
    ];

    $articleSchema['author'] = array_filter($articleSchema['author'], static fn ($value) => $value !== null && $value !== '');
    $articleSchema = array_filter($articleSchema, static fn ($value) => $value !== null && $value !== '');

    $faqItems = collect($blog->faq_items ?? [])
        ->filter(fn ($faq) => filled(data_get($faq, 'question')) && filled(data_get($faq, 'answer')))
        ->values();

    $faqSchema = $faqItems->isEmpty() ? null : [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqItems->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => data_get($faq, 'question'),
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => data_get($faq, 'answer'),
            ],
        ])->all(),
    ];
@endphp

@push('meta')
    <meta property="article:published_time" content="{{ $publishedDate->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $blog->updated_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $authorName }}">
    @if($blog->category?->name)
        <meta property="article:section" content="{{ $blog->category->name }}">
    @endif
    @foreach(collect(explode(',', (string) ($blog->meta_tags ?: $blog->tags)))->map(fn ($tag) => trim($tag))->filter() as $socialTag)
        <meta property="article:tag" content="{{ $socialTag }}">
    @endforeach
    <script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    @if($faqSchema)
        <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    @endif
@endpush

@section('content')
<x-front-breadcrumb
    badge="Blog"
    :title="$blog->title"
    :description="'By ' . ($blog->author ?? 'Bharat Biomer') . ' - ' . $publishedDate->format('M d, Y') . ' - ' . ($blog->reading_time ?? 5) . ' min read'"
    align="center"
/>
{{-- 
                <p class="abth__desc">By {{ $blog->author ?? 'Bharat Biomer' }} • {{ $blog->created_at->format('M d, Y') }} • {{ $blog->reading_time ?? 5 }} min read</p>
            </div>
        </div>
    </div>
</section>
--}}

<section class="py-5 blog-detail-page">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm overflow-hidden mb-4">
                    @if($blog->thumbnail)
                        <img
                            src="{{ $blog->thumbnail_url }}"
                            alt="{{ $blog->thumbnail_alt_text }}"
                            class="blog-detail-hero-image"
                            width="1200"
                            height="675"
                            decoding="async"
                            fetchpriority="high"
                        >
                    @endif
                    <div class="card-body p-4">
                        @if(!empty($tableOfContents))
                            <nav class="blog-toc mb-4" aria-labelledby="blogTocHeading">
                                <h2 class="blog-toc__title" id="blogTocHeading">Table of Contents</h2>
                                <ol class="blog-toc__list mb-0">
                                    @foreach($tableOfContents as $heading)
                                        <li class="blog-toc__item {{ $heading['level'] === 'h3' ? 'blog-toc__item--nested' : '' }}">
                                            <a href="#{{ $heading['id'] }}">{{ $heading['title'] }}</a>
                                        </li>
                                    @endforeach
                                </ol>
                            </nav>
                        @endif

                        <article class="blog-article-content">
                            {!! $renderedContent !!}
                        </article>

                        <div class="blog-author-box mt-4">
                            <div class="blog-author-box__icon" aria-hidden="true"><i class="ri-user-line"></i></div>
                            <div>
                                <div class="blog-author-box__name">{{ $authorName }}</div>
                                @if($blog->author_bio)
                                    <p class="blog-author-box__bio">{{ $blog->author_bio }}</p>
                                @endif
                                <div class="blog-author-box__dates">
                                    <span>Published {{ $publishedDate->format('M d, Y') }}</span>
                                    <span>Last updated {{ $blog->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
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

                @if($faqItems->isNotEmpty())
                    <section class="card border-0 shadow-sm p-4 mb-4" aria-labelledby="blogFaqHeading">
                        <h2 class="h3 mb-4" id="blogFaqHeading">Frequently Asked Questions</h2>
                        <div class="accordion" id="blogFaqAccordion">
                            @foreach($faqItems as $index => $faq)
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="blogFaqQuestion{{ $index }}">
                                        <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#blogFaqAnswer{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="blogFaqAnswer{{ $index }}">
                                            {{ data_get($faq, 'question') }}
                                        </button>
                                    </h3>
                                    <div id="blogFaqAnswer{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="blogFaqQuestion{{ $index }}" data-bs-parent="#blogFaqAccordion">
                                        <div class="accordion-body">{!! nl2br(e(data_get($faq, 'answer'))) !!}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($relatedBlogs->isNotEmpty())
                    <section class="card border-0 shadow-sm p-4 mb-4" aria-labelledby="relatedPostsHeading">
                        <h2 class="h3 mb-4" id="relatedPostsHeading">Related Posts</h2>
                        <div class="row g-3">
                            @foreach($relatedBlogs as $relatedPost)
                                <div class="col-md-4">
                                    <article class="related-post-card h-100">
                                        <a href="{{ route('frontend.blog.show', $relatedPost->slug) }}" class="related-post-card__image-link">
                                            <img src="{{ $relatedPost->thumbnail_url }}" alt="{{ $relatedPost->thumbnail_alt_text }}" class="related-post-card__image">
                                        </a>
                                        <div class="related-post-card__body">
                                            <div class="related-post-card__meta">{{ $relatedPost->category?->name ?? 'Blog' }}</div>
                                            <h3 class="related-post-card__title">
                                                <a href="{{ route('frontend.blog.show', $relatedPost->slug) }}">{{ $relatedPost->title }}</a>
                                            </h3>
                                            <time datetime="{{ ($relatedPost->published_at ?: $relatedPost->created_at)->toDateString() }}">
                                                {{ ($relatedPost->published_at ?: $relatedPost->created_at)->format('M d, Y') }}
                                            </time>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

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
                                            <textarea name="comment" class="rv__textarea form-control" rows="4" minlength="3" maxlength="1000" required>{{ old('comment') }}</textarea>
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
                                <p class="text-secondary small mb-0">{{ ($post->published_at ?: $post->created_at)->format('M d, Y') }}</p>
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
