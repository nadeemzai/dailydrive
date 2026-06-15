<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SitemapController;
use App\Models\AiProvider;
use App\Models\Article;
use App\Models\NewsSource;
use App\Services\ContentGenerationService;
use Illuminate\Http\Request;
use RuntimeException;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        return view('admin.articles.index', [
            'articles' => Article::query()->latest('published_at')->latest('id')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.articles.form', [
            'article' => new Article(),
            'sources' => NewsSource::query()->orderBy('name')->get(),
            'aiProviders' => AiProvider::query()->orderBy('label')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);
        $this->applySourceMetadata($data);

        $isPublished = (bool) ($data['is_published'] ?? false);
        unset($data['is_published']);

        $data['source_url']      = $data['source_url'] ?: 'manual://'.Str::uuid();
        $data['source_url_hash'] = sha1($data['source_url']);
        $data['slug']            = $data['slug'] ?: $this->makeSlug($data['title'], $data['source_url']);
        $data['scraped_at']      = now();
        $data['published_at']    = $data['published_at'] ?? now();

        if ($isPublished) {
            $data['ai_generated_at']        = now();
            $data['generated_title']        = $data['title'];
            $data['generated_excerpt']      = $data['excerpt'] ?? null;
            $data['generated_content_html'] = $data['content_html'] ?? null;
        }

        Article::create($data);

        if ($isPublished) SitemapController::clearCache();

        $msg = $isPublished ? 'Article created and published.' : 'Article saved as draft.';
        return redirect()->route('admin.articles.index')->with('status', $msg);
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', [
            'article' => $article,
            'sources' => NewsSource::query()->orderBy('name')->get(),
            'aiProviders' => AiProvider::query()->orderBy('label')->get(),
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $data = $this->validateRequest($request);
        $this->applySourceMetadata($data);

        $isPublished = (bool) ($data['is_published'] ?? false);
        unset($data['is_published']);

        $data['source_url'] = $data['source_url'] ?: $article->source_url ?: 'manual://'.Str::uuid();

        if ($data['source_url'] !== $article->source_url) {
            $data['source_url_hash'] = sha1($data['source_url']);
        }

        $data['slug']        = $data['slug'] ?: $this->makeSlug($data['title'], $data['source_url']);
        $data['scraped_at']  = now();

        if ($isPublished) {
            // Only set timestamp if not already published (preserve original AI timestamp)
            if (! $article->ai_generated_at) {
                $data['ai_generated_at'] = now();
            }
            // Populate generated fields from original only if AI has never run
            if (! $article->ai_generated_at) {
                $data['generated_title']        = $article->generated_title ?: ($data['title'] ?? $article->title);
                $data['generated_excerpt']      = $article->generated_excerpt ?: ($data['excerpt'] ?? $article->excerpt);
                $data['generated_content_html'] = $article->generated_content_html ?: ($data['content_html'] ?? $article->content_html);
            }
        } else {
            $data['ai_generated_at'] = null;
        }

        $article->update($data);

        SitemapController::clearCache();

        $msg = $isPublished ? 'Article updated and published.' : 'Article saved as draft.';
        return redirect()->route('admin.articles.index')->with('status', $msg);
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('admin.articles.index')->with('status', 'Article deleted.');
    }

    public function togglePublish(Article $article)
    {
        if ($article->ai_generated_at) {
            $article->update(['ai_generated_at' => null]);
            $msg = 'Article unpublished (saved as draft).';
        } else {
            $article->update([
                'ai_generated_at'        => now(),
                'generated_title'        => $article->generated_title ?: $article->title,
                'generated_excerpt'      => $article->generated_excerpt ?: $article->excerpt,
                'generated_content_html' => $article->generated_content_html ?: $article->content_html,
                'published_at'           => $article->published_at ?? now(),
            ]);
            $msg = 'Article published successfully.';
        }

        SitemapController::clearCache();

        return redirect()->back()->with('status', $msg);
    }

    public function regenerate(Request $request, Article $article, ContentGenerationService $service)
    {
        $data = $request->validate([
            'ai_provider_id' => ['nullable', 'exists:ai_providers,id'],
        ]);

        $provider = ($data['ai_provider_id'] ?? null)
            ? AiProvider::query()->whereKey($data['ai_provider_id'])->first()
            : null;

        try {
            $service->regenerate($article, $provider);
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'AI regeneration failed. Please check the provider settings and Laravel logs.');
        }

        SitemapController::clearCache();

        return redirect()->route('admin.articles.edit', $article)->with('status', 'Article regenerated successfully.');
    }

    protected function validateRequest(Request $request): array
    {
        return $request->validate([
            'news_source_id'   => ['nullable', 'exists:news_sources,id'],
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255'],
            'source_name'      => ['nullable', 'string', 'max:255'],
            'source_domain'    => ['nullable', 'string', 'max:190'],
            'source_url'       => ['nullable', 'string'],
            'category'         => ['nullable', 'string', 'max:100'],
            'author_name'      => ['nullable', 'string', 'max:255'],
            'image_url'        => ['nullable', 'string'],
            'excerpt'          => ['nullable', 'string'],
            'content_html'     => ['nullable', 'string'],
            'published_at'     => ['nullable', 'date'],
            'meta_title'       => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:155'],
            'meta_keywords'    => ['nullable', 'string', 'max:500'],
            'is_published'     => ['nullable', 'boolean'],
        ]);
    }

    protected function applySourceMetadata(array &$data): void
    {
        if (! empty($data['news_source_id'])) {
            $source = NewsSource::find($data['news_source_id']);
            if ($source) {
                $data['source_name'] = $source->name;
                $data['source_domain'] = $source->domain;
            }
        }
    }

    protected function makeSlug(string $title, string $url): string
    {
        $base = Str::slug($title) ?: 'article';

        return $base.'-'.substr(sha1($url), 0, 10);
    }
}
