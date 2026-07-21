<!DOCTYPE html>
<html lang="en">

<head>
    @php
        $headerLinks = collect($headerLinks ?? []);
        $footerLinks = collect($footerLinks ?? []);

        if (! $footerLinks->keys()->contains(fn ($section) => strcasecmp((string) $section, 'contact') === 0)) {
            $footerLinks = $footerLinks->put('Contact', collect())->sortKeys();
        }
    @endphp
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @if(request()->is('about', 'technology', 'impact', 'contact', 'products*', 'blogs*', 'customer/login', 'register'))
        <link rel="preload" as="image" href="{{ asset('assets/images/home-img/breadcumb-img.webp') }}" type="image/webp" fetchpriority="high">
    @endif
    <title>@yield('title', 'Bharat Biomer – Nature-Powered Biology')</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/images/home-img/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/images/home-img/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/images/home-img/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/images/home-img/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/images/home-img/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/images/home-img/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/images/home-img/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/images/home-img/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/home-img/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/images/home-img/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/home-img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/images/home-img/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/home-img/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/home-img/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('assets/images/home-img/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('assets/images/home-img/ms-icon-144x144.png') }}">
    <meta name="msapplication-config" content="{{ asset('assets/images/home-img/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="description" content="@yield('seo_description', 'Bharat Biomer - Advanced Biometric Solutions')">
    @if(trim($__env->yieldContent('seo_keywords')))
        <meta name="keywords" content="@yield('seo_keywords')">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'Bharat Biomer')">
    <meta property="og:description" content="@yield('seo_description', 'Bharat Biomer - Advanced Biometric Solutions')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Bharat Biomer">
    <meta property="og:image" content="{{ asset('assets/images/og-image.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Bharat Biomer')">
    <meta name="twitter:description" content="@yield('seo_description', 'Bharat Biomer - Advanced Biometric Solutions')">
    <meta name="twitter:image" content="{{ asset('assets/images/og-image.png') }}">
    
    
    
    
     <meta property="og:type" content="@yield('og_type', 'website')">
     <meta property="og:url" content="@yield('canonical_url', url()->current())">

    <meta property="og:image" content="@yield('social_image', asset('assets/images/og-image.png'))">
    <meta property="og:image:alt" content="@yield('social_image_alt', 'Bharat Biomer')">

    <meta name="twitter:image" content="@yield('social_image', asset('assets/images/og-image.png'))">
    <meta name="twitter:image:alt" content="@yield('social_image_alt', 'Bharat Biomer')">
    
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    @stack('meta')

    <link rel="canonical" href="{{ url()->current() }}">
<!-- Meta Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1302279308741569');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=1302279308741569&ev=PageView&noscript=1"
        /></noscript>
        <meta name="google-site-verification" content="Jln4_qdt_LpjdwN89UBBUZQVuMtCvorEjWXnHW8-BdM" />
<!-- End Meta Pixel Code -->
    
    {{-- Dynamic Meta Tags (pushed from pages) --}}
    @stack('meta')

    {{-- Bootstrap 5 CSS --}}
    <link href="{{ asset('assets/css/lib/bootstrap.min.css') }}" rel="stylesheet" />

    {{-- Google Fonts --}}
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Poppins:wght@400;600;700;800;900&display=swap"
        rel="stylesheet" />

    {{-- Main Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('assets/css/frontcss/style.css') }}?v={{ filemtime(public_path('assets/css/frontcss/style.css')) }}" />
    <link rel="preload" as="image" href="{{ $siteSettings?->logo_url ?? asset('assets/bharat-biomer/bblogo.webp') }}" />

    {{-- Remixicon Icon Library --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.0.1/remixicon.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    {{-- Page-specific styles --}}
    @stack('styles')
    @if(!empty($siteSettings?->google_analytics_id))
        @if(preg_match('/^G-[A-Z0-9]+$/', $siteSettings->google_analytics_id))
            <!-- Google Analytics (GA4) -->
            <script>
                window.addEventListener('load', function () {
                    setTimeout(function () {
                        var analyticsScript = document.createElement('script');
                        analyticsScript.async = true;
                        analyticsScript.src = 'https://www.googletagmanager.com/gtag/js?id={{ $siteSettings->google_analytics_id }}';
                        document.head.appendChild(analyticsScript);

                        window.dataLayer = window.dataLayer || [];
                        window.gtag = function(){ dataLayer.push(arguments); };
                        gtag('js', new Date());
                        gtag('config', '{{ $siteSettings->google_analytics_id }}');
                    }, 2500);
                });
            </script>
        @elseif(preg_match('/^GTM-[A-Z0-9]+$/', $siteSettings->google_analytics_id))
            <!-- Google Tag Manager (GTM) -->
            <script>
                window.addEventListener('load', function () {
                    setTimeout(function () {
                        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                        })(window,document,'script','dataLayer','{{ $siteSettings->google_analytics_id }}');
                    }, 2500);
                });
            </script>
        @endif
    @endif

</head>
<style>
    /* ── Add these to your style.css ─────────────────────────────────── */

    /* Cart Icon */
    .bb-cart-icon,
    .bb-wishlist-icon {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        font-size: 1.3rem;
        color: #245e36;
        text-decoration: none;
        padding: 0;
        border-radius: 14px;
        background: #eef8e8;
        transition: background .2s ease, transform .2s ease;
    }

    .bb-cart-icon:hover,
    .bb-wishlist-icon:hover {
        background: #e0f2d8;
        color: #245e36;
        transform: translateY(-1px);
    }

    /* Cart Badge */
    .bb-cart-badge,
    .bb-wishlist-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #2d7a45;
        color: #fff;
        font-size: .6rem;
        font-weight: 700;
        min-width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
    }

    /* Flash Messages */
    .bb-flash {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        font-size: .88rem;
        font-weight: 600;
    }

    .bb-flash--success {
        background: #e8f5ed;
        color: #2d7a45;
        border-bottom: 1px solid #a8d5b5;
    }

    .bb-flash--danger {
        background: #fff0f0;
        color: #dc3545;
        border-bottom: 1px solid #f5c6cb;
    }

    .bb-review-modal {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 24px 80px rgba(19, 52, 28, 0.22);
    }

    .bb-review-modal--dismissible {
        position: relative;
    }

    .bb-review-modal__close {
        position: absolute;
        top: 14px;
        right: 14px;
        z-index: 5;
        width: 34px;
        height: 34px;
        border: 0;
        border-radius: 50%;
        background: #edf6e8;
        color: #245e36;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        line-height: 1;
        cursor: pointer;
        transition: background .2s ease, color .2s ease, transform .2s ease;
    }

    .bb-review-modal__close:hover {
        background: #245e36;
        color: #fff;
        transform: rotate(90deg);
    }

    .bb-review-modal__badge {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #245e36 0%, #2d7a45 48%, #5ba56f 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.9rem;
        margin: 0 auto 16px;
    }

    .bb-review-modal__content {
        padding: 28px 24px;
        background: #fff;
        text-align: center;
    }

    .bb-review-modal__eyebrow {
        color: #2d7a45;
        text-transform: uppercase;
        letter-spacing: .16em;
        font-size: .75rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .bb-review-modal__title {
        color: #17311f;
        font-size: 1.7rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .bb-review-modal__text {
        color: #4f6555;
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .bb-tech-dropdown .dropdown-menu {
        border: 1px solid #dce9d8;
        border-radius: 14px;
        padding: 10px;
        min-width: 220px;
        box-shadow: 0 20px 40px rgba(19, 52, 28, 0.12);
        margin-top: 0;
    }

    .bb-tech-dropdown .bb-tech-dropdown__toggle {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .bb-tech-dropdown .bb-tech-dropdown__icon {
        font-size: 1rem;
        transition: transform .2s ease;
    }

    .bb-tech-dropdown .dropdown-item {
        border-radius: 10px;
        padding: 10px 14px;
        color: #21422b;
        font-weight: 600;
    }

    .bb-tech-dropdown .dropdown-item:hover {
        background: #f4faf0;
        color: #2d7a45;
    }

    @media (min-width: 992px) {
        .bb-tech-dropdown .dropdown-menu {
            display: block;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(8px);
            transition: opacity .2s ease, transform .2s ease, visibility .2s ease;
        }

        .bb-tech-dropdown:hover .dropdown-menu,
        .bb-tech-dropdown:focus-within .dropdown-menu {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0);
        }

        .bb-tech-dropdown:hover .bb-tech-dropdown__icon,
        .bb-tech-dropdown:focus-within .bb-tech-dropdown__icon {
            transform: rotate(180deg);
        }
    }

    .bb-footer-socials {
        display: flex;
        gap: 10px;
        margin-top: 18px;
        flex-wrap: wrap;
    }

    .bb-footer-social {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, .24);
        background: transparent;
        color: #d5e8d2;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: transform .2s ease, border-color .2s ease, color .2s ease;
    }

    .bb-footer-social:hover {
        transform: translateY(-2px);
        border-color: rgba(255, 255, 255, .58);
        color: #fff;
    }

    .bb-audience-card {
        border: 1px solid #dbead5;
        border-radius: 18px;
        padding: 18px 16px;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s ease, transform .2s ease, background .2s ease;
        background: #fbfdf9;
    }

    .bb-audience-card:hover {
        border-color: #2d7a45;
        background: #f4faf0;
        transform: translateY(-2px);
    }

    .bb-audience-card__icon {
        width: 54px;
        height: 54px;
        margin: 0 auto 10px;
        border-radius: 50%;
        background: #e8f5ed;
        color: #2d7a45;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
</style>

<body class="@yield('body_class')">

    <div id="bb-preloader" class="bb-preloader">
        <div class="bb-preloader-inner">
            <img src="{{ $siteSettings?->preloader_logo_url ?? asset('assets/images/home-img/bb logo.png') }}" alt="{{ $siteSettings?->site_name ?? 'Bharat Biomer' }}" class="bb-preloader-logo" />
            <div class="bb-preloader-ring" aria-hidden="true"></div>
        </div>
    </div>

    {{-- ═══════════════════════════
         NAVBAR
    ═══════════════════════════ --}}
    @php
        $cartCount = collect(session('cart', []))->sum('quantity');
        $wishlistCount = Auth::guard('customer')->check()
            ? Auth::guard('customer')->user()->wishlists()->count()
            : 0;
    @endphp
    <header>
        <div class="wrap nav">
            <a class="logo" href="{{ url('/') }}"><img src="{{ $siteSettings?->logo_url ?? asset('assets/bharat-biomer/bblogo.webp') }}"
                    alt="{{ $siteSettings?->site_name ?? 'Bharat Biomer' }}" /></a>
            <nav class="links">
                @foreach($headerLinks as $link)
                    @php
                        $linkLabel = Str::lower(trim($link->label));
                        $linkHref = in_array($linkLabel, ['product', 'products', 'shop'], true)
                            ? route('products.index')
                            : $link->href;
                    @endphp
                    <a href="{{ $linkHref }}" target="{{ $link->safe_target }}" @if($link->safe_target === '_blank') rel="noopener noreferrer" @endif>
                        @if($link->icon)
                            <iconify-icon icon="{{ $link->icon }}" class="icon"></iconify-icon>
                        @endif
                        {{ $link->label }}
                    </a>
                @endforeach
            </nav>
            <div class="nav-actions">
                @auth('customer')
                    <a class="bb-wishlist-icon" href="{{ route('wishlist.index') }}" aria-label="View wishlist">
                        <i class="ri-heart-line" aria-hidden="true"></i>
                        <span id="wishlist-count" class="bb-wishlist-badge" style="{{ $wishlistCount > 0 ? '' : 'display:none;' }}">{{ $wishlistCount }}</span>
                    </a>
                    <a class="bb-cart-icon" href="{{ route('cart.index') }}" aria-label="View cart">
                        <i class="ri-shopping-cart-2-line" aria-hidden="true"></i>
                        @if($cartCount > 0)
                            <span class="bb-cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a class="login" href="{{ route('customer.dashboard') }}"><i class="fa fa-user-o"
                            aria-hidden="true"></i> {{ Str::limit(Auth::guard('customer')->user()->name, 12) }}</a>
                    <a class="register" href="{{ route('customer.account') }}">Account</a>
                @else
                    <a class="login" href="{{ route('customer.login') }}"><i class="fa fa-user-o" aria-hidden="true"></i>
                        Login</a><a class="register" href="{{ route('customer.register') }}">Register</a>
                    <a class="bb-cart-icon" href="{{ route('cart.index') }}" aria-label="View cart">
                        <i class="ri-shopping-cart-2-line" aria-hidden="true"></i>
                        @if($cartCount > 0)
                            <span class="bb-cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                @endauth
            </div>
            <button class="hamb" type="button" id="hamb"><i class="fa fa-bars" aria-hidden="true"></i></button>
        </div>
        <div class="mobile-panel" id="mobilePanel">
            @foreach($headerLinks as $link)
                @php
                    $linkLabel = Str::lower(trim($link->label));
                    $linkHref = in_array($linkLabel, ['product', 'products', 'shop'], true)
                        ? route('products.index')
                        : $link->href;
                @endphp
                <a href="{{ $linkHref }}" target="{{ $link->safe_target }}" @if($link->safe_target === '_blank') rel="noopener noreferrer" @endif>
                    @if($link->icon)
                        <iconify-icon icon="{{ $link->icon }}" class="icon"></iconify-icon>
                    @endif
                    {{ $link->label }}
                </a>
            @endforeach
            @auth('customer')
                <a href="{{ route('customer.dashboard') }}">Dashboard</a><a href="{{ route('customer.account') }}"
                    class="register">Account</a>
                <a href="{{ route('wishlist.index') }}">Wishlist @if($wishlistCount > 0) ({{ $wishlistCount }}) @endif</a>
            @else
                <a href="{{ route('customer.login') }}">Login</a><a href="{{ route('customer.register') }}"
                    class="register">Register</a>
            @endauth
            <a href="{{ route('cart.index') }}">Cart @if($cartCount > 0) ({{ $cartCount }}) @endif</a>
        </div>
    </header>
    {{-- END NAVBAR --}}

    {{-- Flash Messages --}}
    @if (session('success') && !session('frontend_modal'))
        <div class="bb-flash bb-flash--success">
            <iconify-icon icon="mdi:check-circle-outline" class="icon"></iconify-icon> {{ session('success') }}
            <button onclick="this.parentElement.remove()"
                style="background:none;border:none;cursor:pointer;font-size:1rem;color:inherit;margin-left:auto;">✕</button>
        </div>
    @endif
    @if (session('error'))
        <div class="bb-flash bb-flash--danger">
            ⚠ {{ session('error') }}
            <button onclick="this.parentElement.remove()"
                style="background:none;border:none;cursor:pointer;font-size:1rem;color:inherit;margin-left:auto;">✕</button>
        </div>
    @endif

    {{-- ═══════════════════════════
         MAIN CONTENT
    ═══════════════════════════ --}}
    <main>
        @yield('content')
    </main>

    <div class="modal fade" id="frontendMessageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bb-review-modal">
                    <div class="modal-body p-0">
                        <div class="bb-review-modal__content">
                            <div class="bb-review-modal__badge">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                            <p class="bb-review-modal__eyebrow">Bharat Biomer</p>
                            <h3 class="bb-review-modal__title" id="frontendMessageModalTitle">Message</h3>
                            <p class="bb-review-modal__text" id="frontendMessageModalText"></p>
                            <button type="button" class="bb-btn-contact-nav" id="frontendMessageModalButton" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <div class="modal fade" id="audiencePreferenceModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bb-review-modal bb-review-modal--dismissible">
                <button type="button" class="bb-review-modal__close" aria-label="Close" data-audience-dismiss>&times;</button>
                <div class="modal-body p-0">
                    <div class="bb-review-modal__content">
                        <p class="bb-review-modal__eyebrow">Welcome</p>
                        <h3 class="bb-review-modal__title">Who are you?</h3>
                        <p class="bb-review-modal__text">Choose the option that fits you best so we can personalize your experience.</p>
                        <div class="row g-3">
                            <div class="col-12 col-sm-4">
                                <button type="button" class="bb-audience-card w-100" data-audience-type="kisan">
                                    <span class="bb-audience-card__icon"><i class="ri-plant-line"></i></span>
                                    <strong>Kisan</strong>
                                </button>
                            </div>
                            <div class="col-12 col-sm-4">
                                <button type="button" class="bb-audience-card w-100" data-audience-type="partners">
                                    <span class="bb-audience-card__icon"><i class="ri-hand-heart-line"></i></span>
                                    <strong>Partners</strong>
                                </button>
                            </div>
                            <div class="col-12 col-sm-4">
                                <button type="button" class="bb-audience-card w-100" data-audience-type="dealers">
                                    <span class="bb-audience-card__icon"><i class="ri-store-2-line"></i></span>
                                    <strong>Dealers</strong>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════
         FOOTER
    ═══════════════════════════ --}}
    <footer class="footer" id="contact">
        <div class="wrap">
            <div class="footer-grid">
                <div><a class="logo" style="color:#fff;font-size:21px" href="{{ url('/') }}"><img
                            src="{{ $siteSettings?->footer_logo_url ?? asset('assets/bharat-biomer/footer-logo.svg') }}" alt="{{ $siteSettings?->site_name ?? 'Bharat Biomer' }}" /></a>
                    <p style="margin-top:14px">Nature-powered biological solutions for sustainable and productive
                        farming.</p>
                    <div class="social"><a href="{{ $siteSettings?->facebook_url ?: '#' }}">f</a><a
                            href="{{ $siteSettings?->instagram_url ?: '#' }}">&#9678;</a><a
                            href="{{ $siteSettings?->twitter_url ?: '#' }}">&#9654;</a><a
                            href="{{ $siteSettings?->linkedin_url ?: '#' }}">in</a></div>
                </div>
                @foreach($footerLinks as $section => $links)
                    <div @class(['footer-contact' => strcasecmp((string) $section, 'contact') === 0])>
                        <h4>{{ strtoupper($section) }}</h4>
                        @if(strcasecmp((string) $section, 'contact') === 0)
                            <a class="footer-contact__item" href="tel:{{ preg_replace('/\s+/', '', $siteSettings?->phone ?? '+91 7828333334') }}">
                                <i class="ri-phone-fill footer-contact__icon" aria-hidden="true"></i>
                                <span>{{ $siteSettings?->phone ?? '+91 7828333334' }}</span>
                            </a>
                            <a class="footer-contact__item" href="mailto:{{ $siteSettings?->email ?? 'admin@bharatbiomer.com' }}">
                                <i class="ri-mail-line footer-contact__icon" aria-hidden="true"></i>
                                <span>{{ $siteSettings?->email ?? 'admin@bharatbiomer.com' }}</span>
                            </a>
                            <div class="footer-contact__item footer-contact__item--address">
                                <i class="ri-map-pin-2-fill footer-contact__icon" aria-hidden="true"></i>
                                <span>{{ $siteSettings?->address ?? 'India' }}</span>
                            </div>
                        @else
                            @foreach($links as $link)
                                <a href="{{ $link->href }}" target="{{ $link->safe_target }}" @if($link->safe_target === '_blank') rel="noopener noreferrer" @endif>
                                    {{ $link->label }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="copyright">
                Copyright 2026 Bharat Biomer. All rights reserved. Made by Crescita Software
            </div>
        </div>
    </footer>

    {{-- Shared frontend dependencies --}}
    <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>

    {{-- Bootstrap 5 JS --}}
    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>

    {{-- GSAP for page motion --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script>
        if (typeof window.gsap === 'undefined' || typeof window.ScrollTrigger === 'undefined') {
            document.write('<script src="{{ asset('assets/js/lib/gsap.min.js') }}"><\/script>');
            document.write('<script src="{{ asset('assets/js/lib/ScrollTrigger.min.js') }}"><\/script>');
        }
    </script>

    {{-- Main JS --}}
    <script src="{{ asset('assets/js/app.js') }}?v={{ filemtime(public_path('assets/js/app.js')) }}"></script>
    <script src="{{ asset('assets/js/frontend.js') }}?v={{ filemtime(public_path('assets/js/frontend.js')) }}"></script>

    {{-- Page-specific scripts --}}
    @stack('scripts')

    <script>
        window.BharatBiomerModal = {
            show(options) {
                var modalElement = document.getElementById('frontendMessageModal');
                if (!modalElement || typeof bootstrap === 'undefined') return;

                document.getElementById('frontendMessageModalTitle').textContent = options.title || 'Message';
                document.getElementById('frontendMessageModalText').textContent = options.message || '';
                document.getElementById('frontendMessageModalButton').textContent = options.button || 'Close';

                bootstrap.Modal.getOrCreateInstance(modalElement).show();
            }
        };

        var audienceReminderTimer = null;
        var audienceReminderDelay = 120000;
        var audienceDismissedUntilKey = 'bbAudiencePreferenceDismissedUntil';

        window.BharatBiomerAudience = {
            scheduleReminder(delay) {
                if (audienceReminderTimer) {
                    clearTimeout(audienceReminderTimer);
                }

                if (localStorage.getItem('bbAudiencePreferenceSaved') === '1') return;

                audienceReminderTimer = setTimeout(function () {
                    window.BharatBiomerAudience.showIfNeeded();
                }, Math.max(delay, 0));
            },
            showIfNeeded() {
                var modalElement = document.getElementById('audiencePreferenceModal');
                if (!modalElement || typeof bootstrap === 'undefined') return;
                if (localStorage.getItem('bbAudiencePreferenceSaved') === '1') return;

                var dismissedUntil = Number(localStorage.getItem(audienceDismissedUntilKey) || 0);
                var now = Date.now();

                if (dismissedUntil > now) {
                    this.scheduleReminder(dismissedUntil - now);
                    return;
                }

                bootstrap.Modal.getOrCreateInstance(modalElement).show();
            },
            dismissTemporarily() {
                var modalElement = document.getElementById('audiencePreferenceModal');
                if (!modalElement || typeof bootstrap === 'undefined') return;

                localStorage.setItem(audienceDismissedUntilKey, String(Date.now() + audienceReminderDelay));
                bootstrap.Modal.getOrCreateInstance(modalElement).hide();
                this.scheduleReminder(audienceReminderDelay);
            }
        };

        function hidePreloader() {
            var preloader = document.getElementById('bb-preloader');
            if (!preloader) return;
            preloader.classList.add('bb-preloader--hidden');
            setTimeout(function () {
                if (preloader.parentNode) {
                    preloader.parentNode.removeChild(preloader);
                }
            }, 360);
        }

        window.addEventListener('load', hidePreloader, { once: true });
        window.setTimeout(hidePreloader, 2500);

        document.addEventListener('DOMContentLoaded', function () {
            var headerToggle = document.getElementById('hamb');
            var mobilePanel = document.getElementById('mobilePanel');
            if (headerToggle && mobilePanel) {
                headerToggle.addEventListener('click', function () {
                    var isOpen = mobilePanel.classList.toggle('open');
                    headerToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            }

            document.querySelectorAll('[data-audience-type]').forEach(function (button) {
                button.addEventListener('click', function () {
                    fetch('{{ route('audience-preference.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            audience_type: this.dataset.audienceType,
                            source_url: window.location.href
                        })
                    }).then(function () {
                        localStorage.setItem('bbAudiencePreferenceSaved', '1');
                        localStorage.removeItem(audienceDismissedUntilKey);
                        if (audienceReminderTimer) {
                            clearTimeout(audienceReminderTimer);
                        }
                        var modalElement = document.getElementById('audiencePreferenceModal');
                        if (!modalElement || typeof bootstrap === 'undefined') return;
                        bootstrap.Modal.getOrCreateInstance(modalElement).hide();
                    });
                });
            });

            document.querySelectorAll('[data-audience-dismiss]').forEach(function (button) {
                button.addEventListener('click', function () {
                    window.BharatBiomerAudience.dismissTemporarily();
                });
            });

            setTimeout(function () {
                window.BharatBiomerAudience.showIfNeeded();
            }, 1200);
        });

        @if(session('frontend_modal'))
            window.addEventListener('load', function () {
                window.BharatBiomerModal.show(@json(session('frontend_modal')));
            });
        @endif
    </script>

</body>

</html>
