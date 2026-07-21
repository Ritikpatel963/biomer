

<div class="sb__sidebar">
    <div class="sb__title">Account Menu</div>
    
    <a href="{{ route('customer.account') }}" 
       class="sb__nav-link {{ request()->routeIs('customer.account') && !request()->routeIs('customer.account.edit') ? 'active' : '' }}">
        <div class="sb__icon"><i class="ri-user-3-line" aria-hidden="true"></i></div>
        My Account
    </a>

    <a href="{{ route('orders.index') }}" 
       class="sb__nav-link {{ request()->routeIs('orders.index', 'orders.show') ? 'active' : '' }}">
        <div class="sb__icon"><i class="ri-box-3-line" aria-hidden="true"></i></div>
        My Orders
    </a>

    <a href="{{ route('wishlist.index') }}" 
       class="sb__nav-link {{ request()->routeIs('wishlist.index') ? 'active' : '' }}">
        <div class="sb__icon"><i class="ri-heart-3-line" aria-hidden="true"></i></div>
        My Wishlist
    </a>

    <a href="{{ route('order-returns.index') }}" 
       class="sb__nav-link {{ request()->routeIs('order-returns.index', 'order-returns.create', 'order-returns.show') ? 'active' : '' }}">
        <div class="sb__icon"><i class="ri-refresh-line" aria-hidden="true"></i></div>
        My Returns
    </a>

    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #f0f5ee;">
        <a href="{{ route('customer.dashboard') }}" 
           class="sb__nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <div class="sb__icon"><i class="ri-bar-chart-box-line" aria-hidden="true"></i></div>
            Dashboard
        </a>
    </div>
</div>
