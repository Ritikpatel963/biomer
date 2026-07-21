<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    private function customer()
    {
        return Auth::guard('customer')->user();
    }

    // ── Wishlist Page ──────────────────────────────────────────────────
    public function index()
    {
        $wishlists = Wishlist::with('product.variations')
            ->where('customer_id', $this->customer()->id)
            ->latest()
            ->get();

        return view('wishlist', compact('wishlists'));
    }

    // ── Toggle (Add / Remove) ──────────────────────────────────────────
    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|integer|exists:products,id']);

        $customer  = $this->customer();
        $productId = $request->product_id;

        $existing = Wishlist::where('customer_id', $customer->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $wishlisted = false;
            $message    = 'Removed from wishlist';
        } else {
            Wishlist::create([
                'customer_id' => $customer->id,
                'product_id'  => $productId,
            ]);
            $wishlisted = true;
            $message    = 'Added to wishlist';
        }

        return response()->json([
            'success'    => true,
            'wishlisted' => $wishlisted,
            'message'    => $message,
            'count'      => Wishlist::where('customer_id', $customer->id)->count(),
        ]);
    }

    // ── Remove ─────────────────────────────────────────────────────────
    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required|integer|exists:products,id']);

        Wishlist::where('customer_id', $this->customer()->id)
            ->where('product_id', $request->product_id)
            ->delete();

        return redirect()->back()->with('success', 'Removed from wishlist.');
    }
}
