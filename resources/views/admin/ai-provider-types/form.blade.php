@extends('admin.layout')

@section('title', $type->exists ? 'Edit Provider Type' : 'New Provider Type')

@section('content')

    <div class="page-head">
        <div class="page-head-left">
            <h1>{{ $type->exists ? 'Edit Provider Type' : 'New Provider Type' }}</h1>
            <p>{{ $type->exists ? 'Update name, models, endpoint, or badge color.' : 'Register a new AI provider type. It will appear in the AI Providers form immediately.' }}</p>
        </div>
        <div class="page-head-actions">
            <a class="btn btn-secondary" href="{{ route('admin.ai-provider-types.index') }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back
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

    @if ($type->exists && $type->is_system)
        <div class="flash flash-success" style="background:rgba(99,102,241,0.08); border-color:rgba(99,102,241,0.3); color:var(--brand-2);">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Built-in provider — slug is protected. All other fields can be edited.
        </div>
    @endif

    <form method="POST" action="{{ $type->exists ? route('admin.ai-provider-types.update', $type) : route('admin.ai-provider-types.store') }}">
        @csrf
        @if ($type->exists) @method('PUT') @endif

        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- ── IDENTITY ── --}}
            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Identity
                    </span>
                </div>
                <div class="card-bd">
                    <div class="form-grid cols-2">

                        <div class="form-group">
                            <label class="form-label">Display Name <span style="color:var(--red);">*</span></label>
                            <input class="form-control" name="name"
                                   value="{{ old('name', $type->name) }}"
                                   placeholder="e.g. Mistral AI" required>
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">
                                Shown in the AI Providers dropdown.
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Slug <span style="color:var(--red);">*</span>
                                @if ($type->exists && $type->is_system)
                                    <span style="font-weight:400; text-transform:none; letter-spacing:0; color:var(--muted-2);">(locked)</span>
                                @endif
                            </label>
                            <input class="form-control" name="slug"
                                   value="{{ old('slug', $type->slug) }}"
                                   placeholder="e.g. mistral"
                                   {{ ($type->exists && $type->is_system) ? 'readonly' : '' }}
                                   style="{{ ($type->exists && $type->is_system) ? 'opacity:0.55; cursor:not-allowed;' : '' }}"
                                   required>
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">
                                Lowercase letters, numbers, underscores only. Used as provider identifier in the database.
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sort Order</label>
                            <input class="form-control" type="number" name="sort_order" min="0" max="9999"
                                   value="{{ old('sort_order', $type->sort_order ?? 99) }}">
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">
                                Lower number appears first in lists.
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Badge Color <span style="color:var(--red);">*</span></label>
                            <select class="form-control" name="badge_color" id="badge-color-select">
                                @foreach ($badgeColors as $cls => $label)
                                    <option value="{{ $cls }}" @selected(old('badge_color', $type->badge_color) === $cls)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div style="margin-top:8px;">
                                <span class="badge" id="badge-preview" style="font-size:0.78rem;">Preview</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── API INTEGRATION ── --}}
            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        API Integration
                    </span>
                </div>
                <div class="card-bd">
                    <div class="form-grid cols-2">

                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Call Type <span style="color:var(--red);">*</span></label>
                            <select class="form-control" name="call_type">
                                @foreach ($callTypes as $value => $label)
                                    <option value="{{ $value }}" @selected(old('call_type', $type->call_type) === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">
                                Determines which API method is used internally. Most new providers use <strong>OpenAI-Compatible</strong>.
                            </span>
                        </div>

                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Fixed Base URL</label>
                            <input class="form-control" name="base_url"
                                   value="{{ old('base_url', $type->base_url) }}"
                                   placeholder="https://api.example.com/v1/chat/completions">
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">
                                Hardcoded endpoint for this provider type (e.g. Groq, GLM). Leave blank if users must enter their own URL per instance.
                            </span>
                        </div>

                        <div class="form-group" style="grid-column:1/-1;">
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                                <input type="hidden" name="requires_base_url" value="0">
                                <input type="checkbox" name="requires_base_url" value="1"
                                       {{ old('requires_base_url', $type->requires_base_url) ? 'checked' : '' }}
                                       style="width:16px; height:16px; accent-color:var(--brand); cursor:pointer;">
                                <span>
                                    <strong>Requires Base URL per instance</strong><br>
                                    <span style="font-size:0.72rem; color:var(--muted);">
                                        When checked, the API Providers form will show a required "Base URL" field for this provider type.
                                        Use this for fully custom endpoints (self-hosted Ollama, private APIs, etc.).
                                    </span>
                                </span>
                            </label>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── MODELS ── --}}
            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        Models
                        <span style="font-weight:400; font-size:0.75rem; color:var(--muted); text-transform:none; letter-spacing:0;">(optional)</span>
                    </span>
                    <button type="button" id="add-model-btn"
                            style="margin-left:auto; background:var(--brand-bg); border:1px solid var(--brand-glow); color:var(--brand-2); padding:5px 12px; border-radius:var(--r-sm); font-size:0.78rem; cursor:pointer; display:flex; align-items:center; gap:5px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Model
                    </button>
                </div>
                <div class="card-bd">
                    <div id="models-list" style="display:flex; flex-direction:column; gap:8px;">
                        {{-- Rows populated by JS --}}
                    </div>
                    <div id="models-empty" style="color:var(--muted); font-size:0.82rem; padding:12px 0; display:none;">
                        No models added yet. Click "Add Model" to add one, or leave empty for providers where users type the model name freely.
                    </div>
                    {{-- Hidden textarea submitted as models_raw (newline-separated) --}}
                    <textarea name="models_raw" id="models-raw-input" style="display:none;"></textarea>
                </div>
            </div>

            {{-- ── ACTIONS ── --}}
            <div style="display:flex; gap:10px; align-items:center;">
                <button class="btn btn-primary" type="submit" id="submit-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    {{ $type->exists ? 'Save Changes' : 'Create Provider Type' }}
                </button>
                <a class="btn btn-secondary" href="{{ route('admin.ai-provider-types.index') }}">Cancel</a>
            </div>

        </div>
    </form>

    <script>
    // ── Initial model data from PHP ──
    var existingModels = @json($type->models ?? []);

    var modelsList   = document.getElementById('models-list');
    var modelsEmpty  = document.getElementById('models-empty');
    var modelsRaw    = document.getElementById('models-raw-input');
    var addModelBtn  = document.getElementById('add-model-btn');
    var submitBtn    = document.getElementById('submit-btn');

    function renderModels(models) {
        modelsList.innerHTML = '';
        models.forEach(function(m, i) {
            modelsList.appendChild(makeRow(m));
        });
        syncEmptyState();
    }

    function makeRow(value) {
        var row = document.createElement('div');
        row.style.cssText = 'display:flex; gap:8px; align-items:center;';

        var inp = document.createElement('input');
        inp.className = 'form-control';
        inp.value = value || '';
        inp.placeholder = 'e.g. llama-3.3-70b-versatile';
        inp.style.flex = '1';
        inp.addEventListener('input', syncEmptyState);

        var del = document.createElement('button');
        del.type = 'button';
        del.title = 'Remove';
        del.style.cssText = 'flex-shrink:0; background:var(--red-bg); border:1px solid rgba(244,63,94,0.25); color:#fb7185; width:34px; height:34px; border-radius:var(--r-sm); cursor:pointer; display:flex; align-items:center; justify-content:center;';
        del.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
        del.addEventListener('click', function() {
            row.remove();
            syncEmptyState();
        });

        row.appendChild(inp);
        row.appendChild(del);
        return row;
    }

    function syncEmptyState() {
        var rows = modelsList.querySelectorAll('input');
        modelsEmpty.style.display = rows.length === 0 ? '' : 'none';
    }

    addModelBtn.addEventListener('click', function() {
        var row = makeRow('');
        modelsList.appendChild(row);
        row.querySelector('input').focus();
        syncEmptyState();
    });

    // Before submit: populate hidden textarea
    document.querySelector('form').addEventListener('submit', function() {
        var values = [];
        modelsList.querySelectorAll('input').forEach(function(inp) {
            var v = inp.value.trim();
            if (v) values.push(v);
        });
        modelsRaw.value = values.join('\n');
    });

    // ── Badge color preview ──
    var badgeSel     = document.getElementById('badge-color-select');
    var badgePreview = document.getElementById('badge-preview');

    function updateBadge(cls) {
        badgePreview.className = 'badge ' + cls;
    }

    badgeSel.addEventListener('change', function() { updateBadge(this.value); });
    updateBadge(badgeSel.value);

    // ── Init ──
    renderModels(existingModels);
    if (existingModels.length === 0) modelsEmpty.style.display = '';
    </script>

@endsection
