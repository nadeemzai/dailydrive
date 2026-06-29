<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\AiProviderType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AiProviderTypeController extends Controller
{
    public const CALL_TYPES = [
        'openai'            => 'OpenAI (GPT format)',
        'gemini'            => 'Google Gemini',
        'claude'            => 'Anthropic Claude',
        'deepseek'          => 'DeepSeek',
        'openai_compatible' => 'OpenAI-Compatible (any custom endpoint)',
    ];

    public const BADGE_COLORS = [
        'badge-green'  => 'Green',
        'badge-sky'    => 'Sky Blue',
        'badge-amber'  => 'Amber / Yellow',
        'badge-indigo' => 'Indigo',
        'badge-purple' => 'Purple',
        'badge-orange' => 'Orange',
        'badge-red'    => 'Red',
        'badge-muted'  => 'Grey (default)',
    ];

    public function index()
    {
        return view('admin.ai-provider-types.index', [
            'types' => AiProviderType::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.ai-provider-types.form', [
            'type'        => new AiProviderType([
                'call_type'   => 'openai_compatible',
                'badge_color' => 'badge-muted',
                'sort_order'  => 99,
            ]),
            'callTypes'   => self::CALL_TYPES,
            'badgeColors' => self::BADGE_COLORS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);
        AiProviderType::create($data);
        return redirect()->route('admin.ai-provider-types.index')
            ->with('status', 'Provider type created successfully.');
    }

    public function edit(AiProviderType $aiProviderType)
    {
        return view('admin.ai-provider-types.form', [
            'type'        => $aiProviderType,
            'callTypes'   => self::CALL_TYPES,
            'badgeColors' => self::BADGE_COLORS,
        ]);
    }

    public function update(Request $request, AiProviderType $aiProviderType)
    {
        $data = $this->validateRequest($request, $aiProviderType->id);

        if ($aiProviderType->is_system) {
            unset($data['slug']);
        }

        $aiProviderType->update($data);
        return redirect()->route('admin.ai-provider-types.index')
            ->with('status', 'Provider type updated successfully.');
    }

    public function destroy(AiProviderType $aiProviderType)
    {
        if ($aiProviderType->is_system) {
            return back()->with('error', 'Built-in provider types cannot be deleted.');
        }

        if (AiProvider::where('provider', $aiProviderType->slug)->exists()) {
            return back()->with('error', 'Cannot delete: AI providers are using this type. Remove those providers first.');
        }

        $aiProviderType->delete();
        return redirect()->route('admin.ai-provider-types.index')
            ->with('status', 'Provider type deleted.');
    }

    protected function validateRequest(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'slug'              => [
                'required', 'string', 'max:64', 'alpha_dash',
                Rule::unique('ai_provider_types', 'slug')->ignore($ignoreId),
            ],
            'name'              => ['required', 'string', 'max:255'],
            'call_type'         => ['required', Rule::in(array_keys(self::CALL_TYPES))],
            'base_url'          => ['nullable', 'string', 'max:500'],
            'badge_color'       => ['required', Rule::in(array_keys(self::BADGE_COLORS))],
            'sort_order'        => ['required', 'integer', 'min:0', 'max:9999'],
            'models_raw'        => ['nullable', 'string'],
        ]);

        $modelsRaw = trim($validated['models_raw'] ?? '');
        $models = $modelsRaw !== ''
            ? array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $modelsRaw))))
            : [];

        return [
            'slug'              => strtolower($validated['slug']),
            'name'              => $validated['name'],
            'call_type'         => $validated['call_type'],
            'base_url'          => filled($validated['base_url']) ? $validated['base_url'] : null,
            'requires_base_url' => $request->boolean('requires_base_url'),
            'badge_color'       => $validated['badge_color'],
            'sort_order'        => (int) $validated['sort_order'],
            'models'            => $models,
        ];
    }
}
