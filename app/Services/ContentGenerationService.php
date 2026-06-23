<?php

namespace App\Services;

use App\Models\AiProvider;
use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ContentGenerationService
{
    // Minimum tokens required for full article + FAQ + review + SEO
    protected const MIN_TOKENS = 8000;

    public function regenerate(Article $article, ?AiProvider $provider = null): Article
    {
        $providers = $this->resolveProviders($provider);

        if ($providers->isEmpty()) {
            throw new RuntimeException('No active AI provider is configured.');
        }

        $lastException = null;

        foreach ($providers as $attemptProvider) {
            if (! $attemptProvider->api_key) {
                Log::warning('AI provider skipped — no API key.', [
                    'provider' => $attemptProvider->provider,
                    'label'    => $attemptProvider->label,
                ]);
                continue;
            }

            try {
                $prompt    = $this->buildPrompt($article, $attemptProvider);
                $payload   = $this->callProvider($attemptProvider, $prompt);
                $generated = $this->normalizeGeneratedPayload($payload);

                $newTitle = $generated['title'] ?: $article->title;
                $newSlug  = $this->uniqueSlug($newTitle, $article->id);

                $imageUrl = $this->fetchPexelsImage(
                    ($generated['category'] ?: $article->category) . ' ' . $newTitle
                ) ?? $article->image_url;

                $article->forceFill([
                    'ai_provider_id' => $attemptProvider->id,
                    'ai_provider'    => $attemptProvider->provider,
                    'ai_model'       => $attemptProvider->model,

                    'title'       => $newTitle,
                    'slug'        => $newSlug,
                    'author_name' => 'Farhan',

                    'excerpt'      => $generated['excerpt'] ?: $article->excerpt,
                    'content_html' => $generated['content_html'] ?: $this->fallbackHtml($generated['excerpt'] ?: $article->excerpt),
                    'category'     => $generated['category'] ?: $article->category,
                    'image_url'    => $imageUrl,

                    'published_at' => now(),

                    'generated_title'        => $newTitle,
                    'generated_excerpt'      => $generated['excerpt'] ?: $article->excerpt,
                    'generated_content_html' => $generated['content_html'] ?: '',
                    'generated_faq_json'     => $generated['faq_items'],
                    'generated_review_json'  => $generated['review'],

                    'meta_title'       => $generated['meta_title'] ?: Str::limit($newTitle, 60),
                    'meta_description' => $generated['meta_description'] ?: Str::limit($generated['excerpt'] ?: '', 155),
                    'meta_keywords'    => $generated['meta_keywords'] ?: '',

                    'ai_prompt'        => $prompt,
                    'ai_generated_at'  => now(),
                ])->save();

                $attemptProvider->forceFill(['last_used_at' => now()])->save();

                return $article->refresh();

            } catch (RuntimeException $e) {
                $lastException = $e;

                Log::warning('AI provider failed — trying next.', [
                    'provider' => $attemptProvider->provider,
                    'label'    => $attemptProvider->label,
                    'error'    => $e->getMessage(),
                ]);

                continue;
            }
        }

        throw new RuntimeException(
            'All AI providers failed. Last error: ' . ($lastException?->getMessage() ?? 'No provider available.')
        );
    }

    public function activeProviders()
    {
        return AiProvider::query()->where('is_active', true)->orderBy('label')->get();
    }

    protected function defaultProvider(): ?AiProvider
    {
        return $this->activeProviders()->first();
    }

    protected function resolveProviders(?AiProvider $provider = null)
    {
        $providers = $this->activeProviders();

        if (! $provider) {
            return $providers;
        }

        return $providers
            ->sortBy(fn(AiProvider $p): int => $p->id === $provider->id ? 0 : 1)
            ->values();
    }

    // ─────────────────────────────────────────────────────────────────
    // PROMPT BUILDER
    // ─────────────────────────────────────────────────────────────────

    protected function buildPrompt(Article $article, AiProvider $provider): string
    {
        $sourceText   = Str::limit(trim(strip_tags($article->content_html ?: '')), 3000);
        $excerpt      = trim((string) ($article->excerpt ?: ''));
        $systemPrompt = trim((string) $provider->system_prompt);
        $systemPrompt = $systemPrompt !== ''
            ? $systemPrompt
            : 'You are an expert SEO content writer and news editor who rewrites articles into high-quality, original, comprehensive blog posts.';

        $categoryList = implode(', ', [
            'Technology', 'Artificial Intelligence', 'Business', 'Security',
            'Science', 'Environment', 'Health', 'Gaming', 'Policy', 'Other',
        ]);

        $exampleJson = json_encode([
            'title'            => 'SEO-optimized headline for the article (max 60 characters)',
            'slug'             => 'seo-friendly-url-slug',
            'author'           => 'Farhan',
            'category'         => 'Technology',
            'meta_title'       => 'SEO meta title — same as or close to title (max 60 chars)',
            'meta_description' => 'Compelling SEO meta description summarizing the article (max 155 chars)',
            'meta_keywords'    => 'keyword1, keyword2, keyword3, keyword4, keyword5, keyword6',
            'excerpt'          => 'Engaging 2–3 sentence summary that hooks the reader.',
            'content_html'     => '<h2>Section Heading</h2><p>Detailed paragraph...</p><h3>Sub-heading</h3><p>More content...</p><ul><li>Key point one</li><li>Key point two</li></ul><p>Closing paragraph...</p>',
            'faq_items'        => [
                ['question' => 'What is the main topic of this article?', 'answer' => 'Explain in 2-3 sentences.'],
                ['question' => 'Why does this matter to readers?', 'answer' => 'Explain in 2-3 sentences.'],
                ['question' => 'What are the key takeaways?', 'answer' => 'Explain in 2-3 sentences.'],
                ['question' => 'How does this affect the industry?', 'answer' => 'Explain in 2-3 sentences.'],
                ['question' => 'What should readers do next?', 'answer' => 'Explain in 2-3 sentences.'],
            ],
            'review'           => [
                'summary' => '2-3 sentence overview of the subject being reviewed.',
                'rating'  => 4.2,
                'pros'    => ['Strength or positive point one', 'Strength or positive point two', 'Strength or positive point three', 'Strength or positive point four'],
                'cons'    => ['Weakness or limitation one', 'Weakness or limitation two', 'Weakness or limitation three'],
                'verdict' => 'One clear concluding verdict sentence.',
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return implode("\n\n", [
            $systemPrompt,

            '=== YOUR TASK ===',
            'Completely rewrite the source article below into a new, original, SEO-optimized blog post.',
            'Return ONLY a valid JSON object — no markdown fences, no extra text before or after.',

            '=== MANDATORY RULES ===',
            '1. Rewrite everything in fresh, unique language. Never copy source sentences.',
            '2. "author" MUST always be "Farhan".',
            '3. "category" MUST be exactly one of: ' . $categoryList . '. Pick the best fit.',
            '4. "content_html" MUST be rich HTML using <h2>, <h3>, <p>, <ul>, <li>, <strong> tags. Minimum 600 words.',
            '5. "faq_items" MUST be an array of EXACTLY 5 objects, each with "question" and "answer" keys. Both must be non-empty strings.',
            '6. "review" MUST be a complete object with: summary (string), rating (float 1–5), pros (array of 4 strings), cons (array of 3 strings), verdict (string). ALL fields required.',
            '7. "meta_title" max 60 characters. "meta_description" max 155 characters. "meta_keywords" must be 5–8 comma-separated keywords.',
            '8. Return ONLY the JSON object. No ```json wrapper. No extra explanation.',

            '=== EXACT JSON STRUCTURE TO FOLLOW ===',
            $exampleJson,

            '=== SOURCE ARTICLE ===',
            'Title: ' . $article->title,
            'Excerpt: ' . $excerpt,
            'Body: ' . $sourceText,
            'Source URL: ' . $article->source_url,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    // PROVIDER CALLS
    // ─────────────────────────────────────────────────────────────────

    protected function callProvider(AiProvider $provider, string $prompt): array
    {
        return match ($provider->provider) {
            'openai'    => $this->callOpenAi($provider, $prompt),
            'gemini'    => $this->callGemini($provider, $prompt),
            'claude'    => $this->callClaude($provider, $prompt),
            'deepseek'  => $this->callDeepSeek($provider, $prompt),
            default     => throw new RuntimeException("Unsupported AI provider [{$provider->provider}]."),
        };
    }

    protected function callOpenAi(AiProvider $provider, string $prompt): array
    {
        $response = Http::withToken($provider->api_key)
            ->acceptJson()
            ->timeout(120)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model'           => $provider->model ?: 'gpt-4o-mini',
                'temperature'     => (float) $provider->temperature,
                'max_tokens'      => max(self::MIN_TOKENS, (int) $provider->max_tokens),
                'response_format' => ['type' => 'json_object'],
                'messages'        => [
                    ['role' => 'system', 'content' => 'You are an expert SEO content writer. Always respond with valid JSON only.'],
                    ['role' => 'user',   'content' => $prompt],
                ],
            ]);

        if (! $response->successful()) {
            $this->logProviderFailure($provider, $response->status(), $response->body());
            throw new RuntimeException("OpenAI request failed [{$response->status()}].");
        }

        $content = data_get($response->json(), 'choices.0.message.content', '');

        if (empty($content)) {
            throw new RuntimeException('OpenAI response contained no content.');
        }

        return $this->decodeJsonPayload((string) $content, $provider);
    }

    protected function callGemini(AiProvider $provider, string $prompt): array
    {
        $model    = $provider->model ?: 'gemini-2.5-flash';
        $response = $this->attemptGeminiRequest($provider, $prompt, $model);

        if (! $response->successful()) {
            $this->logProviderFailure($provider, $response->status(), $response->body());
            throw new RuntimeException("Gemini request failed [{$response->status()}].");
        }

        $content = $this->extractGeminiTextContent($response->json());

        if (empty($content)) {
            throw new RuntimeException('Gemini response contained no text content.');
        }

        return $this->decodeJsonPayload((string) $content, $provider);
    }

    protected function attemptGeminiRequest(AiProvider $provider, string $prompt, string $model)
    {
        $errorsToRetry = [429, 503, 504];
        $maxAttempts   = 2;
        $response      = null;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                $response = Http::acceptJson()
                    ->timeout(120)
                    ->post(
                        'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $provider->api_key,
                        [
                            'contents' => [
                                [
                                    'role'  => 'user',
                                    'parts' => [['text' => $prompt]],
                                ],
                            ],
                            'generationConfig' => [
                                'temperature'     => (float) $provider->temperature,
                                'maxOutputTokens' => max(self::MIN_TOKENS, (int) $provider->max_tokens),
                                'responseMimeType' => 'application/json',
                            ],
                        ]
                    );
            } catch (Throwable $e) {
                Log::warning('Gemini network error.', [
                    'provider' => $provider->provider,
                    'attempt'  => $attempt,
                    'error'    => $e->getMessage(),
                ]);

                if ($attempt >= $maxAttempts) {
                    throw new RuntimeException('Gemini network error. Check logs.');
                }

                sleep(2);
                continue;
            }

            if ($response->successful() || ! in_array($response->status(), $errorsToRetry, true)) {
                return $response;
            }

            $this->logProviderFailure($provider, $response->status(), $response->body());

            if ($attempt < $maxAttempts) {
                sleep(2);
            }
        }

        return $response;
    }

    protected function extractGeminiTextContent(array $response): string
    {
        return (string) (
            data_get($response, 'candidates.0.content.parts.0.text') ??
            data_get($response, 'candidates.0.content.0.text') ??
            data_get($response, 'candidates.0.output.0.content.0.text') ??
            data_get($response, 'candidates.0.output.0.text') ??
            ''
        );
    }

    protected function callClaude(AiProvider $provider, string $prompt): array
    {
        $response = Http::withHeaders([
            'x-api-key'         => $provider->api_key,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
            'accept'            => 'application/json',
        ])
            ->timeout(120)
            ->post('https://api.anthropic.com/v1/messages', [
                'model'      => $provider->model ?: 'claude-3-5-sonnet-latest',
                'max_tokens' => max(self::MIN_TOKENS, (int) $provider->max_tokens),
                'temperature' => (float) $provider->temperature,
                'messages'   => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if (! $response->successful()) {
            $this->logProviderFailure($provider, $response->status(), $response->body());
            throw new RuntimeException("Claude request failed [{$response->status()}].");
        }

        $content = data_get($response->json(), 'content.0.text', '');

        if (empty($content)) {
            throw new RuntimeException('Claude response contained no text content.');
        }

        return $this->decodeJsonPayload((string) $content, $provider);
    }

    protected function callDeepSeek(AiProvider $provider, string $prompt): array
    {
        $response = Http::withToken($provider->api_key)
            ->acceptJson()
            ->timeout(120)
            ->post('https://api.deepseek.com/v1/chat/completions', [
                'model'       => $provider->model ?: 'deepseek-chat',
                'temperature' => (float) $provider->temperature,
                'max_tokens'  => max(self::MIN_TOKENS, (int) $provider->max_tokens),
                'messages'    => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if (! $response->successful()) {
            $this->logProviderFailure($provider, $response->status(), $response->body());
            throw new RuntimeException("DeepSeek request failed [{$response->status()}].");
        }

        $content = data_get($response->json(), 'choices.0.message.content', '');

        if (empty($content)) {
            throw new RuntimeException('DeepSeek response contained no content.');
        }

        return $this->decodeJsonPayload((string) $content, $provider);
    }

    // ─────────────────────────────────────────────────────────────────
    // JSON DECODING + NORMALIZATION
    // ─────────────────────────────────────────────────────────────────

    protected function decodeJsonPayload(string $payload, ?AiProvider $provider = null): array
    {
        $payload = trim($payload);

        if (empty($payload)) {
            throw new RuntimeException('AI response was empty.');
        }

        // Strip markdown fences if any
        $payload = preg_replace('/^```(?:json)?\s*/i', '', $payload);
        $payload = preg_replace('/\s*```$/', '', $payload);
        $payload = trim($payload);

        $jsonText = $this->extractJsonObject($payload);
        $data     = json_decode($jsonText, true);

        if (! is_array($data)) {
            $jsonText = $this->repairJsonPayload($jsonText);
            $data     = json_decode($jsonText, true);
        }

        if (! is_array($data)) {
            $jsonText = $this->repairJsonPayload($jsonText, true);
            $data     = json_decode($jsonText, true);
        }

        if (! is_array($data)) {
            Log::error('AI response was not valid JSON.', [
                'provider'   => $provider?->provider,
                'label'      => $provider?->label,
                'preview'    => substr($jsonText, 0, 800),
                'json_error' => json_last_error_msg(),
            ]);
            throw new RuntimeException('AI response was not valid JSON.');
        }

        $validCategories = [
            'Technology', 'Artificial Intelligence', 'Business', 'Security',
            'Science', 'Environment', 'Health', 'Gaming', 'Policy', 'Other',
        ];
        $rawCategory = trim((string) ($data['category'] ?? ''));
        $category = in_array($rawCategory, $validCategories, true) ? $rawCategory : null;

        return [
            'title'            => trim((string) ($data['title'] ?? '')),
            'excerpt'          => trim((string) ($data['excerpt'] ?? '')),
            'content_html'     => trim((string) ($data['content_html'] ?? '')),
            'category'         => $category,
            'meta_title'       => Str::limit(trim((string) ($data['meta_title'] ?? '')), 60),
            'meta_description' => Str::limit(trim((string) ($data['meta_description'] ?? '')), 155),
            'meta_keywords'    => trim((string) ($data['meta_keywords'] ?? '')),
            'faq_items'        => $this->normalizeFaqItems($data['faq_items'] ?? []),
            'review'           => $this->normalizeReview($data['review'] ?? []),
        ];
    }

    protected function normalizeGeneratedPayload(array $generated): array
    {
        return [
            'title'            => $generated['title'] ?? '',
            'excerpt'          => $generated['excerpt'] ?? '',
            'content_html'     => $generated['content_html'] ?? '',
            'category'         => $generated['category'] ?? null,
            'meta_title'       => $generated['meta_title'] ?? '',
            'meta_description' => $generated['meta_description'] ?? '',
            'meta_keywords'    => $generated['meta_keywords'] ?? '',
            'faq_items'        => $generated['faq_items'] ?? [],
            'review'           => $generated['review'] ?? [],
        ];
    }

    protected function normalizeFaqItems(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->filter(fn($item) => is_array($item))
            ->map(fn(array $item): array => [
                'question' => trim((string) ($item['question'] ?? '')),
                'answer'   => trim((string) ($item['answer'] ?? '')),
            ])
            ->filter(fn(array $item): bool => $item['question'] !== '' && $item['answer'] !== '')
            ->values()
            ->all();
    }

    protected function normalizeReview(mixed $review): array
    {
        if (! is_array($review) || empty($review)) {
            return [];
        }

        $pros = array_values(array_filter(array_map('trim', (array) ($review['pros'] ?? []))));
        $cons = array_values(array_filter(array_map('trim', (array) ($review['cons'] ?? []))));

        return [
            'summary' => trim((string) ($review['summary'] ?? '')),
            'rating'  => (float) ($review['rating'] ?? 0),
            'pros'    => $pros,
            'cons'    => $cons,
            'verdict' => trim((string) ($review['verdict'] ?? '')),
        ];
    }

    // ─────────────────────────────────────────────────────────────────
    // JSON REPAIR HELPERS
    // ─────────────────────────────────────────────────────────────────

    protected function extractJsonObject(string $payload): string
    {
        $start = strpos($payload, '{');
        if ($start === false) return $payload;

        $depth    = 0;
        $inString = false;
        $escaped  = false;
        $length   = strlen($payload);

        for ($i = $start; $i < $length; $i++) {
            $char = $payload[$i];

            if ($escaped) { $escaped = false; continue; }
            if ($char === '\\') { $escaped = true; continue; }
            if ($char === '"') { $inString = ! $inString; continue; }
            if ($inString) continue;

            if ($char === '{') { $depth++; continue; }
            if ($char === '}') {
                $depth--;
                if ($depth === 0) return substr($payload, $start, $i - $start + 1);
            }
        }

        return $payload;
    }

    protected function escapeJsonStringNewlines(string $payload): string
    {
        $result   = '';
        $inString = false;
        $escaped  = false;
        $length   = strlen($payload);

        for ($i = 0; $i < $length; $i++) {
            $char = $payload[$i];

            if ($escaped) { $result .= $char; $escaped = false; continue; }
            if ($char === '\\') { $result .= $char; $escaped = true; continue; }
            if ($char === '"') { $result .= $char; $inString = ! $inString; continue; }

            if ($inString) {
                if ($char === "\n") { $result .= '\\n'; continue; }
                if ($char === "\r") { $result .= '\\r'; continue; }
            }

            $result .= $char;
        }

        return $result;
    }

    protected function removeTrailingCommas(string $payload): string
    {
        return preg_replace('#,\s*([}\]])#', '$1', $payload);
    }

    protected function repairJsonPayload(string $payload, bool $forceBalance = false): string
    {
        if ($forceBalance) {
            $payload = $this->truncateAfterLastBrace($payload);
            $payload = $this->closeJsonStructure($payload);
        }

        $payload = $this->escapeJsonStringNewlines($payload);
        $payload = $this->removeTrailingCommas($payload);
        $payload = $this->closeJsonStructure($payload);

        return $payload;
    }

    protected function truncateAfterLastBrace(string $payload): string
    {
        $lastBrace = strrpos($payload, '}');
        return $lastBrace === false ? $payload : substr($payload, 0, $lastBrace + 1);
    }

    protected function closeJsonStructure(string $payload): string
    {
        $quoteCount = preg_match_all('/(?<!\\\\)"/', $payload);
        if ($quoteCount !== false && $quoteCount % 2 === 1) {
            $payload .= '"';
        }

        $missing = substr_count($payload, '{') - substr_count($payload, '}');
        if ($missing > 0) {
            $payload .= str_repeat('}', $missing);
        }

        return $payload;
    }

    // ─────────────────────────────────────────────────────────────────
    // UTILITIES
    // ─────────────────────────────────────────────────────────────────

    protected function fetchPexelsImage(string $query): ?string
    {
        $apiKey = config('services.pexels.key');

        if (empty($apiKey)) {
            return null;
        }

        // Keep query concise — first 8 words is enough for Pexels search
        $query = implode(' ', array_slice(preg_split('/\s+/', trim($query)), 0, 8));

        try {
            $response = Http::withHeaders(['Authorization' => $apiKey])
                ->timeout(10)
                ->get('https://api.pexels.com/v1/search', [
                    'query'       => $query,
                    'per_page'    => 5,
                    'orientation' => 'landscape',
                    'size'        => 'medium',
                ]);

            if (! $response->successful()) {
                Log::warning('Pexels API error.', ['status' => $response->status(), 'query' => $query]);
                return null;
            }

            $photos = $response->json('photos', []);

            if (empty($photos)) {
                return null;
            }

            // Pick randomly from top 5 so repeated topics don't always get the same photo
            $photo = $photos[array_rand($photos)];

            return data_get($photo, 'src.large2x')
                ?? data_get($photo, 'src.large')
                ?? data_get($photo, 'src.original');

        } catch (Throwable $e) {
            Log::warning('Pexels image fetch failed.', ['error' => $e->getMessage(), 'query' => $query]);
            return null;
        }
    }

    protected function uniqueSlug(string $title, int $articleId): string
    {
        $base  = Str::slug($title) ?: 'article';
        $slug  = $base;
        $count = 1;

        while (
            Article::where('slug', $slug)
                ->where('id', '!=', $articleId)
                ->exists()
        ) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }

    protected function fallbackHtml(?string $excerpt): string
    {
        return '<p>' . e($excerpt ?: 'Content generated successfully.') . '</p>';
    }

    protected function logProviderFailure(AiProvider $provider, int $status, string $body): void
    {
        Log::warning('AI provider request failed.', [
            'provider' => $provider->provider,
            'label'    => $provider->label,
            'model'    => $provider->model,
            'status'   => $status,
            'body'     => Str::limit($body, 2000),
        ]);
    }
}
