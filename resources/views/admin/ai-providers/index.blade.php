@extends('admin.layout')

@section('title', 'AI Providers')

@section('content')

    <div class="page-head">
        <div class="page-head-left">
            <h1>AI Providers</h1>
            <p>Configure AI providers — Gemini, OpenAI, Claude, DeepSeek, GLM (Zhipu), or any OpenAI-compatible API.</p>
        </div>
        <div class="page-head-actions">
            <a class="btn btn-primary" href="{{ route('admin.ai-providers.create') }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Provider
            </a>
        </div>
    </div>

    <div class="card">

        <div class="dt-search-bar">
            <span style="font-size:0.77rem; color:var(--muted);">
                {{ $providers->total() }} {{ Str::plural('provider', $providers->total()) }} configured
            </span>
        </div>

        <div class="dt-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Provider</th>
                        <th>Model</th>
                        <th>Temperature</th>
                        <th>Max Tokens</th>
                        <th>Last Used</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($providers as $provider)
                    <tr>
                        <td>
                            <div class="dt-cell-primary">{{ $provider->label }}</div>
                            <div class="dt-cell-muted">#{{ $provider->id }}</div>
                        </td>
                        <td>
                            @php
                                $providerColors = [
                                    'gemini'            => 'badge-sky',
                                    'openai'            => 'badge-green',
                                    'claude'            => 'badge-amber',
                                    'deepseek'          => 'badge-indigo',
                                    'groq'              => 'badge-orange',
                                    'glm'               => 'badge-purple',
                                    'openai_compatible' => 'badge-muted',
                                ];
                                $providerLabels = [
                                    'openai_compatible' => 'OAI Compat',
                                    'groq'              => 'Groq',
                                    'glm'               => 'GLM',
                                ];
                                $cls   = $providerColors[$provider->provider] ?? 'badge-muted';
                                $label = $providerLabels[$provider->provider] ?? ucfirst($provider->provider);
                            @endphp
                            <span class="badge {{ $cls }}">{{ $label }}</span>
                        </td>
                        <td style="font-size:0.78rem; color:var(--muted); font-family:ui-monospace, monospace;">
                            {{ $provider->model ?: '—' }}
                        </td>
                        <td style="font-size:0.84rem; color:var(--text-2);">
                            {{ $provider->temperature ?? '—' }}
                        </td>
                        <td style="font-size:0.84rem; color:var(--text-2);">
                            {{ number_format($provider->max_tokens ?? 0) }}
                        </td>
                        <td style="font-size:0.78rem; color:var(--muted);">
                            {{ $provider->last_used_at ? optional($provider->last_used_at)->diffForHumans() : 'Never' }}
                        </td>
                        <td>
                            @if ($provider->is_active)
                                <span class="badge badge-green">Active</span>
                            @else
                                <span class="badge badge-muted">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <a class="btn btn-secondary btn-sm" href="{{ route('admin.ai-providers.edit', $provider) }}">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.ai-providers.destroy', $provider) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($provider->label) }}?')" style="display:inline;">
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
                        <td colspan="8" style="text-align:center; padding:40px; color:var(--muted);">
                            No AI providers configured yet. Add Gemini, OpenAI, or Claude.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="dt-footer">
            <span class="dt-count">{{ $providers->total() }} providers total</span>
            {{ $providers->links() }}
        </div>

    </div>

@endsection
