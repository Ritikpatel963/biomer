@extends('layout.frontlayout')
@section('title', 'My Dashboard – Bharat Biomer')

@section('content')
<div class="container py-5 customer-dashboard">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-lg-3 mb-4 mb-lg-0">
            @include('components.customer-sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-lg-9">
            <h1 class="db-page-title mb-4">My Dashboard</h1>

            {{-- ── Stats Cards ── --}}
            <div class="row g-4 mb-5">
                <div class="col-md-4 col-sm-6">
                    <div class="db-card card-green">
                        <div class="db-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg>
                        </div>
                        <div class="db-card-info"><h3>{{ $totalOrders ?? 0 }}</h3><span>Total Orders</span></div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="db-card card-blue">
                        <div class="db-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"/></svg>
                        </div>
                        <div class="db-card-info"><h3>{{ $completedOrders ?? 0 }}</h3><span>Completed Orders</span></div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="db-card card-orange">
                        <div class="db-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        </div>
                        <div class="db-card-info"><h3>{{ $pendingOrders ?? 0 }}</h3><span>Pending Orders</span></div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="db-card card-red">
                        <div class="db-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </div>
                        <div class="db-card-info"><h3>{{ $cancelledOrders ?? 0 }}</h3><span>Cancelled Orders</span></div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="db-card card-purple">
                        <div class="db-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                        </div>
                        <div class="db-card-info"><h3>{{ $totalWishlist ?? 0 }}</h3><span>Total Wishlist</span></div>
                    </div>
                </div>
            </div>

            {{-- ── Bottom Section ── --}}
            <div class="row g-4">
                {{-- Recent Orders --}}
                <div class="col-lg-12">
                    <div class="db-panel">
                        <h3>Recent Orders</h3>
                        @if(optional($recentOrders)->count() > 0)
                        <div class="table-responsive">
                            <table class="db-orders-table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders ?? [] as $order)
                                    <tr>
                                        <td><strong>#{{ $order->order_number }}</strong></td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
                                        <td>
                                            <span class="db-status db-status--{{ $order->status }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.show', $order->order_number) }}" class="db-view-link">View →</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('orders.index') }}" class="db-view-link">View all orders →</a>
                        </div>
                        @else
                        <div class="db-empty-order">No recent orders found.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

@endsection
