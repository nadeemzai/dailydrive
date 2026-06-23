@extends('admin.layout')

@section('title', 'Articles')

@section('content')

<style>
    /* ── Filter tabs ── */
    .filter-tabs {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
    }

    .filter-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 13px;
        border-radius: 6px;
        border: 1px solid transparent;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--muted);
        text-decoration: none;
        transition: all 0.13s;
        white-space: nowrap;
    }

    .filter-tab:hover {
        background: var(--bg-hover, #f0f0f0);
        color: var(--text-2);
        border-color: var(--border);
    }

    .filter-tab.is-active {
        background: var(--brand-bg, rgba(80,70,228,0.08));
        color: var(--brand);
        border-color: rgba(80,70,228,0.25);
    }

    .filter-tab-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 18px;
        padding: 0 5px;
        border-radius: 999px;
        background: var(--bg, #f5f5f5);
        border: 1px solid var(--border);
        font-size: 0.68rem;
        font-weight: 700;
        color: var(--muted-2);
    }

    .filter-tab.is-active .filter-tab-count {
        background: rgba(80,70,228,0.12);
        color: var(--brand);
        border-color: rgba(80,70,228,0.2);
    }

    /* ── Search bar row ── */
    .dt-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
    }

    .dt-toolbar-search {
        display: flex;
        align-items: center;
        gap: 6px;
        flex: 1;
        min-width: 200px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 5px 10px;
    }

    .dt-toolbar-search svg { color: var(--muted-2); flex-shrink: 0; }

    .dt-toolbar-search input {
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.84rem;
        font-family: inherit;
        color: var(--text);
        flex: 1;
        min-width: 0;
    }

    .dt-toolbar-search input::placeholder { color: var(--muted-2); }

</style>

<div class="page-head">
    <div class="page-head-left">
        <h1>Articles</h1>
        <p>Manage scraped and AI-generated articles. Regenerate or delete individual records.</p>
    </div>
    <div class="page-head-actions">
        <a class="btn btn-primary" href="{{ route('admin.articles.create') }}">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Article
        </a>
    </div>
</div>

<div class="card">

    {{-- ── Filter tabs ── --}}
    <div style="padding: 10px 16px; border-bottom: 1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div class="filter-tabs">
            @php
                $tabBase = route('admin.articles.index');
                $searchParam = $search ? ['search' => $search] : [];
            @endphp
            <a href="{{ $tabBase . ($searchParam ? '?' . http_build_query($searchParam) : '') }}"
               class="filter-tab {{ $statusFilter === 'all' ? 'is-active' : '' }}">
                All
                <span class="filter-tab-count">{{ $counts['all'] }}</span>
            </a>
            <a href="{{ $tabBase . '?' . http_build_query(array_merge(['status' => 'published'], $searchParam)) }}"
               class="filter-tab {{ $statusFilter === 'published' ? 'is-active' : '' }}">
                <span style="width:7px;height:7px;border-radius:50%;background:#059669;display:inline-block;"></span>
                Published
                <span class="filter-tab-count">{{ $counts['published'] }}</span>
            </a>
            <a href="{{ $tabBase . '?' . http_build_query(array_merge(['status' => 'inactive'], $searchParam)) }}"
               class="filter-tab {{ $statusFilter === 'inactive' ? 'is-active' : '' }}">
                <span style="width:7px;height:7px;border-radius:50%;background:#e53e3e;display:inline-block;"></span>
                Inactive
                <span class="filter-tab-count">{{ $counts['inactive'] }}</span>
            </a>
            <a href="{{ $tabBase . '?' . http_build_query(array_merge(['status' => 'draft'], $searchParam)) }}"
               class="filter-tab {{ $statusFilter === 'draft' ? 'is-active' : '' }}">
                <span style="width:7px;height:7px;border-radius:50%;background:#d97706;display:inline-block;"></span>
                Draft
                <span class="filter-tab-count">{{ $counts['draft'] }}</span>
            </a>
        </div>
        <span style="font-size:0.77rem; color:var(--muted); white-space:nowrap;">
            {{ $articles->total() }} {{ Str::plural('article', $articles->total()) }}
        </span>
    </div>

    {{-- ── Search bar ── --}}
    <form method="GET" action="{{ route('admin.articles.index') }}" class="dt-toolbar">
        @if ($statusFilter !== 'all')
            <input type="hidden" name="status" value="{{ $statusFilter }}">
        @endif
        <div class="dt-toolbar-search">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search title or slug…" autocomplete="off">
        </div>
        <button class="btn btn-secondary btn-sm" type="submit">Search</button>
        @if ($search)
            <a href="{{ route('admin.articles.index', $statusFilter !== 'all' ? ['status' => $statusFilter] : []) }}"
               class="btn btn-secondary btn-sm" style="white-space:nowrap;">Clear</a>
        @endif
    </form>

    <div class="dt-wrap">
        <table class="data-table" id="articles-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title / Slug</th>
                    <th>Category</th>
                    <th>Published</th>
                    <th>SEO</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($articles as $article)
                <tr>
                    <td style="color:var(--muted-2); font-size:0.76rem;">{{ $article->id }}</td>
                    <td style="max-width:260px;">
                        <div class="dt-cell-primary" style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; line-height:1.4; font-size:0.84rem;">
                            {{ $article->displayTitle() }}
                        </div>
                        <div class="dt-cell-muted">{{ $article->slug }}</div>
                    </td>
                    <td>
                        @if ($article->category)
                            <span class="badge badge-indigo">{{ $article->category }}</span>
                        @else
                            <span class="badge badge-muted">—</span>
                        @endif
                    </td>
                    <td style="white-space:nowrap; font-size:0.79rem; color:var(--muted);">
                        {{ optional($article->published_at)->format('M d, Y') ?? optional($article->scraped_at)->format('M d, Y') ?? '—' }}
                    </td>
                    <td>
                        @if ($article->meta_title)
                            <span class="badge badge-green">SEO ✓</span>
                        @else
                            <span class="badge badge-muted">No SEO</span>
                        @endif
                    </td>

                    {{-- Status: Published or Draft --}}
                    <td>
                        @if ($article->ai_generated_at)
                            <span class="badge badge-green">Published</span>
                        @else
                            <span class="badge badge-amber">Draft</span>
                        @endif
                    </td>

                    <td>
                        <div class="row-actions">
                            <a class="btn btn-secondary btn-sm" href="{{ route('admin.articles.edit', $article) }}">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </a>

                            {{-- Quick Publish / Unpublish --}}
                            <form method="POST" action="{{ route('admin.articles.publish', $article) }}" style="display:inline;"
                                  onsubmit="return confirm('{{ $article->ai_generated_at ? 'Unpublish this article?' : 'Publish this article to the public site?' }}')">
                                @csrf
                                @if ($article->ai_generated_at)
                                    <button class="btn btn-sm" type="submit" title="Unpublish"
                                            style="background:rgba(229,62,62,0.1); color:#e53e3e; border:1px solid rgba(229,62,62,0.25);">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                        Unpublish
                                    </button>
                                @else
                                    <button class="btn btn-sm" type="submit" title="Publish"
                                            style="background:rgba(5,150,105,0.1); color:#059669; border:1px solid rgba(5,150,105,0.25);">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        Publish
                                    </button>
                                @endif
                            </form>

                            <form method="POST" action="{{ route('admin.articles.regenerate', $article) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-success btn-sm" type="submit" title="AI Regenerate">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                                    AI
                                </button>
                            </form>

                            @if ($article->slug && $article->ai_generated_at)
                            <a class="btn btn-secondary btn-sm" href="{{ route('articles.show', $article) }}" target="_blank" title="View on site">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                            @endif

                            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}"
                                  onsubmit="return confirm('Delete «{{ addslashes($article->displayTitle()) }}»?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit" title="Delete">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px; color:var(--muted);">
                        No articles found.
                        @if ($search || $statusFilter !== 'all')
                            <a href="{{ route('admin.articles.index') }}" style="color:var(--brand); margin-left:6px;">Clear filters</a>
                        @endif
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="dt-footer">
        @if ($articles->firstItem())
        <span class="dt-count">
            Showing {{ $articles->firstItem() }}–{{ $articles->lastItem() }} of {{ $articles->total() }}
        </span>
        @endif
        {{ $articles->links() }}
    </div>

</div>

@endsection
