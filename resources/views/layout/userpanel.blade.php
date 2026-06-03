@php $customer = auth('customer')->user(); @endphp

<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    /* ─────────────────────────────────────────
   TOP NAVBAR
───────────────────────────────────────── */
    .up-navbar {
        background: #fff;
        border-bottom: 1px solid #e8f0e4;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        position: sticky;
        top: 0;
        z-index: 150;
    }

    .up-navbar__inner {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
        height: 64px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    /* Brand */
    .up-navbar__brand {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        flex-shrink: 0;
    }

    .up-navbar__brand-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, #2d7a45, #1a4d2e);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .up-navbar__brand-text {
        font-size: 17px;
        font-weight: 800;
        color: #1a2e1a;
    }

    .up-navbar__brand-text span {
        color: #2d7a45;
    }

    /* Divider */
    .up-navbar__divider {
        width: 1px;
        height: 28px;
        background: #e8f0e4;
        flex-shrink: 0;
    }

    /* Breadcrumb */
    .up-navbar__breadcrumb {
        font-size: 13px;
        color: #aaa;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .up-navbar__breadcrumb a {
        color: #2d7a45;
        text-decoration: none;
        font-weight: 600;
    }

    .up-navbar__breadcrumb a:hover {
        text-decoration: underline;
    }

    .up-navbar__breadcrumb svg {
        width: 13px;
        height: 13px;
    }

    /* Right side */
    .up-navbar__right {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Icon buttons */
    .up-navbar__icon-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid #e8f0e4;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #555;
        text-decoration: none;
        transition: all .2s;
        position: relative;
        flex-shrink: 0;
    }

    .up-navbar__icon-btn:hover {
        background: #f4faf0;
        border-color: #2d7a45;
        color: #2d7a45;
    }

    .up-navbar__icon-btn svg {
        width: 18px;
        height: 18px;
    }

    /* Shop button */
    .up-navbar__shop-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 10px;
        background: #f4faf0;
        border: 1px solid #c8e0c8;
        color: #2d7a45;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        white-space: nowrap;
    }

    .up-navbar__shop-btn:hover {
        background: #2d7a45;
        color: #fff;
        border-color: #2d7a45;
    }

    .up-navbar__shop-btn svg {
        width: 15px;
        height: 15px;
    }

    /* User chip */
    .up-navbar__user {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 12px 5px 5px;
        border-radius: 10px;
        border: 1px solid #e8f0e4;
        background: #fafafa;
    }

    .up-navbar__avatar {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: linear-gradient(135deg, #2d7a45, #1a4d2e);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .up-navbar__user-name {
        font-size: 13px;
        font-weight: 600;
        color: #1a2e1a;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ─────────────────────────────────────────
   LAYOUT
───────────────────────────────────────── */
    .db-wrapper {
        display: flex;
        max-width: 1400px;
        margin: 0 auto;
        padding: 28px 20px;
        gap: 24px;
        align-items: flex-start;
    }

    /* Hamburger (mobile only) */
    .up-hamburger {
        display: none;
        background: #fff;
        border: 1px solid #e8f0e4;
        border-radius: 8px;
        padding: 8px 9px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .up-hamburger svg {
        width: 20px;
        height: 20px;
        display: block;
        color: #555;
    }

    .overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 98;
    }

    .overlay.open {
        display: block;
    }

    /* Sidebar */
    .db-sidebar {
        width: 260px;
        flex-shrink: 0;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
        overflow: hidden;
        position: sticky;
        top: 92px;
    }

    .db-user {
        padding: 24px 20px;
        text-align: center;
        border-bottom: 1px solid #f0f0f0;
    }

    .db-avatar-wrap {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin: 0 auto 12px;
    }

    .db-avatar-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, #2d7a45, #1a4d2e);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        font-weight: 700;
        color: #fff;
    }

    .db-user h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 3px;
    }

    .db-user p {
        font-size: 12px;
        color: #999;
        margin: 0;
    }

    .db-nav {
        padding: 6px 0 12px;
    }

    .db-nav-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.2px;
        color: #2d7a45;
        text-transform: uppercase;
        padding: 10px 20px 7px;
        background: #f4faf0;
        border-top: 1px dashed #eee;
        border-bottom: 1px dashed #eee;
        margin: 4px 0;
        display: block;
    }

    .db-nav a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        font-size: 14px;
        color: #555;
        text-decoration: none;
        border-left: 3px solid transparent;
        transition: all .2s;
    }

    .db-nav a svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .db-nav a:hover,
    .db-nav a.active {
        color: #2d7a45;
        background: #f4faf0;
        border-left-color: #2d7a45;
        font-weight: 600;
    }

    /* Main */
    .db-main {
        flex: 1;
        min-width: 0;
    }

    .db-content {
        background: #fff;
        border-radius: 14px;
        padding: 28px 30px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
    }

    /* Responsive */
    @media (max-width: 960px) {
        .up-navbar__breadcrumb {
            display: none;
        }

        .up-navbar__divider {
            display: none;
        }
    }

    @media (max-width: 640px) {
        .up-navbar__user-name {
            display: none;
        }

        .up-navbar__shop-btn span {
            display: none;
        }

        .up-navbar__shop-btn {
            padding: 8px 10px;
        }
    }

    @media (max-width: 768px) {
        .up-hamburger {
            display: block;
        }

        .db-wrapper {
            padding: 16px 14px 24px;
            flex-direction: column;
            gap: 16px;
        }

        .db-sidebar {
            position: fixed;
            top: 0;
            left: -300px;
            bottom: 0;
            z-index: 99;
            border-radius: 0;
            width: 260px;
            overflow-y: auto;
            transition: left .3s ease;
            height: 100vh;
        }

        .db-sidebar.open {
            left: 0;
        }

        .db-content {
            padding: 20px 16px;
        }
    }
</style>

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

<div class="overlay" id="overlay"></div>

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
    hamburger.addEventListener("click", () => {
        sidebar.classList.add("open");
        overlay.classList.add("open");
    });
    overlay.addEventListener("click", () => {
        sidebar.classList.remove("open");
        overlay.classList.remove("open");
    });
</script>
