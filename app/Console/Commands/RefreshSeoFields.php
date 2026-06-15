<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class RefreshSeoFields extends Command
{
    protected $signature = 'blog:refresh-seo
                            {--limit=10 : Max articles to queue per run}
                            {--dry-run  : Preview what would be queued without making changes}';

    protected $description = 'Queue articles missing SEO meta fields for AI regeneration.';

    public function handle(): int
    {
        $query = Article::query()
            ->whereNotNull('ai_generated_at')
            ->whereNull('meta_title')
            ->oldest('published_at');

        $total = $query->count();

        if ($total === 0) {
            $this->info('All published articles already have SEO meta fields. Nothing to do.');
            return self::SUCCESS;
        }

        $limit   = (int) $this->option('limit');
        $dryRun  = $this->option('dry-run');
        $pending = $query->limit($limit)->get();

        $this->line('');
        $this->info("Found {$total} article(s) missing SEO meta. Processing {$pending->count()} now.");
        $this->line('');

        if ($dryRun) {
            foreach ($pending as $article) {
                $this->line("  [DRY] Would queue: [{$article->id}] " . mb_strimwidth($article->displayTitle(), 0, 70, '…'));
            }
            $this->line('');
            $this->warn('Dry run — no changes made. Remove --dry-run to apply.');
            return self::SUCCESS;
        }

        $count = $pending->each(function (Article $article): void {
            $article->forceFill(['ai_generated_at' => null])->save();
            $this->line("  Queued: [{$article->id}] " . mb_strimwidth($article->displayTitle(), 0, 70, '…'));
        })->count();

        $this->line('');
        $this->info("✓ {$count} article(s) queued for re-generation.");
        $this->line('  Run: php artisan blog:auto-publish --skip-scrape --regen-limit=' . $count);
        $this->line('');

        return self::SUCCESS;
    }
}
