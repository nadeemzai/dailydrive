<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AiProviderController extends Controller
{
    public const PROVIDER_TYPES = [
        'openai', 'gemini', 'claude', 'deepseek', 'groq', 'glm', 'openai_compatible',
    ];

    protected array $modelOptions = [
        'openai' => [
            'gpt-4o-mini',
            'gpt-4o',
            'gpt-4.1-mini',
            'gpt-4.1',
            'o4-mini',
        ],
        'gemini' => [
            'gemini-1.5-flash-latest',
            'gemini-1.5-pro',
            'gemini-2.0-flash',
            'gemini-2.5-flash',
            'gemini-2.5-pro',
        ],
        'claude' => [
            'claude-sonnet-4-6',
            'claude-opus-4-8',
            'claude-haiku-4-5-20251001',
            'claude-3-5-sonnet-latest',
            'claude-3-opus-latest',
        ],
        'deepseek' => [
            'deepseek-chat',
            'deepseek-reasoner',
        ],
        'groq' => [
            'llama-3.3-70b-versatile',
            'llama-3.1-70b-versatile',
            'llama-3.1-8b-instant',
            'gemma2-9b-it',
            'mixtral-8x7b-32768',
            'llama3-70b-8192',
        ],
        'glm' => [
            'glm-4-flash',
            'glm-4-flashx',
            'glm-4-air',
            'glm-4-airx',
            'glm-4-plus',
            'glm-4',
            'glm-z1-flash',
            'glm-z1-air',
        ],
        'openai_compatible' => [],
    ];

    protected array $defaultBaseUrls = [
        'groq'             => 'https://api.groq.com/openai/v1/chat/completions',
        'glm'              => 'https://open.bigmodel.cn/api/paas/v4/chat/completions',
        'openai_compatible' => '',
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
            'provider'        => new AiProvider([
                'provider'    => 'openai',
                'model'       => 'gpt-4o-mini',
                'temperature' => 0.70,
                'max_tokens'  => 8000,
                'is_active'   => true,
            ]),
            'modelOptions'    => $this->modelOptions,
            'defaultBaseUrls' => $this->defaultBaseUrls,
            'providerTypes'   => self::PROVIDER_TYPES,
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
            'provider'        => $aiProvider,
            'modelOptions'    => $this->modelOptions,
            'defaultBaseUrls' => $this->defaultBaseUrls,
            'providerTypes'   => self::PROVIDER_TYPES,
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
        $models   = $this->modelOptions[$provider] ?? [];

        $modelRules = ['required', 'string', 'max:255'];
        if (! empty($models)) {
            $modelRules[] = Rule::in($models);
        }

        return $request->validate([
            'provider'     => ['required', Rule::in(self::PROVIDER_TYPES)],
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
