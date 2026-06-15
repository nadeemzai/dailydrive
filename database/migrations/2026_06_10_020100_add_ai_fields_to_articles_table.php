<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('ai_provider_id')->nullable()->after('news_source_id')->constrained('ai_providers')->nullOnDelete();
            $table->string('ai_provider')->nullable()->after('ai_provider_id');
            $table->string('ai_model')->nullable()->after('ai_provider');
            $table->longText('generated_title')->nullable()->after('ai_model');
            $table->longText('generated_excerpt')->nullable()->after('generated_title');
            $table->longText('generated_content_html')->nullable()->after('generated_excerpt');
            $table->longText('ai_prompt')->nullable()->after('generated_content_html');
            $table->timestamp('ai_generated_at')->nullable()->index()->after('ai_prompt');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ai_provider_id');
            $table->dropColumn([
                'ai_provider',
                'ai_model',
                'generated_title',
                'generated_excerpt',
                'generated_content_html',
                'ai_prompt',
                'ai_generated_at',
            ]);
        });
    }
};
