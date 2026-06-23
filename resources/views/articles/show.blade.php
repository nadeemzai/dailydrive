@extends('layouts.public')

@section('title', $article->displayTitle() . ' — DAILYdRIVE')
@section('seo_title', $article->seoTitle() . ' — DAILYdRIVE')
@section('meta_description', $article->seoDescription())
@section('meta_keywords', $article->seoKeywords())
@section('meta_author', 'DAILYdRIVE Editorial')
@section('og_type', 'article')
@if ($article->image_url)
    @section('og_image', $article->image_url)
@endif

@section('content')
    <style>
        /* ── Like button ── */
        .like-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            border-radius: 999px;
            border: 1.5px solid var(--border);
            background: var(--bg-card);
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--muted);
            cursor: pointer;
            transition: all 0.15s;
            font-family: inherit;
        }

        .like-btn:hover {
            border-color: #e53e3e;
            color: #e53e3e;
            background: #fff5f5;
        }

        .like-btn.liked {
            border-color: #e53e3e;
            color: #e53e3e;
            background: #fff5f5;
        }

        .like-btn.liked svg {
            fill: #e53e3e;
            stroke: #e53e3e;
        }

        .like-btn svg {
            transition: transform 0.15s;
        }

        .like-btn:active svg {
            transform: scale(1.3);
        }

        /* ── Bookmark btn (article page) ── */
        .art-bookmark-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 999px;
            border: 1.5px solid var(--border);
            background: var(--bg-card);
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--muted);
            cursor: pointer;
            transition: all 0.15s;
            font-family: inherit;
        }

        .art-bookmark-btn:hover {
            border-color: var(--brand);
            color: var(--brand);
            background: var(--brand-bg);
        }

        .art-bookmark-btn.bookmarked {
            border-color: var(--brand);
            color: var(--brand);
            background: var(--brand-bg);
        }

        .art-bookmark-btn.bookmarked svg {
            fill: var(--brand);
        }

        /* ── Engagement bar ── */
        .eng-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            margin: 28px 0;
            flex-wrap: wrap;
        }

        .eng-stat {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.82rem;
            color: var(--muted);
            font-weight: 500;
            padding: 0 4px;
        }

        .eng-divider {
            width: 1px;
            height: 18px;
            background: var(--border);
            flex-shrink: 0;
        }

        .eng-spacer {
            flex: 1;
        }

        /* ── Bottom related articles ── */
        .related-bottom {
            margin-top: 48px;
            padding-top: 32px;
            border-top: 2px solid var(--border);
        }

        .related-bottom-hd {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.02em;
        }

        .related-bottom-hd::before {
            content: '';
            display: block;
            width: 4px;
            height: 20px;
            border-radius: 2px;
            background: var(--brand);
            flex-shrink: 0;
        }

        .related-bottom-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .rb-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            overflow: hidden;
            transition: box-shadow 0.15s, transform 0.15s;
            text-decoration: none;
            display: block;
        }

        .rb-card:hover {
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }

        .rb-card-img {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            display: block;
            background: var(--bg-hover);
        }

        .rb-card-img-ph {
            width: 100%;
            aspect-ratio: 16/9;
            background: var(--bg-hover);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            opacity: 0.4;
        }

        .rb-card-body {
            padding: 12px 14px 14px;
        }

        .rb-card-cat {
            font-size: 0.67rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--brand);
            margin-bottom: 5px;
        }

        .rb-card-title {
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--text-2);
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .rb-card-date {
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: 8px;
        }

        /* ── Disqus section ── */
        .disqus-section {
            margin-top: 48px;
            padding-top: 32px;
            border-top: 2px solid var(--border);
        }

        .disqus-section-hd {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.02em;
        }

        .disqus-section-hd::before {
            content: '';
            display: block;
            width: 4px;
            height: 20px;
            border-radius: 2px;
            background: var(--muted-2);
            flex-shrink: 0;
        }

        @media (max-width: 700px) {
            .related-bottom-grid {
                grid-template-columns: 1fr;
            }

            .eng-bar {
                gap: 8px;
            }
        }

        @media (max-width: 480px) {
            .related-bottom-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

    <div class="container">
        <div class="detail-wrap">

            {{-- ====================================================
             MAIN ARTICLE
        ==================================================== --}}
            <article class="article-panel">

                {{-- Hero image --}}
                @if ($article->image_url)
                    <img class="article-hero" src="{{ $article->image_url }}" alt="{{ $article->displayTitle() }}">
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
 <div class="eng-bar">
                        {{-- Like --}}
                        <button class="like-btn {{ '' }}" id="like-btn" data-slug="{{ $article->slug }}"
                            onclick="handleLike(this)">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                            <span id="like-count">{{ number_format($article->likes) }}</span>
                            <span id="like-label">{{ $article->likes === 1 ? 'Like' : 'Likes' }}</span>
                        </button>

                        {{-- Bookmark --}}
                        <button class="art-bookmark-btn" data-slug="{{ $article->slug }}"
                            onclick="toggleBookmark(event,'{{ $article->slug }}','{{ addslashes($article->displayTitle()) }}','{{ addslashes($article->image_url ?? '') }}','{{ $article->category }}')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z" />
                            </svg>
                            <span class="bm-btn-label">Save</span>
                        </button>

                        <div class="eng-divider"></div>

                        {{-- Views stat --}}
                        <span class="eng-stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            {{ $article->views >= 1000 ? number_format($article->views / 1000, 1) . 'k' : number_format($article->views) }}
                            views
                        </span>

                        <div class="eng-spacer"></div>

                        {{-- Share --}}
                        <span style="font-size:0.78rem;color:var(--muted);font-weight:600;">Share:</span>
                        <a class="share-btn twitter"
                            href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->displayTitle()) }}"
                            target="_blank" rel="noreferrer">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.259 5.631 5.905-5.631zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                            X
                        </a>
                        <a class="share-btn whatsapp"
                            href="https://wa.me/?text={{ urlencode($article->displayTitle() . ' ' . request()->url()) }}"
                            target="_blank" rel="noreferrer"
                            style="background:rgba(37,211,102,0.1);color:#128c7e;border:1px solid rgba(37,211,102,0.25);">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            WhatsApp
                        </a>
                        <a class="share-btn linkedin"
                            href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                            target="_blank" rel="noreferrer">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z" />
                                <circle cx="4" cy="4" r="2" />
                            </svg>
                            LinkedIn
                        </a>
                        <button class="share-btn copy-link">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                            </svg>
                            Copy Link
                        </button>
                    </div>
                    <div class="article-meta">
                        <div class="article-meta-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            {{ optional($article->published_at)->format('M d, Y') ?? optional($article->scraped_at)->format('M d, Y') }}
                        </div>

                        <span class="article-meta-sep">&middot;</span>
                        <div class="article-meta-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            DAILYdRIVE Editorial
                        </div>

                        <span class="article-meta-sep">&middot;</span>
                        <div class="article-meta-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            {{ $article->estimatedReadMinutes() }} min read
                        </div>

                        <span class="article-meta-sep">&middot;</span>
                        <div class="article-meta-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            {{ $article->views >= 1000 ? number_format($article->views / 1000, 1) . 'k' : number_format($article->views) }}
                            views
                        </div>
                    </div>

                </div>

                {{-- Body --}}
                <div class="article-bd">

                    <div class="article-body">
                        {!! $article->displayContentHtml() !!}
                    </div>

                    {{-- ── ENGAGEMENT BAR (like + bookmark + share) ── --}}


                    {{-- ---- REVIEW BLOCK ---- --}}
                    @if ($article->reviewData())
                        @php($review = $article->reviewData())
                        <div class="review-block">
                            <div class="review-block-hd">
                                <div class="review-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
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
                                                        <li><span
                                                                class="pc-icon">&#43;</span><span>{{ $item }}</span>
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
                                                        <li><span
                                                                class="pc-icon">&#8722;</span><span>{{ $item }}</span>
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
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" style="color:var(--brand);">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                    <line x1="12" y1="17" x2="12.01" y2="17" />
                                </svg>
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

                    {{-- ── RELATED ARTICLES (bottom, 3 cards) ── --}}
                    @if ($related->isNotEmpty())
                        <div class="related-bottom">
                            <div class="related-bottom-hd">You Might Also Like</div>
                            <div class="related-bottom-grid">
                                @foreach ($related as $item)
                                    <a class="rb-card" href="{{ route('articles.show', $item) }}">
                                        @if ($item->image_url)
                                            <img class="rb-card-img" src="{{ $item->image_url }}"
                                                alt="{{ $item->displayTitle() }}" loading="lazy">
                                        @else
                                            <div class="rb-card-img-ph">📰</div>
                                        @endif
                                        <div class="rb-card-body">
                                            @if ($item->category)
                                                <div class="rb-card-cat">{{ $item->category }}</div>
                                            @endif
                                            <div class="rb-card-title">{{ $item->displayTitle() }}</div>
                                            <div class="rb-card-date">
                                                {{ optional($item->published_at)->format('M d, Y') }}</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ── DISQUS COMMENTS ── --}}
                    @if (config('services.disqus.shortname'))
                        <div class="disqus-section">
                            <div class="disqus-section-hd">Comments</div>
                            <div id="disqus_thread"></div>
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
                            <span
                                class="info-val">{{ optional($article->published_at)->format('M d, Y') ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Views</span>
                            <span class="info-val">{{ number_format($article->views) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Likes</span>
                            <span class="info-val" id="sidebar-likes">{{ number_format($article->likes) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Read time</span>
                            <span class="info-val">{{ $article->estimatedReadMinutes() }} min</span>
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

                {{-- Sidebar related --}}
                @if ($related->isNotEmpty())
                    <div class="scard">
                        <div class="scard-hd">More in {{ $article->category ?? 'Related' }}</div>
                        <div class="scard-bd">
                            @foreach ($related as $item)
                                <a class="related-item" href="{{ route('articles.show', $item) }}">
                                    @if ($item->image_url)
                                        <img class="related-thumb" src="{{ $item->image_url }}"
                                            alt="{{ $item->displayTitle() }}" loading="lazy">
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

@section('page_scripts')
    <script>
        // ── LIKE BUTTON ───────────────────────────────
        (function() {
            var btn = document.getElementById('like-btn');
            var slug = btn ? btn.dataset.slug : null;
            var key = 'liked_' + slug;

            // Restore liked state from localStorage
            if (slug && localStorage.getItem(key)) {
                btn.classList.add('liked');
            }

            window.handleLike = function(el) {
                if (!slug) return;
                if (localStorage.getItem(key)) {
                    showToast('Already liked ❤');
                    return;
                }
                var csrfToken = document.querySelector('meta[name=csrf-token]').content;
                fetch('/articles/' + slug + '/like', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(data) {
                        document.getElementById('like-count').textContent = data.likes;
                        document.getElementById('like-label').textContent = data.likes === 1 ? 'Like' : 'Likes';
                        var sidebarLikes = document.getElementById('sidebar-likes');
                        if (sidebarLikes) sidebarLikes.textContent = data.likes.toLocaleString();
                        localStorage.setItem(key, '1');
                        el.classList.add('liked');
                        showToast('❤ Thanks for the like!');
                    })
                    .catch(function() {
                        showToast('Could not register like. Try again.');
                    });
            };
        })();

        // ── BOOKMARK BUTTON LABEL SYNC ────────────────
        (function() {
            var slug = '{{ $article->slug }}';

            function syncLabel() {
                var on = typeof isBookmarked === 'function' && isBookmarked(slug);
                document.querySelectorAll('.bm-btn-label').forEach(function(el) {
                    el.textContent = on ? 'Saved ✓' : 'Save';
                });
            }
            // Run after global bookmark JS loads
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', syncLabel);
            } else {
                setTimeout(syncLabel, 0);
            }

            // Override toggleBookmark to also update label
            var _orig = window.toggleBookmark;
            window.toggleBookmark = function(evt, s, t, img, cat) {
                _orig(evt, s, t, img, cat);
                syncLabel();
            };
        })();

        @if (config('services.disqus.shortname'))
            // ── DISQUS ────────────────────────────────────
            var disqus_config = function() {
                this.page.url = '{{ url()->current() }}';
                this.page.identifier = 'article-{{ $article->id }}';
            };
            (function() {
                var d = document,
                    s = d.createElement('script');
                s.src = 'https://{{ config('services.disqus.shortname') }}.disqus.com/embed.js';
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);
            })();
        @endif
    </script>
@endsection
