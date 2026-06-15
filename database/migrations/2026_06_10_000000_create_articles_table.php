<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('source_name');
            $table->string('source_domain', 190);
            $table->char('source_url_hash', 64)->unique();
            $table->longText('source_url');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author_name')->nullable();
            $table->string('image_url', 2048)->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content_html')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('scraped_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
