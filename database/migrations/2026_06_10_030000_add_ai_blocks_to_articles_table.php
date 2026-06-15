<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->longText('generated_faq_json')->nullable()->after('generated_content_html');
            $table->longText('generated_review_json')->nullable()->after('generated_faq_json');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['generated_faq_json', 'generated_review_json']);
        });
    }
};
