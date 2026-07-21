<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogReview;
use App\Services\BlogContentProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public function blog()
    {
        $blogs = Blog::with('category')->latest()->paginate(20);
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
        $request->merge([
            'slug' => Str::slug((string) $request->input('slug')),
            'canonical_url' => trim((string) $request->input('canonical_url', '')),
        ]);
        $this->normalizeFaqs($request);

        $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:blogs,slug'],
            'category_id'      => 'required|exists:blog_categories,id',
            'author'           => 'nullable|string|max:255',
            'author_bio'       => 'nullable|string|max:2000',
            'reading_time'     => 'nullable|integer|min:1|max:120',
            'published_at'     => 'nullable|date',
            'description'      => 'required|string',
            'tags'             => 'nullable|string|max:255',
            'status'           => 'required|in:draft,published',
            'meta_title'       => 'nullable|string|max:255',
            'meta_tags'        => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'canonical_url'    => 'nullable|url:http,https|max:2048',
            'thumbnail_alt'    => 'nullable|string|max:255',
            'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'faqs'             => 'nullable|array|max:20',
            'faqs.*.question'  => 'required|string|max:500',
            'faqs.*.answer'    => 'required|string|max:5000',
        ]);

        $data = $request->only([
            'title',
            'slug',
            'category_id',
            'author',
            'author_bio',
            'reading_time',
            'published_at',
            'description',
            'tags',
            'status',
            'meta_title',
            'meta_tags',
            'meta_description',
            'canonical_url',
            'thumbnail_alt',
        ]);
        $data['faq_items'] = $request->input('faqs') ?: null;

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
        $request->merge([
            'slug' => Str::slug((string) $request->input('slug')),
            'canonical_url' => trim((string) $request->input('canonical_url', '')),
        ]);
        $this->normalizeFaqs($request);

        $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('blogs', 'slug')->ignore($blog->id),
            ],
            'category_id'      => 'required|exists:blog_categories,id',
            'author'           => 'nullable|string|max:255',
            'author_bio'       => 'nullable|string|max:2000',
            'reading_time'     => 'nullable|integer|min:1|max:120',
            'published_at'     => 'nullable|date',
            'description'      => 'required|string',
            'tags'             => 'nullable|string|max:255',
            'status'           => 'required|in:draft,published',
            'meta_title'       => 'nullable|string|max:255',
            'meta_tags'        => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'canonical_url'    => 'nullable|url:http,https|max:2048',
            'thumbnail_alt'    => 'nullable|string|max:255',
            'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'faqs'             => 'nullable|array|max:20',
            'faqs.*.question'  => 'required|string|max:500',
            'faqs.*.answer'    => 'required|string|max:5000',
        ]);

        $data = $request->only([
            'title',
            'slug',
            'category_id',
            'author',
            'author_bio',
            'reading_time',
            'published_at',
            'description',
            'tags',
            'status',
            'meta_title',
            'meta_tags',
            'meta_description',
            'canonical_url',
            'thumbnail_alt',
        ]);
        $data['faq_items'] = $request->input('faqs') ?: null;

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
            ->orderByDesc('published_at')
            ->latest('id')
            ->paginate(9);

        $categories = BlogCategory::withCount('blogs')->orderBy('name')->get();
        $tags = Blog::where('status', 'published')->pluck('tags')->filter()->flatMap(function ($tags) {
            return array_map('trim', explode(',', $tags));
        })->unique()->values();

        $recentBlogs = Blog::where('status', 'published')
            ->orderByDesc('published_at')
            ->latest('id')
            ->limit(3)
            ->get();

        return view('blogs.index', compact('blogs', 'categories', 'tags', 'recentBlogs'));
    }

    public function frontendDetails(string $slug, BlogContentProcessor $contentProcessor)
    {
        $blog = Blog::with('category')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $recentBlogs = Blog::with('category')
            ->where('id', '!=', $blog->id)
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->latest('id')
            ->take(5)
            ->get();

        $categories = BlogCategory::withCount('blogs')->orderBy('name')->get();
        $reviews = $blog->approvedReviews()->latest()->get();
        $customer = Auth::guard('customer')->user();
        $alreadyReviewed = $customer
            ? $blog->reviews()->where('customer_id', $customer->id)->exists()
            : false;

        $processedContent = $contentProcessor->process($blog->description);
        $renderedContent = $processedContent['html'];
        $tableOfContents = $processedContent['headings'];

        $tags = collect(explode(',', (string) $blog->tags))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values();

        $relatedQuery = Blog::with('category')
            ->where('status', 'published')
            ->whereKeyNot($blog->id);

        if ($blog->category_id || $tags->isNotEmpty()) {
            $relatedQuery->where(function ($query) use ($blog, $tags) {
                if ($blog->category_id) {
                    $query->where('category_id', $blog->category_id);
                }

                foreach ($tags as $tag) {
                    $query->orWhere('tags', 'like', '%' . addcslashes($tag, '%_\\') . '%');
                }
            });

            $relatedBlogs = $relatedQuery
                ->orderByDesc('published_at')
                ->latest('id')
                ->take(3)
                ->get();
        } else {
            $relatedBlogs = collect();
        }

        if ($relatedBlogs->count() < 3) {
            $fallbackBlogs = Blog::with('category')
                ->where('status', 'published')
                ->whereNotIn('id', $relatedBlogs->pluck('id')->push($blog->id))
                ->orderByDesc('published_at')
                ->latest('id')
                ->take(3 - $relatedBlogs->count())
                ->get();

            $relatedBlogs = $relatedBlogs->concat($fallbackBlogs);
        }

        return view('blogs.show', compact(
            'blog',
            'recentBlogs',
            'relatedBlogs',
            'categories',
            'reviews',
            'customer',
            'alreadyReviewed',
            'renderedContent',
            'tableOfContents'
        ));
    }

    public function storeReview(Request $request, Blog $blog)
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            session()->put('url.intended', route('frontend.blog.show', $blog->slug) . '#blog-review-form');

            return redirect()->route('customer.login')
                ->with('error', 'Please login to continue.');
        }

        $request->merge([
            'comment' => trim((string) $request->input('comment', '')),
        ]);

        $rules = [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:3', 'max:1000'],
        ];

        if (filled(config('services.recaptcha.secret_key'))) {
            $rules['g-recaptcha-response'] = 'required|string';
        }

        $validated = $request->validate($rules, [
            'rating.required' => 'Please select a rating.',
            'rating.min' => 'Please select a valid rating.',
            'rating.max' => 'Please select a valid rating.',
            'comment.required' => 'Please enter your comment.',
            'comment.min' => 'Comment must be at least 3 characters.',
            'comment.max' => 'Comment cannot be more than 1000 characters.',
        ]);

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
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
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

    protected function normalizeFaqs(Request $request): void
    {
        $faqs = collect($request->input('faqs', []))
            ->map(fn ($faq) => [
                'question' => trim((string) data_get($faq, 'question')),
                'answer' => trim((string) data_get($faq, 'answer')),
            ])
            ->filter(fn ($faq) => $faq['question'] !== '' || $faq['answer'] !== '')
            ->values()
            ->all();

        $request->merge(['faqs' => $faqs]);
    }
}
