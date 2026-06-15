<?php

namespace Database\Seeders;

use App\Models\AiProvider;
use App\Models\NewsSource;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::query()->updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);

        foreach (config('news-scraper.sources', []) as $index => $source) {
            NewsSource::query()->updateOrCreate(
                ['domain' => $source['domain']],
                [
                    'name' => $source['name'],
                    'home_url' => $source['url'],
                    'feed_url' => $source['feed_url'] ?? null,
                    'sitemap_url' => $source['sitemap_url'] ?? null,
                    'crawl_mode' => 'latest',
                    'max_articles_per_run' => 10,
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );
        }

        $providers = [
            [
                'provider' => 'openai',
                'label' => 'OpenAI',
                'model' => 'gpt-4o-mini',
            ],
            [
                'provider' => 'gemini',
                'label' => 'Gemini',
                'model' => 'gemini-2.5-flash',
            ],
            [
                'provider' => 'claude',
                'label' => 'Claude',
                'model' => 'claude-3-5-sonnet-latest',
            ],
        ];

        foreach ($providers as $provider) {
            AiProvider::query()->updateOrCreate(
                ['provider' => $provider['provider']],
                [
                    'label' => $provider['label'],
                    'model' => $provider['model'],
                    'temperature' => 0.70,
                    'max_tokens' => 2500,
                    'is_active' => true,
                ]
            );
        }
    }
}
