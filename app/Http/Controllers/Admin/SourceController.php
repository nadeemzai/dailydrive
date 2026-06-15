<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsSource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SourceController extends Controller
{
    public function index()
    {
        return view('admin.sources.index', [
            'sources' => NewsSource::query()->orderBy('sort_order')->orderBy('id')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.sources.form', [
            'source' => new NewsSource([
                'crawl_mode' => 'latest',
                'max_articles_per_run' => 10,
                'sort_order' => 0,
                'is_active' => true,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);
        NewsSource::create($data);

        return redirect()->route('admin.sources.index')->with('status', 'Source created.');
    }

    public function edit(NewsSource $source)
    {
        return view('admin.sources.form', compact('source'));
    }

    public function update(Request $request, NewsSource $source)
    {
        $source->update($this->validateRequest($request));

        return redirect()->route('admin.sources.index')->with('status', 'Source updated.');
    }

    public function destroy(NewsSource $source)
    {
        $source->delete();

        return redirect()->route('admin.sources.index')->with('status', 'Source deleted.');
    }

    protected function validateRequest(Request $request): array
    {
        $sourceId = $request->route('source')?->id;

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain' => [
                'required',
                'string',
                'max:190',
                Rule::unique('news_sources', 'domain')->ignore($sourceId),
            ],
            'home_url' => ['required', 'string'],
            'feed_url' => ['nullable', 'string'],
            'sitemap_url' => ['nullable', 'string'],
            'crawl_mode' => ['required', 'in:latest,backfill'],
            'max_articles_per_run' => ['required', 'integer', 'min:1', 'max:200'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
