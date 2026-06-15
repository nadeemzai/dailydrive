@extends('layouts.public')

@section('title', 'DAILYdRIVE — AI-Powered Tech News')

@section('content')

        {{-- ── 1. HERO CAROUSEL ────────────────────────────────────── --}}
        @if ($carouselArticles->isNotEmpty())
            <div class="carousel-wrap">
                <div class="carousel-slides">
                    @foreach ($carouselArticles as $i => $slide)
                        <div class="cslide {{ $i === 0 ? 'is-active' : '' }}">
                            @if ($slide->image_url)
                                <img class="cslide-img" src="{{ $slide->image_url }}" alt="{{ $slide->displayTitle() }}">
                            @else
                                <div class="cslide-fallback"></div>
                            @endif
                            <div class="cslide-overlay"></div>
                            <div class="cslide-content">
                                <span class="cslide-tag">{{ $slide->category ?? 'Technology' }}</span>
                                <h2 class="cslide-title">{{ $slide->displayTitle() }}</h2>
                                @if ($slide->displayExcerpt())
                                    <p class="cslide-excerpt">{{ $slide->displayExcerpt() }}</p>
                                @endif
                                <a href="{{ route('articles.show', $slide) }}" class="cslide-cta">
                                    Read Article &nbsp;&rsaquo;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($carouselArticles->count() > 1)
                    <button class="carousel-btn prev" aria-label="Previous">&#8592;</button>
                    <button class="carousel-btn next" aria-label="Next">&#8594;</button>
                    <div class="carousel-dots">
                        @foreach ($carouselArticles as $i => $slide)
                            <button class="cdot {{ $i === 0 ? 'is-active' : '' }}"
                                aria-label="Slide {{ $i + 1 }}"></button>
                        @endforeach
                    </div>
                @endif

                <div class="carousel-progress"></div>
            </div>
        @endif


        {{-- ── CATEGORY CARDS ─────────────────────────────────────── --}}
        @if ($categoryCards->isNotEmpty())
            <div class="categories-section">
                <div class="container">
                    {{-- Section Header --}}
                    <div class="section-header">
                        <div class="section-tag">Explore Topics</div>
                        <h2 class="section-title">Browse Categories</h2>
                        <p class="section-subtitle">Discover articles and resources tailored to your interests</p>
                    </div>

                    {{-- Categories Grid --}}
                    <div class="cat-cards-wrap">
                        <div class="cat-cards-row">
                            @foreach ($categoryCards as $catName => $total)
                                @php
                                    $catIcons = [
                                        'Technology' =>
                                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>',
                                        'Artificial Intelligence' =>
                                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a4 4 0 0 1 4 4v1h1a3 3 0 0 1 0 6h-1v1a4 4 0 0 1-8 0v-1H7a3 3 0 0 1 0-6h1V6a4 4 0 0 1 4-4z"/><path d="M9 12h.01M15 12h.01M12 15h.01"/></svg>',
                                        'Business' =>
                                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
                                        'Security' =>
                                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
                                        'Science' =>
                                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3h6v11l3.5 6H5.5L9 14V3z"/><line x1="9" y1="7" x2="15" y2="7"/></svg>',
                                        'Environment' =>
                                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22c1-5 4-8 10-8s9 3 10 8"/><path d="M12 14C10 10 6 5 2 2c5 1 9 4 10 8"/><path d="M12 14c2-4 6-9 10-12-5 1-9 4-10 8"/></svg>',
                                        'Health' =>
                                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
                                        'Gaming' =>
                                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 12h4M8 10v4"/><circle cx="16" cy="11" r="1" fill="currentColor" stroke="none"/><circle cx="18" cy="13" r="1" fill="currentColor" stroke="none"/></svg>',
                                        'Policy' =>
                                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
                                        'Other' =>
                                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
                                    ];
                                    $icon = $catIcons[$catName] ?? $catIcons['Other'];
                                @endphp
                                <a href="{{ route('home', ['category' => $catName]) }}"
                                    class="cat-card {{ request()->query('category') === $catName ? 'is-active' : '' }}">
                                    <div class="cat-card-icon">{!! $icon !!}</div>
                                    <div class="cat-card-content">
                                        <span class="cat-card-name">{{ $catName }}</span>
                                        <span class="cat-card-count">{{ $total }} articles</span>
                                    </div>
                                    <div class="cat-card-arrow">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Styles --}}
            <style>
                /* =========================
           Categories Section
        ========================= */

                .categories-section {
                    padding: 4rem 0;
                    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                }

                /* =========================
           Section Header
        ========================= */

                .section-header {
                    text-align: center;
                    margin-bottom: 3rem;
                }

                .section-tag {
                    display: inline-block;
                    font-size: 0.85rem;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                    color: #3b82f6;
                    background: #dbeafe;
                    padding: 0.35rem 1rem;
                    border-radius: 999px;
                    margin-bottom: 1rem;
                }

                .section-title {
                    font-size: 2.4rem;
                    font-weight: 800;
                    color: #0f172a;
                    margin: 0 0 .75rem;
                    letter-spacing: -0.03em;
                }

                .section-subtitle {
                    font-size: 1.05rem;
                    color: #64748b;
                    max-width: 560px;
                    margin: 0 auto;
                    line-height: 1.7;
                }

                /* =========================
           Grid
        ========================= */

                .cat-cards-wrap {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 0 1rem;
                }

                .cat-cards-row {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
                    gap: 1.25rem;
                }

                /* =========================
           Card
        ========================= */

                .cat-card {
                    position: relative;
                    display: flex;
                    align-items: center;
                    gap: 1rem;

                    padding: 1rem 1.4rem;

                    background: #fff;
                    border: 1px solid #e2e8f0;
                    border-radius: 20px;

                    text-decoration: none;

                    overflow: hidden;

                    transition: all .35s cubic-bezier(.4, 0, .2, 1);

                    box-shadow:
                        0 1px 3px rgba(15, 23, 42, .04),
                        0 1px 2px rgba(15, 23, 42, .02);
                }

                /* Top Gradient Accent */

                .cat-card::before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;

                    width: 100%;
                    height: 3px;

                    background: linear-gradient(90deg,
                            #3b82f6,
                            #6366f1);

                    transform: scaleX(0);
                    transform-origin: left;

                    transition: .35s ease;
                }

                .cat-card:hover::before {
                    transform: scaleX(1);
                }

                .cat-card:hover {
                    transform: translateY(-6px);

                    border-color: rgba(59, 130, 246, .25);

                    box-shadow:
                        0 20px 30px -12px rgba(59, 130, 246, .12),
                        0 10px 15px -8px rgba(15, 23, 42, .08);
                }

                /* =========================
           Icon
        ========================= */

                .cat-card-icon {
                    flex-shrink: 0;

                    width: 52px;
                    height: 52px;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    border-radius: 18px;

                    background: linear-gradient(135deg,
                            #eff6ff,
                            #eef2ff);

                    transition: all .35s ease;
                }

                .cat-card-icon svg {
                    width: 24px;
                    height: 24px;

                    stroke: #2563eb;
                    stroke-width: 2;

                    transition: all .35s ease;
                }

                .cat-card:hover .cat-card-icon {
                    transform: scale(1.08) rotate(-3deg);

                    background: linear-gradient(135deg,
                            #dbeafe,
                            #e0e7ff);
                }

                /* =========================
           Content
        ========================= */

                .cat-card-content {
                    flex: 1;
                }

                .cat-card-name {
                    display: block;

                    font-size: 1rem;
                    font-weight: 700;

                    color: #0f172a;

                    margin-bottom: .35rem;

                    transition: .25s ease;
                }

                .cat-card-count {
                    display: inline-flex;
                    align-items: center;

                    padding: 4px 10px;

                    border-radius: 999px;

                    background: #f8fafc;

                    font-size: 12px;
                    font-weight: 600;

                    color: #64748b;

                    transition: .25s ease;
                }

                /* =========================
           Arrow
        ========================= */

                .cat-card-arrow {
                    flex-shrink: 0;

                    opacity: 0;
                    transform: translateX(-10px);

                    transition: all .3s ease;
                }

                .cat-card-arrow svg {
                    width: 18px;
                    height: 18px;

                    stroke: #3b82f6;
                    stroke-width: 2;
                }

                .cat-card:hover .cat-card-arrow {
                    opacity: 1;
                    transform: translateX(0);
                }

                /* =========================
           Active State
        ========================= */

                .cat-card.is-active {
                    background: linear-gradient(135deg,
                            #2563eb,
                            #3b82f6);

                    border-color: transparent;

                    box-shadow:
                        0 15px 30px rgba(37, 99, 235, .25);
                }

                .cat-card.is-active::before {
                    display: none;
                }

                .cat-card.is-active .cat-card-name,
                .cat-card.is-active .cat-card-count {
                    color: #fff;
                }

                .cat-card.is-active .cat-card-count {
                    background: rgba(255, 255, 255, .15);
                }

                .cat-card.is-active .cat-card-icon {
                    background: rgba(255, 255, 255, .15);
                    backdrop-filter: blur(10px);
                }

                .cat-card.is-active .cat-card-icon svg,
                .cat-card.is-active .cat-card-arrow svg {
                    stroke: #fff;
                }

                /* =========================
           Mobile
        ========================= */

                @media (max-width: 768px) {

                    .categories-section {
                        padding: 3rem 0;
                    }

                    .section-title {
                        font-size: 1.8rem;
                    }

                    .cat-cards-row {
                        grid-template-columns: 1fr;
                        gap: 1rem;
                    }

                    .cat-card {
                        padding: .95rem 1.2rem;
                    }

                    .cat-card-icon {
                        width: 46px;
                        height: 46px;
                    }

                    .cat-card-icon svg {
                        width: 22px;
                        height: 22px;
                    }
                }
            </style>
        @endif

        <div class="container">

            {{-- ── 2. FEATURED ARTICLE ─────────────────────────────── --}}
            @if ($featuredArticle)
                <div class="sec-head" style="padding-top: 44px;">
                    <h2 class="sec-title">Featured</h2>
                </div>

                <a href="{{ route('articles.show', $featuredArticle) }}" class="featured-hcard">
                    <div class="fhcard-img">
                        @if ($featuredArticle->image_url)
                            <img src="{{ $featuredArticle->image_url }}" alt="{{ $featuredArticle->displayTitle() }}">
                        @else
                            <div class="fhcard-img-ph">&#128240;</div>
                        @endif
                    </div>
                    <div class="fhcard-body">
                        <div class="fhcard-tags">
                            <span class="tag tag-featured">&#9733; Featured</span>
                            @if ($featuredArticle->category)
                                <span class="tag tag-source">{{ $featuredArticle->category }}</span>
                            @endif
                        </div>
                        <h3 class="fhcard-title">{{ $featuredArticle->displayTitle() }}</h3>
                        @if ($featuredArticle->displayExcerpt())
                            <p class="fhcard-excerpt">{{ $featuredArticle->displayExcerpt() }}</p>
                        @endif
                        <div class="fhcard-foot">
                            <div class="fhcard-meta">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                {{ optional($featuredArticle->published_at)->format('M d, Y') ?? optional($featuredArticle->scraped_at)->format('M d, Y') }}
                                <span class="fhcard-meta-sep">&middot;</span>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                DAILYdRIVE Editorial
                            </div>
                            <span class="fhcard-cta">
                                Read Full Article
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                    <polyline points="12 5 19 12 12 19" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endif


            {{-- ── 3. LATEST NEWS ──────────────────────────────────── --}}
            @if ($latestArticles->isNotEmpty())
                <div class="latest-section">
                    <div class="sec-head">
                        <h2 class="sec-title">Latest News</h2>
                        <a href="{{ route('articles.index') }}" class="see-all-link">
                            View all articles
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>

                    <div class="articles-grid home-latest-grid">
                        @foreach ($latestArticles as $article)
                            @include('articles._card', ['article' => $article])
                        @endforeach
                    </div>
                </div>
            @endif

        </div>{{-- /container --}}


        {{-- ── 4. SOURCE / CATEGORY SECTIONS (full-width strips) ───── --}}
        @foreach ($categoryGroups as $sourceName => $data)
            <div class="source-strip {{ $loop->odd ? 'source-strip-alt' : '' }}">
                <div class="container">

                    {{-- Section header --}}
                    <div class="source-strip-hd">
                        <div class="source-name-block">
                            <span class="source-accent-line"></span>
                            <span class="source-title">{{ $sourceName }}</span>
                            <span class="source-count-badge">{{ $data['total'] }} articles</span>
                        </div>
                        <a href="{{ route('articles.index', ['category' => $sourceName]) }}" class="source-view-all">
                            View all from {{ $sourceName }}
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12" />
                                <polyline points="12 5 19 12 12 19" />
                            </svg>
                        </a>
                    </div>

                    {{-- 4-col mini article grid --}}
                    <div class="source-4grid">
                        @foreach ($data['articles'] as $art)
                            @include('articles._card', ['article' => $art])
                        @endforeach
                    </div>

                </div>
            </div>
        @endforeach



@endsection
