@php $customer = auth('customer')->user(); @endphp

{{-- ─── TOP NAVBAR ─── --}}
<nav class="up-navbar">
    <div class="up-navbar__inner">

        {{-- <button class="up-hamburger" id="hamburgerBtn" aria-label="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
            </svg>
        </button>

        <a href="{{ route('products.index') }}" class="up-navbar__brand">
            <div class="up-navbar__brand-icon"><iconify-icon icon="mdi:leaf" class="icon"></iconify-icon></div>
            <div class="up-navbar__brand-text">Bharat<span>Biomer</span></div>
        </a> --}}

        <div class="up-navbar__divider"></div>

        <div class="up-navbar__breadcrumb">
            <a href="{{ route('customer.dashboard') }}">My Account</a>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
            @yield('breadcrumb', 'Dashboard')
        </div>

        <div class="up-navbar__right">

            <a href="{{ route('products.index') }}" class="up-navbar__shop-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016 2.993 2.993 0 0 0 2.25-1.016 3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h10.5" />
                </svg>
                <span>Shop</span>
            </a>

            <a href="{{ route('wishlist.index') }}" class="up-navbar__icon-btn" title="My Wishlist">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
            </a>

            <a href="{{ route('cart.index') }}" class="up-navbar__icon-btn" title="My Cart">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </a>

            <div class="up-navbar__user">
                <div class="up-navbar__avatar">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <span class="up-navbar__user-name">{{ $customer->name }}</span>
            </div>

        </div>
    </div>
</nav>

so where css store<div class="up-panel-overlay" id="overlay"></div>

{{-- ─── BODY ─── --}}
<div class="db-wrapper">

    <aside class="db-sidebar" id="sidebar">
        <div class="db-user">
            <div class="db-avatar-wrap">
                <div class="db-avatar-placeholder">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
            </div>
            <h3>{{ $customer->name }}</h3>
            <p>{{ $customer->email }}</p>
        </div>

        <nav class="db-nav">
            <span class="db-nav-label">Dashboard</span>

            <a href="{{ route('customer.dashboard') }}"
                class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                </svg>
                Overview
            </a>

            <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                My Orders
            </a>

            <a href="{{ route('wishlist.index') }}" class="{{ request()->routeIs('wishlist.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
                Wishlist
            </a>

            <a href="{{ route('order-returns.index') }}"
                class="{{ request()->routeIs('order-returns.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                My Returns
            </a>

            <span class="db-nav-label">Account Settings</span>

            <a href="{{ route('customer.account') }}"
                class="{{ request()->routeIs('customer.account') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                My Account
            </a>

            <form action="{{ route('customer.logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit"
                    style="width:100%; display:flex; align-items:center; gap:10px; padding:10px 20px; font-size:14px; color:#e53935; background:none; border:none; border-left:3px solid transparent; cursor:pointer; transition:all .2s; font-family:inherit;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" style="width:18px;height:18px;flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                    </svg>
                    Logout
                </button>
            </form>
        </nav>
    </aside>

    <main class="db-main">
        <div class="db-content">
            @yield('panel')
        </div>
    </main>

</div>

<footer style="background: #f4faf0; border-top: 1px solid #e8f0e4; margin-top: 40px; padding: 30px 20px;">
    <div style="max-width: 1400px; margin: 0 auto; text-align: center; color: #6b7c6b; font-size: 14px;">
        <p style="margin: 0 0 10px 0;">&copy; 2026 Bharat Biomer. All rights reserved.</p>
        <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; font-size: 13px;">
            <a href="{{ url('/') }}" style="color: #2d7a45; text-decoration: none;">Home</a>
            <a href="{{ route('products.index') }}" style="color: #2d7a45; text-decoration: none;">Products</a>
            <a href="{{ url('/about') }}" style="color: #2d7a45; text-decoration: none;">About</a>
            <a href="{{ url('/contact') }}" style="color: #2d7a45; text-decoration: none;">Contact</a>
        </div>
    </div>
</footer>

<script>
    const hamburger = document.getElementById("hamburgerBtn");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");
    if (hamburger && sidebar && overlay) {
        hamburger.addEventListener("click", () => {
            sidebar.classList.add("open");
            overlay.classList.add("open");
        });
        overlay.addEventListener("click", () => {
            sidebar.classList.remove("open");
            overlay.classList.remove("open");
        });
    }
</script>
