<!DOCTYPE html>
<html lang="en">

<head>
    @php
        $siteSettings = \App\Models\SiteSetting::first();
    @endphp
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Bharat Biomer – Nature-Powered Biology')</title>
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
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Dynamic Meta Tags (pushed from pages) --}}
    @stack('meta')

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    {{-- Google Fonts - Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

    {{-- Main Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('assets/css/frontcss/style.css') }}?v={{ filemtime(public_path('assets/css/frontcss/style.css')) }}" />
    <link rel="preload" as="image" href="{{ asset('assets/bharat-biomer/bblogo.webp') }}" />

    {{-- Remixicon Icon Library --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.0.1/remixicon.min.css" rel="stylesheet" />

    {{-- Page-specific styles --}}
    @stack('styles')

    @if(!empty($siteSettings?->google_analytics_id) && preg_match('/^G-[A-Z0-9]+$/', $siteSettings->google_analytics_id))
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
    @endif
</head>
<style>
    /* ── Add these to your style.css ─────────────────────────────────── */

    /* Cart Icon */
    .bb-cart-icon {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        font-size: 1.3rem;
        text-decoration: none;
        padding: 0;
        border-radius: 14px;
        background: #eef8e8;
        transition: background .2s ease, transform .2s ease;
    }

    .bb-cart-icon:hover {
        background: #e0f2d8;
        transform: translateY(-1px);
    }

    /* Cart Badge */
    .bb-cart-badge {
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
        gap: 12px;
        margin-top: 18px;
        flex-wrap: wrap;
    }

    .bb-footer-social {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #edf6e8;
        color: #2d7a45;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: transform .2s ease, background .2s ease;
    }

    .bb-footer-social:hover {
        transform: translateY(-2px);
        background: #dff0d6;
        color: #245e36;
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

<body>

    <div id="bb-preloader" class="bb-preloader">
        <div class="bb-preloader-inner">
            <img src="{{ asset('assets/images/home-img/bb logo.png') }}" alt="Bharat Biomer" class="bb-preloader-logo" />
            <div class="bb-preloader-ring" aria-hidden="true"></div>
        </div>
    </div>

    {{-- ═══════════════════════════
         NAVBAR
    ═══════════════════════════ --}}
    @php
        $cartCount = collect(session('cart', []))->sum('quantity');
        $headerLinks = \App\Models\HeaderLink::getActive();
    @endphp
    <header class="bb-main-header">
        <div class="container bb-main-nav">
            <a class="bb-main-logo" href="{{ url('/') }}">
                <img src="{{ asset('assets/bharat-biomer/bblogo.webp') }}" alt="Bharat Biomer Logo" loading="eager" fetchpriority="high" />
            </a>

            <nav class="bb-main-links" aria-label="Primary navigation">
                @foreach ($headerLinks as $link)
                    @php
                        $isTechnologyLink = str_contains(strtolower($link->label), 'technology') || str_contains(strtolower($link->url), 'technology');
                    @endphp
                    @if ($isTechnologyLink)
                        <div class="bb-main-dropdown">
                            <a href="{{ $link->url }}" target="{{ $link->target }}">
                                @if ($link->icon)
                                    <iconify-icon icon="{{ $link->icon }}"></iconify-icon>
                                @endif
                                {{ $link->label }}
                                <i class="ri-arrow-down-s-line" aria-hidden="true"></i>
                            </a>
                            <div class="bb-main-dropdown-menu">
                                <a href="{{ url('/technology#technology-crop') }}">Crop</a>
                                <a href="{{ url('/technology#technology-solution') }}">Solution</a>
                            </div>
                        </div>
                    @else
                        <a href="{{ $link->url }}" target="{{ $link->target }}">
                            @if ($link->icon)
                                <iconify-icon icon="{{ $link->icon }}"></iconify-icon>
                            @endif
                            {{ $link->label }}
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="bb-main-actions">
                <a href="{{ route('cart.index') }}" class="bb-main-icon-btn" aria-label="Cart">
                    <img src="{{ asset('assets/images/trolley.png') }}" alt="">
                    @if ($cartCount > 0)
                        <span class="bb-cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>

                <a href="{{ route('wishlist.index') }}" class="bb-main-icon-btn" aria-label="Wishlist">
                    <img src="{{ asset('assets/images/love.png') }}" alt="">
                    @auth('customer')
                        @php $wlCount = Auth::guard('customer')->user()->wishlists()->count(); @endphp
                        @if ($wlCount > 0)
                            <span id="wishlist-count" class="bb-main-badge bb-main-badge--danger">{{ $wlCount }}</span>
                        @endif
                    @endauth
                </a>

                @auth('customer')
                    <div class="dropdown">
                        <button class="bb-main-register dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-user-3-line" aria-hidden="true"></i>
                            <span>{{ Str::limit(Auth::guard('customer')->user()->name, 12) }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end bb-main-account-menu">
                            <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}">My Orders</a></li>
                            <li><a class="dropdown-item" href="{{ route('order-returns.index') }}">My Returns</a></li>
                            <li><a class="dropdown-item" href="{{ route('wishlist.index') }}">Wishlist</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.account') }}">My Account</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('customer.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item bb-main-logout">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="bb-main-login" href="{{ route('customer.login') }}"><i class="ri-user-3-line" aria-hidden="true"></i> Login</a>
                    <a class="bb-main-register" href="{{ route('customer.register') }}">Register</a>
                @endauth
            </div>

            <button class="bb-main-hamb" type="button" id="bbHeaderToggle" aria-label="Toggle navigation" aria-controls="bbMobilePanel" aria-expanded="false">
                <i class="ri-menu-line" aria-hidden="true"></i>
            </button>
        </div>

        <div class="bb-main-mobile-panel" id="bbMobilePanel">
            @foreach ($headerLinks as $link)
                <a href="{{ $link->url }}" target="{{ $link->target }}">{{ $link->label }}</a>
            @endforeach
            <a href="{{ route('cart.index') }}">Cart @if($cartCount > 0)<span>{{ $cartCount }}</span>@endif</a>
            <a href="{{ route('wishlist.index') }}">Wishlist</a>
            @auth('customer')
                <a href="{{ route('customer.dashboard') }}">Dashboard</a>
                <a href="{{ route('orders.index') }}">My Orders</a>
                <a href="{{ route('customer.account') }}">My Account</a>
                <form action="{{ route('customer.logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('customer.login') }}">Login</a>
                <a href="{{ route('customer.register') }}" class="bb-main-mobile-register">Register</a>
            @endauth
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
    <footer class="site-footer" id="footer">
        <div class="container">
            <div class="row g-4">

                {{-- Footer Brand Section --}}
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="footer-logo-icon">
                            @if ($siteSettings && $siteSettings->footer_logo_path)
                                <img src="{{ asset('storage/' . $siteSettings->footer_logo_path) }}"
                                    alt="{{ $siteSettings->site_name ?? 'Bharat Biomer' }}" height="40" />
                            @else
                                <img src="{{ asset('assets/images/footer-logo.svg') }}" alt="Bharat Biomer"
                                    height="40" />
                            @endif
                        </div>
                    </div>
                    <p class="footer-brand-tagline">
                        {{ $siteSettings->tagline ?? 'Advanced biological solutions for sustainable farming.' }}</p>
                    <div class="bb-footer-socials">
                        @if($siteSettings?->facebook_url)
                            <a class="bb-footer-social" href="{{ $siteSettings->facebook_url }}" target="_blank" rel="noopener"><i class="ri-facebook-fill"></i></a>
                        @endif
                        @if($siteSettings?->instagram_url)
                            <a class="bb-footer-social" href="{{ $siteSettings->instagram_url }}" target="_blank" rel="noopener"><i class="ri-instagram-line"></i></a>
                        @endif
                        @if($siteSettings?->twitter_url)
                            <a class="bb-footer-social" href="{{ $siteSettings->twitter_url }}" target="_blank" rel="noopener"><i class="ri-twitter-x-line"></i></a>
                        @endif
                        @if($siteSettings?->linkedin_url)
                            <a class="bb-footer-social" href="{{ $siteSettings->linkedin_url }}" target="_blank" rel="noopener"><i class="ri-linkedin-fill"></i></a>
                        @endif
                    </div>
                </div>
                {{-- Static policy links --}}
                <div class="col-6 col-md-3 col-lg-3">
                    <p class="footer-col-title">Policies</p>
                    <a href="{{ route('policy.terms') }}" class="footer-link">Terms & Conditions</a>
                    <a href="{{ route('policy.privacy') }}" class="footer-link">Privacy Policy</a>
                    <a href="{{ route('policy.shipping') }}" class="footer-link">Shipping Policy</a>
                    <a href="{{ route('policy.return') }}" class="footer-link">Return Policy</a>
                </div>
                {{-- Dynamic Footer Links Sections (Column-wise) --}}
                @php
                    $footerSections = \App\Models\FooterLink::selectRaw('DISTINCT section')
                        ->where('is_active', true)
                        ->orderBy('section')
                        ->pluck('section');
                @endphp

                @foreach ($footerSections as $section)
                    <div class="col-6 col-md-3 col-lg-3">
                        <p class="footer-col-title">{{ $section }}</p>
                        @php
                            $sectionLinks = \App\Models\FooterLink::where('section', $section)
                                ->where('is_active', true)
                                ->orderBy('position')
                                ->get();
                        @endphp
                        @foreach ($sectionLinks as $link)
                            <a href="{{ $link->url }}" class="footer-link"
                                target="{{ $link->target }}">{{ $link->label }}</a>
                        @endforeach
                    </div>
                @endforeach

                

            </div>

            <hr class="footer-divider" />
            <div class="footer-bottom">
                {{ $siteSettings->footer_text ?? '© ' . date('Y') . ' Bharat Biomer. All rights reserved.' }}
            </div>
        </div>
    </footer>

    {{-- Shared frontend dependencies --}}
    <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
    <script src="{{ asset('assets/js/app.js') }}"></script>

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

        window.addEventListener('load', function () {
            var preloader = document.getElementById('bb-preloader');
            if (!preloader) return;
            preloader.classList.add('bb-preloader--hidden');
            setTimeout(function () {
                if (preloader.parentNode) {
                    preloader.parentNode.removeChild(preloader);
                }
            }, 360);
        });

        document.addEventListener('DOMContentLoaded', function () {
            var headerToggle = document.getElementById('bbHeaderToggle');
            var mobilePanel = document.getElementById('bbMobilePanel');
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
