<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_provider_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->string('name');
            $table->string('call_type', 64);
            $table->string('base_url', 500)->nullable();
            $table->boolean('requires_base_url')->default(false);
            $table->string('badge_color', 64)->default('badge-muted');
            $table->json('models')->nullable();
            $table->boolean('is_system')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();

        DB::table('ai_provider_types')->insert([
            [
                'slug'              => 'openai',
                'name'              => 'OpenAI (GPT)',
                'call_type'         => 'openai',
                'base_url'          => null,
                'requires_base_url' => false,
                'badge_color'       => 'badge-green',
                'models'            => json_encode(['gpt-4o-mini', 'gpt-4o', 'gpt-4.1-mini', 'gpt-4.1', 'o4-mini']),
                'is_system'         => true,
                'sort_order'        => 1,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'slug'              => 'gemini',
                'name'              => 'Gemini (Google)',
                'call_type'         => 'gemini',
                'base_url'          => null,
                'requires_base_url' => false,
                'badge_color'       => 'badge-sky',
                'models'            => json_encode(['gemini-1.5-flash-latest', 'gemini-1.5-pro', 'gemini-2.0-flash', 'gemini-2.5-flash', 'gemini-2.5-pro']),
                'is_system'         => true,
                'sort_order'        => 2,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'slug'              => 'claude',
                'name'              => 'Claude (Anthropic)',
                'call_type'         => 'claude',
                'base_url'          => null,
                'requires_base_url' => false,
                'badge_color'       => 'badge-amber',
                'models'            => json_encode(['claude-sonnet-4-6', 'claude-opus-4-8', 'claude-haiku-4-5-20251001', 'claude-3-5-sonnet-latest', 'claude-3-opus-latest']),
                'is_system'         => true,
                'sort_order'        => 3,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'slug'              => 'deepseek',
                'name'              => 'DeepSeek AI',
                'call_type'         => 'deepseek',
                'base_url'          => null,
                'requires_base_url' => false,
                'badge_color'       => 'badge-indigo',
                'models'            => json_encode(['deepseek-chat', 'deepseek-reasoner']),
                'is_system'         => true,
                'sort_order'        => 4,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'slug'              => 'groq',
                'name'              => 'Groq',
                'call_type'         => 'openai_compatible',
                'base_url'          => 'https://api.groq.com/openai/v1/chat/completions',
                'requires_base_url' => false,
                'badge_color'       => 'badge-orange',
                'models'            => json_encode(['llama-3.3-70b-versatile', 'llama-3.1-70b-versatile', 'llama-3.1-8b-instant', 'gemma2-9b-it', 'mixtral-8x7b-32768', 'llama3-70b-8192']),
                'is_system'         => true,
                'sort_order'        => 5,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'slug'              => 'glm',
                'name'              => 'GLM (Zhipu AI)',
                'call_type'         => 'openai_compatible',
                'base_url'          => 'https://open.bigmodel.cn/api/paas/v4/chat/completions',
                'requires_base_url' => false,
                'badge_color'       => 'badge-purple',
                'models'            => json_encode(['glm-4-flash', 'glm-4-flashx', 'glm-4-air', 'glm-4-airx', 'glm-4-plus', 'glm-4', 'glm-z1-flash', 'glm-z1-air']),
                'is_system'         => true,
                'sort_order'        => 6,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'slug'              => 'openai_compatible',
                'name'              => 'OpenAI Compatible (Custom)',
                'call_type'         => 'openai_compatible',
                'base_url'          => null,
                'requires_base_url' => true,
                'badge_color'       => 'badge-muted',
                'models'            => json_encode([]),
                'is_system'         => true,
                'sort_order'        => 7,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_provider_types');
    }
};
