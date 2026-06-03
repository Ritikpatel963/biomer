@extends('layout.frontlayout')
@section('title', 'My Returns – Bharat Biomer')

@push('styles')
<style>
* { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }

.or__card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8f0e4;
    box-shadow: 0 2px 16px rgba(60,120,60,0.06);
    margin-bottom: 1.25rem;
    overflow: hidden;
}
.or__card:hover { box-shadow: 0 2px 16px rgba(60,120,60,0.06); }

.or__card-header {
    background: #f4faf0;
    border-bottom: 1px solid #e8f0e4;
    padding: 1rem 1.5rem;
    display: flex; flex-wrap: wrap;
    align-items: center; gap: 1rem;
}
.or__return-num { font-size: 0.95rem; font-weight: 800; color: #1a2e1a; }
.or__return-date { font-size: 0.8rem; color: #6b7c6b; }
.or__header-right { margin-left: auto; display: flex; align-items: center; gap: 1rem; }
.or__refund { font-size: 1rem; font-weight: 800; color: #2d7a45; }

.or__status {
    display: inline-block;
    padding: 4px 14px; border-radius: 20px;
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.4px;
}
.or__status--pending    { background:#fff8e1; color:#b45309; border:1px solid #fcd34d; }
.or__status--approved   { background:#e8f5ed; color:#2d7a45; border:1px solid #a8d5b5; }
.or__status--rejected   { background:#fdecea; color:#c0392b; border:1px solid #f5a9a4; }
.or__status--refunded   { background:#e8f5ed; color:#2d7a45; border:1px solid #a8d5b5; }

.or__card-body { padding: 1.1rem 1.5rem; }
.or__order-info { margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #f0f5ee; }
.or__order-num { font-size: 0.9rem; color: #1a2e1a; font-weight: 600; }
.or__reason { font-size: 0.85rem; color: #6b7c6b; margin-top: 0.25rem; }
.or__description { font-size: 0.8rem; color: #6b7c6b; margin-top: 0.5rem; line-height: 1.4; }

.or__card-footer {
    border-top: 1px solid #f0f5ee;
    padding: 0.85rem 1.5rem;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 0.5rem;
}
.or__view-btn {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.5rem 1.25rem;
    background: #2d7a45; color: #fff;
    font-size: 0.82rem; font-weight: 700;
    border-radius: 8px; text-decoration: none;
    transition: background 0.2s;
}
.or__view-btn:hover { background: #245e36; color: #fff; }

.or__empty {
    text-align: center; padding: 5rem 1rem;
    background: #fff; border-radius: 16px;
    border: 1px solid #e8f0e4;
}
.or__empty-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }
.or__empty h3 { font-size: 1.4rem; font-weight: 700; color: #1a2e1a; margin-bottom: 0.5rem; }
.or__empty p { color: #6b7c6b; margin-bottom: 1.5rem; font-size: 0.92rem; }
.or__empty-btn {
    display: inline-block; padding: 0.75rem 2rem;
    background: #2d7a45; color: #fff;
    font-weight: 700; border-radius: 10px; text-decoration: none;
}
.or__empty-btn:hover { background: #245e36; color: #fff; }

@media (max-width: 576px) {
    .or__card-header { flex-direction: column; align-items: flex-start; }
    .or__header-right { margin-left: 0; }
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
            <h1 style="font-size:1.6rem; font-weight:800; color:#1a2e1a; margin-bottom:0.2rem;">My Returns</h1>
            <p style="font-size:0.9rem; color:#6b7c6b; margin-bottom:1.75rem;">Track and manage your return requests</p>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($returns->count() > 0)
        @foreach($returns as $return)
        <div class="or__card">
            <div class="or__card-header">
                <div>
                    <div class="or__return-num">Return #{{ $return->id }}</div>
                    <div class="or__return-date">{{ $return->created_at->format('d M Y, h:i A') }}</div>
                </div>
                <div class="or__header-right">
                    <span class="or__status or__status--{{ $return->status }}">
                        {{ ucfirst($return->status) }}
                    </span>
                    <span class="or__refund">₹{{ number_format($return->refund_amount, 2) }}</span>
                </div>
            </div>

            <div class="or__card-body">
                <div class="or__order-info">
                    <div class="or__order-num">Order #{{ $return->order->order_number }}</div>
                    <div class="or__reason"><strong>Reason:</strong> {{ ucfirst(str_replace('_', ' ', $return->reason)) }}</div>
                    @if($return->description)
                        <div class="or__description">{{ $return->description }}</div>
                    @endif
                </div>
            </div>

            <div class="or__card-footer">
                <div>
                    @if($return->status === 'approved' && $return->approved_at)
                        <small class="text-muted">Approved on {{ $return->approved_at->format('d M Y') }}</small>
                    @elseif($return->status === 'refunded' && $return->refunded_at)
                        <small class="text-muted">Refunded on {{ $return->refunded_at->format('d M Y') }}</small>
                    @elseif($return->status === 'rejected')
                        <small class="text-muted">Request reviewed</small>
                    @else
                        <small class="text-muted">Under review</small>
                    @endif
                </div>
                <a href="{{ route('order-returns.show', $return->id) }}" class="or__view-btn">
                    View Details →
                </a>
            </div>
        </div>
        @endforeach

        <div class="d-flex justify-content-center mt-4">
            {{ $returns->links() }}
        </div>

    @else
        <div class="or__empty">
            <div class="or__empty-icon"><i class="ri-refresh-line" aria-hidden="true"></i></div>
            <h3>No return requests yet</h3>
            <p>You haven't submitted any return requests. If you need to return an item, you can do so from your order details.</p>
            <a href="{{ route('orders.index') }}" class="or__empty-btn">View My Orders</a>
        </div>
    @endif
        </div>
    </div>
</div>
@endsection
