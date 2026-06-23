@extends('admin.layout')

@section('title', 'Archive')

@section('content')

<style>
    .arc-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 14px;
        border-radius: 999px;
        border: 1px solid var(--border);
        background: var(--bg-card);
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--muted);
        text-decoration: none;
        transition: all 0.12s;
        white-space: nowrap;
    }
    .arc-pill:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-bg); }
    .arc-pill.on    { background: var(--brand); border-color: var(--brand); color: #fff; }

    .arc-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
    }
    .arc-label {
        font-size: 0.71rem;
        font-weight: 700;
        letter-spacing: .07em;
        text-transform: uppercase;
        color: var(--muted-2);
        min-width: 62px;
        flex-shrink: 0;
    }

    .arc-date-input {
        padding: 6px 10px;
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        font-size: 0.84rem;
        font-family: inherit;
        background: var(--bg-card);
        color: var(--text);
        cursor: pointer;
        min-width: 140px;
    }
    .arc-date-input:focus { outline: none; border-color: var(--brand); }

    .arc-range-sep {
        font-size: 0.82rem;
        color: var(--muted-2);
        font-weight: 600;
        flex-shrink: 0;
    }

    .arc-select {
        padding: 5px 10px;
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        font-size: 0.84rem;
        font-family: inherit;
        background: var(--bg-card);
        color: var(--text);
    }
</style>

<div class="page-head">
    <div class="page-head-left">
        <h1>Archive</h1>
        <p>Articles expired 7 days after publishing — filter by date range, reactivate, or delete.</p>
    </div>
    <div class="page-head-actions">
        <a class="btn btn-secondary" href="{{ route('admin.articles.index') }}">← All Articles</a>
    </div>
</div>

{{-- Stats --}}
<div style="display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
    <div style="padding:14px 20px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-lg);flex:1;min-width:120px;">
        <div style="font-size:1.6rem;font-weight:800;color:var(--text);">{{ $articles->total() }}</div>
        <div style="font-size:0.78rem;color:var(--muted);margin-top:2px;">{{ ($from||$to) ? 'Filtered' : 'Total Archived' }}</div>
    </div>
    <div style="padding:14px 20px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-lg);flex:1;min-width:120px;">
        <div style="font-size:1.6rem;font-weight:800;color:var(--brand);">{{ $categories->count() }}</div>
        <div style="font-size:0.78rem;color:var(--muted);margin-top:2px;">Categories</div>
    </div>
</div>

<div class="card">

    {{-- ══ Filters form ══ --}}
    <form method="GET" action="{{ route('admin.archive') }}" id="arc-form">

        {{-- 1. Date type toggle --}}
        @php
            $baseParams = ['filter_by' => $filterBy, 'from' => $from, 'to' => $to, 'category' => $category, 'search' => $search];
            $pubUrl = route('admin.archive', array_filter(array_merge($baseParams, ['filter_by' => 'published'])));
            $expUrl = route('admin.archive', array_filter(array_merge($baseParams, ['filter_by' => 'expired'])));
        @endphp
        <div class="arc-row">
            <span class="arc-label">Filter by</span>
            <a href="{{ $pubUrl }}" class="arc-pill {{ $filterBy === 'published' ? 'on' : '' }}">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Published Date
            </a>
            <a href="{{ $expUrl }}" class="arc-pill {{ $filterBy === 'expired' ? 'on' : '' }}">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Expired Date
            </a>
        </div>

        {{-- 2. Date range: From → To --}}
        <input type="hidden" name="filter_by" value="{{ $filterBy }}">
        <div class="arc-row">
            <span class="arc-label">Date Range</span>
            <input type="date" name="from" value="{{ $from }}" class="arc-date-input" placeholder="From">
            <span class="arc-range-sep">→</span>
            <input type="date" name="to"   value="{{ $to }}"   class="arc-date-input" placeholder="To">
            <button class="btn btn-secondary btn-sm" type="submit">Apply</button>
            @if($from || $to)
                <a href="{{ route('admin.archive', array_filter(['filter_by'=>$filterBy,'category'=>$category,'search'=>$search])) }}"
                   class="btn btn-secondary btn-sm" style="white-space:nowrap;">✕ Clear dates</a>
            @endif
        </div>

        {{-- 3. Search + Category --}}
        <div style="display:flex;align-items:center;gap:10px;padding:10px 16px;border-bottom:1px solid var(--border);flex-wrap:wrap;">
            <div class="dt-search-wrap" style="flex:1;min-width:180px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input class="dt-search-input" type="text" name="search" value="{{ $search }}"
                       placeholder="Search archived articles…" autocomplete="off">
            </div>

            @if($categories->isNotEmpty())
            <select name="category" class="arc-select" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->category }}" {{ $category === $cat->category ? 'selected' : '' }}>
                        {{ $cat->category }} ({{ $cat->cnt }})
                    </option>
                @endforeach
            </select>
            @endif

            <button class="btn btn-secondary btn-sm" type="submit">Search</button>

            @if($search || $category || $from || $to)
                <a href="{{ route('admin.archive', ['filter_by' => $filterBy]) }}"
                   class="btn btn-secondary btn-sm" style="white-space:nowrap;">✕ Clear all</a>
            @endif

            <span style="font-size:0.77rem;color:var(--muted);margin-left:auto;white-space:nowrap;">
                {{ $articles->total() }} {{ Str::plural('article', $articles->total()) }}
            </span>
        </div>

    </form>

    {{-- Active filter badge strip --}}
    @if($from || $to || $category || $search)
    <div style="padding:7px 16px;background:rgba(80,70,228,0.03);border-bottom:1px solid var(--border);font-size:0.79rem;color:var(--muted);display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        <strong style="color:var(--text-2);">{{ ucfirst($filterBy) }} date</strong>
        @if($from && $to)
            <span class="badge badge-indigo">{{ \Carbon\Carbon::parse($from)->format('M d, Y') }} → {{ \Carbon\Carbon::parse($to)->format('M d, Y') }}</span>
        @elseif($from)
            <span class="badge badge-indigo">From {{ \Carbon\Carbon::parse($from)->format('M d, Y') }}</span>
        @elseif($to)
            <span class="badge badge-indigo">Until {{ \Carbon\Carbon::parse($to)->format('M d, Y') }}</span>
        @endif
        @if($category) <span class="badge badge-indigo">{{ $category }}</span>   @endif
        @if($search)   <span class="badge badge-muted">"{{ $search }}"</span>   @endif
    </div>
    @endif

    {{-- ══ Table ══ --}}
    <div class="dt-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title / Slug</th>
                    <th>Category</th>
                    <th>Published Date</th>
                    <th>Expired Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($articles as $article)
                <tr>
                    <td style="color:var(--muted-2);font-size:0.76rem;">{{ $article->id }}</td>
                    <td style="max-width:300px;">
                        <div class="dt-cell-primary" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.4;font-size:0.84rem;">
                            {{ $article->displayTitle() }}
                        </div>
                        <div class="dt-cell-muted">{{ $article->slug }}</div>
                    </td>
                    <td>
                        @if($article->category)
                            <span class="badge badge-indigo">{{ $article->category }}</span>
                        @else
                            <span class="badge badge-muted">—</span>
                        @endif
                    </td>
                    <td style="white-space:nowrap;font-size:0.79rem;color:var(--muted);">
                        <div>{{ optional($article->published_at)->format('M d, Y') ?? '—' }}</div>
                        @if($article->published_at)
                            <div style="font-size:0.72rem;color:var(--muted-2);">{{ $article->published_at->format('H:i') }}</div>
                        @endif
                    </td>
                    <td style="white-space:nowrap;font-size:0.79rem;">
                        @if($article->published_at)
                            <div style="color:#e53e3e;font-weight:600;">{{ $article->published_at->addDays(7)->format('M d, Y') }}</div>
                            <div style="font-size:0.72rem;color:var(--muted-2);">{{ $article->published_at->addDays(7)->diffForHumans() }}</div>
                        @else
                            <span style="color:var(--muted);">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="row-actions">
                            <a class="btn btn-secondary btn-sm" href="{{ route('admin.articles.edit', $article) }}">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.articles.status', $article) }}" style="display:inline;"
                                  onsubmit="return confirm('Reactivate this article?')">
                                @csrf
                                <button type="submit" class="btn btn-sm"
                                        style="background:rgba(5,150,105,0.08);color:#059669;border:1px solid rgba(5,150,105,0.2);">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    Reactivate
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}"
                                  onsubmit="return confirm('Permanently delete «{{ addslashes($article->displayTitle()) }}»?')" style="display:inline;">
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
                    <td colspan="6" style="text-align:center;padding:48px;color:var(--muted);">
                        <div style="font-size:2rem;opacity:.35;margin-bottom:10px;">📦</div>
                        No archived articles
                        @if($from || $to || $search || $category)
                            match this filter.
                            <br><a href="{{ route('admin.archive', ['filter_by' => $filterBy]) }}" style="color:var(--brand);font-size:0.83rem;">Clear filters</a>
                        @else
                            .<br><span style="font-size:0.82rem;">Articles expire automatically 7 days after publishing.</span>
                        @endif
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="dt-footer">
        @if($articles->firstItem())
        <span class="dt-count">Showing {{ $articles->firstItem() }}–{{ $articles->lastItem() }} of {{ $articles->total() }}</span>
        @endif
        {{ $articles->links() }}
    </div>

</div>

@endsection
