@extends('layouts.public')

@section('title', 'About Us — DAILYdRIVE')
@section('meta_description', 'DAILYdRIVE is an AI-powered news platform delivering fresh, curated articles across Technology, AI, Business, Security, and more — every day.')

@section('content')

<style>
    .legal-wrap {
        max-width: 760px;
        margin: 0 auto;
        padding: 60px 0 90px;
    }

    .legal-badge {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--brand);
        background: var(--brand-bg);
        border: 1px solid rgba(80, 70, 228, 0.15);
        padding: 4px 12px;
        border-radius: 999px;
        margin-bottom: 16px;
    }

    .legal-h1 {
        font-size: clamp(1.8rem, 4vw, 2.6rem);
        font-weight: 900;
        letter-spacing: -0.04em;
        color: var(--text);
        line-height: 1.15;
        margin-bottom: 12px;
    }

    .legal-section { margin-bottom: 44px; }

    .legal-section h2 {
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: -0.025em;
        color: var(--text);
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .legal-section h2 .legal-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--brand-bg);
        border: 1px solid rgba(80, 70, 228, 0.2);
        color: var(--brand);
        font-size: 0.72rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    .legal-section p {
        font-size: 0.93rem;
        color: var(--muted);
        line-height: 1.82;
        margin-bottom: 12px;
    }

    .legal-section ul {
        padding-left: 0;
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 7px;
        margin-bottom: 14px;
    }

    .legal-section ul li {
        font-size: 0.9rem;
        color: var(--muted);
        line-height: 1.7;
        padding-left: 18px;
        position: relative;
    }

    .legal-section ul li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 10px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--brand);
        opacity: 0.5;
    }

    /* Feature cards */
    .about-features {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin: 20px 0 4px;
    }

    @media (max-width: 640px) {
        .about-features { grid-template-columns: 1fr; }
    }

    .about-feature-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: var(--r-lg);
        padding: 20px 18px;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .about-feature-card:hover {
        border-color: rgba(80,70,228,0.3);
        box-shadow: 0 4px 18px rgba(80,70,228,0.07);
    }

    .about-feature-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--r);
        background: var(--brand-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .about-feature-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }

    .about-feature-desc {
        font-size: 0.81rem;
        color: var(--muted);
        line-height: 1.65;
        margin: 0;
    }

    /* Stats row */
    .about-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1px;
        background: var(--border);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        overflow: hidden;
        margin: 20px 0 4px;
    }

    .about-stat {
        background: var(--bg-card);
        padding: 22px 18px;
        text-align: center;
    }

    .about-stat-val {
        font-size: 1.6rem;
        font-weight: 900;
        letter-spacing: -0.04em;
        color: var(--brand);
        line-height: 1;
        margin-bottom: 5px;
    }

    .about-stat-label {
        font-size: 0.78rem;
        color: var(--muted);
        font-weight: 500;
    }

    /* Newsletter CTA */
    .about-cta {
        background: linear-gradient(135deg, #5046e4 0%, #7c3aed 100%);
        border-radius: var(--r-xl);
        padding: 36px 32px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
        margin: 20px 0 4px;
    }

    .about-cta-title { font-size: 1.1rem; font-weight: 800; margin-bottom: 6px; }
    .about-cta-desc  { font-size: 0.85rem; opacity: 0.8; max-width: 38ch; line-height: 1.6; margin: 0; }

    .about-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 24px;
        background: #fff;
        color: var(--brand);
        border: none;
        border-radius: var(--r);
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        font-family: inherit;
        text-decoration: none;
        transition: opacity 0.14s;
        flex-shrink: 0;
    }

    .about-cta-btn:hover { opacity: 0.9; }

    .legal-contact-box {
        background: var(--bg-card);
        border: 1.5px solid var(--border);
        border-radius: var(--r-lg);
        padding: 24px 28px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .legal-contact-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--r);
        background: var(--brand-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .legal-contact-box h3 { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 4px; }
    .legal-contact-box p  { font-size: 0.84rem; color: var(--muted); line-height: 1.65; margin: 0; }
    .legal-contact-box a  { color: var(--brand); }
</style>

<div class="container">
    <div class="legal-wrap">

        {{-- Header --}}
        <div style="margin-bottom: 44px;">
            <span class="legal-badge">About Us</span>
            <h1 class="legal-h1">News that moves<br>as fast as the world does.</h1>
            <p style="font-size:1rem; color:var(--muted); line-height:1.8; max-width:62ch; margin-top:14px;">
                DAILYdRIVE is an AI-powered news aggregation platform. We pull stories from the best sources across the web, then use artificial intelligence to rewrite, summarise, and enrich each article — so you get the full picture, faster.
            </p>
        </div>

        {{-- What We Do --}}
        <div class="legal-section" id="what-we-do">
            <h2><span class="legal-num">1</span> What We Do</h2>
            <p>Every few hours, our automated scraper collects breaking news and in-depth articles from trusted sources across the web. Each article is then passed through an AI pipeline that rewrites the content into clear, concise summaries — stripping away noise and surfacing what matters.</p>
            <p>The result is a continuously updated stream of enriched articles, complete with AI-generated headlines, excerpts, FAQs, and SEO metadata — all in one place.</p>

            <div class="about-features">
                <div class="about-feature-card">
                    <div class="about-feature-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--brand)"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                    </div>
                    <div class="about-feature-title">AI-Curated</div>
                    <p class="about-feature-desc">Every article is rewritten and enriched by AI to give you clear, readable summaries with full context.</p>
                </div>
                <div class="about-feature-card">
                    <div class="about-feature-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--brand)"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div class="about-feature-title">Always Fresh</div>
                    <p class="about-feature-desc">Our scraper runs regularly, pulling the latest stories so you're never behind the news cycle.</p>
                </div>
                <div class="about-feature-card">
                    <div class="about-feature-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--brand)"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    </div>
                    <div class="about-feature-title">Multi-Topic</div>
                    <p class="about-feature-desc">Technology, AI, Business, Security, Environment, Health — one place for everything that matters.</p>
                </div>
            </div>
        </div>

        {{-- Our Mission --}}
        <div class="legal-section" id="mission">
            <h2><span class="legal-num">2</span> Our Mission</h2>
            <p>We believe good information should be accessible, readable, and fast. The internet is overloaded with content — long articles, paywalls, and noise. DAILYdRIVE cuts through that by giving you the key insights from every story in seconds.</p>
            <p>We're not here to replace journalism. We're here to make it easier to stay informed without spending hours reading. Source links are always provided so you can read the full original whenever you want.</p>
        </div>

        {{-- How It Works --}}
        <div class="legal-section" id="how-it-works">
            <h2><span class="legal-num">3</span> How It Works</h2>
            <ul>
                <li><strong>Scrape</strong> — Our crawler pulls fresh articles from trusted news sources across the web on a regular schedule.</li>
                <li><strong>Enrich</strong> — Each article is sent to an AI model (OpenAI, Gemini, Claude, or DeepSeek) which rewrites the title, generates a summary, creates FAQ blocks, and fills in SEO metadata.</li>
                <li><strong>Publish</strong> — Enriched articles are instantly published on DAILYdRIVE, categorised by topic, and made searchable.</li>
                <li><strong>Deliver</strong> — Subscribers receive curated highlights via our newsletter.</li>
            </ul>

            <div class="about-stats">
                <div class="about-stat">
                    <div class="about-stat-val">4+</div>
                    <div class="about-stat-label">AI Providers</div>
                </div>
                <div class="about-stat">
                    <div class="about-stat-val">10+</div>
                    <div class="about-stat-label">Categories</div>
                </div>
                <div class="about-stat">
                    <div class="about-stat-val">Daily</div>
                    <div class="about-stat-label">Updates</div>
                </div>
            </div>
        </div>

        {{-- AI & Accuracy --}}
        <div class="legal-section" id="ai-accuracy">
            <h2><span class="legal-num">4</span> AI &amp; Accuracy</h2>
            <p>All article content on DAILYdRIVE is processed by artificial intelligence. While our AI pipeline is designed for accuracy and clarity, AI-generated summaries may occasionally contain errors or miss nuance from the original source.</p>
            <p>We always link back to the original article so you can verify information directly. If you notice an error, feel free to contact us and we'll review it promptly.</p>
        </div>

        {{-- Newsletter CTA --}}
        <div class="legal-section" id="newsletter">
            <h2><span class="legal-num">5</span> Stay in the Loop</h2>
            <p>Subscribe to the DAILYdRIVE newsletter and get the top stories delivered to your inbox — no noise, just the news that matters.</p>

            <div class="about-cta">
                <div>
                    <div class="about-cta-title">Get daily news in your inbox</div>
                    <p class="about-cta-desc">Free, concise, and AI-curated. Unsubscribe any time.</p>
                </div>
                <a href="{{ route('home') }}#newsletter" class="about-cta-btn">
                    Subscribe Free
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
        </div>

        {{-- Contact --}}
        <div class="legal-section" id="contact">
            <h2><span class="legal-num">6</span> Get in Touch</h2>
            <p>Have a question, spotted an error, or want to suggest a news source? We'd love to hear from you.</p>
        </div>

        <div class="legal-contact-box">
            <div class="legal-contact-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--brand)"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <div>
                <h3>DAILYdRIVE</h3>
                <p>Email: <a href="mailto:hello@dailydrive.com">hello@dailydrive.com</a></p>
            </div>
        </div>

        {{-- Footer nav --}}
        <div style="margin-top: 48px; padding-top: 24px; border-top: 1px solid var(--border); display: flex; gap: 20px; flex-wrap: wrap; font-size: 0.82rem;">
            <a href="{{ route('privacy') }}" style="color:var(--brand);">Privacy Policy</a>
            <a href="{{ route('terms') }}" style="color:var(--brand);">Terms &amp; Conditions</a>
            <a href="{{ route('home') }}" style="color:var(--muted);">← Back to Home</a>
        </div>

    </div>
</div>

@endsection
