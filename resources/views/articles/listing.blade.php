@extends('layouts.public')

@section('title', $search ? 'Search: ' . $search . ' — DAILYdRIVE' : ($category ? $category . ' — DAILYdRIVE' : 'All Articles — DAILYdRIVE'))
@section('meta_description', $category ? 'Browse all ' . $category . ' articles on DAILYdRIVE.' : 'Browse all articles on DAILYdRIVE — AI-powered tech news.')

@section('content')

<style>
    /* ── Listing layout ── */
    .listing-wrap {
        display: grid;
        grid-template-columns: 268px 1fr;
        gap: 28px;
        padding: 36px 0 72px;
        align-items: start;
    }

    /* ── Sidebar ── */
    .listing-sidebar {
        position: sticky;
        top: calc(var(--nav-h) + 3px + 20px);
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .sbar-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        overflow: hidden;
    }

    .sbar-search-wrap {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 6px 8px;
    }

    .sbar-search-wrap svg { flex-shrink: 0; color: var(--muted-2); margin: 0 4px; }

    .sbar-search-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 0.875rem;
        font-family: inherit;
        padding: 7px 4px;
        background: transparent;
        color: var(--text);
        min-width: 0;
    }

    .sbar-search-input::placeholder { color: var(--muted-2); }

    .sbar-search-btn {
        flex-shrink: 0;
        padding: 6px 14px;
        background: var(--brand);
        color: #fff;
        border: none;
        border-radius: var(--r-sm);
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: background 0.14s;
    }

    .sbar-search-btn:hover { background: var(--brand-2); }

    .sbar-hd {
        padding: 11px 16px;
        border-bottom: 1px solid var(--border);
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--muted);
        background: var(--bg);
    }

    .sbar-cat-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 16px;
        font-size: 0.845rem;
        font-weight: 500;
        color: var(--text-2);
        border-bottom: 1px solid var(--border);
        transition: background 0.12s, color 0.12s;
        gap: 8px;
        text-decoration: none;
    }

    .sbar-cat-link:last-child { border-bottom: none; }

    .sbar-cat-link:hover {
        background: var(--bg-hover);
        color: var(--brand);
    }

    .sbar-cat-link.is-active {
        background: var(--brand-bg);
        color: var(--brand);
        font-weight: 700;
    }

    .sbar-cat-count {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 999px;
        background: var(--bg);
        border: 1px solid var(--border);
        color: var(--muted-2);
        white-space: nowrap;
        flex-shrink: 0;
    }

    .sbar-cat-link.is-active .sbar-cat-count {
        background: rgba(80, 70, 228, 0.1);
        color: var(--brand);
        border-color: rgba(80, 70, 228, 0.2);
    }

    /* ── Main content ── */
    .listing-main { min-width: 0; }

    .listing-hd {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        padding-bottom: 18px;
        border-bottom: 1.5px solid var(--border);
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .listing-hd-title {
        font-size: 1.3rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .listing-hd-title::before {
        content: '';
        display: block;
        width: 4px;
        height: 22px;
        border-radius: 2px;
        background: var(--brand);
        flex-shrink: 0;
    }

    .listing-hd-count {
        font-size: 0.8rem;
        color: var(--muted);
        margin-top: 3px;
        font-weight: 400;
    }

    .listing-clear-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--muted);
        padding: 5px 12px;
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        transition: all 0.13s;
        white-space: nowrap;
    }

    .listing-clear-link:hover {
        border-color: var(--brand);
        color: var(--brand);
        background: var(--brand-bg);
    }

    /* ── 2-column article grid ── */
    .listing-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .listing-grid .acard-img { aspect-ratio: 16/9; }

    /* ── Empty state ── */
    .listing-empty {
        text-align: center;
        padding: 60px 24px;
        background: var(--bg-card);
        border: 1.5px dashed var(--border-2);
        border-radius: var(--r-xl);
        color: var(--muted);
    }

    .listing-empty-icon { font-size: 2.6rem; margin-bottom: 12px; opacity: 0.5; }
    .listing-empty-title { font-size: 1.05rem; font-weight: 700; color: var(--text-2); margin-bottom: 6px; }
    .listing-empty-desc { font-size: 0.85rem; max-width: 38ch; margin: 0 auto; line-height: 1.7; }

    /* ── Suggested articles header ── */
    .listing-suggested-hd {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--text);
        margin: 36px 0 18px;
    }

    .listing-suggested-hd::before {
        content: '';
        display: block;
        width: 4px;
        height: 18px;
        border-radius: 2px;
        background: var(--muted-2);
        flex-shrink: 0;
    }

    /* ── Responsive ── */
    @media (max-width: 1000px) {
        .listing-wrap { grid-template-columns: 230px 1fr; gap: 20px; }
    }

    @media (max-width: 820px) {
        .listing-wrap { grid-template-columns: 1fr; }
        .listing-sidebar { position: static; flex-direction: row; flex-wrap: wrap; gap: 12px; }
        .sbar-card { flex: 1; min-width: 220px; }
    }

    @media (max-width: 600px) {
        .listing-grid { grid-template-columns: 1fr; }
        .listing-wrap { padding: 20px 0 48px; }
    }
</style>

<div class="container">
    <div class="listing-wrap">

        {{-- ══════════════════════════
             SIDEBAR
        ══════════════════════════ --}}
        <aside class="listing-sidebar">

            {{-- Search box --}}
            <div class="sbar-card">
                <form action="{{ route('articles.index') }}" method="GET">
                    @if ($category)
                        <input type="hidden" name="category" value="{{ $category }}">
                    @endif
                    <div class="sbar-search-wrap">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input class="sbar-search-input" type="text" name="search"
                               value="{{ $search }}" placeholder="Search articles…" autocomplete="off">
                        <button class="sbar-search-btn" type="submit">Search</button>
                    </div>
                </form>
            </div>

            {{-- Category filter --}}
            <div class="sbar-card">
                <div class="sbar-hd">Browse Categories</div>
                <nav>
                    <a href="{{ route('articles.index', $search ? ['search' => $search] : []) }}"
                       class="sbar-cat-link {{ !$category ? 'is-active' : '' }}">
                        <span>All Articles</span>
                        <span class="sbar-cat-count">{{ $totalCount }}</span>
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('articles.index', array_filter(['category' => $cat->category, 'search' => $search])) }}"
                           class="sbar-cat-link {{ $category === $cat->category ? 'is-active' : '' }}">
                            <span>{{ $cat->category }}</span>
                            <span class="sbar-cat-count">{{ $cat->cnt }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

        </aside>

        {{-- ══════════════════════════
             MAIN CONTENT
        ══════════════════════════ --}}
        <main class="listing-main">

            {{-- Heading bar --}}
            <div class="listing-hd">
                <div>
                    <div class="listing-hd-title">
                        @if ($search)
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:.55"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            Search: "{{ $search }}"
                        @elseif ($category)
                            {{ $category }}
                        @else
                            All Articles
                        @endif
                    </div>
                    <div class="listing-hd-count">
                        {{ $articles->total() }} {{ Str::plural('article', $articles->total()) }} found
                    </div>
                </div>
                @if ($search || $category)
                    <a href="{{ route('articles.index') }}" class="listing-clear-link">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Clear filter
                    </a>
                @endif
            </div>

            {{-- Articles grid --}}
            @if ($articles->isEmpty())

                <div class="listing-empty">
                    <div class="listing-empty-icon">{{ $search ? '&#128269;' : '&#128240;' }}</div>
                    @if ($search)
                        <div class="listing-empty-title">No results for "{{ $search }}"</div>
                        <p class="listing-empty-desc">Try different keywords or browse a category from the sidebar.</p>
                    @else
                        <div class="listing-empty-title">No articles in this category yet</div>
                        <p class="listing-empty-desc">Check back soon or browse other categories.</p>
                    @endif
                    <a href="{{ route('articles.index') }}"
                       style="display:inline-flex;align-items:center;gap:6px;margin-top:16px;padding:8px 20px;border-radius:8px;background:var(--brand);color:#fff;font-size:0.84rem;font-weight:700;transition:background 0.14s;"
                       onmouseover="this.style.background='var(--brand-2)'" onmouseout="this.style.background='var(--brand)'">
                        Browse all articles
                    </a>
                </div>

                {{-- Show 3 recent articles as suggestions on search no-results --}}
                @if ($suggestedArticles->isNotEmpty())
                    <div class="listing-suggested-hd">Recent Articles</div>
                    <div class="listing-grid">
                        @foreach ($suggestedArticles as $article)
                            @include('articles._card', ['article' => $article])
                        @endforeach
                    </div>
                @endif

            @else

                <div class="listing-grid">
                    @foreach ($articles as $article)
                        @include('articles._card', ['article' => $article])
                    @endforeach
                </div>

                @if ($articles->hasPages())
                    <div class="pagination-wrap">
                        {{ $articles->links() }}
                    </div>
                @endif

            @endif

        </main>

    </div>
</div>

@endsection
