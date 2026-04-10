@extends('layout.frontlayout')
@section('title', 'My Orders – Bharat Biomer')

@push('styles')
<style>
* { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }

.mo__card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8f0e4;
    box-shadow: 0 2px 16px rgba(60,120,60,0.06);
    margin-bottom: 1.25rem;
    overflow: hidden;
    transition: box-shadow 0.2s;
}
.mo__card:hover { box-shadow: 0 4px 24px rgba(60,120,60,0.12); }

.mo__card-header {
    background: #f4faf0;
    border-bottom: 1px solid #e8f0e4;
    padding: 1rem 1.5rem;
    display: flex; flex-wrap: wrap;
    align-items: center; gap: 1rem;
}
.mo__order-num { font-size: 0.95rem; font-weight: 800; color: #1a2e1a; }
.mo__order-date { font-size: 0.8rem; color: #6b7c6b; }
.mo__header-right { margin-left: auto; display: flex; align-items: center; gap: 1rem; }
.mo__total { font-size: 1rem; font-weight: 800; color: #2d7a45; }

.mo__status {
    display: inline-block;
    padding: 4px 14px; border-radius: 20px;
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.4px;
}
.mo__status--pending    { background:#fff8e1; color:#b45309; border:1px solid #fcd34d; }
.mo__status--confirmed  { background:#e8f5ed; color:#2d7a45; border:1px solid #a8d5b5; }
.mo__status--processing { background:#e8f5fd; color:#1a6fa8; border:1px solid #90caf9; }
.mo__status--shipped    { background:#f3e8fd; color:#6d28d9; border:1px solid #c4b5fd; }
.mo__status--delivered  { background:#e8f5ed; color:#2d7a45; border:1px solid #a8d5b5; }
.mo__status--cancelled  { background:#fdecea; color:#c0392b; border:1px solid #f5a9a4; }

.mo__card-body { padding: 1.1rem 1.5rem; }
.mo__items-row { display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; }
.mo__item-chip {
    display: flex; align-items: center; gap: 0.5rem;
    background: #f4faf0; border: 1px solid #e8f0e4;
    border-radius: 10px; padding: 0.4rem 0.75rem;
    font-size: 0.8rem; color: #1a2e1a;
}
.mo__item-chip img { width: 32px; height: 32px; object-fit: contain; border-radius: 6px; background: #fff; }
.mo__item-chip-placeholder {
    width: 32px; height: 32px; background: #fff; border-radius: 6px;
    display: flex; align-items: center; justify-content: center; font-size: 0.9rem;
}
.mo__more-items {
    font-size: 0.78rem; color: #6b7c6b;
    background: #f0f5ee; border: 1px solid #e8f0e4;
    border-radius: 10px; padding: 0.4rem 0.75rem;
}

.mo__card-footer {
    border-top: 1px solid #f0f5ee;
    padding: 0.85rem 1.5rem;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 0.5rem;
}
.mo__address { font-size: 0.78rem; color: #6b7c6b; }
.mo__view-btn {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.5rem 1.25rem;
    background: #2d7a45; color: #fff;
    font-size: 0.82rem; font-weight: 700;
    border-radius: 8px; text-decoration: none;
    transition: background 0.2s;
}
.mo__view-btn:hover { background: #245e36; color: #fff; }

.mo__empty {
    text-align: center; padding: 5rem 1rem;
    background: #fff; border-radius: 16px;
    border: 1px solid #e8f0e4;
}
.mo__empty-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }
.mo__empty h3 { font-size: 1.4rem; font-weight: 700; color: #1a2e1a; margin-bottom: 0.5rem; }
.mo__empty p { color: #6b7c6b; margin-bottom: 1.5rem; font-size: 0.92rem; }
.mo__empty-btn {
    display: inline-block; padding: 0.75rem 2rem;
    background: #2d7a45; color: #fff;
    font-weight: 700; border-radius: 10px; text-decoration: none;
}
.mo__empty-btn:hover { background: #245e36; color: #fff; }

@media (max-width: 576px) {
    .mo__card-header { flex-direction: column; align-items: flex-start; }
    .mo__header-right { margin-left: 0; }
}
</style>
@endpush

@section('content')
<div class="container my-4">
    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-lg-3">
            @include('components.customer-sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-lg-9">
            <h1 style="font-size:1.6rem; font-weight:800; color:#1a2e1a; margin-bottom:0.2rem;">My Orders</h1>
            <p style="font-size:0.9rem; color:#6b7c6b; margin-bottom:1.75rem;">Track and manage your orders</p>

@if($orders->count() > 0)

    @foreach($orders as $order)
    <div class="mo__card">

        <div class="mo__card-header">
            <div>
                <div class="mo__order-num"># {{ $order->order_number }}</div>
                <div class="mo__order-date">{{ $order->created_at->format('d M Y, h:i A') }}</div>
            </div>
            <div class="mo__header-right">
                <span class="mo__status mo__status--{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
                <span class="mo__total">₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <div class="mo__card-body">
            <div class="mo__items-row">
                @foreach($order->items->take(3) as $item)
                <div class="mo__item-chip">
                    @if($item->product && $item->product->featured_image)
                        <img src="{{ Storage::url($item->product->featured_image) }}" alt="{{ $item->product_name }}">
                    @else
                        <div class="mo__item-chip-placeholder">🌿</div>
                    @endif
                    <span>{{ Str::limit($item->product_name, 25) }} × {{ $item->quantity }}</span>
                </div>
                @endforeach
                @if($order->items->count() > 3)
                    <span class="mo__more-items">+{{ $order->items->count() - 3 }} more</span>
                @endif
            </div>
        </div>

        <div class="mo__card-footer">
            <div class="mo__address">
                📦 {{ $order->city }}, {{ $order->state }} – {{ $order->pincode }}
            </div>
            <div class="d-flex align-items-center gap-2">
                @if($order->canRequestReturn())
                    <a href="{{ route('order-returns.create', $order->order_number) }}"
                       class="mo__view-btn" style="background:#fff4e5; color:#9a5b00; border:1px solid #f3c77a;">
                        Return Product
                    </a>
                @elseif($order->orderReturn)
                    <a href="{{ route('order-returns.show', $order->orderReturn->id) }}"
                       class="mo__view-btn" style="background:#f4faf0; color:#2d7a45; border:1px solid #a8d5b5;">
                        Return Status
                    </a>
                @endif
                <a href="{{ route('orders.invoice', $order->order_number) }}"
                   class="mo__view-btn" style="background:#1a6fa8;" target="_blank">
                    ⬇ Invoice
                </a>
                <a href="{{ route('orders.show', $order->order_number) }}" class="mo__view-btn">
                    View Details →
                </a>
            </div>
        </div>

    </div>
    @endforeach

    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>

@else
    <div class="mo__empty">
        <div class="mo__empty-icon">📦</div>
        <h3>No orders yet</h3>
        <p>You haven't placed any orders. Start shopping!</p>
        <a href="{{ route('products.index') }}" class="mo__empty-btn">Browse Products</a>
    </div>
@endif
        </div>
    </div>
</div>
@endsection
