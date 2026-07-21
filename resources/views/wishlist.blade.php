@extends('layout.frontlayout')
@section('title', 'My Wishlist – Bharat Biomer')

@section('content')
<div class="container my-4 wl-page" data-cart-add-url="{{ route('cart.add') }}">
    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-lg-3">
            @include('components.customer-sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-lg-9">
            <h1 class="wl__page-title">My Wishlist</h1>
            <p class="wl__page-subtitle">{{ $wishlists->count() }} saved product(s)</p>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($wishlists->count() > 0)
<div class="row g-4">
    @foreach($wishlists as $wishlist)
    <div class="col-12 col-sm-6 col-lg-4" id="wl-item-{{ $wishlist->product_id }}">
        <div class="wl__card">

            <div class="wl__img-wrap">
                @if($wishlist->product->featured_image)
                    <img src="{{ Storage::url($wishlist->product->featured_image) }}" alt="{{ $wishlist->product->name }}" class="wl__img">
                @else
                    <img src="assets/images/product-bottle.svg" alt="{{ $wishlist->product->name }}" class="wl__img">
                @endif

                <form action="{{ route('wishlist.remove') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $wishlist->product_id }}">
                    <button type="submit" class="wl__remove-btn" title="Remove from wishlist">✕</button>
                </form>
            </div>

            <div class="wl__body">
                <h4 class="wl__name">{{ $wishlist->product->name }}</h4>
                <div class="wl__price">
                    @if($wishlist->product->variations->count())
                        From ₹{{ number_format($wishlist->product->variations->min('price'), 2) }}
                    @else
                        ₹{{ number_format($wishlist->product->base_price, 2) }}
                    @endif
                </div>
                <div class="wl__actions">
                    <a href="{{ route('products.show', $wishlist->product->slug) }}" class="wl__btn wl__btn--outline">View</a>
                    <button class="wl__btn wl__btn--primary add-to-cart-wl"
                            data-id="{{ $wishlist->product_id }}"
                            data-name="{{ $wishlist->product->name }}">
                        Add to Cart
                    </button>
                </div>
            </div>

        </div>
    </div>
    @endforeach
</div>

@else
<div class="wl__empty">
    <div class="wl__empty-icon"><i class="ri-heart-3-line" aria-hidden="true"></i></div>
    <h3 class="wl__empty-title">Your wishlist is empty</h3>
    <p class="wl__empty-text">Save products you love and come back to them later.</p>
    <a href="{{ route('products.index') }}" class="wl__empty-btn">
        Browse Products
    </a>
</div>
@endif

        </div>
    </div>
</div>
@endsection
