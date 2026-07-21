<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    // ── ADMIN: list all reviews ─────────────────────────────────────────
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = trim($request->get('q', ''));

        $reviews = ProductReview::with(['product', 'customer'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->whereHas('customer', fn($sub) => $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"))
                        ->orWhereHas('product', fn($sub) => $sub->where('name', 'like', "%{$search}%"))
                        ->orWhere('review_text', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'all' => ProductReview::count(),
            'pending' => ProductReview::where('status', 'pending')->count(),
            'approved' => ProductReview::where('status', 'approved')->count(),
            'rejected' => ProductReview::where('status', 'rejected')->count(),
        ];

        return view('dashboard.reviews.index', compact('reviews', 'status', 'counts', 'search'));
    }

    // ── ADMIN: approve a review ─────────────────────────────────────────
    public function approve(ProductReview $review)
    {
        $review->update(['status' => 'approved']);
        return back()->with('success', 'Review approved.');
    }

    // ── ADMIN: reject a review ──────────────────────────────────────────
    public function reject(ProductReview $review)
    {
        $review->update(['status' => 'rejected']);
        return back()->with('success', 'Review rejected.');
    }

    // ── ADMIN: delete a review ──────────────────────────────────────────
    public function destroy(ProductReview $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }

    // ── FRONTEND: customer submits a review ─────────────────────────────
    public function store(Request $request, Product $product)
    {
        // Must be logged-in customer
        if (!Auth::guard('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'Please login to submit a review.'], 401);
        }

        $customer = Auth::guard('customer')->user();

        // Prevent duplicate reviews for the same product
        $exists = ProductReview::where('product_id', $product->id)
            ->where('customer_id', $customer->id)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'You have already reviewed this product.'], 422);
        }

        $request->merge([
            'review_text' => trim((string) $request->input('review_text', '')),
        ]);

        $validated = $request->validate([
            'rating'      => ['required', 'integer', 'min:1', 'max:5'],
            'review_text' => ['required', 'string', 'min:3', 'max:1000'],
        ], [
            'rating.required' => 'Please select a rating.',
            'rating.min' => 'Please select a valid rating.',
            'rating.max' => 'Please select a valid rating.',
            'review_text.required' => 'Please enter your comment.',
            'review_text.min' => 'Comment must be at least 3 characters.',
            'review_text.max' => 'Comment cannot be more than 1000 characters.',
        ]);

        ProductReview::create([
            'product_id'  => $product->id,
            'customer_id' => $customer->id,
            'rating'      => $validated['rating'],
            'review_text' => $validated['review_text'],
            'status'      => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your review has been submitted and is awaiting approval.',
            'modal' => [
                'title' => 'Review Submitted',
                'message' => 'Thank you! Your review has been submitted and is awaiting approval.',
                'button' => 'Continue Shopping',
            ],
        ]);
    }
}
