<?php

namespace App\Console\Commands;

use App\Services\NewsScraperService;
use Illuminate\Console\Command;

class SyncNewsArticles extends Command
{
    protected $signature = 'news:scrape {--limit=10 : Maximum articles to inspect per source} {--backfill : Use backfill crawl mode and sitemap discovery}';

    protected $description = 'Scrape the configured news sources and store new articles in the database.';

    public function handle(NewsScraperService $scraper): int
    {
        $limit = (int) $this->option('limit');
        $effectiveLimit = $this->option('backfill') ? max(20, $limit) : max(1, $limit);
        $results = [];

        foreach ($scraper->getSources() as $source) {
            if ($this->option('backfill') && is_array($source)) {
                $source['crawl_mode'] = 'backfill';
                $source['max_articles_per_run'] = max($effectiveLimit, (int) ($source['max_articles_per_run'] ?? $effectiveLimit));
            }

            $results[] = $scraper->scrapeSource($source, $effectiveLimit);
        }

        $totalFetched = 0;
        $totalSaved = 0;
        $totalFailed = 0;

        foreach ($results as $result) {
            $this->line(sprintf(
                '%s: fetched %d, saved %d, failed %d',
                $result['source'],
                $result['fetched'],
                $result['saved'],
                $result['failed']
            ));

            $totalFetched += $result['fetched'];
            $totalSaved += $result['saved'];
            $totalFailed += $result['failed'];
        }

        $this->info(sprintf(
            'Done. fetched %d, saved %d, failed %d.',
            $totalFetched,
            $totalSaved,
            $totalFailed
        ));

        return self::SUCCESS;
    }
}
