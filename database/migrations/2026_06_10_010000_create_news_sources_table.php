<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain', 190)->unique();
            $table->longText('home_url');
            $table->longText('feed_url')->nullable();
            $table->longText('sitemap_url')->nullable();
            $table->enum('crawl_mode', ['latest', 'backfill'])->default('latest');
            $table->unsignedInteger('max_articles_per_run')->default(10);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_scraped_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_sources');
    }
};
