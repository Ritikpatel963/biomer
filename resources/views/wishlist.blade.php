@extends('layout.frontlayout')
@section('title', 'My Wishlist – Bharat Biomer')

@push('styles')
<style>
.wl__card {
    background: #fff; border-radius: 16px; border: 1px solid #e8f0e4;
    box-shadow: 0 2px 16px rgba(60,120,60,0.06);
    overflow: hidden;
    height: 100%; display: flex; flex-direction: column;
}
.wl__card:hover { box-shadow: 0 2px 16px rgba(60,120,60,0.06); }
.wl__img-wrap {
    background: #f4faf0; padding: 1.5rem;
    display: flex; align-items: center; justify-content: center;
    min-height: 180px; position: relative;
}
.wl__img { max-height: 140px; width: auto; object-fit: contain; }
.wl__remove-btn {
    position: absolute; top: 10px; right: 10px;
    width: 32px; height: 32px; border-radius: 50%;
    background: #fff; border: 1px solid #f0e4e4;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 1rem; color: #e74c3c; transition: all 0.2s;
}
.wl__remove-btn:hover { background: #fdecea; }
.wl__body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
.wl__name { font-size: 1rem; font-weight: 700; color: #1a2e1a; margin-bottom: 0.4rem; }
.wl__price { font-size: 1.2rem; font-weight: 800; color: #2d7a45; margin-bottom: 1rem; }
.wl__actions { display: flex; gap: 8px; margin-top: auto; }
.wl__btn {
    flex: 1; padding: 0.5rem; border-radius: 8px;
    font-size: 0.85rem; font-weight: 600; text-align: center;
    text-decoration: none; border: none; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;
}
.wl__btn--primary { background: #2d7a45; color: #fff; }
.wl__btn--primary:hover { background: #245e36; color: #fff; }
.wl__btn--outline { background: transparent; color: #2d7a45; border: 1.5px solid #2d7a45; }
.wl__btn--outline:hover { background: #f0faf4; }
.wl__empty { text-align: center; padding: 5rem 1rem; background: #fff; border-radius: 16px; border: 1px solid #e8f0e4; }
.wl__empty-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }
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
            <h1 style="font-size:1.6rem; font-weight:800; color:#1a2e1a; margin-bottom:0.2rem;">My Wishlist</h1>
            <p style="font-size:0.9rem; color:#6b7c6b; margin-bottom:1.75rem;">{{ $wishlists->count() }} saved product(s)</p>

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
    <h3 style="font-size:1.4rem; font-weight:700; color:#1a2e1a; margin-bottom:0.5rem;">Your wishlist is empty</h3>
    <p style="color:#6b7c6b; margin-bottom:1.5rem;">Save products you love and come back to them later.</p>
    <a href="{{ route('products.index') }}" style="display:inline-block; padding:0.75rem 2rem; background:#2d7a45; color:#fff; font-weight:700; border-radius:10px; text-decoration:none;">
        Browse Products
    </a>
</div>
@endif

@push('scripts')
<script>
document.querySelectorAll('.add-to-cart-wl').forEach(btn => {
    btn.addEventListener('click', function () {
        const id   = this.dataset.id;
        const name = this.dataset.name;
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_id: id, quantity: 1 })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                this.textContent = '✓ Added!';
                this.style.background = '#4caf72';
                setTimeout(() => { this.textContent = 'Add to Cart'; this.style.background = ''; }, 2000);
            }
        });
    });
});
</script>
@endpush
        </div>
    </div>
</div>
@endsection
