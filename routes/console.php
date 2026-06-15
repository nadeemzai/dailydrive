<?php

use Illuminate\Support\Facades\Schedule;

/*
|──────────────────────────────────────────────────────────────────────────────
| DAILYdRIVE — Automated Pipeline
|
| Every 10 minutes:
|   1. Scrape up to 5 new articles per source
|   2. AI-regenerate up to 10 pending articles
|   3. Auto-publish (ai_generated_at gets stamped → article goes live)
|
| withoutOverlapping(5) — if a run takes > 5 min, the next is skipped
|──────────────────────────────────────────────────────────────────────────────
*/

Schedule::command('blog:auto-publish --scrape-limit=20 --regen-limit=10')
    ->everyTenMinutes()
    ->withoutOverlapping(5)
    ->runInBackground();

// Schedule::command('news:scrape --limit=30 --backfill')
//     ->dailyAt('03:00')
//     ->withoutOverlapping(30)
//     ->runInBackground();
