@extends('layouts.public')

@section('title',            $article->displayTitle() . ' — DAILYdRIVE')
@section('seo_title',        $article->seoTitle() . ' — DAILYdRIVE')
@section('meta_description', $article->seoDescription())
@section('meta_keywords',    $article->seoKeywords())
@section('meta_author',      'DAILYdRIVE Editorial')
@section('og_type',          'article')
@if ($article->image_url)@section('og_image', $article->image_url)@endif

@section('content')
<div class="container">
    <div class="detail-wrap">

        {{-- ====================================================
             MAIN ARTICLE
        ==================================================== --}}
        <article class="article-panel">

            {{-- Hero image --}}
            @if ($article->image_url)
                <img class="article-hero"
                     src="{{ $article->image_url }}"
                     alt="{{ $article->displayTitle() }}">
            @endif

            {{-- Header --}}
            <div class="article-hd">

                {{-- Breadcrumb --}}
                <nav class="article-breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span class="article-breadcrumb-sep">&rsaquo;</span>
                    @if ($article->category)
                        <a href="{{ route('home', ['category' => $article->category]) }}">{{ $article->category }}</a>
                    @else
                        <span>Articles</span>
                    @endif
                    <span class="article-breadcrumb-sep">&rsaquo;</span>
                    <span>Article</span>
                </nav>

                @if ($article->category)
                    <span class="tag tag-source">{{ $article->category }}</span>
                @endif

                <h1 class="article-title">{{ $article->displayTitle() }}</h1>

                <div class="article-meta">
                    <div class="article-meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ optional($article->published_at)->format('M d, Y') ?? optional($article->scraped_at)->format('M d, Y') }}
                    </div>

                    <span class="article-meta-sep">&middot;</span>
                    <div class="article-meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        DAILYdRIVE Editorial
                    </div>

                    <span class="article-meta-sep">&middot;</span>
                    <div class="article-meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        {{ $article->estimatedReadMinutes() }} min read
                    </div>


                </div>

            </div>

            {{-- Body --}}
            <div class="article-bd">

                <div class="article-body">
                    {!! $article->displayContentHtml() !!}
                </div>

                {{-- ---- SHARE BAR ---- --}}
                <div class="share-bar">
                    <span class="share-label">Share</span>
                    <a class="share-btn twitter"
                       href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->displayTitle()) }}"
                       target="_blank" rel="noreferrer">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.259 5.631 5.905-5.631zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        Twitter
                    </a>
                    <a class="share-btn linkedin"
                       href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                       target="_blank" rel="noreferrer">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                        LinkedIn
                    </a>
                    <button class="share-btn copy-link">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        Copy Link
                    </button>
                </div>

                {{-- ---- REVIEW BLOCK ---- --}}
                @if ($article->reviewData())
                    @php($review = $article->reviewData())
                    <div class="review-block">
                        <div class="review-block-hd">
                            <div class="review-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                Review Summary
                            </div>
                            @if (!empty($review['rating']))
                                <span class="rating-badge">
                                    &#9733; {{ number_format((float) $review['rating'], 1) }} / 5.0
                                </span>
                            @endif
                        </div>

                        <div class="review-block-bd">
                            @if (!empty($review['summary']))
                                <p class="review-summary-text">{{ $review['summary'] }}</p>
                            @endif

                            @if (!empty($review['pros']) || !empty($review['cons']))
                                <div class="pros-cons-grid">
                                    @if (!empty($review['pros']))
                                        <div class="pros">
                                            <div class="pc-col-title">Pros</div>
                                            <ul class="pc-list">
                                                @foreach ($review['pros'] as $item)
                                                    <li>
                                                        <span class="pc-icon">&#43;</span>
                                                        <span>{{ $item }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if (!empty($review['cons']))
                                        <div class="cons">
                                            <div class="pc-col-title">Cons</div>
                                            <ul class="pc-list">
                                                @foreach ($review['cons'] as $item)
                                                    <li>
                                                        <span class="pc-icon">&#8722;</span>
                                                        <span>{{ $item }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if (!empty($review['verdict']))
                                <div class="verdict-box">
                                    <strong>Verdict:</strong> {{ $review['verdict'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ---- FAQ BLOCK ---- --}}
                @if ($article->faqItems())
                    <div class="faq-block">
                        <div class="faq-block-hd">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--brand);"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            <span class="faq-hd-label">FAQ</span>
                            <span class="faq-hd-title">Frequently Asked Questions</span>
                        </div>
                        <div class="faq-list">
                            @foreach ($article->faqItems() as $faq)
                                <div class="faq-item">
                                    <div class="faq-q">
                                        <span>{{ $faq['question'] ?? '' }}</span>
                                        <span class="faq-toggle">+</span>
                                    </div>
                                    <div class="faq-a">
                                        <div class="faq-a-inner">{{ $faq['answer'] ?? '' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </article>

        {{-- ====================================================
             SIDEBAR
        ==================================================== --}}
        <aside class="sidebar-sticky">

            {{-- Article info --}}
            <div class="scard">
                <div class="scard-hd">Article Info</div>
                <div class="scard-bd">
                    @if ($article->category)
                    <div class="info-row">
                        <span class="info-label">Category</span>
                        <span class="info-val brand">{{ $article->category }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">Published</span>
                        <span class="info-val">{{ optional($article->published_at)->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Author</span>
                        <span class="info-val">DAILYdRIVE Editorial</span>
                    </div>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="scard">
                <div class="scard-hd">Quick Links</div>
                <div class="scard-bd">
                    <a href="{{ route('home') }}" class="action-link">
                        <span>&#8592; All Articles</span>
                        <span class="action-link-arr">&#8250;</span>
                    </a>
                    @if ($article->category)
                    <a href="{{ route('home', ['category' => $article->category]) }}" class="action-link">
                        <span>More {{ $article->category }}</span>
                        <span class="action-link-arr">&#8250;</span>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Related articles --}}
            @if ($related->isNotEmpty())
                <div class="scard">
                    <div class="scard-hd">More in {{ $article->category ?? 'Related' }}</div>
                    <div class="scard-bd">
                        @foreach ($related as $item)
                            <a class="related-item" href="{{ route('articles.show', $item) }}">
                                @if ($item->image_url)
                                    <img class="related-thumb"
                                         src="{{ $item->image_url }}"
                                         alt="{{ $item->displayTitle() }}"
                                         loading="lazy">
                                @else
                                    <div class="related-thumb-ph">&#128240;</div>
                                @endif
                                <div class="related-info">
                                    <div class="related-title">{{ $item->displayTitle() }}</div>
                                    <div class="related-date">
                                        {{ optional($item->published_at)->format('M d, Y') ?? optional($item->scraped_at)->format('M d, Y') }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </aside>

    </div>
</div>
@endsection
