{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ url('/sitemap-pages.xml') }}</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap-products.xml') }}</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap-blogs.xml') }}</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
    </sitemap>
</sitemapindex>
