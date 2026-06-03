{{-- Customer Sidebar Navigation --}}
<style>
.sb__sidebar {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e8f0e4;
    padding: 1.5rem;
    height: fit-content;
    position: sticky;
    top: 20px;
}

.sb__title {
    font-size: 1rem;
    font-weight: 700;
    color: #1a2e1a;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f0f5ee;
}

.sb__nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    margin-bottom: 0.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.95rem;
    color: #555;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}

.sb__nav-link:hover {
    background: #f4faf0;
    color: #2d7a45;
    border-left-color: #2d7a45;
}

.sb__nav-link.active {
    background: #e8f5ed;
    color: #2d7a45;
    font-weight: 600;
    border-left-color: #2d7a45;
}

.sb__icon {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sb__badge {
    margin-left: auto;
    background: #2d7a45;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
}

@media (max-width: 768px) {
    .sb__sidebar {
        margin-bottom: 1.5rem;
        position: static;
    }
}
</style>

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
