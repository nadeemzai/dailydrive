<?php

namespace App\Services;

use App\Models\Article;
use App\Models\NewsSource;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class NewsScraperService
{
    public function scrapeAll(int $limitPerSource = 10): array
    {
        $results = [];

        foreach ($this->getSources() as $source) {
            $results[] = $this->scrapeSource($source, $limitPerSource);
        }

        return $results;
    }

    public function scrapeSource(NewsSource|array $source, int $limitPerSource = 10): array
    {
        $sourceModel = $source instanceof NewsSource ? $source : null;
        $source = $sourceModel ? $this->sourceToArray($sourceModel) : $source;
        $homeUrl = $source['home_url'];
        $domain = $source['domain'];
        $name = $source['name'];

        // $links is url => rssCategory (string, may be empty)
        $links = [];

        if (($source['crawl_mode'] ?? 'latest') === 'backfill' && ! empty($source['sitemap_url'])) {
            $sitemapBody = $this->fetchUrl($source['sitemap_url']);
            if ($sitemapBody !== null) {
                foreach ($this->extractLinksFromSitemap($sitemapBody, $domain, $limitPerSource * 3) as $url) {
                    $links[$url] = '';
                }
            }
        }

        // feed links merged below
        if (! empty($source['feed_url'])) {
            $feedBody = $this->fetchUrl($source['feed_url']);
            if ($feedBody !== null) {
                $links = array_merge($links, $this->extractLinksFromFeed($feedBody, $domain, $limitPerSource * 2));
            }
        }

        if (count($links) < $limitPerSource) {
            $html = $this->fetchUrl($homeUrl);
            if ($html !== null) {
                foreach ($this->extractArticleLinks($html, $homeUrl, $domain, $limitPerSource) as $url) {
                    if (! isset($links[$url])) {
                        $links[$url] = '';
                    }
                }
            }
        }

        // Deduplicate and cap
        $maxToProcess = max(1, (int) ($source['max_articles_per_run'] ?? $limitPerSource));
        $links = array_slice($links, 0, $maxToProcess, true);
        $saved = 0;
        $failed = 0;

        foreach ($links as $link => $rssCategory) {
            try {
                $article = $this->scrapeArticle($link, $source, $rssCategory);
                if ($article !== null) {
                    $saved++;
                }
            } catch (Throwable) {
                $failed++;
            }
        }

        if ($sourceModel) {
            $sourceModel->forceFill(['last_scraped_at' => now()])->save();
        } elseif (! empty($source['id'])) {
            NewsSource::query()->whereKey($source['id'])->update(['last_scraped_at' => now()]);
        }

        return [
            'source' => $name,
            'fetched' => count($links),
            'saved' => $saved,
            'failed' => $failed,
        ];
    }

    public function getSources(): array
    {
        $sources = NewsSource::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($sources->isNotEmpty()) {
            return $sources->map(fn (NewsSource $source) => $this->sourceToArray($source))->all();
        }

        return collect(config('news-scraper.sources', []))
            ->map(fn (array $source) => [
                'name' => $source['name'],
                'domain' => $source['domain'],
                'home_url' => $source['url'],
                'feed_url' => $source['feed_url'] ?? null,
                'sitemap_url' => $source['sitemap_url'] ?? null,
                'crawl_mode' => 'latest',
                'max_articles_per_run' => 10,
                'sort_order' => 0,
                'is_active' => true,
            ])
            ->all();
    }

    protected function extractLinksFromFeed(string $xmlBody, string $domain, int $limit): array
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlBody, 'SimpleXMLElement', LIBXML_NOCDATA);
        libxml_clear_errors();

        if ($xml === false) {
            return [];
        }

        $links = [];

        foreach ($xml->xpath('//item') ?: [] as $item) {
            $linkNodes = $item->xpath('link');
            $url = $linkNodes ? trim((string) $linkNodes[0]) : '';
            if ($url !== '' && $this->isLikelyArticleUrl($url, $domain)) {
                // Extract category from <category> tag
                $catNodes = $item->xpath('category');
                $rssCategory = $catNodes ? trim((string) $catNodes[0]) : '';
                $links[$url] = $rssCategory;
            }
            if (count($links) >= $limit) {
                return $links;
            }
        }

        foreach ($xml->xpath('//entry') ?: [] as $entry) {
            $url = null;
            foreach ($entry->link as $linkNode) {
                $attributes = $linkNode->attributes();
                if (isset($attributes['href'])) {
                    $url = trim((string) $attributes['href']);
                    break;
                }
            }
            if ($url !== null && $url !== '' && $this->isLikelyArticleUrl($url, $domain)) {
                $catNodes = $entry->xpath('category');
                $rssCategory = $catNodes ? trim((string) $catNodes[0]) : '';
                $links[$url] = $rssCategory;
            }
            if (count($links) >= $limit) {
                return $links;
            }
        }

        return $links;
    }

    protected function extractLinksFromSitemap(string $xmlBody, string $domain, int $limit): array
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlBody, 'SimpleXMLElement', LIBXML_NOCDATA);
        libxml_clear_errors();

        if ($xml === false) {
            return [];
        }

        $links = [];

        $locs = $xml->xpath('//url/loc') ?: [];
        if (count($locs) > 0) {
            foreach ($locs as $locNode) {
                $url = trim((string) $locNode);
                if ($url !== '' && $this->isLikelyArticleUrl($url, $domain)) {
                    $links[] = $url;
                }
                if (count($links) >= $limit) {
                    return $links;
                }
            }
        }

        $sitemaps = $xml->xpath('//sitemap/loc') ?: [];
        foreach ($sitemaps as $sitemapNode) {
            $sitemapUrl = trim((string) $sitemapNode);
            if ($sitemapUrl === '') {
                continue;
            }

            $nestedBody = $this->fetchUrl($sitemapUrl);
            if ($nestedBody === null) {
                continue;
            }

            $nestedLinks = $this->extractLinksFromSitemap($nestedBody, $domain, $limit - count($links));
            $links = array_merge($links, $nestedLinks);

            if (count($links) >= $limit) {
                return array_values(array_unique($links));
            }
        }

        return array_values(array_unique($links));
    }

    protected function scrapeArticle(string $url, array $source, string $rssCategory = ''): ?Article
    {
        $html = $this->fetchUrl($url);
        if ($html === null) {
            return null;
        }

        $dom = $this->loadDom($html);
        if ($dom === null) {
            return null;
        }

        $xpath = new DOMXPath($dom);

        $title = $this->firstMeta($xpath, 'property', 'og:title')
            ?? $this->firstMeta($xpath, 'name', 'twitter:title')
            ?? $this->textFromNodes($xpath, '//h1')
            ?? $this->firstMeta($xpath, 'name', 'title')
            ?? $source['name'];

        $excerpt = $this->firstMeta($xpath, 'name', 'description')
            ?? $this->firstMeta($xpath, 'property', 'og:description')
            ?? $this->firstMeta($xpath, 'name', 'twitter:description');

        $imageUrl = $this->firstMeta($xpath, 'property', 'og:image')
            ?? $this->firstMeta($xpath, 'name', 'twitter:image');

        $author = $this->firstMeta($xpath, 'name', 'author')
            ?? $this->firstMeta($xpath, 'property', 'article:author');

        $publishedAt = $this->firstMeta($xpath, 'property', 'article:published_time')
            ?? $this->firstMeta($xpath, 'name', 'pubdate')
            ?? $this->firstMeta($xpath, 'property', 'og:updated_time');

        $contentHtml = $this->extractContentHtml($dom, $xpath);
        $excerpt = $excerpt ?: $this->excerptFromHtml($contentHtml);

        // Determine category: RSS tag → URL path → null (AI will assign later)
        $category = $this->resolveCategory($rssCategory, $url);

        $sourceUrlHash = sha1($url);
        $existing = Article::query()->where('source_url_hash', $sourceUrlHash)->first();
        $slug = $existing?->slug ?? Article::makeSlug($title, $url);

        return Article::query()->updateOrCreate(
            ['source_url_hash' => $sourceUrlHash],
            [
                'news_source_id' => $source['id'] ?? null,
                'source_name' => $source['name'],
                'source_domain' => $source['domain'],
                'source_url_hash' => $sourceUrlHash,
                'source_url' => $url,
                'category' => $category,
                'title' => trim(html_entity_decode($title, ENT_QUOTES | ENT_HTML5)),
                'slug' => $slug,
                'author_name' => $author ? trim(html_entity_decode($author, ENT_QUOTES | ENT_HTML5)) : null,
                'image_url' => $imageUrl,
                'excerpt' => $excerpt ? trim(strip_tags(html_entity_decode($excerpt, ENT_QUOTES | ENT_HTML5))) : null,
                'content_html' => $contentHtml,
                'published_at' => $this->parseDate($publishedAt),
                'scraped_at' => now(),
            ]
        );
    }

    protected function resolveCategory(string $rssCategory, string $url): ?string
    {
        // 1. Try RSS category tag first
        if ($rssCategory !== '') {
            $mapped = $this->mapToCategory($rssCategory);
            if ($mapped !== null) {
                return $mapped;
            }
        }

        // 2. Guess from URL path segments
        $path = strtolower(parse_url($url, PHP_URL_PATH) ?? '');
        $segments = array_filter(explode('/', trim($path, '/')));
        foreach ($segments as $seg) {
            $mapped = $this->mapToCategory($seg);
            if ($mapped !== null) {
                return $mapped;
            }
        }

        return null;
    }

    protected function mapToCategory(string $term): ?string
    {
        $term = strtolower(trim($term));

        $map = [
            // Technology
            'tech'         => 'Technology',
            'technology'   => 'Technology',
            'gadgets'      => 'Technology',
            'hardware'     => 'Technology',
            'software'     => 'Technology',
            'devices'      => 'Technology',
            'mobile'       => 'Technology',

            // Artificial Intelligence
            'ai'               => 'Artificial Intelligence',
            'artificial-intelligence' => 'Artificial Intelligence',
            'machine-learning' => 'Artificial Intelligence',
            'ml'               => 'Artificial Intelligence',
            'deepmind'         => 'Artificial Intelligence',
            'openai'           => 'Artificial Intelligence',
            'llm'              => 'Artificial Intelligence',
            'generative-ai'    => 'Artificial Intelligence',

            // Business
            'business'     => 'Business',
            'startups'     => 'Business',
            'startup'      => 'Business',
            'enterprise'   => 'Business',
            'finance'      => 'Business',
            'economy'      => 'Business',
            'market'       => 'Business',
            'markets'      => 'Business',
            'funding'      => 'Business',
            'venture'      => 'Business',

            // Security
            'security'     => 'Security',
            'cybersecurity'=> 'Security',
            'privacy'      => 'Security',
            'hacking'      => 'Security',
            'breach'       => 'Security',

            // Science
            'science'      => 'Science',
            'research'     => 'Science',
            'space'        => 'Science',
            'biology'      => 'Science',
            'physics'      => 'Science',

            // Environment
            'environment'  => 'Environment',
            'climate'      => 'Environment',
            'energy'       => 'Environment',
            'green'        => 'Environment',
            'sustainability'=> 'Environment',
            'renewables'   => 'Environment',

            // Health
            'health'       => 'Health',
            'medicine'     => 'Health',
            'biotech'      => 'Health',
            'covid'        => 'Health',
            'wellness'     => 'Health',

            // Gaming
            'gaming'       => 'Gaming',
            'games'        => 'Gaming',
            'esports'      => 'Gaming',

            // Policy & Politics
            'policy'       => 'Policy',
            'politics'     => 'Policy',
            'government'   => 'Policy',
            'regulation'   => 'Policy',
            'law'          => 'Policy',
        ];

        return $map[$term] ?? null;
    }

    protected function fetchUrl(string $url): ?string
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => config('news-scraper.user_agent'),
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ])
                ->retry(2, 500)
                ->timeout(25)
                ->get($url);
        } catch (Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        return $response->body();
    }

    protected function extractArticleLinks(string $html, string $baseUrl, string $domain, int $limit): array
    {
        $dom = $this->loadDom($html);
        if ($dom === null) {
            return [];
        }

        $xpath = new DOMXPath($dom);
        $links = [];

        foreach ($xpath->query('//a[@href]') as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            $href = trim($node->getAttribute('href'));
            $url = $this->normalizeUrl($href, $baseUrl);
            if ($url === null) {
                continue;
            }

            if (! $this->isLikelyArticleUrl($url, $domain)) {
                continue;
            }

            $links[$url] = true;

            if (count($links) >= $limit) {
                break;
            }
        }

        return array_keys($links);
    }

    protected function isLikelyArticleUrl(string $url, string $domain): bool
    {
        $host = parse_url($url, PHP_URL_HOST) ?: '';
        $path = parse_url($url, PHP_URL_PATH) ?: '';

        if (! Str::contains($host, $domain)) {
            return false;
        }

        if ($path === '' || $path === '/') {
            return false;
        }

        $blocked = [
            '/tag/',
            '/tags/',
            '/category/',
            '/topic/',
            '/author/',
            '/authors/',
            '/about',
            '/contact',
            '/privacy',
            '/terms',
            '/feed',
            '/search',
            '/newsletter',
            '/podcast',
            '/video',
            '/videos',
            '/events',
        ];

        foreach ($blocked as $needle) {
            if (Str::contains($path, $needle)) {
                return false;
            }
        }

        $segments = array_values(array_filter(explode('/', trim($path, '/'))));
        $count = count($segments);
        // Allow single-segment slugs (e.g. /article-title/) if the slug is long enough to be an article
        if ($count === 1) {
            return strlen($segments[0]) >= 8;
        }
        return $count >= 2;
    }

    protected function normalizeUrl(string $href, string $baseUrl): ?string
    {
        if ($href === '' || Str::startsWith($href, ['javascript:', 'mailto:', '#'])) {
            return null;
        }

        if (Str::startsWith($href, '//')) {
            return parse_url($baseUrl, PHP_URL_SCHEME).':'.$href;
        }

        if (Str::startsWith($href, ['http://', 'https://'])) {
            return $href;
        }

        $scheme = parse_url($baseUrl, PHP_URL_SCHEME) ?: 'https';
        $host = parse_url($baseUrl, PHP_URL_HOST);
        if ($host === null) {
            return null;
        }

        if (Str::startsWith($href, '/')) {
            return $scheme.'://'.$host.$href;
        }

        $basePath = rtrim(dirname(parse_url($baseUrl, PHP_URL_PATH) ?: '/'), '/');

        return $scheme.'://'.$host.($basePath ? $basePath.'/' : '/').$href;
    }

    protected function loadDom(string $html): ?DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $loaded = $dom->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();

        return $loaded ? $dom : null;
    }

    protected function firstMeta(DOMXPath $xpath, string $attribute, string $value): ?string
    {
        $query = sprintf('//meta[@%s="%s"]/@content', $attribute, $value);
        $node = $xpath->query($query)?->item(0);

        return $node?->nodeValue !== '' ? trim($node->nodeValue) : null;
    }

    protected function textFromNodes(DOMXPath $xpath, string $query): ?string
    {
        $node = $xpath->query($query)?->item(0);
        if (! $node) {
            return null;
        }

        return trim(preg_replace('/\s+/', ' ', $node->textContent ?? '') ?: '');
    }

    protected function extractContentHtml(DOMDocument $dom, DOMXPath $xpath): ?string
    {
        $candidates = [
            '//article',
            '//main',
            '//div[contains(@class, "post-content")]',
            '//div[contains(@class, "entry-content")]',
            '//div[contains(@class, "article-content")]',
            '//div[contains(@class, "content")]',
        ];

        foreach ($candidates as $query) {
            $node = $xpath->query($query)?->item(0);
            if ($node instanceof DOMElement) {
                $this->removeUnwantedNodes($node);

                $html = '';
                foreach (iterator_to_array($node->childNodes) as $child) {
                    $html .= $dom->saveHTML($child);
                }

                if (trim(strip_tags($html)) !== '') {
                    return trim($html);
                }
            }
        }

        return null;
    }

    protected function removeUnwantedNodes(DOMElement $node): void
    {
        $xpath = new DOMXPath($node->ownerDocument);
        foreach ($xpath->query('.//script|.//style|.//noscript|.//iframe', $node) as $remove) {
            $remove->parentNode?->removeChild($remove);
        }
    }

    protected function excerptFromHtml(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)) ?: '');
        if ($text === '') {
            return null;
        }

        return Str::limit($text, 280);
    }

    protected function parseDate(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (Throwable) {
            return null;
        }
    }

    protected function sourceToArray(NewsSource $source): array
    {
        return [
            'id' => $source->id,
            'name' => $source->name,
            'domain' => $source->domain,
            'home_url' => $source->home_url,
            'feed_url' => $source->feed_url,
            'sitemap_url' => $source->sitemap_url,
            'crawl_mode' => $source->crawl_mode,
            'max_articles_per_run' => $source->max_articles_per_run,
            'sort_order' => $source->sort_order,
            'is_active' => $source->is_active,
        ];
    }
}
