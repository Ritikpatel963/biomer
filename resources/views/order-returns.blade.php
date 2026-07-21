@extends('layout.frontlayout')
@section('title', 'My Returns – Bharat Biomer')

@section('content')
<div class="container my-4 order-returns-page">
    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-lg-3">
            @include('components.customer-sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-lg-9">
            <h1 class="or__page-title">My Returns</h1>
            <p class="or__page-subtitle">Track and manage your return requests</p>

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
