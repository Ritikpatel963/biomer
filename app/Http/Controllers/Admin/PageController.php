<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public const FIXED_PAGES = [
        ['title' => 'Home', 'slug' => 'home', 'url' => '/', 'meta_title' => 'Bharat Biomer - Nature-Powered Biology'],
        ['title' => 'Products', 'slug' => 'products', 'url' => '/products', 'meta_title' => 'Products - Bharat Biomer'],
        ['title' => 'Technology', 'slug' => 'technology', 'url' => '/technology', 'meta_title' => 'Technology - Bharat Biomer'],
        ['title' => 'Impact', 'slug' => 'impact', 'url' => '/impact', 'meta_title' => 'Impact - Bharat Biomer'],
        ['title' => 'About', 'slug' => 'about', 'url' => '/about', 'meta_title' => 'About - Bharat Biomer'],
        ['title' => 'Blog', 'slug' => 'blog', 'url' => '/blogs', 'meta_title' => 'Blog - Bharat Biomer'],
        ['title' => 'Contact', 'slug' => 'contact', 'url' => '/contact', 'meta_title' => 'Contact - Bharat Biomer'],
        ['title' => 'Return Policy', 'slug' => 'return-policy', 'url' => '/return-policy', 'meta_title' => 'Return Policy - Bharat Biomer'],
        ['title' => 'Shipping Policy', 'slug' => 'shipping-policy', 'url' => '/shipping-policy', 'meta_title' => 'Shipping Policy - Bharat Biomer'],
        ['title' => 'Terms & Conditions', 'slug' => 'terms-and-conditions', 'url' => '/terms-and-conditions', 'meta_title' => 'Terms & Conditions - Bharat Biomer'],
        ['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'url' => '/privacy-policy', 'meta_title' => 'Privacy Policy - Bharat Biomer'],
    ];

    public function index()
    {
        $this->ensureFixedPages();

        $fixedPages = collect(self::FIXED_PAGES);
        $pages = Page::whereIn('slug', $fixedPages->pluck('slug'))
            ->get()
            ->keyBy('slug');

        $pages = $fixedPages->map(function (array $fixedPage) use ($pages) {
            $page = $pages->get($fixedPage['slug']);
            $page->admin_url = $fixedPage['url'];

            return $page;
        });

        return view('dashboard.pages.index', compact('pages'));
    }

    public function create()
    {
        return redirect()->route('dashboard.pages.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('dashboard.pages.index');
    }

    public function edit(Page $page)
    {
        return view('dashboard.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        abort_unless($this->isFixedPage($page), 404);

        $validated = $request->validate([
            'title'              => ['required', 'string', 'max:255', Rule::unique('pages', 'title')->ignore($page->id)],
            'meta_title'         => 'nullable|string|max:255',
            'meta_description'   => 'nullable|string|max:500',
            'meta_keyword'       => 'nullable|string|max:500',
            'status'             => 'required|boolean',
        ]);

        $page->update($validated);

        return redirect()->route('dashboard.pages.index')
            ->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        return redirect()->route('dashboard.pages.index')
            ->with('error', 'Core website pages cannot be deleted.');
    }

    private function ensureFixedPages(): void
    {
        foreach (self::FIXED_PAGES as $page) {
            $existingPage = Page::where('slug', $page['slug'])
                ->orWhere('title', $page['title'])
                ->first();

            if ($existingPage) {
                if ($existingPage->slug !== $page['slug']) {
                    $existingPage->update(['slug' => $page['slug']]);
                }

                continue;
            }

            Page::create([
                'title' => $page['title'],
                'slug' => $page['slug'],
                'meta_title' => $page['meta_title'],
                'status' => true,
            ]);
        }
    }

    private function isFixedPage(Page $page): bool
    {
        return collect(self::FIXED_PAGES)->pluck('slug')->contains($page->slug);
    }
}
