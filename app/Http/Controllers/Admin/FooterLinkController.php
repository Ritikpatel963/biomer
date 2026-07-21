<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterLink;
use Illuminate\Http\Request;

class FooterLinkController extends Controller
{
    private const DEFAULT_SECTIONS = ['Products', 'Company', 'Policies', 'Support'];

    private const DEFAULT_LINKS = [
        ['section' => 'Products', 'label' => 'Bio-Stimulants', 'url' => '/products', 'position' => 1, 'target' => '_self'],
        ['section' => 'Products', 'label' => 'Microbial Solutions', 'url' => '/products', 'position' => 2, 'target' => '_self'],
        ['section' => 'Products', 'label' => 'Crop Nutrition Support', 'url' => '/products', 'position' => 3, 'target' => '_self'],
        ['section' => 'Products', 'label' => 'Residue-free Farming', 'url' => '/products', 'position' => 4, 'target' => '_self'],
        ['section' => 'Products', 'label' => 'All Products', 'url' => '/products', 'position' => 5, 'target' => '_self'],
        ['section' => 'Company', 'label' => 'About Us', 'url' => '/about', 'position' => 1, 'target' => '_self'],
        ['section' => 'Company', 'label' => 'Our Technology', 'url' => '/technology', 'position' => 2, 'target' => '_self'],
        ['section' => 'Company', 'label' => 'Crops', 'url' => '/#crops', 'position' => 3, 'target' => '_self'],
        ['section' => 'Company', 'label' => 'Blog', 'url' => 'frontend.blog.index', 'position' => 4, 'target' => '_self'],
        ['section' => 'Company', 'label' => 'Contact Us', 'url' => '/contact', 'position' => 5, 'target' => '_self'],
        ['section' => 'Policies', 'label' => 'Privacy Policy', 'url' => 'policy.privacy', 'position' => 1, 'target' => '_self'],
        ['section' => 'Policies', 'label' => 'Terms & Conditions', 'url' => 'policy.terms', 'position' => 2, 'target' => '_self'],
        ['section' => 'Policies', 'label' => 'Return Policy', 'url' => 'policy.return', 'position' => 3, 'target' => '_self'],
        ['section' => 'Policies', 'label' => 'Shipping Policy', 'url' => 'policy.shipping', 'position' => 4, 'target' => '_self'],
        ['section' => 'Support', 'label' => 'My Account', 'url' => 'customer.account', 'position' => 1, 'target' => '_self'],
        ['section' => 'Support', 'label' => 'My Orders', 'url' => 'orders.index', 'position' => 2, 'target' => '_self'],
        ['section' => 'Support', 'label' => 'Contact Support', 'url' => '/contact', 'position' => 3, 'target' => '_self'],
    ];

    /**
     * Display all footer links
     */
    public function index()
    {
        $this->ensureDefaultFooterLinks();

        $links = FooterLink::orderBy('section')->orderBy('position')->get();
        $sections = collect(self::DEFAULT_SECTIONS)
            ->merge($links->pluck('section'))
            ->unique()
            ->values();

        return view('dashboard.settings.footer-links.index', compact('links', 'sections'));
    }

    /**
     * Show create footer link form
     */
    public function create()
    {
        return view('dashboard.settings.footer-links.create');
    }

    /**
     * Store a new footer link
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'position' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'target' => 'required|in:_self,_blank',
        ]);

        FooterLink::create($validated);
        return redirect()->route('dashboard.footer-links.index')->with('success', 'Footer link created successfully!');
    }

    /**
     * Show edit footer link form
     */
    public function edit(FooterLink $footerLink)
    {
        return view('dashboard.settings.footer-links.edit', compact('footerLink'));
    }

    /**
     * Update a footer link
     */
    public function update(Request $request, FooterLink $footerLink)
    {
        $validated = $request->validate([
            'section' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'position' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'target' => 'required|in:_self,_blank',
        ]);

        $footerLink->update($validated);
        return redirect()->route('dashboard.footer-links.index')->with('success', 'Footer link updated successfully!');
    }

    /**
     * Delete a footer link
     */
    public function destroy(FooterLink $footerLink)
    {
        $footerLink->delete();
        return redirect()->back()->with('success', 'Footer link deleted successfully!');
    }

    private function ensureDefaultFooterLinks(): void
    {
        if (FooterLink::exists()) {
            return;
        }

        foreach (self::DEFAULT_LINKS as $link) {
            FooterLink::create($link + ['is_active' => true]);
        }
    }
}
