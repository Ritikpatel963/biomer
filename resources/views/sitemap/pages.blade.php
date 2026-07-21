{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static Pages -->
    <url>
        <loc>{{ url('/') }}</loc>
    </url>
    <url>
        <loc>{{ url('/about') }}</loc>
    </url>
    <url>
        <loc>{{ url('/technology') }}</loc>
    </url>
    <url>
        <loc>{{ url('/collaboration') }}</loc>
    </url>
    <url>
        <loc>{{ url('/impact') }}</loc>
    </url>
    <url>
        <loc>{{ url('/contact') }}</loc>
    </url>
    <url>
        <loc>{{ url('/bharat-biomer') }}</loc>
    </url>
    <url>
        <loc>{{ url('/products') }}</loc>
    </url>
    <url>
        <loc>{{ url('/blogs') }}</loc>
    </url>

    <!-- Dynamic DB Pages -->
    @foreach($pages as $page)
    <url>
        <loc>{{ url('/' . $page->slug) }}</loc>
        <lastmod>{{ optional($page->updated_at)->tz('UTC')->toAtomString() }}</lastmod>
    </url>
    @endforeach
</urlset>
