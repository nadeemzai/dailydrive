<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $articles = Article::whereNotNull('ai_generated_at')
                ->latest('published_at')
                ->get(['slug', 'published_at', 'updated_at', 'ai_generated_at']);

            $categories = Article::whereNotNull('ai_generated_at')
                ->whereNotNull('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category');

            return view('sitemap', compact('articles', 'categories'))->render();
        });

        return response($xml, 200, [
            'Content-Type'  => 'application/xml; charset=utf-8',
            'X-Robots-Tag'  => 'noindex',
        ]);
    }

    public static function clearCache(): void
    {
        Cache::forget('sitemap.xml');
    }
}
