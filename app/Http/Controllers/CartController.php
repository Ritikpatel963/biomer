<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Services\CartPricingService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $coupon = session()->get('coupon');
        $totals = $this->calculateCartTotals($cart, $coupon);

        return view('cart', array_merge($totals, compact('cart', 'coupon')));
    }

    public function applyCoupon(Request $request)
    {
        $request->merge([
            'code' => strtoupper(trim((string) $request->input('code', ''))),
        ]);

        $request->validate(['code' => 'required|string|max:50']);
        $cart = session()->get('cart', []);
        $code = $request->code;
        
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty.']);
        }

        $coupon = \App\Models\Coupon::where('code', $code)->where('is_active', true)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid or inactive coupon.']);
        }

        if ($coupon->expires_at && \Carbon\Carbon::parse($coupon->expires_at)->isPast()) {
            return response()->json(['success' => false, 'message' => 'This coupon has expired.']);
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['success' => false, 'message' => 'This coupon usage limit has been reached.']);
        }

        $totalsWithoutCoupon = $this->calculateCartTotals($cart);
        if ($totalsWithoutCoupon['subtotal'] < $coupon->min_order_amount) {
            return response()->json(['success' => false, 'message' => 'Minimum order amount for this coupon is ₹' . number_format($coupon->min_order_amount, 2)]);
        }

        $discount = $coupon->type === 'percent' ? ($totalsWithoutCoupon['subtotal'] * ($coupon->value / 100)) : $coupon->value;
        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount' => $discount
        ]);

        $totalsWithCoupon = $this->calculateCartTotals($cart, session()->get('coupon'));

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => '₹' . number_format($totalsWithCoupon['discount'], 2),
            'subtotal' => '₹' . number_format($totalsWithCoupon['subtotal'], 2),
            'shipping_total' => '₹' . number_format($totalsWithCoupon['shippingTotal'], 2),
            'tax_amount' => '₹' . number_format($totalsWithCoupon['taxAmount'], 2),
            'final_total' => '₹' . number_format($totalsWithCoupon['total'], 2),
        ]);
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('coupon');
        
        $cart = session()->get('cart', []);
        $totals = $this->calculateCartTotals($cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
            'subtotal' => '₹' . number_format($totals['subtotal'], 2),
            'shipping_total' => '₹' . number_format($totals['shippingTotal'], 2),
            'tax_amount' => '₹' . number_format($totals['taxAmount'], 2),
            'final_total' => '₹' . number_format($totals['total'], 2),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:product_variations,id',
            'quantity'     => 'nullable|integer|min:1|max:100',
        ]);

        $product   = Product::findOrFail($request->product_id);
        $variation = $request->variation_id
            ? ProductVariation::findOrFail($request->variation_id)
            : null;

        if ($variation && ((int) $variation->product_id !== (int) $product->id || !$variation->is_active)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a valid product pack.',
            ], 422);
        }

        $price = $variation ? $variation->price : $product->base_price;
        $key   = $product->id . ':' . ($variation ? $variation->id : '0');
        $cart  = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity ?? 1;
        } else {
            $cart[$key] = [
                'product_id'     => $product->id,
                'variation_id'   => $variation?->id,
                'name'           => $product->name,
                'variation'      => $variation?->attribute_value ?? null,
                'sku'            => $variation?->sku ?? $product->sku,
                'price'          => $price,
                'shipping_charge'=> $product->shipping_charge ?? 0,
                'quantity'       => $request->quantity ?? 1,
                'image'          => $variation?->image_path ?? $product->featured_image,
            ];
        }

        session()->put('cart', $cart);
        $totals = $this->calculateCartTotals($cart, session()->get('coupon'));

        return response()->json([
            'success'    => true,
            'message'    => $product->name . ' added to cart!',
            'cart_count' => collect($cart)->sum('quantity'),
            'tax_amount' => '₹' . number_format($totals['taxAmount'], 2),
            'final_total' => '₹' . number_format($totals['total'], 2),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'key'      => 'required|string',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->key])) {
            $cart[$request->key]['quantity'] = $request->quantity;
            session()->put('cart', $cart);

            $item      = $cart[$request->key];
            $itemTotal = $item['price'] * $item['quantity'];
            $totals = $this->calculateCartTotals($cart, session()->get('coupon'));

            return response()->json([
                'success'        => true,
                'item_total'     => '₹' . number_format($itemTotal, 2),
                'subtotal'       => '₹' . number_format($totals['subtotal'], 2),
                'shipping_total' => '₹' . number_format($totals['shippingTotal'], 2),
                'tax_amount'     => '₹' . number_format($totals['taxAmount'], 2),
                'final_total'    => '₹' . number_format($totals['total'], 2),
                'cart_count'     => collect($cart)->sum('quantity'),
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    public function remove(Request $request)
    {
        $request->validate(['key' => 'required|string']);
        $cart = session()->get('cart', []);
        unset($cart[$request->key]);
        session()->put('cart', $cart);

        $totals = $this->calculateCartTotals($cart, session()->get('coupon'));

        return response()->json([
            'success'        => true,
            'subtotal'       => '₹' . number_format($totals['subtotal'], 2),
            'shipping_total' => '₹' . number_format($totals['shippingTotal'], 2),
            'tax_amount'     => '₹' . number_format($totals['taxAmount'], 2),
            'final_total'    => '₹' . number_format($totals['total'], 2),
            'cart_count'     => collect($cart)->sum('quantity'),
            'empty'          => count($cart) === 0,
        ]);
    }

    private function calculateCartTotals(array $cart, ?array $coupon = null): array
    {
        return app(CartPricingService::class)->calculate($cart, $coupon);
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }
}
