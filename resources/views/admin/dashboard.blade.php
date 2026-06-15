@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')

    <div class="page-head">
        <div class="page-head-left">
            <h1>Dashboard</h1>
            <p>Overview of your DAILYdRIVE pipeline — scraping, AI generation, and publishing.</p>
        </div>
        <div class="page-head-actions">
            <a class="btn btn-secondary" href="{{ route('admin.articles.create') }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Article
            </a>
            <a class="btn btn-primary" href="{{ route('admin.sources.index') }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                Manage Sources
            </a>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="stats-grid">
        <div class="stat-card indigo">
            <div class="stat-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div class="stat-val">{{ $articleCount }}</div>
            <div class="stat-label">Total Articles</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            </div>
            <div class="stat-val">{{ $aiPublishedCount ?? 0 }}</div>
            <div class="stat-label">AI Published</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="stat-val">{{ $pendingCount ?? 0 }}</div>
            <div class="stat-label">Pending Regen</div>
        </div>
        <div class="stat-card sky">
            <div class="stat-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            </div>
            <div class="stat-val">{{ $sourceCount }}</div>
            <div class="stat-label">News Sources</div>
        </div>
        <div class="stat-card" style="--stat-accent:#8b5cf6;">
            <div class="stat-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="stat-val">{{ $subscriberCount ?? 0 }}</div>
            <div class="stat-label">Subscribers</div>
        </div>
    </div>

    {{-- TABLES ROW --}}
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">

        {{-- Latest Articles --}}
        <div class="card">
            <div class="card-hd">
                <span class="card-hd-title">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Latest Articles
                </span>
                <a class="btn btn-secondary btn-sm" href="{{ route('admin.articles.index') }}">View all</a>
            </div>
            <div class="dt-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Source</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($latestArticles as $article)
                        <tr>
                            <td>
                                <a href="{{ route('admin.articles.edit', $article) }}" class="dt-cell-link" style="font-size:0.82rem; display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; max-width:180px;">
                                    {{ $article->displayTitle() }}
                                </a>
                                <div class="dt-cell-muted">{{ optional($article->published_at)->format('M d') ?? optional($article->scraped_at)->format('M d') }}</div>
                            </td>
                            <td><span class="badge badge-indigo">{{ $article->source_name }}</span></td>
                            <td>
                                @if ($article->ai_generated_at)
                                    <span class="badge badge-green">Published</span>
                                @else
                                    <span class="badge badge-amber">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="color:var(--muted); text-align:center; padding:24px;">No articles yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sources + Providers --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        News Sources
                    </span>
                    <a class="btn btn-secondary btn-sm" href="{{ route('admin.sources.index') }}">Manage</a>
                </div>
                <div class="dt-wrap">
                    <table class="data-table">
                        <thead><tr><th>Name</th><th>Mode</th><th>Status</th></tr></thead>
                        <tbody>
                        @forelse ($latestSources as $source)
                            <tr>
                                <td class="dt-cell-primary" style="font-size:0.83rem;">{{ $source->name }}</td>
                                <td><span class="badge badge-sky">{{ $source->crawl_mode }}</span></td>
                                <td>
                                    @if ($source->is_active)
                                        <span class="badge badge-green">Active</span>
                                    @else
                                        <span class="badge badge-red">Paused</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="color:var(--muted); text-align:center; padding:20px;">No sources.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        AI Providers
                    </span>
                    <a class="btn btn-secondary btn-sm" href="{{ route('admin.ai-providers.index') }}">Manage</a>
                </div>
                <div class="dt-wrap">
                    <table class="data-table">
                        <thead><tr><th>Label</th><th>Model</th><th>Status</th></tr></thead>
                        <tbody>
                        @forelse ($latestProviders ?? [] as $provider)
                            <tr>
                                <td class="dt-cell-primary" style="font-size:0.83rem;">{{ $provider->label }}</td>
                                <td style="font-size:0.76rem; color:var(--muted);">{{ $provider->model }}</td>
                                <td>
                                    @if ($provider->is_active)
                                        <span class="badge badge-green">Active</span>
                                    @else
                                        <span class="badge badge-muted">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="color:var(--muted); text-align:center; padding:20px;">No providers.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection
