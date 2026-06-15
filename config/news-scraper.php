<?php

return [
    'user_agent' => env('NEWS_SCRAPER_USER_AGENT', 'AutoBlogScraper/1.0 (+https://localhost)'),
    'sources' => [
        [
            'name' => 'TechCrunch',
            'domain' => 'techcrunch.com',
            'url' => 'https://techcrunch.com/',
            'feed_url' => 'https://techcrunch.com/feed/',
            'sitemap_url' => 'https://techcrunch.com/sitemap_index.xml',
        ],
        [
            'name' => 'Ars Technica',
            'domain' => 'arstechnica.com',
            'url' => 'https://arstechnica.com/',
            'feed_url' => 'https://feeds.arstechnica.com/arstechnica/index',
            'sitemap_url' => 'https://arstechnica.com/sitemap.xml',
        ],
        [
            'name' => 'VentureBeat',
            'domain' => 'venturebeat.com',
            'url' => 'https://venturebeat.com/',
            'feed_url' => 'https://venturebeat.com/feed/',
            'sitemap_url' => 'https://venturebeat.com/sitemap_index.xml',
        ],
    ],
];
