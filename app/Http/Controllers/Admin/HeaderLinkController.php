<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderLink;
use Illuminate\Http\Request;

class HeaderLinkController extends Controller
{
    private const DEFAULT_LINKS = [
        ['label' => 'Products', 'url' => '/products', 'position' => 1, 'target' => '_self'],
        ['label' => 'Technology', 'url' => '/technology', 'position' => 2, 'target' => '_self'],
        ['label' => 'Crops', 'url' => '/#crops', 'position' => 3, 'target' => '_self'],
        ['label' => 'About', 'url' => '/about', 'position' => 4, 'target' => '_self'],
        ['label' => 'Blog', 'url' => 'frontend.blog.index', 'position' => 5, 'target' => '_self'],
        ['label' => 'Contact', 'url' => '/contact', 'position' => 6, 'target' => '_self'],
    ];

    /**
     * Display all header links
     */
    public function index()
    {
        $this->ensureDefaultHeaderLinks();

        $links = HeaderLink::orderBy('position')->get();
        return view('dashboard.settings.header-links.index', compact('links'));
    }

    /**
     * Show create header link form
     */
    public function create()
    {
        return view('dashboard.settings.header-links.create');
    }

    /**
     * Store a new header link
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'position' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'target' => 'required|in:_self,_blank',
        ]);

        HeaderLink::create($validated);
        return redirect()->route('dashboard.header-links.index')->with('success', 'Header link created successfully!');
    }

    /**
     * Show edit header link form
     */
    public function edit(HeaderLink $headerLink)
    {
        return view('dashboard.settings.header-links.edit', compact('headerLink'));
    }

    /**
     * Update a header link
     */
    public function update(Request $request, HeaderLink $headerLink)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'position' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'target' => 'required|in:_self,_blank',
        ]);

        $headerLink->update($validated);
        return redirect()->route('dashboard.header-links.index')->with('success', 'Header link updated successfully!');
    }

    /**
     * Delete a header link
     */
    public function destroy(HeaderLink $headerLink)
    {
        $headerLink->delete();
        return redirect()->back()->with('success', 'Header link deleted successfully!');
    }

    private function ensureDefaultHeaderLinks(): void
    {
        if (HeaderLink::exists()) {
            return;
        }

        foreach (self::DEFAULT_LINKS as $link) {
            HeaderLink::create($link + ['icon' => null, 'is_active' => true]);
        }
    }
}
