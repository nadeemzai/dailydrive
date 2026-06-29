<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('category') || $request->filled('search')) {
            return redirect()->route('articles.index', $request->only(['category', 'search']));
        }

        $carouselArticles = Article::published()
            ->whereNotNull('image_url')
            ->latest('published_at')
            ->limit(5)
            ->get();

        $featuredArticle = Article::published()
            ->whereNotNull('image_url')
            ->whereNotIn('id', $carouselArticles->pluck('id'))
            ->latest('published_at')
            ->first();

        $latestArticles = Article::published()
            ->latest('published_at')
            ->limit(12)
            ->get();

        // Single query replaces the N*2 loop: load light columns, group in PHP
        $allCategoryArticles = Article::published()
            ->whereNotNull('category')
            ->select([
                'id', 'category', 'title', 'slug', 'image_url',
                'published_at', 'scraped_at',
                'generated_title', 'generated_excerpt', 'excerpt',
                'views',
            ])
            ->latest('published_at')
            ->get();

        $categoryGroups = $allCategoryArticles
            ->groupBy('category')
            ->map(fn ($items) => [
                'articles' => $items->take(4),
                'total'    => $items->count(),
            ])
            ->sortKeys()
            ->all();

        $categoryCards = collect($categoryGroups)->map(fn ($g) => $g['total']);

        return view('articles.index', compact(
            'carouselArticles', 'featuredArticle', 'latestArticles', 'categoryGroups', 'categoryCards'
        ));
    }

    public function listing(Request $request)
    {
        $category = $request->query('category');
        $search   = $request->query('search');

        $categories = Article::published()
            ->whereNotNull('category')
            ->selectRaw('category, count(*) as cnt')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        $totalCount = Article::published()->count();

        $articles = Article::published()
            ->when($category, fn ($q) => $q->where('category', $category))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('generated_title', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%")
                      ->orWhere('generated_excerpt', 'like', "%{$search}%");
                });
            })
            ->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        $suggestedArticles = ($articles->isEmpty() && $search)
            ? Article::published()->latest('published_at')->limit(3)->get()
            : collect();

        return view('articles.listing', compact(
            'articles', 'categories', 'category', 'search', 'totalCount', 'suggestedArticles'
        ));
    }

    public function show(Article $article)
    {
        abort_if($article->ai_generated_at === null, 404);

        // Count view once per session per article
        $sessionKey = 'viewed_' . $article->id;
        if (! session()->has($sessionKey)) {
            $article->increment('views');
            session()->put($sessionKey, true);
        }

        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->when($article->category, fn ($q) => $q->where('category', $article->category))
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('articles.show', compact('article', 'related'));
    }

    public function like(Article $article)
    {
        abort_if($article->ai_generated_at === null, 404);
        $article->increment('likes');
        return response()->json(['likes' => $article->fresh()->likes]);
    }
}
