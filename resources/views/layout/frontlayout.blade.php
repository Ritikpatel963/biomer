<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Bharat Biomer – Nature-Powered Biology')</title>

    {{-- Dynamic Meta Tags (pushed from pages) --}}
    @stack('meta')

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    {{-- Google Fonts - Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

    {{-- Main Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('assets/css/frontcss/style.css') }}" />

    {{-- Remixicon Icon Library --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.0.1/remixicon.min.css" rel="stylesheet" />

    {{-- Page-specific styles --}}
    @stack('styles')
</head>
<style>
    /* ── Add these to your style.css ─────────────────────────────────── */

    /* Cart Icon */
    .bb-cart-icon {
        position: relative;
        display: inline-flex;
        align-items: center;
        font-size: 1.3rem;
        text-decoration: none;
        padding: 4px 6px;
        border-radius: 8px;
        transition: background .2s;
    }

    .bb-cart-icon:hover {
        background: #f4faf0;
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
</style>

<body>

    {{-- ═══════════════════════════
         NAVBAR
    ═══════════════════════════ --}}
    <nav class="bb-navbar navbar navbar-expand-lg">
    <div class="container">

        {{-- Brand / Logo --}}
        <a class="navbar-brand" href="{{ url('/') }}">
            <img class="bb-logo-icon" src="{{ asset('assets/images/home-img/bb logo.png') }}"
                alt="Bharat Biomer Logo" />
        </a>

        {{-- Mobile: Cart + Toggler --}}
        @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
        <div class="d-flex align-items-center gap-2 d-lg-none">
            <a href="{{ route('cart.index') }}" class="bb-cart-icon">
                <img src="{{ asset('assets/images/trolley.png') }}" alt="Cart" style="width:20px;height:20px;">
                @if ($cartCount > 0)
                    <span class="bb-cart-badge">{{ $cartCount }}</span>
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bbNavMenu"
                aria-controls="bbNavMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        {{-- Nav Links --}}
        <div class="collapse navbar-collapse" id="bbNavMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1 py-3 py-lg-0">

                {{-- Dynamic Navigation from Database --}}
                @php
                    $headerLinks = \App\Models\HeaderLink::getActive();
                @endphp
                @foreach ($headerLinks as $link)
                    <li class="nav-item">
                        <a class="nav-link bb-nav-link" href="{{ $link->url }}" target="{{ $link->target }}">
                            @if ($link->icon)
                                <iconify-icon icon="{{ $link->icon }}"></iconify-icon>
                            @endif
                            {{ $link->label }}
                        </a>
                    </li>
                @endforeach

                {{-- ── Divider (mobile only) ── --}}
                <li class="nav-item d-lg-none">
                    <hr style="border-color:#e8f0e4;margin:.5rem 0;">
                </li>

                {{-- ── Cart + Wishlist (desktop only) ── --}}
                <li class="nav-item ms-lg-2 d-none d-lg-flex align-items-center gap-2">

                    {{-- Cart --}}
                    <a href="{{ route('cart.index') }}" class="bb-cart-icon">
                        <img src="{{ asset('assets/images/trolley.png') }}" alt="Cart" style="width:20px;height:20px;">
                        @if ($cartCount > 0)
                            <span class="bb-cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>

                    {{-- Wishlist --}}
                    <a href="{{ route('wishlist.index') }}" style="position:relative; text-decoration:none;">
                        <img src="{{ asset('assets/images/love.png') }}" alt="Wishlist" style="width:20px;height:20px;">
                        @auth('customer')
                            @php $wlCount = Auth::guard('customer')->user()->wishlists()->count(); @endphp
                            @if ($wlCount > 0)
                                <span id="wishlist-count"
                                    style="position:absolute; top:-8px; right:-8px;
                                    background:#e74c3c; color:#fff;
                                    font-size:0.65rem; font-weight:700;
                                    width:18px; height:18px; border-radius:50%;
                                    display:flex; align-items:center; justify-content:center;">
                                    {{ $wlCount }}
                                </span>
                            @endif
                        @endauth
                    </a>

                </li>

                {{-- ── Auth Links ── --}}
                @auth('customer')
                    <li class="nav-item ms-lg-1 mt-2 mt-lg-0">
                        <div class="dropdown">
                            <button class="bb-btn-contact-nav dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false" style="border:none;cursor:pointer;">
                                👤 {{ Str::limit(Auth::guard('customer')->user()->name, 12) }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end"
                                style="border:1px solid #e8f0e4;border-radius:10px;padding:8px;">

                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.dashboard') }}"
                                        style="font-size:.88rem;color:#1a2e1a;padding:8px 16px;border-radius:6px;">
                                        Dashboard
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.index') }}"
                                        style="font-size:.88rem;color:#2d7a45;padding:8px 16px;border-radius:6px;">
                                        My Orders
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('order-returns.index') }}"
                                        style="font-size:.88rem;color:#6b7c6b;padding:8px 16px;border-radius:6px;">
                                        My Returns
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('wishlist.index') }}"
                                        style="font-size:.88rem;color:#e74c3c;padding:8px 16px;border-radius:6px;">
                                        Wishlist
                                        @php $wlCount = Auth::guard('customer')->user()->wishlists()->count(); @endphp
                                        @if ($wlCount > 0)
                                            <span
                                                style="background:#e74c3c;color:#fff;font-size:10px;font-weight:700;padding:1px 6px;border-radius:20px;margin-left:4px;">{{ $wlCount }}</span>
                                        @endif
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.account') }}"
                                        style="font-size:.88rem;color:#1a2e1a;padding:8px 16px;border-radius:6px;">
                                        My Account
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider" style="border-color:#e8f0e4;">
                                </li>

                                <li>
                                    <form action="{{ route('customer.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item"
                                            style="font-size:.88rem;color:#dc3545;padding:8px 16px;border-radius:6px;background:none;border:none;width:100%;text-align:left;cursor:pointer;">
                                            Logout
                                        </button>
                                    </form>
                                </li>

                            </ul>
                        </div>
                    </li>
                @else
                    <li class="nav-item ms-lg-1 mt-2 mt-lg-0">
                        <a class="nav-link bb-nav-link {{ request()->routeIs('customer.login') ? 'active' : '' }}"
                            href="{{ route('customer.login') }}">Login</a>
                    </li>
                    <li class="nav-item ms-lg-1 mt-2 mt-lg-0">
                        <a class="bb-btn-contact-nav {{ request()->routeIs('customer.register') ? 'active' : '' }}"
                            href="{{ route('customer.register') }}">Register</a>
                    </li>
                @endauth

            </ul>
        </div>

    </div>
</nav>
    {{-- END NAVBAR --}}

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bb-flash bb-flash--success">
            ✅ {{ session('success') }}
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

    {{-- ═══════════════════════════
         FOOTER
    ═══════════════════════════ --}}
    <footer class="site-footer" id="footer">
        <div class="container">
            <div class="row g-4">

                {{-- Footer Brand Section --}}
                @php
                    $settings = \App\Models\SiteSetting::first();
                @endphp
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="footer-logo-icon">
                            @if ($settings && $settings->footer_logo_path)
                                <img src="{{ asset('storage/' . $settings->footer_logo_path) }}"
                                    alt="{{ $settings->site_name ?? 'Bharat Biomer' }}" height="40" />
                            @else
                                <img src="{{ asset('assets/images/footer-logo.svg') }}" alt="Bharat Biomer"
                                    height="40" />
                            @endif
                        </div>
                    </div>
                    <p class="footer-brand-tagline">
                        {{ $settings->tagline ?? 'Advanced biological solutions for sustainable farming.' }}</p>
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
                {{ $settings->footer_text ?? '© ' . date('Y') . ' Bharat Biomer. All rights reserved.' }}
            </div>
        </div>
    </footer>

    {{-- Shared frontend dependencies --}}
    <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- GSAP for page motion --}}
    <script src="{{ asset('assets/js/lib/gsap.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/ScrollTrigger.min.js') }}"></script>

    {{-- Main JS --}}
    <script src="{{ asset('assets/js/app.js') }}"></script>

    {{-- Page-specific scripts --}}
    @stack('scripts')

</body>

</html>
