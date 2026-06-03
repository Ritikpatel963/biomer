<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function blog()
    {
        $blogs = Blog::with('category')->latest()->get();
        return view('blog/blog', compact('blogs'));
    }

    public function addBlog()
    {
        $categories = BlogCategory::orderBy('name')->get();
        $blogs      = Blog::with('category')->latest()->take(5)->get();
        return view('blog/addBlog', compact('categories', 'blogs'));
    }

    public function storeBlog(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'category_id'      => 'required|exists:blog_categories,id',
            'author'           => 'nullable|string|max:255',
            'reading_time'     => 'nullable|integer|min:1|max:120',
            'description'      => 'required|string',
            'tags'             => 'nullable|string|max:255',
            'status'           => 'required|in:draft,published',
            'meta_title'       => 'nullable|string|max:255',
            'meta_tags'        => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'title',
            'category_id',
            'author',
            'reading_time',
            'description',
            'tags',
            'status',
            'meta_title',
            'meta_tags',
            'meta_description',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->route('blog')->with('success', 'Blog post created successfully.');
    }

    public function editBlog(Blog $blog)
    {
        $categories = BlogCategory::orderBy('name')->get();
        $blogs      = Blog::with('category')->latest()->take(5)->get();
        return view('blog/addBlog', compact('categories', 'blogs', 'blog'));
    }

    public function updateBlog(Request $request, Blog $blog)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'category_id'      => 'required|exists:blog_categories,id',
            'author'           => 'nullable|string|max:255',
            'reading_time'     => 'nullable|integer|min:1|max:120',
            'description'      => 'required|string',
            'tags'             => 'nullable|string|max:255',
            'status'           => 'required|in:draft,published',
            'meta_title'       => 'nullable|string|max:255',
            'meta_tags'        => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'title',
            'category_id',
            'author',
            'reading_time',
            'description',
            'tags',
            'status',
            'meta_title',
            'meta_tags',
            'meta_description',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($blog->thumbnail) Storage::disk('public')->delete($blog->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('blogs', 'public');
        }

        $blog->update($data);

        return redirect()->route('blog')->with('success', 'Blog post updated successfully.');
    }

    public function destroyBlog(Blog $blog)
    {
        if ($blog->thumbnail) Storage::disk('public')->delete($blog->thumbnail);
        $blog->delete();
        return redirect()->route('blog')->with('success', 'Blog post deleted successfully.');
    }

    public function blogDetails(Blog $blog)
    {
        $recentBlogs = Blog::with('category')
                           ->where('id', '!=', $blog->id)
                           ->latest()
                           ->take(5)
                           ->get();

        $categories = BlogCategory::withCount('blogs')->orderBy('name')->get();

        return view('blog/blogDetails', compact('blog', 'recentBlogs', 'categories'));
    }

    public function frontendIndex()
    {
        $blogs = Blog::with('category')
            ->where('status', 'published')
            ->latest()
            ->paginate(9);

        $categories = BlogCategory::withCount('blogs')->orderBy('name')->get();
        $tags = Blog::where('status', 'published')->pluck('tags')->filter()->flatMap(function ($tags) {
            return array_map('trim', explode(',', $tags));
        })->unique()->values();

        return view('blogs.index', compact('blogs', 'categories', 'tags'));
    }

    public function frontendDetails(string $slug)
    {
        $blog = Blog::with('category')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $recentBlogs = Blog::with('category')
            ->where('id', '!=', $blog->id)
            ->where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        $categories = BlogCategory::withCount('blogs')->orderBy('name')->get();
        $reviews = $blog->approvedReviews()->latest()->get();
        $customer = Auth::guard('customer')->user();
        $alreadyReviewed = $customer
            ? $blog->reviews()->where('customer_id', $customer->id)->exists()
            : false;

        return view('blogs.show', compact('blog', 'recentBlogs', 'categories', 'reviews', 'customer', 'alreadyReviewed'));
    }

    public function storeReview(Request $request, Blog $blog)
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            session()->put('url.intended', route('frontend.blog.show', $blog->slug) . '#blog-review-form');

            return redirect()->route('customer.login')
                ->with('error', 'Please login to continue.');
        }

        $rules = [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ];

        if (filled(config('services.recaptcha.secret_key'))) {
            $rules['g-recaptcha-response'] = 'required|string';
        }

        $request->validate($rules);

        if (
            filled(config('services.recaptcha.secret_key'))
            && !$this->passesRecaptcha($request->input('g-recaptcha-response'))
        ) {
            return back()
                ->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.'])
                ->withInput();
        }

        $alreadyReviewed = BlogReview::where('blog_id', $blog->id)
            ->where('customer_id', $customer->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()
                ->withErrors(['comment' => 'You have already submitted a review for this blog.'])
                ->withInput();
        }

        BlogReview::create([
            'blog_id' => $blog->id,
            'customer_id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return back()->with('frontend_modal', [
            'title' => 'Review Submitted',
            'message' => 'Thank you! Your review has been submitted and is awaiting approval.',
            'button' => 'Back to Blog',
        ]);
    }

    protected function passesRecaptcha(?string $token): bool
    {
        $secretKey = config('services.recaptcha.secret_key');

        if (blank($secretKey) || blank($token)) {
            return false;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $token,
        ]);

        if (!$response->ok()) {
            return false;
        }

        return (bool) data_get($response->json(), 'success', false);
    }
}
