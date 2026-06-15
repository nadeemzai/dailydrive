<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AiProviderController extends Controller
{
    protected array $modelOptions = [
        'openai' => [
            'gpt-4o-mini',
            'gpt-4o',
            'gpt-4.1-mini',
        ],
        'gemini' => [
            'gemini-1.5-flash-latest',
            'gemini-1.5-pro',
            'gemini-2.0-flash',
            'gemini-2.5-flash',
            'gemini-2.5-pro',
            'gemini-2.5-pro-tts',
            'gemini-3-flash',
            'gemini-3-flash-live',
        ],
        'claude' => [
            'claude-3-5-sonnet-latest',
            'claude-3-opus-latest',
            'claude-3-haiku-latest',
        ],
        'deepseek' => [
            'deepseek-chat',
            'deepseek-reasoner',
        ],
    ];

    public function index()
    {
        return view('admin.ai-providers.index', [
            'providers' => AiProvider::query()->orderBy('label')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.ai-providers.form', [
            'provider' => new AiProvider([
                'provider' => 'openai',
                'model' => 'gpt-4o-mini',
                'temperature' => 0.70,
                'max_tokens' => 2500,
                'is_active' => true,
            ]),
            'modelOptions' => $this->modelOptions,
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
            'provider' => $aiProvider,
            'modelOptions' => $this->modelOptions,
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
        $provider = (string) $request->input('provider');
        $models = $this->modelOptions[$provider] ?? [];

        return $request->validate([
            'provider' => [
                'required',
                Rule::in(['openai', 'gemini', 'claude', 'deepseek']),
                Rule::unique('ai_providers', 'provider')->ignore($ignoreId),
            ],
            'label' => ['required', 'string', 'max:255'],
            'api_key' => ['nullable', 'string'],
            'model' => [
                'required',
                'string',
                Rule::in($models),
            ],
            'system_prompt' => ['nullable', 'string'],
            'temperature' => ['required', 'numeric', 'min:0', 'max:2'],
            'max_tokens' => ['required', 'integer', 'min:1', 'max:20000'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
