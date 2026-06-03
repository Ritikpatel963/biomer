<?php

namespace App\Http\Controllers;

use App\Models\BlogReview;
use Illuminate\Http\Request;

class BlogReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = trim($request->get('q', ''));

        $reviews = BlogReview::with(['blog', 'customer'])
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('comment', 'like', "%{$search}%")
                        ->orWhereHas('blog', fn ($sub) => $sub->where('title', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'all' => BlogReview::count(),
            'pending' => BlogReview::where('status', 'pending')->count(),
            'approved' => BlogReview::where('status', 'approved')->count(),
            'rejected' => BlogReview::where('status', 'rejected')->count(),
        ];

        return view('dashboard.blog-reviews.index', compact('reviews', 'status', 'counts', 'search'));
    }

    public function approve(BlogReview $review)
    {
        $review->update(['status' => 'approved']);

        return back()->with('success', 'Blog review approved.');
    }

    public function reject(BlogReview $review)
    {
        $review->update(['status' => 'rejected']);

        return back()->with('success', 'Blog review rejected.');
    }

    public function destroy(BlogReview $review)
    {
        $review->delete();

        return back()->with('success', 'Blog review deleted.');
    }
}
