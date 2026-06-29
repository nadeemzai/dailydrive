@extends('admin.layout')

@section('title', 'Provider Registry')

@section('content')

    <div class="page-head">
        <div class="page-head-left">
            <h1>Provider Registry</h1>
            <p>Manage AI provider types, their models, and API endpoints. Changes apply immediately to the AI Providers form.</p>
        </div>
        <div class="page-head-actions">
            <a class="btn btn-primary" href="{{ route('admin.ai-provider-types.create') }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Provider Type
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="flash flash-error">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="dt-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Call Type</th>
                        <th>Base URL</th>
                        <th>Models</th>
                        <th>Badge</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($types as $type)
                    <tr>
                        <td style="color:var(--muted-2); font-size:0.8rem;">{{ $type->sort_order }}</td>
                        <td>
                            <div class="dt-cell-primary">{{ $type->name }}</div>
                            @if ($type->is_system)
                                <div style="font-size:0.68rem; color:var(--muted-2); margin-top:2px;">Built-in</div>
                            @endif
                        </td>
                        <td style="font-size:0.78rem; font-family:ui-monospace,monospace; color:var(--muted);">
                            {{ $type->slug }}
                        </td>
                        <td style="font-size:0.78rem; color:var(--text-2);">
                            {{ $type->call_type }}
                        </td>
                        <td style="font-size:0.72rem; color:var(--muted); max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            @if ($type->requires_base_url)
                                <span style="color:var(--brand-2); font-style:italic;">User-defined per instance</span>
                            @elseif ($type->base_url)
                                {{ $type->base_url }}
                            @else
                                <span style="color:var(--muted-2);">—</span>
                            @endif
                        </td>
                        <td style="font-size:0.84rem; color:var(--text-2);">
                            @php $count = count($type->models ?? []) @endphp
                            {{ $count > 0 ? $count . ' model' . ($count !== 1 ? 's' : '') : '—' }}
                        </td>
                        <td>
                            <span class="badge {{ $type->badge_color }}">{{ $type->name }}</span>
                        </td>
                        <td>
                            <div class="row-actions">
                                <a class="btn btn-secondary btn-sm" href="{{ route('admin.ai-provider-types.edit', $type) }}">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                @if (!$type->is_system)
                                    <form method="POST" action="{{ route('admin.ai-provider-types.destroy', $type) }}"
                                          onsubmit="return confirm('Delete {{ addslashes($type->name) }}?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M9 6V4h6v2"/></svg>
                                            Delete
                                        </button>
                                    </form>
                                @else
                                    <span style="font-size:0.72rem; color:var(--muted-2); padding:4px 6px;">Protected</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:40px; color:var(--muted);">
                            No provider types found. Run <code>php artisan migrate</code> to seed built-in types.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="dt-footer">
            <span class="dt-count">{{ $types->count() }} provider type{{ $types->count() !== 1 ? 's' : '' }}</span>
        </div>
    </div>

@endsection
