<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\AiProviderType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AiProviderController extends Controller
{
    protected function providerTypes(): \Illuminate\Database\Eloquent\Collection
    {
        return AiProviderType::orderBy('sort_order')->orderBy('name')->get();
    }

    protected function modelOptions(): array
    {
        return $this->providerTypes()
            ->mapWithKeys(fn(AiProviderType $t) => [$t->slug => $t->models ?? []])
            ->toArray();
    }

    protected function defaultBaseUrls(): array
    {
        return $this->providerTypes()
            ->whereNotNull('base_url')
            ->mapWithKeys(fn(AiProviderType $t) => [$t->slug => $t->base_url])
            ->toArray();
    }

    protected function requiresBaseUrlSlugs(): array
    {
        return $this->providerTypes()
            ->where('requires_base_url', true)
            ->pluck('slug')
            ->values()
            ->toArray();
    }

    public function index()
    {
        return view('admin.ai-providers.index', [
            'providers' => AiProvider::query()->orderBy('label')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.ai-providers.form', [
            'provider'              => new AiProvider([
                'provider'          => 'openai',
                'model'             => 'gpt-4o-mini',
                'temperature'       => 0.70,
                'max_tokens'        => 8000,
                'is_active'         => true,
            ]),
            'providerTypes'         => $this->providerTypes(),
            'modelOptions'          => $this->modelOptions(),
            'defaultBaseUrls'       => $this->defaultBaseUrls(),
            'requiresBaseUrlSlugs'  => $this->requiresBaseUrlSlugs(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        AiProvider::create($data);

        return redirect()->route('admin.ai-providers.index')->with('status', 'AI provider created.');
    }

    public function edit(AiProvider $aiProvider)
    {
        return view('admin.ai-providers.form', [
            'provider'             => $aiProvider,
            'providerTypes'        => $this->providerTypes(),
            'modelOptions'         => $this->modelOptions(),
            'defaultBaseUrls'      => $this->defaultBaseUrls(),
            'requiresBaseUrlSlugs' => $this->requiresBaseUrlSlugs(),
        ]);
    }

    public function update(Request $request, AiProvider $aiProvider)
    {
        $data = $this->validateRequest($request, $aiProvider->id);

        if (! $request->filled('api_key')) {
            unset($data['api_key']);
        }

        $aiProvider->update($data);

        return redirect()->route('admin.ai-providers.index')->with('status', 'AI provider updated.');
    }

    public function destroy(AiProvider $aiProvider)
    {
        $aiProvider->delete();

        return redirect()->route('admin.ai-providers.index')->with('status', 'AI provider deleted.');
    }

    protected function validateRequest(Request $request, ?int $ignoreId = null): array
    {
        $provider     = (string) $request->input('provider');
        $validSlugs   = $this->providerTypes()->pluck('slug')->toArray();
        $models       = $this->modelOptions()[$provider] ?? [];

        $modelRules = ['required', 'string', 'max:255'];
        if (! empty($models)) {
            $modelRules[] = Rule::in($models);
        }

        return $request->validate([
            'provider'     => ['required', Rule::in($validSlugs)],
            'label'        => ['required', 'string', 'max:255'],
            'api_key'      => ['nullable', 'string'],
            'model'        => $modelRules,
            'base_url'     => [
                'nullable', 'string', 'max:500',
                $provider === 'openai_compatible' ? 'required' : 'nullable',
            ],
            'system_prompt' => ['nullable', 'string'],
            'temperature'   => ['required', 'numeric', 'min:0', 'max:2'],
            'max_tokens'    => ['required', 'integer', 'min:1', 'max:200000'],
            'is_active'     => ['nullable', 'boolean'],
        ]);
    }
}
