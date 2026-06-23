<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class ExpireOldArticles extends Command
{
    protected $signature   = 'articles:expire';
    protected $description = 'Mark articles inactive when their publish date is older than 7 days';

    public function handle(): int
    {
        $count = Article::query()
            ->where('status', 'active')
            ->whereNotNull('published_at')
            ->where('published_at', '<', now()->subDays(7))
            ->update(['status' => 'inactive']);

        $this->info("Marked {$count} article(s) as inactive.");

        return self::SUCCESS;
    }
}
