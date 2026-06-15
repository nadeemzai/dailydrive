<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Static pages --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/articles') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/about') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ url('/privacy-policy') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ url('/terms') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    {{-- Category pages --}}
    @foreach ($categories as $category)
    <url>
        <loc>{{ url('/articles') }}?category={{ urlencode($category) }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Article pages --}}
    @foreach ($articles as $article)
    <url>
        <loc>{{ url('/articles/' . $article->slug) }}</loc>
        <lastmod>{{ ($article->updated_at ?? $article->ai_generated_at)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

</urlset>
