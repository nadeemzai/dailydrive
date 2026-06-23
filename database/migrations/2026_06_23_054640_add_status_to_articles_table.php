<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->after('category')->index();
        });

        // Mark already-expired articles inactive (published more than 7 days ago)
        DB::table('articles')
            ->whereNotNull('published_at')
            ->where('published_at', '<', now()->subDays(7))
            ->update(['status' => 'inactive']);
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
