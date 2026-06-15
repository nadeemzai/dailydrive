@extends('admin.layout')

@section('title', $provider->exists ? 'Edit AI Provider' : 'New AI Provider')

@section('content')

    <div class="page-head">
        <div class="page-head-left">
            <h1>{{ $provider->exists ? 'Edit AI Provider' : 'New AI Provider' }}</h1>
            <p>{{ $provider->exists ? 'Update API key, model, or generation settings.' : 'Connect a new AI provider to power article regeneration.' }}</p>
        </div>
        <div class="page-head-actions">
            <a class="btn btn-secondary" href="{{ route('admin.ai-providers.index') }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to Providers
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

    <form method="POST" action="{{ $provider->exists ? route('admin.ai-providers.update', $provider) : route('admin.ai-providers.store') }}">
        @csrf
        @if ($provider->exists) @method('PUT') @endif

        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- ── PROVIDER TYPE & IDENTITY ── --}}
            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        Provider Identity
                    </span>
                </div>
                <div class="card-bd">
                    <div class="form-grid cols-2">

                        <div class="form-group">
                            <label class="form-label">Provider <span style="color:var(--red);">*</span></label>
                            <select class="form-control" name="provider" id="provider-select">
                                <option value="gemini" @selected(old('provider', $provider->provider) === 'gemini')>
                                    Gemini (Google)
                                </option>
                                <option value="openai" @selected(old('provider', $provider->provider) === 'openai')>
                                    OpenAI (GPT)
                                </option>
                                <option value="claude" @selected(old('provider', $provider->provider) === 'claude')>
                                    Claude (Anthropic)
                                </option>
                                <option value="deepseek" @selected(old('provider', $provider->provider) === 'deepseek')>
                                    DeepSeek AI
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Display Label <span style="color:var(--red);">*</span></label>
                            <input class="form-control" name="label"
                                   value="{{ old('label', $provider->label) }}"
                                   placeholder="e.g. Gemini 2.5 Flash" required>
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">Shown in admin panel and article AI badge.</span>
                        </div>

                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">
                                API Key
                                @if ($provider->exists)
                                    <span style="font-weight:400; text-transform:none; letter-spacing:0; color:var(--muted-2);">(leave blank to keep existing)</span>
                                @else
                                    <span style="color:var(--red);">*</span>
                                @endif
                            </label>
                            <div style="position:relative;">
                                <input class="form-control" type="password" name="api_key" id="api-key-input"
                                       placeholder="{{ $provider->exists ? '••••••••••••••••••••••••' : 'sk-… / AIzaSy… / sk-ant-…' }}"
                                       style="padding-right:44px;">
                                <button type="button" id="toggle-key"
                                        style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:var(--muted); padding:4px;">
                                    <svg id="eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── MODEL SELECTION ── --}}
            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        Model & Generation Settings
                    </span>
                </div>
                <div class="card-bd">
                    <div class="form-grid cols-2">

                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Model <span style="color:var(--red);">*</span></label>
                            <select class="form-control" name="model" id="model-select">
                                @php
                                    $providerKey = old('provider', $provider->provider);
                                    $options = $modelOptions[$providerKey] ?? [];
                                    $currentModel = old('model', $provider->model);
                                @endphp
                                @if ($currentModel && !in_array($currentModel, $options, true))
                                    <option value="{{ $currentModel }}" selected>{{ $currentModel }} (current)</option>
                                @endif
                                @foreach ($options as $opt)
                                    <option value="{{ $opt }}" @selected($currentModel === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">
                                Model list updates automatically when you change the provider above.
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Temperature</label>
                            <input class="form-control" type="number" step="0.05" min="0" max="2" name="temperature"
                                   value="{{ old('temperature', $provider->temperature ?? 0.70) }}"
                                   id="temp-input">
                            <div style="margin-top:8px;">
                                <input type="range" min="0" max="2" step="0.05"
                                       value="{{ old('temperature', $provider->temperature ?? 0.70) }}"
                                       id="temp-slider"
                                       style="width:100%; accent-color:var(--brand);">
                                <div style="display:flex; justify-content:space-between; font-size:0.68rem; color:var(--muted-2); margin-top:2px;">
                                    <span>0 — deterministic</span>
                                    <span>2 — very random</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Max Output Tokens</label>
                            <input class="form-control" type="number" min="1000" max="32000" name="max_tokens"
                                   value="{{ old('max_tokens', $provider->max_tokens ?? 8000) }}">
                            <span style="font-size:0.72rem; color:var(--muted); margin-top:3px;">
                                Minimum 8000 enforced internally for complete FAQ + review generation.
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="is_active">
                                <option value="1" @selected(old('is_active', $provider->is_active ?? true) == '1')>
                                    Active — used in auto-publish pipeline
                                </option>
                                <option value="0" @selected(old('is_active', $provider->is_active ?? true) == '0')>
                                    Inactive — skip this provider
                                </option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── SYSTEM PROMPT ── --}}
            <div class="card">
                <div class="card-hd">
                    <span class="card-hd-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        System Prompt
                        <span style="font-weight:400; font-size:0.75rem; color:var(--muted); text-transform:none; letter-spacing:0;">(optional)</span>
                    </span>
                </div>
                <div class="card-bd">
                    <div class="form-group">
                        <label class="form-label">Custom Writing Instructions</label>
                        <textarea class="form-control" name="system_prompt" rows="5"
                                  style="font-size:0.83rem; line-height:1.65;"
                                  placeholder="You are an expert tech journalist writing for a professional audience. Use clear, engaging language…">{{ old('system_prompt', $provider->system_prompt) }}</textarea>
                        <span style="font-size:0.72rem; color:var(--muted); margin-top:4px;">
                            Overrides the default system prompt for this provider only. Leave blank to use the built-in prompt.
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── ACTIONS ── --}}
            <div style="display:flex; gap:10px; align-items:center;">
                <button class="btn btn-primary" type="submit">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    {{ $provider->exists ? 'Save Changes' : 'Add Provider' }}
                </button>
                <a class="btn btn-secondary" href="{{ route('admin.ai-providers.index') }}">Cancel</a>
            </div>

        </div>
    </form>

    <script>
    // All models by provider
    var modelMap = @json($modelOptions);

    var providerSel = document.getElementById('provider-select');
    var modelSel    = document.getElementById('model-select');
    var currentModel = "{{ old('model', $provider->model) }}";

    if (providerSel) {
        providerSel.addEventListener('change', function() {
            var models = modelMap[this.value] || [];
            modelSel.innerHTML = '';
            if (models.length === 0) {
                var placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = '— no models defined —';
                modelSel.appendChild(placeholder);
                return;
            }
            models.forEach(function(m, i) {
                var opt = document.createElement('option');
                opt.value = m;
                opt.textContent = m;
                if (i === 0) opt.selected = true;
                modelSel.appendChild(opt);
            });
        });
    }

    // Temperature slider sync
    var tempInput  = document.getElementById('temp-input');
    var tempSlider = document.getElementById('temp-slider');
    if (tempInput && tempSlider) {
        tempSlider.addEventListener('input', function() { tempInput.value = this.value; });
        tempInput.addEventListener('input', function() {
            var v = parseFloat(this.value);
            if (!isNaN(v)) tempSlider.value = v;
        });
    }

    // Show/hide API key
    var toggleBtn  = document.getElementById('toggle-key');
    var apiInput   = document.getElementById('api-key-input');
    if (toggleBtn && apiInput) {
        toggleBtn.addEventListener('click', function() {
            apiInput.type = apiInput.type === 'password' ? 'text' : 'password';
        });
    }
    </script>

@endsection
