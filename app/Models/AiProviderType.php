<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiProviderType extends Model
{
    protected $fillable = [
        'slug', 'name', 'call_type', 'base_url',
        'requires_base_url', 'badge_color', 'models',
        'is_system', 'sort_order',
    ];

    protected $casts = [
        'models'            => 'array',
        'requires_base_url' => 'boolean',
        'is_system'         => 'boolean',
    ];
}
