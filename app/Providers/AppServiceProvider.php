<?php

namespace App\Providers;

use App\Models\FooterLink;
use App\Models\HeaderLink;
use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Implicitly grant "super-admin" role all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
        View::composer('layout.frontlayout', function ($view) {
            $headerLinks = HeaderLink::getActive();
            $footerLinks = FooterLink::getActiveGrouped();

            $view->with([
                'siteSettings' => SiteSetting::current(),
                'headerLinks' => $headerLinks->isNotEmpty() ? $headerLinks : $this->defaultHeaderLinks(),
                'footerLinks' => $footerLinks->isNotEmpty() ? $footerLinks : $this->defaultFooterLinks(),
            ]);
        });

        View::composer([
            'bharat-biomer',
            'product',
            'ourtechnology',
            'about',
            'blogs.index',
            'contact',
            'fieldresult',
            'policies.return',
            'policies.shipping',
            'policies.terms',
            'policies.privacy',
        ], function ($view) {
            $seoPages = [
                'bharat-biomer' => 'home',
                'product' => 'products',
                'ourtechnology' => 'technology',
                'about' => 'about',
                'blogs.index' => 'blog',
                'contact' => 'contact',
                'fieldresult' => 'impact',
                'policies.return' => 'return-policy',
                'policies.shipping' => 'shipping-policy',
                'policies.terms' => 'terms-and-conditions',
                'policies.privacy' => 'privacy-policy',
            ];

            $slug = $seoPages[$view->getName()] ?? null;

            $view->with('pageSeo', $slug ? Page::where('slug', $slug)->where('status', true)->first() : null);
        });
    }

    private function defaultHeaderLinks()
    {
        return collect([
            ['label' => 'Products', 'url' => '/products', 'position' => 1, 'is_active' => true, 'target' => '_self'],
            ['label' => 'Technology', 'url' => '/technology', 'position' => 2, 'is_active' => true, 'target' => '_self'],
            ['label' => 'Crops', 'url' => '/impact', 'position' => 3, 'is_active' => true, 'target' => '_self'],
            ['label' => 'About', 'url' => '/about', 'position' => 4, 'is_active' => true, 'target' => '_self'],
            ['label' => 'Blog', 'url' => 'frontend.blog.index', 'position' => 5, 'is_active' => true, 'target' => '_self'],
            ['label' => 'Contact', 'url' => '/contact', 'position' => 6, 'is_active' => true, 'target' => '_self'],
        ])->map(fn($link) => new HeaderLink($link));
    }

    private function defaultFooterLinks()
    {
        return collect([
            'Products' => collect([
                new FooterLink(['label' => 'Bio-Stimulants', 'url' => '/products', 'target' => '_self']),
                new FooterLink(['label' => 'Microbial Solutions', 'url' => '/products', 'target' => '_self']),
                new FooterLink(['label' => 'Crop Nutrition Support', 'url' => '/products', 'target' => '_self']),
                new FooterLink(['label' => 'Residue-free Farming', 'url' => '/products', 'target' => '_self']),
                new FooterLink(['label' => 'All Products', 'url' => '/products', 'target' => '_self']),
            ]),
            'Company' => collect([
                new FooterLink(['label' => 'About Us', 'url' => '/about', 'target' => '_self']),
                new FooterLink(['label' => 'Our Technology', 'url' => '/technology', 'target' => '_self']),
                new FooterLink(['label' => 'News & Media', 'url' => 'frontend.blog.index', 'target' => '_self']),
            ]),
            'Policies' => collect([
                new FooterLink(['label' => 'Privacy Policy', 'url' => 'policy.privacy', 'target' => '_self']),
                new FooterLink(['label' => 'Terms & Conditions', 'url' => 'policy.terms', 'target' => '_self']),
                new FooterLink(['label' => 'Refund Policy', 'url' => 'policy.return', 'target' => '_self']),
                new FooterLink(['label' => 'Shipping Policy', 'url' => 'policy.shipping', 'target' => '_self']),
            ]),
        ]);
    }
}
