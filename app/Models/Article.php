<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_source_id',
        'ai_provider_id',
        'ai_provider',
        'ai_model',
        'source_name',
        'source_domain',
        'category',
        'source_url_hash',
        'source_url',
        'title',
        'slug',
        'author_name',
        'image_url',
        'excerpt',
        'content_html',
        'generated_title',
        'generated_excerpt',
        'generated_content_html',
        'generated_faq_json',
        'generated_review_json',
        // SEO fields
        'meta_title',
        'meta_description',
        'meta_keywords',
        // timestamps
        'ai_prompt',
        'ai_generated_at',
        'published_at',
        'scraped_at',
    ];

    protected $casts = [
        'published_at'        => 'datetime',
        'scraped_at'          => 'datetime',
        'ai_generated_at'     => 'datetime',
        'generated_faq_json'  => 'array',
        'generated_review_json' => 'array',
    ];

    // ─────────────────────────────────────────────────────────────────
    // RELATIONSHIPS
    // ─────────────────────────────────────────────────────────────────

    public function source(): BelongsTo
    {
        return $this->belongsTo(NewsSource::class, 'news_source_id');
    }

    public function aiProvider(): BelongsTo
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }

    // ─────────────────────────────────────────────────────────────────
    // DISPLAY HELPERS
    // ─────────────────────────────────────────────────────────────────

    public function displayTitle(): string
    {
        return $this->generated_title ?: $this->title;
    }

    public function displayExcerpt(): ?string
    {
        return $this->generated_excerpt ?: $this->excerpt;
    }

    public function displayContentHtml(): ?string
    {
        return $this->generated_content_html;
    }

    public function faqItems(): array
    {
        return is_array($this->generated_faq_json) ? $this->generated_faq_json : [];
    }

    public function reviewData(): array
    {
        $review = $this->generated_review_json;
        return is_array($review) && ! empty($review) ? $review : [];
    }

    // ─────────────────────────────────────────────────────────────────
    // SEO HELPERS
    // ─────────────────────────────────────────────────────────────────

    public function seoTitle(): string
    {
        return $this->meta_title ?: Str::limit($this->displayTitle(), 60);
    }

    public function seoDescription(): string
    {
        return $this->meta_description ?: Str::limit($this->displayExcerpt() ?? '', 155);
    }

    public function seoKeywords(): string
    {
        return $this->meta_keywords ?: '';
    }

    public function estimatedReadMinutes(): int
    {
        $wordCount = str_word_count(strip_tags($this->displayContentHtml() ?? $this->displayExcerpt() ?? ''));
        return max(1, (int) ceil($wordCount / 200));
    }

    // ─────────────────────────────────────────────────────────────────
    // ROUTING
    // ─────────────────────────────────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
