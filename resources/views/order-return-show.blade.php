@extends('layout.frontlayout')
@section('title', 'Return Details – Bharat Biomer')

@section('content')
<div class="container my-4 order-return-show-page">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="ors__page-title">Return Details</h1>
            <p class="ors__page-subtitle">Return request #{{ $return->id }}</p>

            <div class="ors__card">
                <div class="ors__header">
                    <div class="ors__return-num">Return #{{ $return->id }}</div>
                    <div class="ors__return-date">Submitted on {{ $return->created_at->format('d M Y, h:i A') }}</div>
                    <div class="ors__status-wrap">
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
                                <span class="ors__detail-value ors__detail-value--left">{{ $return->description }}</span>
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
                            @foreach($return->order->items as $item)
                            <div class="ors__item">
                                @if($item->product->featured_image)
                                    <img src="{{ Storage::url($item->product->featured_image) }}" alt="{{ $item->product->name }}" class="ors__item-img">
                                @else
                                    <div class="ors__item-img ors__item-img--placeholder"><iconify-icon icon="mdi:package-variant" class="icon"></iconify-icon></div>
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
