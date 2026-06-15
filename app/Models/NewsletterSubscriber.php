<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = ['email', 'name', 'subscribed_at', 'unsubscribed_at', 'token'];

    protected $casts = [
        'subscribed_at'   => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    public function isActive(): bool
    {
        return $this->subscribed_at !== null && $this->unsubscribed_at === null;
    }
}
