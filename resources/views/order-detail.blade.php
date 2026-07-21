@extends('layout.frontlayout')
@section('title', 'Order #{{ $order->order_number }} – Bharat Biomer')

@section('content')
<div class="container my-4 order-detail-page">
    <div class="row g-4">
        <div class="col-lg-3">
            @include('components.customer-sidebar')
        </div>

        <div class="col-lg-9">

<a href="{{ route('orders.index') }}" class="od__back"><i class="ri-arrow-left-line" aria-hidden="true"></i> Back to My Orders</a>

<h1 class="od__page-title">Order Details</h1>
<p class="od__page-subtitle">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>

<div class="od__status-card">
    <div>
        <div class="od__order-num"># {{ $order->order_number }}</div>
        <div class="od__order-date">{{ $order->items->count() }} item(s)</div>
    </div>
    <div class="od__status-right">
        <span class="od__status od__status--{{ $order->status }}">{{ ucfirst($order->status) }}</span>
        <span class="od__total-big">₹{{ number_format($order->total_amount, 2) }}</span>
    </div>
</div>

@php
    $steps   = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
    $current = array_search($order->status, $steps);
    if ($current === false) $current = -1;
@endphp
@if($order->status !== 'cancelled')
<div class="od__progress-card">
    <div class="od__progress-steps">
        @foreach($steps as $i => $step)
            <div class="od__step {{ $i < $current ? 'done' : ($i == $current ? 'active' : '') }}">
                <div class="od__step-circle">
                    @if($i < $current) <i class="ri-check-line" aria-hidden="true"></i> @else {{ $i + 1 }} @endif
                </div>
                <div class="od__step-label">{{ ucfirst($step) }}</div>
            </div>
            @if(!$loop->last)
                <div class="od__step-line {{ $i < $current ? 'done' : '' }}"></div>
            @endif
        @endforeach
    </div>
</div>
@endif

<div class="row g-4">

    <div class="col-12 col-lg-7">
        <div class="od__card">
            <div class="od__card-title"><i class="ri-box-3-line" aria-hidden="true"></i> Items Ordered</div>

            @foreach($order->items as $item)
            <div class="od__item">
                @if($item->product && $item->product->featured_image)
                    <img src="{{ Storage::url($item->product->featured_image) }}" alt="{{ $item->product_name }}" class="od__item-img">
                @else
                    <div class="od__item-placeholder"><i class="ri-box-3-line" aria-hidden="true"></i></div>
                @endif
                <div class="od__item-info">
                    <div class="od__item-name">{{ $item->product_name }}</div>
                    @if($item->variation_name)
                        <div class="od__item-meta">{{ $item->variation_name }}</div>
                    @endif
                    <div class="od__item-meta">Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price, 2) }}</div>
                    @if($item->sku)
                        <div class="od__item-meta">SKU: {{ $item->sku }}</div>
                    @endif
                </div>
                <div class="od__item-price">₹{{ number_format($item->subtotal, 2) }}</div>
            </div>
            @endforeach

            <div class="od__totals">
                <div class="od__total-row"><span>Subtotal</span><span>₹{{ number_format($order->total_amount - $order->shipping_amount, 2) }}</span></div>
                <div class="od__total-row"><span>Shipping</span><span class="od__shipping-value">@if($order->shipping_amount > 0)₹{{ number_format($order->shipping_amount, 2) }}@else Free @endif</span></div>
                <div class="od__total-row"><span>Tax (GST)</span><span>Included</span></div>
                <div class="od__total-row grand"><span>Total</span><span>₹{{ number_format($order->total_amount, 2) }}</span></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="od__card">
            <div class="od__card-title"><i class="ri-truck-line" aria-hidden="true"></i> Shipping Details</div>
            <div class="od__detail-row"><span class="od__detail-label">Name</span><span class="od__detail-value">{{ $order->name }}</span></div>
            <div class="od__detail-row"><span class="od__detail-label">Phone</span><span class="od__detail-value">{{ $order->phone }}</span></div>
            <div class="od__detail-row"><span class="od__detail-label">Email</span><span class="od__detail-value">{{ $order->email }}</span></div>
            <div class="od__detail-row"><span class="od__detail-label">Address</span><span class="od__detail-value">{{ $order->address }}</span></div>
            <div class="od__detail-row"><span class="od__detail-label">City</span><span class="od__detail-value">{{ $order->city }}</span></div>
            <div class="od__detail-row"><span class="od__detail-label">State</span><span class="od__detail-value">{{ $order->state }}</span></div>
            <div class="od__detail-row"><span class="od__detail-label">PIN Code</span><span class="od__detail-value">{{ $order->pincode }}</span></div>
            @if($order->notes)
            <div class="od__detail-row"><span class="od__detail-label">Notes</span><span class="od__detail-value">{{ $order->notes }}</span></div>
            @endif
        </div>

        <div class="od__card od__help-card">
            <div class="od__card-title od__help-title"><i class="ri-customer-service-2-line" aria-hidden="true"></i> Need Help?</div>
            <p class="od__help-text">
                For any queries about this order, please contact our support team.
            </p>
            @if($order->canRequestReturn())
            <a href="{{ route('order-returns.create', $order->order_number) }}"
               class="od__help-btn od__help-btn--return">
                Return Product
            </a>
            @elseif($order->orderReturn)
            <a href="{{ route('order-returns.show', $order->orderReturn->id) }}"
               class="od__help-btn od__help-btn--status">
                View Return Status
            </a>
            @endif
            <a href="mailto:support@bharatbiomer.com"
               class="od__help-btn od__help-btn--support">
                <i class="ri-mail-line" aria-hidden="true"></i> Email Support
            </a>
            <div class="od__back-all-wrap">
                <a href="{{ route('orders.index') }}" class="od__back-all">
                    <i class="ri-arrow-left-line" aria-hidden="true"></i> Back to All Orders
                </a>
            </div>
        </div>
    </div>

</div>

        </div>
    </div>
</div>

@endsection
