@extends('admin.layout')

@section('title', $source->exists ? 'Edit Source' : 'New Source')

@section('content')

    <div class="page-head">
        <div class="page-head-left">
            <h1>{{ $source->exists ? 'Edit Source' : 'New News Source' }}</h1>
            <p>{{ $source->exists ? 'Update the crawl settings for this source.' : 'Add a website — the scraper will crawl its RSS feed, sitemap, or HTML pages automatically.' }}</p>
        </div>
        <div class="page-head-actions">
            <a class="btn btn-secondary" href="{{ route('admin.sources.index') }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to Sources
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="flash flash-error" style="flex-direction:column; align-items:flex-start; gap:6px;">
            <strong>Please fix the following:</strong>
            <ul style="list-style:disc; padding-left:18px; margin:0;">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $source->exists ? route('admin.sources.update', $source) : route('admin.sources.store') }}">
        @csrf
        @if ($source->exists) @method('PUT') @endif

        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- ── SECTION 1: IDENTITY ── --}}
            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        Source Identity
                    </span>
                </div>
                <div class="card-bd">
                    <div class="form-grid cols-2">

                        <div class="form-group">
                            <label class="form-label">Source Name <span style="color:var(--red);">*</span></label>
                            <input class="form-control" name="name"
                                   value="{{ old('name', $source->name) }}"
                                   placeholder="e.g. TechCrunch" required>
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">
                                Used as the category label on the public site.
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Domain <span style="color:var(--red);">*</span></label>
                            <div style="position:relative;">
                                <span style="position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--muted-2); font-size:0.82rem; pointer-events:none;">https://</span>
                                <input class="form-control" name="domain"
                                       style="padding-left:68px;"
                                       value="{{ old('domain', $source->domain) }}"
                                       placeholder="techcrunch.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Home URL <span style="color:var(--red);">*</span></label>
                            <input class="form-control" name="home_url" type="url"
                                   value="{{ old('home_url', $source->home_url) }}"
                                   placeholder="https://techcrunch.com" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="is_active">
                                <option value="1" @selected(old('is_active', $source->is_active ?? true) == '1')>
                                    Active — scraped every run
                                </option>
                                <option value="0" @selected(old('is_active', $source->is_active ?? true) == '0')>
                                    Paused — skip for now
                                </option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── SECTION 2: FEED / CRAWL URLS ── --}}
            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1"/></svg>
                        Feed & Crawl URLs
                    </span>
                    <span style="font-size:0.75rem; color:var(--muted);">Fill at least one feed or sitemap URL</span>
                </div>
                <div class="card-bd">
                    <div class="form-grid">

                        <div class="form-group">
                            <label class="form-label">
                                RSS / Atom Feed URL
                                <span class="badge badge-sky" style="margin-left:6px; vertical-align:middle;">Recommended</span>
                            </label>
                            <input class="form-control" name="feed_url" type="url"
                                   value="{{ old('feed_url', $source->feed_url) }}"
                                   placeholder="https://techcrunch.com/feed/">
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">
                                Most reliable method. Find it at /feed, /rss, or /atom.
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sitemap URL</label>
                            <input class="form-control" name="sitemap_url" type="url"
                                   value="{{ old('sitemap_url', $source->sitemap_url) }}"
                                   placeholder="https://techcrunch.com/sitemap.xml">
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">
                                Used as fallback or for backfill mode. Often at /sitemap.xml or /sitemap_index.xml.
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── SECTION 3: CRAWL SETTINGS ── --}}
            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                        Crawl Settings
                    </span>
                </div>
                <div class="card-bd">
                    <div class="form-grid cols-2">

                        <div class="form-group">
                            <label class="form-label">Crawl Mode</label>
                            <select class="form-control" name="crawl_mode">
                                <option value="latest" @selected(old('crawl_mode', $source->crawl_mode) === 'latest')>
                                    Latest — fetch newest articles only
                                </option>
                                <option value="backfill" @selected(old('crawl_mode', $source->crawl_mode) === 'backfill')>
                                    Backfill — fetch older articles too
                                </option>
                            </select>
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">
                                Use "Latest" for the 10-min auto-cycle. Use "Backfill" in the daily 3am job.
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Max Articles Per Run</label>
                            <input class="form-control" type="number" name="max_articles_per_run"
                                   min="1" max="200"
                                   value="{{ old('max_articles_per_run', $source->max_articles_per_run ?? 10) }}">
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">
                                Limits how many new articles are saved per scraper run.
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sort Order</label>
                            <input class="form-control" type="number" name="sort_order"
                                   min="0" max="9999"
                                   value="{{ old('sort_order', $source->sort_order ?? 0) }}">
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">
                                Lower numbers are scraped first. 0 = default.
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── ACTIONS ── --}}
            <div style="display:flex; gap:10px; align-items:center;">
                <button class="btn btn-primary" type="submit">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    {{ $source->exists ? 'Save Changes' : 'Add Source' }}
                </button>
                <a class="btn btn-secondary" href="{{ route('admin.sources.index') }}">Cancel</a>
            </div>

        </div>
    </form>

@endsection
