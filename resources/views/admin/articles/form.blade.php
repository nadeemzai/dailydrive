@extends('admin.layout')

@section('title', $article->exists ? 'Edit Article' : 'New Article')

@section('content')

    <div class="page-head">
        <div class="page-head-left">
            <h1>{{ $article->exists ? 'Edit Article' : 'New Article' }}</h1>
            <p>{{ $article->exists ? 'Update content, metadata, or re-run AI regeneration.' : 'Manually add an article — or let the scraper fill this automatically.' }}</p>
        </div>
        <div class="page-head-actions">
            @if ($article->exists && $article->slug)
                <a class="btn btn-secondary" href="{{ route('articles.show', $article) }}" target="_blank">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    View Live
                </a>
            @endif
            <a class="btn btn-secondary" href="{{ route('admin.articles.index') }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to Articles
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="flash flash-error" style="flex-direction:column; align-items:flex-start; gap:6px;">
            <strong>Please fix the following errors:</strong>
            <ul style="list-style:disc; padding-left:18px; margin:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}">
        @csrf
        @if ($article->exists) @method('PUT') @endif

        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- ── SECTION 1: BASIC INFO ── --}}
            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Basic Information
                    </span>
                </div>
                <div class="card-bd">
                    <div class="form-grid cols-2">

                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Title <span style="color:var(--red);">*</span></label>
                            <input class="form-control" name="title"
                                   value="{{ old('title', $article->title) }}"
                                   placeholder="Article headline…" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slug</label>
                            <input class="form-control" name="slug"
                                   value="{{ old('slug', $article->slug) }}"
                                   placeholder="auto-generated-if-empty">
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">Leave blank to auto-generate from title.</span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Author</label>
                            <input class="form-control" name="author_name"
                                   value="{{ old('author_name', $article->author_name ?? 'Farhan') }}"
                                   placeholder="Farhan">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Source</label>
                            <select class="form-control" name="news_source_id">
                                <option value="">— Manual / none —</option>
                                @foreach ($sources as $source)
                                    <option value="{{ $source->id }}"
                                        @selected(old('news_source_id', $article->news_source_id) == $source->id)>
                                        {{ $source->name }} ({{ $source->domain }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select class="form-control" name="category">
                                <option value="">— Not assigned —</option>
                                @foreach (['Technology','Artificial Intelligence','Business','Security','Science','Environment','Health','Gaming','Policy','Other'] as $catOpt)
                                    <option value="{{ $catOpt }}" @selected(old('category', $article->category) === $catOpt)>{{ $catOpt }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Source Domain</label>
                            <input class="form-control" name="source_domain"
                                   value="{{ old('source_domain', $article->source_domain) }}"
                                   placeholder="e.g. techcrunch.com">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Source URL</label>
                            <input class="form-control" name="source_url" type="url"
                                   value="{{ old('source_url', !str_starts_with($article->source_url ?? '', 'manual://') ? $article->source_url : '') }}"
                                   placeholder="https://… (leave blank for manual articles)">
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">Optional for manual articles — auto-generated if left blank.</span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Image URL</label>
                            <input class="form-control" name="image_url" type="url"
                                   value="{{ old('image_url', $article->image_url) }}"
                                   placeholder="https://…" id="image-url-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Published At</label>
                            <input class="form-control" type="datetime-local" name="published_at"
                                   value="{{ old('published_at', optional($article->published_at)->format('Y-m-d\TH:i')) }}">
                        </div>

                        {{-- Image preview --}}
                        <div id="img-preview-wrap" style="grid-column:1/-1; display:{{ $article->image_url ? 'block' : 'none' }};">
                            <label class="form-label">Image Preview</label>
                            <img id="img-preview" src="{{ $article->image_url }}"
                                 style="max-height:160px; border-radius:var(--r); border:1px solid var(--border); object-fit:cover;">
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── SECTION 2: CONTENT ── --}}
            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                        Article Content
                    </span>
                </div>
                <div class="card-bd">
                    <div class="form-grid">

                        <div class="form-group">
                            <label class="form-label">Excerpt / Summary</label>
                            <textarea class="form-control" name="excerpt" rows="3"
                                      placeholder="2–3 sentence summary shown in cards and meta descriptions…">{{ old('excerpt', $article->excerpt) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Content HTML
                                @if ($article->exists && $article->ai_generated_at)
                                    <span class="badge badge-green" style="margin-left:6px; vertical-align:middle;">AI Generated</span>
                                @endif
                            </label>
                            <textarea class="form-control" name="content_html" rows="12"
                                      style="font-family:ui-monospace,'Cascadia Code',Consolas,monospace; font-size:0.8rem; line-height:1.6;"
                                      placeholder="<h2>Section</h2><p>Paragraph…</p>">{{ old('content_html', $article->content_html) }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── SECTION 3: SEO ── --}}
            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        SEO Meta Fields
                    </span>
                    <span style="font-size:0.75rem; color:var(--muted);">Auto-filled by AI — override manually if needed</span>
                </div>
                <div class="card-bd">
                    <div class="form-grid cols-2">

                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Meta Title <span style="color:var(--muted-2); font-weight:400; text-transform:none; letter-spacing:0;">(max 60 chars)</span></label>
                            <input class="form-control" name="meta_title" maxlength="60"
                                   value="{{ old('meta_title', $article->meta_title) }}"
                                   placeholder="SEO-optimized page title…">
                        </div>

                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Meta Description <span style="color:var(--muted-2); font-weight:400; text-transform:none; letter-spacing:0;">(max 155 chars)</span></label>
                            <textarea class="form-control" name="meta_description" rows="2" maxlength="155"
                                      placeholder="Compelling description for search engines…">{{ old('meta_description', $article->meta_description) }}</textarea>
                        </div>

                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Meta Keywords</label>
                            <input class="form-control" name="meta_keywords"
                                   value="{{ old('meta_keywords', $article->meta_keywords) }}"
                                   placeholder="keyword1, keyword2, keyword3…">
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── PUBLISH STATUS ── --}}
            @php $isPublished = old('is_published', $article->ai_generated_at ? '1' : '0'); @endphp
            <div class="card" style="border:1.5px solid {{ $article->ai_generated_at ? 'rgba(5,150,105,0.35)' : 'var(--border)' }}; background:{{ $article->ai_generated_at ? 'rgba(5,150,105,0.03)' : '' }}">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Publish Status
                    </span>
                    @if ($article->exists)
                        @if ($article->ai_generated_at)
                            <span class="badge badge-green">
                                ✓ Published {{ $article->ai_generated_at->diffForHumans() }}
                            </span>
                        @else
                            <span class="badge badge-amber">Draft — not visible on public site</span>
                        @endif
                    @endif
                </div>
                <div class="card-bd">
                    <label style="display:flex; align-items:flex-start; gap:12px; cursor:pointer; user-select:none;">
                        <input type="checkbox" name="is_published" value="1"
                               id="is_published"
                               {{ $isPublished ? 'checked' : '' }}
                               style="width:18px; height:18px; margin-top:2px; cursor:pointer; accent-color:#6366f1; flex-shrink:0;">
                        <div>
                            <div style="font-weight:700; font-size:0.9rem; color:var(--text);">Publish to public site</div>
                            <div style="font-size:0.78rem; color:var(--muted); margin-top:3px; line-height:1.55;">
                                When checked, this article will be visible to all visitors.
                                Uncheck to save as a draft (hidden from public).
                            </div>
                            @if (!$article->exists)
                                <div style="font-size:0.76rem; color:var(--muted-2); margin-top:8px; padding:8px 12px; background:var(--bg); border-radius:6px; border:1px solid var(--border); line-height:1.55;">
                                    <strong style="color:var(--text);">Tip:</strong> For manual articles, the content you enter will be used directly as the published version.
                                    You can also save as draft first and run AI Regeneration later.
                                </div>
                            @endif
                        </div>
                    </label>
                </div>
            </div>

            {{-- ── SAVE BUTTON ── --}}
            <div style="display:flex; gap:10px; align-items:center;">
                <button class="btn btn-primary" type="submit" id="save-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    {{ $article->exists ? 'Save Changes' : 'Create Article' }}
                </button>
                <a class="btn btn-secondary" href="{{ route('admin.articles.index') }}">Cancel</a>
            </div>

        </div>
    </form>

    {{-- ── AI REGENERATION CARD (edit only) ── --}}
    @if ($article->exists)
    <div class="card" style="margin-top:20px;">
        <div class="card-hd">
            <span class="card-hd-title">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                AI Regeneration
            </span>
            @if ($article->ai_generated_at)
                <span style="font-size:0.75rem; color:var(--muted);">
                    Last generated {{ $article->ai_generated_at->diffForHumans() }}
                    via {{ $article->aiProvider?->label ?? $article->ai_provider }}
                </span>
            @endif
        </div>
        <div class="card-bd">
            <p style="font-size:0.84rem; color:var(--muted); margin-bottom:18px; line-height:1.65;">
                Rewrites the article title, content, excerpt, FAQ, review, and SEO meta using AI.
                The original scraped content is preserved in the database.
            </p>
            <form method="POST" action="{{ route('admin.articles.regenerate', $article) }}">
                @csrf
                <div style="display:flex; align-items:flex-end; gap:12px; flex-wrap:wrap;">
                    <div class="form-group" style="min-width:260px; flex:1;">
                        <label class="form-label">AI Provider</label>
                        <select class="form-control" name="ai_provider_id">
                            <option value="">— Use default active provider —</option>
                            @foreach ($aiProviders as $prov)
                                <option value="{{ $prov->id }}">
                                    {{ $prov->label }} — {{ $prov->model }} ({{ ucfirst($prov->provider) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-success" type="submit"
                            onclick="return confirm('Regenerate this article with AI? Current generated content will be overwritten.')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        Run AI Regeneration
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <script>
    // Image preview on URL input
    var imgInput = document.getElementById('image-url-input');
    var imgPreview = document.getElementById('img-preview');
    var imgWrap = document.getElementById('img-preview-wrap');
    if (imgInput) {
        imgInput.addEventListener('input', function() {
            var val = this.value.trim();
            if (val) {
                imgPreview.src = val;
                imgWrap.style.display = 'block';
            } else {
                imgWrap.style.display = 'none';
            }
        });
    }
    </script>

@endsection
