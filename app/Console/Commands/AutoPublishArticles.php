<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Services\ContentGenerationService;
use App\Services\NewsScraperService;
use Illuminate\Console\Command;
use Throwable;

class AutoPublishArticles extends Command
{
    protected $signature = 'blog:auto-publish
                            {--scrape-limit=5  : Max articles to scrape per source per run}
                            {--regen-limit=10  : Max pending articles to AI-regenerate per run}
                            {--skip-scrape     : Skip scraping, only regenerate pending articles}';

    protected $description = 'Auto-cycle: scrape new articles → AI regenerate → publish. Run via scheduler.';

    public function handle(NewsScraperService $scraper, ContentGenerationService $generator): int
    {
        $this->line('');
        $this->line('┌─────────────────────────────────────────────┐');
        $this->line('│  DAILYdRIVE — Auto Publish Cycle             │');
        $this->line('└─────────────────────────────────────────────┘');
        $this->line('');

        // ── STEP 1: Scrape new articles ───────────────────────────
        $totalSaved = 0;

        if (! $this->option('skip-scrape')) {
            $this->info('► Step 1 — Scraping new articles...');
            $scrapeLimit = (int) $this->option('scrape-limit');

            foreach ($scraper->getSources() as $source) {
                try {
                    $result = $scraper->scrapeSource($source, $scrapeLimit);
                    $this->line(sprintf(
                        '  %-30s fetched: %d  saved: %d  failed: %d',
                        $result['source'],
                        $result['fetched'],
                        $result['saved'],
                        $result['failed']
                    ));
                    $totalSaved += $result['saved'];
                } catch (Throwable $e) {
                    $this->warn('  Error scraping source: ' . $e->getMessage());
                }
            }

            $this->line("  → Total new articles saved: {$totalSaved}");
            $this->line('');
        } else {
            $this->line('  Scraping skipped (--skip-scrape flag set).');
            $this->line('');
        }

        // ── STEP 2: AI Regenerate pending articles ────────────────
        $this->info('► Step 2 — AI regenerating pending articles...');

        $regenLimit = (int) $this->option('regen-limit');

        $pending = Article::query()
            ->whereNull('ai_generated_at')
            ->whereNotNull('content_html')
            ->whereRaw("LENGTH(TRIM(content_html)) > 100")
            ->oldest('scraped_at')
            ->limit($regenLimit)
            ->get();

        if ($pending->isEmpty()) {
            $this->line('  No pending articles to regenerate.');
            $this->line('');
            $this->info('✓ Cycle complete — nothing to do.');
            return self::SUCCESS;
        }

        $this->line("  Found {$pending->count()} pending article(s) to regenerate.");
        $this->line('');

        $succeeded = 0;
        $failed    = 0;

        foreach ($pending as $i => $article) {
            // Pause between calls to stay under Gemini free-tier rate limit (15 RPM)
            if ($i > 0) {
                sleep(12);
            }

            $this->line("  Processing: [{$article->id}] " . mb_strimwidth($article->title, 0, 70, '…'));

            try {
                $regenerated = $generator->regenerate($article);
                $succeeded++;
                $this->line("  <fg=green>✓</> Published → {$regenerated->slug}");
            } catch (Throwable $e) {
                $failed++;
                $this->warn("  ✗ Failed: " . $e->getMessage());
            }

            $this->line('');
        }

        // ── SUMMARY ───────────────────────────────────────────────
        $this->line('┌─────────────────────────────────────────────┐');
        $this->line("│  Scraped: {$totalSaved}  |  Published: {$succeeded}  |  Failed: {$failed}");
        $this->line('└─────────────────────────────────────────────┘');
        $this->line('');

        return self::SUCCESS;
    }
}
