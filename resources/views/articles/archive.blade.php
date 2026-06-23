@extends('layouts.public')

@section('title', $search ? 'Archive Search: ' . $search . ' — DAILYdRIVE' : ($category ? 'Archive: ' . $category . ' — DAILYdRIVE' : 'Article Archive — DAILYdRIVE'))
@section('meta_description', 'Browse archived articles on DAILYdRIVE — older content no longer featured on the main listing.')

@section('content')

<style>
    /* ── Layout (same as listing) ── */
    .listing-wrap {
        display: grid;
        grid-template-columns: 268px 1fr;
        gap: 28px;
        padding: 36px 0 72px;
        align-items: start;
    }

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
    .sbar-cat-link:hover { background: var(--bg-hover); color: var(--brand); }

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
        background: rgba(80,70,228,0.1);
        color: var(--brand);
        border-color: rgba(80,70,228,0.2);
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
        background: var(--muted-2);
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

    /* ── Archive notice banner ── */
    .archive-notice {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        background: rgba(217, 119, 6, 0.07);
        border: 1px solid rgba(217, 119, 6, 0.2);
        border-radius: var(--r-lg);
        font-size: 0.82rem;
        color: #92400e;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .archive-notice svg { flex-shrink: 0; opacity: 0.7; }

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
                <form action="{{ route('articles.archive') }}" method="GET">
                    @if ($category)
                        <input type="hidden" name="category" value="{{ $category }}">
                    @endif
                    <div class="sbar-search-wrap" >
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input class="sbar-search-input" type="text" name="search"
                               value="{{ $search }}" placeholder="Search archive…" autocomplete="off">
                        <button class="sbar-search-btn" type="submit">Search</button>
                    </div>
                </form>
            </div>

            {{-- Category filter --}}
            <div class="sbar-card">
                <div class="sbar-hd">Archive Categories</div>
                <nav>
                    <a href="{{ route('articles.archive', $search ? ['search' => $search] : []) }}"
                       class="sbar-cat-link {{ !$category ? 'is-active' : '' }}">
                        <span>All Archived</span>
                        <span class="sbar-cat-count">{{ $archiveCount }}</span>
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('articles.archive', array_filter(['category' => $cat->category, 'search' => $search])) }}"
                           class="sbar-cat-link {{ $category === $cat->category ? 'is-active' : '' }}">
                            <span>{{ $cat->category }}</span>
                            <span class="sbar-cat-count">{{ $cat->cnt }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- Back to live articles --}}
            <div class="sbar-card">
                <a href="{{ route('articles.index') }}" class="sbar-cat-link" style="border-bottom:none;">
                    <span style="display:flex; align-items:center; gap:7px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.6; flex-shrink:0;"><polyline points="15 18 9 12 15 6"/></svg>
                        Live Articles
                    </span>
                    <span class="sbar-cat-count">{{ $totalCount }}</span>
                </a>
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
                            Archive: "{{ $search }}"
                        @elseif ($category)
                            Archive: {{ $category }}
                        @else
                            Article Archive
                        @endif
                    </div>
                    <div class="listing-hd-count">
                        {{ $articles->total() }} archived {{ Str::plural('article', $articles->total()) }}
                    </div>
                </div>
                @if ($search || $category)
                    <a href="{{ route('articles.archive') }}" class="listing-clear-link">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Clear filter
                    </a>
                @endif
            </div>

            {{-- Archive notice --}}
            <div class="archive-notice">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                These articles were published more than 7 days ago and are no longer featured on the main listing.
            </div>

            {{-- Flash message --}}
            @if (session('info'))
                <div style="padding:10px 16px; background:rgba(80,70,228,0.07); border:1px solid rgba(80,70,228,0.18); border-radius:var(--r-lg); font-size:0.84rem; color:var(--brand); margin-bottom:18px;">
                    {{ session('info') }}
                </div>
            @endif

            {{-- Articles grid --}}
            @if ($articles->isEmpty())
                <div class="listing-empty">
                    <div class="listing-empty-icon">&#128269;</div>
                    @if ($search)
                        <div class="listing-empty-title">No archived articles match "{{ $search }}"</div>
                        <p class="listing-empty-desc">Try different keywords or browse a category.</p>
                    @else
                        <div class="listing-empty-title">No archived articles yet</div>
                        <p class="listing-empty-desc">Articles older than 7 days will appear here automatically.</p>
                    @endif
                    <a href="{{ route('articles.index') }}"
                       style="display:inline-flex;align-items:center;gap:6px;margin-top:16px;padding:8px 20px;border-radius:8px;background:var(--brand);color:#fff;font-size:0.84rem;font-weight:700;transition:background 0.14s;"
                       onmouseover="this.style.background='var(--brand-2)'" onmouseout="this.style.background='var(--brand)'">
                        Browse live articles
                    </a>
                </div>
            @else
                <div class="listing-grid">
                    @foreach ($articles as $article)
                        @include('articles._card', ['article' => $article])
                    @endforeach
                </div>

                {{ $articles->links() }}
            @endif

        </main>

    </div>
</div>

@endsection
