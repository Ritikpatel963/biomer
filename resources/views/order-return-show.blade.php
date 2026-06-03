@extends('layout.frontlayout')
@section('title', 'Return Details – Bharat Biomer')

@push('styles')
<style>
* { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }

.ors__card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8f0e4;
    box-shadow: 0 2px 16px rgba(60,120,60,0.06);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.ors__header {
    background: #f4faf0;
    border-bottom: 1px solid #e8f0e4;
    padding: 1.25rem 1.5rem;
}
.ors__return-num { font-size: 1.1rem; font-weight: 800; color: #1a2e1a; margin-bottom: 0.25rem; }
.ors__return-date { font-size: 0.85rem; color: #6b7c6b; }
.ors__status {
    display: inline-block;
    padding: 4px 14px; border-radius: 20px;
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.4px;
}
.ors__status--pending    { background:#fff8e1; color:#b45309; border:1px solid #fcd34d; }
.ors__status--approved   { background:#e8f5ed; color:#2d7a45; border:1px solid #a8d5b5; }
.ors__status--rejected   { background:#fdecea; color:#c0392b; border:1px solid #f5a9a4; }
.ors__status--refunded   { background:#e8f5ed; color:#2d7a45; border:1px solid #a8d5b5; }

.ors__body { padding: 1.5rem; }
.ors__section { margin-bottom: 2rem; }
.ors__section-title { font-size: 1rem; font-weight: 700; color: #1a2e1a; margin-bottom: 1rem; }

.ors__order-info {
    background: #f9fbf8; border: 1px solid #e8f0e4; border-radius: 10px;
    padding: 1rem; margin-bottom: 1rem;
}
.ors__order-num { font-size: 0.9rem; font-weight: 600; color: #1a2e1a; margin-bottom: 0.25rem; }
.ors__order-date { font-size: 0.8rem; color: #6b7c6b; }

.ors__details { display: grid; gap: 1rem; }
.ors__detail-row { display: flex; justify-content: space-between; align-items: center; }
.ors__detail-label { font-size: 0.9rem; color: #6b7c6b; }
.ors__detail-value { font-size: 0.9rem; font-weight: 600; color: #1a2e1a; }

.ors__items { display: grid; gap: 1rem; }
.ors__item {
    display: flex; align-items: center; gap: 1rem;
    padding: 1rem; background: #f9fbf8; border-radius: 10px;
    border: 1px solid #e8f0e4;
}
.ors__item-img {
    width: 60px; height: 60px; object-fit: contain;
    border-radius: 8px; background: #fff; border: 1px solid #e8f0e4;
}
.ors__item-details { flex: 1; }
.ors__item-name { font-size: 0.9rem; font-weight: 600; color: #1a2e1a; margin-bottom: 0.25rem; }
.ors__item-meta { font-size: 0.8rem; color: #6b7c6b; }

.ors__timeline {
    position: relative;
    padding-left: 2rem;
}
.ors__timeline::before {
    content: ''; position: absolute; left: 0.75rem; top: 0; bottom: 0;
    width: 2px; background: #e8f0e4;
}
.ors__timeline-item {
    position: relative; margin-bottom: 1.5rem;
    padding-left: 1rem;
}
.ors__timeline-item::before {
    content: ''; position: absolute; left: -1.25rem; top: 0.25rem;
    width: 12px; height: 12px; border-radius: 50%;
    background: #2d7a45; border: 2px solid #fff;
}
.ors__timeline-item--pending::before { background: #f59e0b; }
.ors__timeline-item--rejected::before { background: #ef4444; }
.ors__timeline-date { font-size: 0.8rem; color: #6b7c6b; margin-bottom: 0.25rem; }
.ors__timeline-title { font-size: 0.9rem; font-weight: 600; color: #1a2e1a; margin-bottom: 0.25rem; }
.ors__timeline-desc { font-size: 0.85rem; color: #6b7c6b; }

.ors__actions {
    display: flex; gap: 1rem; justify-content: flex-end;
    padding-top: 1rem; border-top: 1px solid #f0f5ee;
}
.ors__btn {
    padding: 0.75rem 2rem; border-radius: 8px;
    font-size: 0.9rem; font-weight: 600; border: none;
    text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
    transition: all 0.2s;
}
.ors__btn--outline { background: transparent; color: #2d7a45; border: 1.5px solid #2d7a45; }
.ors__btn--outline:hover { background: #f0faf4; }

@media (max-width: 768px) {
    .ors__detail-row { flex-direction: column; align-items: flex-start; gap: 0.25rem; }
    .ors__item { flex-direction: column; text-align: center; }
    .ors__actions { flex-direction: column; }
    .ors__btn { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 style="font-size:1.6rem; font-weight:800; color:#1a2e1a; margin-bottom:0.2rem;">Return Details</h1>
            <p style="font-size:0.9rem; color:#6b7c6b; margin-bottom:1.75rem;">Return request #{{ $return->id }}</p>

            <div class="ors__card">
                <div class="ors__header">
                    <div class="ors__return-num">Return #{{ $return->id }}</div>
                    <div class="ors__return-date">Submitted on {{ $return->created_at->format('d M Y, h:i A') }}</div>
                    <div style="margin-top: 0.5rem;">
                        <span class="ors__status ors__status--{{ $return->status }}">
                            {{ ucfirst($return->status) }}
                        </span>
                    </div>
                </div>

                <div class="ors__body">
                    <div class="ors__section">
                        <h3 class="ors__section-title">Order Information</h3>
                        <div class="ors__order-info">
                            <div class="ors__order-num">Order #{{ $return->order->order_number }}</div>
                            <div class="ors__order-date">Ordered on {{ $return->order->created_at->format('d M Y') }} • Total: ₹{{ number_format($return->order->total_amount, 2) }}</div>
                        </div>
                    </div>

                    <div class="ors__section">
                        <h3 class="ors__section-title">Return Details</h3>
                        <div class="ors__details">
                            <div class="ors__detail-row">
                                <span class="ors__detail-label">Reason:</span>
                                <span class="ors__detail-value">{{ ucfirst(str_replace('_', ' ', $return->reason)) }}</span>
                            </div>
                            @if($return->description)
                            <div class="ors__detail-row">
                                <span class="ors__detail-label">Description:</span>
                                <span class="ors__detail-value" style="text-align: left;">{{ $return->description }}</span>
                            </div>
                            @endif
                            <div class="ors__detail-row">
                                <span class="ors__detail-label">Refund Amount:</span>
                                <span class="ors__detail-value">₹{{ number_format($return->refund_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="ors__section">
                        <h3 class="ors__section-title">Order Items</h3>
                        <div class="ors__items">
                            @foreach($return->order->orderItems as $item)
                            <div class="ors__item">
                                @if($item->product->featured_image)
                                    <img src="{{ Storage::url($item->product->featured_image) }}" alt="{{ $item->product->name }}" class="ors__item-img">
                                @else
                                    <div class="ors__item-img" style="display:flex; align-items:center; justify-content:center; background:#f0f5ee;"><iconify-icon icon="mdi:package-variant" class="icon"></iconify-icon></div>
                                @endif
                                <div class="ors__item-details">
                                    <div class="ors__item-name">{{ $item->product->name }}</div>
                                    <div class="ors__item-meta">
                                        Quantity: {{ $item->quantity }} • Price: ₹{{ number_format($item->price, 2) }}
                                        @if($item->variation_name)
                                            • {{ $item->variation_name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="ors__section">
                        <h3 class="ors__section-title">Status Timeline</h3>
                        <div class="ors__timeline">
                            <div class="ors__timeline-item">
                                <div class="ors__timeline-date">{{ $return->created_at->format('d M Y, h:i A') }}</div>
                                <div class="ors__timeline-title">Return Request Submitted</div>
                                <div class="ors__timeline-desc">Your return request has been received and is under review.</div>
                            </div>

                            @if($return->status === 'approved' && $return->approved_at)
                            <div class="ors__timeline-item">
                                <div class="ors__timeline-date">{{ $return->approved_at->format('d M Y, h:i A') }}</div>
                                <div class="ors__timeline-title">Return Request Approved</div>
                                <div class="ors__timeline-desc">Your return request has been approved. Refund processing will begin shortly.</div>
                            </div>
                            @endif

                            @if($return->status === 'refunded' && $return->refunded_at)
                            <div class="ors__timeline-item">
                                <div class="ors__timeline-date">{{ $return->refunded_at->format('d M Y, h:i A') }}</div>
                                <div class="ors__timeline-title">Refund Processed</div>
                                <div class="ors__timeline-desc">The refund of ₹{{ number_format($return->refund_amount, 2) }} has been processed to your original payment method.</div>
                            </div>
                            @endif

                            @if($return->status === 'rejected')
                            <div class="ors__timeline-item ors__timeline-item--rejected">
                                <div class="ors__timeline-date">{{ $return->updated_at->format('d M Y, h:i A') }}</div>
                                <div class="ors__timeline-title">Return Request Rejected</div>
                                <div class="ors__timeline-desc">
                                    @if($return->admin_notes)
                                        {{ $return->admin_notes }}
                                    @else
                                        Your return request could not be processed. Please contact customer support for more details.
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($return->status === 'pending')
                            <div class="ors__timeline-item ors__timeline-item--pending">
                                <div class="ors__timeline-date">In Progress</div>
                                <div class="ors__timeline-title">Under Review</div>
                                <div class="ors__timeline-desc">Our team is reviewing your return request. We'll update you soon.</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="ors__actions">
                        <a href="{{ route('order-returns.index') }}" class="ors__btn ors__btn--outline">
                            ← Back to Returns
                        </a>
                        @if($return->status === 'pending')
                        <a href="mailto:support@bharatbiomer.com?subject=Return Request #{{ $return->id }}" class="ors__btn ors__btn--outline">
                            Contact Support
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection