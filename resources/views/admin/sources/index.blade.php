@extends('admin.layout')

@section('title', 'News Sources')

@section('content')

    <div class="page-head">
        <div class="page-head-left">
            <h1>News Sources</h1>
            <p>Add websites here — the scraper will crawl their sitemaps, RSS feeds, or HTML pages automatically.</p>
        </div>
        <div class="page-head-actions">
            <a class="btn btn-primary" href="{{ route('admin.sources.create') }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Source
            </a>
        </div>
    </div>

    <div class="card">

        <div class="dt-search-bar">
            <div class="dt-search-wrap">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input class="dt-search-input" id="src-search" type="text" placeholder="Search sources…">
            </div>
        </div>

        <div class="dt-wrap">
            <table class="data-table" id="sources-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Domain</th>
                        <th>Crawl Mode</th>
                        <th>Articles</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($sources as $source)
                    <tr>
                        <td style="color:var(--muted-2); font-size:0.76rem;">{{ $source->id }}</td>
                        <td>
                            <div class="dt-cell-primary">{{ $source->name }}</div>
                        </td>
                        <td>
                            <a href="https://{{ $source->domain }}" target="_blank" rel="noreferrer"
                               style="font-size:0.78rem; color:var(--muted); display:flex; align-items:center; gap:4px;">
                                {{ $source->domain }}
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                        </td>
                        <td>
                            @php
                                $modeColors = ['rss' => 'badge-sky', 'sitemap' => 'badge-indigo', 'html' => 'badge-amber'];
                                $cls = $modeColors[$source->crawl_mode] ?? 'badge-muted';
                            @endphp
                            <span class="badge {{ $cls }}">{{ strtoupper($source->crawl_mode) }}</span>
                        </td>
                        <td>
                            @php $cnt = \App\Models\Article::where('source_name', $source->name)->count(); @endphp
                            <span style="font-size:0.84rem; font-weight:600; color:var(--text-2);">{{ $cnt }}</span>
                        </td>
                        <td>
                            @if ($source->is_active)
                                <span class="badge badge-green">Active</span>
                            @else
                                <span class="badge badge-red">Paused</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <a class="btn btn-secondary btn-sm" href="{{ route('admin.sources.edit', $source) }}">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.sources.destroy', $source) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($source->name) }} source? This will NOT delete scraped articles.')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M9 6V4h6v2"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:40px; color:var(--muted);">
                            No sources yet. Add TechCrunch, Ars Technica, or any RSS/sitemap source.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <script>
    document.getElementById('src-search').addEventListener('input', function() {
        var q = this.value.toLowerCase();
        document.querySelectorAll('#sources-table tbody tr').forEach(function(row) {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
    </script>

@endsection
