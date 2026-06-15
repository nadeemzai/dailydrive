<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'label',
        'api_key',
        'model',
        'system_prompt',
        'temperature',
        'max_tokens',
        'is_active',
        'last_used_at',
    ];

    protected $casts = [
        'api_key' => 'encrypted',
        'system_prompt' => 'encrypted',
        'temperature' => 'decimal:2',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
