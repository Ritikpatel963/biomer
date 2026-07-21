@extends('layout.frontlayout')
@section('title', 'My Orders – Bharat Biomer')

@section('content')
<div class="container my-4 my-orders-page">
    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-lg-3">
            @include('components.customer-sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-lg-9">
            <h1 class="mo__page-title">My Orders</h1>
            <p class="mo__page-subtitle">Track and manage your orders</p>

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
                        <div class="mo__item-chip-placeholder"><i class="ri-box-3-line" aria-hidden="true"></i></div>
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
                <i class="ri-map-pin-line" aria-hidden="true"></i> {{ $order->city }}, {{ $order->state }} – {{ $order->pincode }}
            </div>
            <div class="d-flex align-items-center gap-2">
                @if($order->canRequestReturn())
                    <a href="{{ route('order-returns.create', $order->order_number) }}"
                       class="mo__view-btn mo__view-btn--return">
                        Return Product
                    </a>
                @elseif($order->orderReturn)
                    <a href="{{ route('order-returns.show', $order->orderReturn->id) }}"
                       class="mo__view-btn mo__view-btn--status">
                        Return Status
                    </a>
                @endif
                <a href="{{ route('orders.invoice', $order->order_number) }}"
                   class="mo__view-btn mo__view-btn--invoice" target="_blank">
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
        <div class="mo__empty-icon"><i class="ri-box-3-line" aria-hidden="true"></i></div>
        <h3>No orders yet</h3>
        <p>You haven't placed any orders. Start shopping!</p>
        <a href="{{ route('products.index') }}" class="mo__empty-btn">Browse Products</a>
    </div>
@endif
        </div>
    </div>
</div>
@endsection
