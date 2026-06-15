@extends('admin.layout')

@section('title', 'Articles')

@section('content')

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

        {{-- Search bar --}}
        <div class="dt-search-bar">
            <div class="dt-search-wrap">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input class="dt-search-input" id="dt-search" type="text" placeholder="Search articles…">
            </div>
            <span style="font-size:0.77rem; color:var(--muted); margin-left:auto;">
                {{ $articles->total() }} {{ Str::plural('article', $articles->total()) }}
            </span>
        </div>

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
                        <td style="max-width:280px;">
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
                        <td>
                            @if ($article->ai_generated_at)
                                <span class="badge badge-green">Published</span>
                            @else
                                <span class="badge badge-amber">Pending</span>
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
                            No articles found. Run the scraper to populate content.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="dt-footer">
            <span class="dt-count">
                Showing {{ $articles->firstItem() }}–{{ $articles->lastItem() }} of {{ $articles->total() }}
            </span>
            {{ $articles->links() }}
        </div>

    </div>

    <script>
    // Client-side table search
    document.getElementById('dt-search').addEventListener('input', function() {
        var q = this.value.toLowerCase();
        document.querySelectorAll('#articles-table tbody tr').forEach(function(row) {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
    </script>

@endsection
