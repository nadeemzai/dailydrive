<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        // Redirect category/search queries to the dedicated listing page
        if ($request->filled('category') || $request->filled('search')) {
            return redirect()->route('articles.index', $request->only(['category', 'search']));
        }

        $categories = Article::query()
            ->whereNotNull('ai_generated_at')
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $categoryCards = Article::query()
            ->whereNotNull('category')
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->orderBy('category')
            ->pluck('total', 'category');

        $carouselArticles = Article::query()
            ->whereNotNull('ai_generated_at')
            ->whereNotNull('image_url')
            ->latest('published_at')
            ->limit(5)
            ->get();

        $featuredArticle = Article::query()
            ->whereNotNull('ai_generated_at')
            ->whereNotNull('image_url')
            ->whereNotIn('id', $carouselArticles->pluck('id'))
            ->latest('published_at')
            ->first();

        $latestArticles = Article::query()
            ->whereNotNull('ai_generated_at')
            ->latest('published_at')
            ->limit(12)
            ->get();

        $categoryGroups = [];
        foreach ($categories as $cat) {
            $catArticles = Article::query()
                ->whereNotNull('ai_generated_at')
                ->where('category', $cat)
                ->latest('published_at')
                ->limit(4)
                ->get();

            if ($catArticles->isNotEmpty()) {
                $total = Article::query()
                    ->whereNotNull('ai_generated_at')
                    ->where('category', $cat)
                    ->count();

                $categoryGroups[$cat] = [
                    'articles' => $catArticles,
                    'total'    => $total,
                ];
            }
        }

        return view('articles.index', compact(
            'carouselArticles', 'featuredArticle', 'latestArticles', 'categoryGroups', 'categoryCards'
        ));
    }

    public function listing(Request $request)
    {
        $category = $request->query('category');
        $search   = $request->query('search');

        $categories = Article::query()
            ->whereNotNull('ai_generated_at')
            ->whereNotNull('category')
            ->selectRaw('category, count(*) as cnt')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        $totalCount = Article::query()->whereNotNull('ai_generated_at')->count();

        $articles = Article::query()
            ->whereNotNull('ai_generated_at')
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
            ? Article::query()->whereNotNull('ai_generated_at')->latest('published_at')->limit(3)->get()
            : collect();

        return view('articles.listing', compact(
            'articles', 'categories', 'category', 'search', 'totalCount', 'suggestedArticles'
        ));
    }

    public function show(Article $article)
    {
        abort_if($article->ai_generated_at === null, 404);

        $related = Article::query()
            ->where('id', '!=', $article->id)
            ->whereNotNull('ai_generated_at')
            ->when($article->category, fn ($q) => $q->where('category', $article->category))
            ->latest('published_at')
            ->limit(4)
            ->get();

        return view('articles.show', compact('article', 'related'));
    }
}
