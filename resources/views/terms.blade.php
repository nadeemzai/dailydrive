@extends('layouts.public')

@section('title', 'Terms & Conditions — DAILYdRIVE')
@section('meta_description', 'Read the DAILYdRIVE Terms and Conditions governing your use of our AI-powered news platform.')

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

    .legal-updated {
        font-size: 0.82rem;
        color: var(--muted-2);
        margin-bottom: 40px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .legal-toc {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 20px 24px;
        margin-bottom: 48px;
    }

    .legal-toc-title {
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 12px;
    }

    .legal-toc ol {
        padding-left: 18px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 4px 24px;
    }

    .legal-toc li { font-size: 0.84rem; color: var(--muted); line-height: 1.7; }
    .legal-toc a { color: var(--brand); transition: opacity 0.13s; }
    .legal-toc a:hover { opacity: 0.75; }

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

    .legal-highlight {
        background: var(--amber-bg);
        border: 1px solid rgba(217, 119, 6, 0.18);
        border-radius: var(--r);
        padding: 14px 18px;
        margin-bottom: 14px;
    }

    .legal-highlight p {
        margin-bottom: 0;
        font-size: 0.875rem;
        color: var(--text-2);
    }

    .legal-contact-box {
        background: var(--bg-card);
        border: 1.5px solid var(--border);
        border-radius: var(--r-lg);
        padding: 24px 28px;
        margin-top: 40px;
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

    .legal-contact-box h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }

    .legal-contact-box p {
        font-size: 0.84rem;
        color: var(--muted);
        line-height: 1.65;
        margin: 0;
    }

    .legal-contact-box a { color: var(--brand); }
</style>

<div class="container">
    <div class="legal-wrap">

        {{-- Header --}}
        <div style="margin-bottom: 36px;">
            <span class="legal-badge">Legal</span>
            <h1 class="legal-h1">Terms &amp; Conditions</h1>
            <div class="legal-updated">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Last updated: June 15, 2026
            </div>
            <p style="font-size:1rem; color:var(--muted); line-height:1.8; max-width:62ch;">
                These Terms &amp; Conditions govern your use of <strong style="color:var(--text);">DAILYdRIVE</strong>. By accessing or using this website, you agree to be bound by these terms. Please read them carefully.
            </p>
        </div>

        {{-- Table of Contents --}}
        <div class="legal-toc">
            <div class="legal-toc-title">Table of Contents</div>
            <ol>
                <li><a href="#acceptance">Acceptance of Terms</a></li>
                <li><a href="#description">Description of Service</a></li>
                <li><a href="#ai-content">AI-Generated Content</a></li>
                <li><a href="#intellectual-property">Intellectual Property</a></li>
                <li><a href="#user-conduct">User Conduct</a></li>
                <li><a href="#third-party-links">Third-Party Links</a></li>
                <li><a href="#disclaimer">Disclaimer of Warranties</a></li>
                <li><a href="#limitation">Limitation of Liability</a></li>
                <li><a href="#newsletter-terms">Newsletter Terms</a></li>
                <li><a href="#changes">Changes to Terms</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </ol>
        </div>

        {{-- Sections --}}
        <div class="legal-section" id="acceptance">
            <h2><span class="legal-num">1</span> Acceptance of Terms</h2>
            <p>By accessing and using DAILYdRIVE ("the Site", "we", "us"), you accept and agree to be bound by these Terms &amp; Conditions and our <a href="{{ route('privacy') }}" style="color:var(--brand);">Privacy Policy</a>. If you do not agree to these terms, please do not use this site.</p>
        </div>

        <div class="legal-section" id="description">
            <h2><span class="legal-num">2</span> Description of Service</h2>
            <p>DAILYdRIVE is an AI-powered news aggregation platform. We collect publicly available news articles from third-party sources, process them using artificial intelligence to produce summaries and enriched content, and publish the results on this website.</p>
            <p>We reserve the right to modify, suspend, or discontinue any part of the service at any time without notice.</p>
        </div>

        <div class="legal-section" id="ai-content">
            <h2><span class="legal-num">3</span> AI-Generated Content</h2>
            <div class="legal-highlight">
                <p><strong>Important:</strong> Articles on DAILYdRIVE are rewritten, summarised, or otherwise processed by artificial intelligence. While we strive for accuracy, AI-generated content may contain errors, omissions, or inaccuracies.</p>
            </div>
            <p>DAILYdRIVE makes no representations or warranties about the accuracy, completeness, or reliability of any AI-generated content. You should independently verify any information before relying on it for important decisions.</p>
            <p>Original source attribution is provided where available. The original copyright of underlying news stories remains with the respective publishers.</p>
        </div>

        <div class="legal-section" id="intellectual-property">
            <h2><span class="legal-num">4</span> Intellectual Property</h2>
            <p>The DAILYdRIVE name, logo, website design, and all original content created by our team are the intellectual property of DAILYdRIVE and are protected by applicable copyright and trademark laws.</p>
            <ul>
                <li>You may share links to articles on DAILYdRIVE.</li>
                <li>You may quote brief excerpts for non-commercial purposes with attribution.</li>
                <li>You may <strong>not</strong> reproduce, republish, or redistribute full articles without prior written permission.</li>
                <li>You may <strong>not</strong> scrape or crawl the site in a way that disrupts service.</li>
            </ul>
        </div>

        <div class="legal-section" id="user-conduct">
            <h2><span class="legal-num">5</span> User Conduct</h2>
            <p>When using DAILYdRIVE, you agree not to:</p>
            <ul>
                <li>Use the site for any unlawful purpose or in violation of any regulations.</li>
                <li>Attempt to gain unauthorised access to any part of the site or its infrastructure.</li>
                <li>Interfere with or disrupt the integrity or performance of the site.</li>
                <li>Use automated tools to scrape, copy, or mirror the site's content without permission.</li>
                <li>Submit false, misleading, or harmful information through any contact forms.</li>
            </ul>
        </div>

        <div class="legal-section" id="third-party-links">
            <h2><span class="legal-num">6</span> Third-Party Links</h2>
            <p>DAILYdRIVE links to original source articles hosted on third-party websites. We have no control over those websites and are not responsible for their content, privacy practices, or availability. Visiting external links is at your own risk.</p>
        </div>

        <div class="legal-section" id="disclaimer">
            <h2><span class="legal-num">7</span> Disclaimer of Warranties</h2>
            <p>DAILYdRIVE is provided on an <strong>"as is" and "as available"</strong> basis without any warranties of any kind, either express or implied, including but not limited to warranties of merchantability, fitness for a particular purpose, or non-infringement.</p>
            <p>We do not warrant that the site will be uninterrupted, error-free, or free of viruses or other harmful components.</p>
        </div>

        <div class="legal-section" id="limitation">
            <h2><span class="legal-num">8</span> Limitation of Liability</h2>
            <p>To the maximum extent permitted by applicable law, DAILYdRIVE and its operators shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of, or inability to use, the site or its content — even if we have been advised of the possibility of such damages.</p>
        </div>

        <div class="legal-section" id="newsletter-terms">
            <h2><span class="legal-num">9</span> Newsletter Terms</h2>
            <p>By subscribing to the DAILYdRIVE newsletter, you consent to receive periodic email communications from us. You may unsubscribe at any time. See our <a href="{{ route('privacy') }}" style="color:var(--brand);">Privacy Policy</a> for details on how we handle subscriber data.</p>
        </div>

        <div class="legal-section" id="changes">
            <h2><span class="legal-num">10</span> Changes to Terms</h2>
            <p>We reserve the right to update these Terms &amp; Conditions at any time. Changes will be posted on this page with a revised "Last updated" date. Your continued use of the site after any changes constitutes your acceptance of the new terms.</p>
        </div>

        <div class="legal-section" id="contact">
            <h2><span class="legal-num">11</span> Contact Us</h2>
            <p>If you have any questions about these Terms &amp; Conditions, please contact us:</p>
        </div>

        {{-- Contact box --}}
        <div class="legal-contact-box">
            <div class="legal-contact-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--brand)"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <div>
                <h3>DAILYdRIVE</h3>
                <p>
                    Email: <a href="mailto:hello@dailydrive.com">hello@dailydrive.com</a><br>
                    You can also find us at <a href="{{ route('about') }}">our About page</a>.
                </p>
            </div>
        </div>

        {{-- Footer nav --}}
        <div style="margin-top: 48px; padding-top: 24px; border-top: 1px solid var(--border); display: flex; gap: 20px; flex-wrap: wrap; font-size: 0.82rem;">
            <a href="{{ route('about') }}" style="color:var(--brand);">About Us</a>
            <a href="{{ route('privacy') }}" style="color:var(--brand);">Privacy Policy</a>
            <a href="{{ route('home') }}" style="color:var(--muted);">← Back to Home</a>
        </div>

    </div>
</div>

@endsection
