@extends('layouts.public')

@section('title', 'Privacy Policy — DAILYdRIVE')
@section('meta_description', 'Read the DAILYdRIVE Privacy Policy to understand how we collect, use, and protect your personal information.')

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
        background: var(--brand-bg-2);
        border: 1px solid rgba(80, 70, 228, 0.12);
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
            <h1 class="legal-h1">Privacy Policy</h1>
            <div class="legal-updated">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Last updated: June 15, 2026
            </div>
            <p style="font-size:1rem; color:var(--muted); line-height:1.8; max-width:62ch;">
                This Privacy Policy explains how <strong style="color:var(--text);">DAILYdRIVE</strong> collects, uses, and safeguards your information when you visit our website. Please read it carefully.
            </p>
        </div>

        {{-- Table of Contents --}}
        <div class="legal-toc">
            <div class="legal-toc-title">Table of Contents</div>
            <ol>
                <li><a href="#info-we-collect">Information We Collect</a></li>
                <li><a href="#how-we-use">How We Use Your Information</a></li>
                <li><a href="#cookies">Cookies &amp; Tracking</a></li>
                <li><a href="#newsletter">Newsletter Subscription</a></li>
                <li><a href="#third-party">Third-Party Services</a></li>
                <li><a href="#data-security">Data Security</a></li>
                <li><a href="#your-rights">Your Rights</a></li>
                <li><a href="#children">Children's Privacy</a></li>
                <li><a href="#changes">Changes to This Policy</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </ol>
        </div>

        {{-- Sections --}}
        <div class="legal-section" id="info-we-collect">
            <h2><span class="legal-num">1</span> Information We Collect</h2>
            <p>We collect information in the following ways:</p>
            <ul>
                <li><strong>Information you provide voluntarily</strong> — such as your email address when you subscribe to our newsletter.</li>
                <li><strong>Usage data</strong> — pages visited, time spent on the site, referring URLs, and browser/device type, collected automatically via server logs and analytics tools.</li>
                <li><strong>Cookies &amp; local storage</strong> — small data files stored in your browser to improve site performance and remember your preferences.</li>
            </ul>
            <p>We do <strong>not</strong> collect payment information, government IDs, or sensitive personal data.</p>
        </div>

        <div class="legal-section" id="how-we-use">
            <h2><span class="legal-num">2</span> How We Use Your Information</h2>
            <p>The information we collect is used to:</p>
            <ul>
                <li>Deliver the newsletter and other communications you have requested.</li>
                <li>Analyse site traffic to improve content and user experience.</li>
                <li>Detect and prevent fraudulent or abusive activity.</li>
                <li>Comply with applicable legal obligations.</li>
            </ul>
            <p>We do <strong>not</strong> sell, rent, or trade your personal information to third parties for marketing purposes.</p>
        </div>

        <div class="legal-section" id="cookies">
            <h2><span class="legal-num">3</span> Cookies &amp; Tracking</h2>
            <p>DAILYdRIVE uses cookies to:</p>
            <ul>
                <li>Keep you authenticated (admin users only).</li>
                <li>Remember your preferences between visits.</li>
                <li>Gather anonymous analytics about how visitors use the site.</li>
            </ul>
            <p>You can disable cookies in your browser settings at any time. Disabling cookies may affect certain site features.</p>
        </div>

        <div class="legal-section" id="newsletter">
            <h2><span class="legal-num">4</span> Newsletter Subscription</h2>
            <p>When you subscribe to our newsletter, we collect your email address. We use this solely to send you the DAILYdRIVE newsletter.</p>
            <div class="legal-highlight">
                <p>You can unsubscribe at any time by clicking the <strong>Unsubscribe</strong> link at the bottom of any newsletter email, or by contacting us directly. We will remove your address within 5 business days.</p>
            </div>
            <p>We do not share subscriber email addresses with any third party.</p>
        </div>

        <div class="legal-section" id="third-party">
            <h2><span class="legal-num">5</span> Third-Party Services</h2>
            <p>We may use the following third-party services, each governed by their own privacy policies:</p>
            <ul>
                <li><strong>AI Providers</strong> (OpenAI, Google Gemini, Anthropic Claude, DeepSeek) — used to rewrite and enrich article content. No personal user data is sent to these services.</li>
                <li><strong>Web fonts</strong> — served by Bunny Fonts (privacy-friendly CDN).</li>
                <li><strong>Hosting infrastructure</strong> — your IP address may be processed by our hosting provider for security purposes.</li>
            </ul>
        </div>

        <div class="legal-section" id="data-security">
            <h2><span class="legal-num">6</span> Data Security</h2>
            <p>We take reasonable technical and organisational measures to protect your data against unauthorised access, alteration, disclosure, or destruction. However, no method of internet transmission is 100% secure, and we cannot guarantee absolute security.</p>
        </div>

        <div class="legal-section" id="your-rights">
            <h2><span class="legal-num">7</span> Your Rights</h2>
            <p>Depending on your location, you may have the right to:</p>
            <ul>
                <li>Access the personal data we hold about you.</li>
                <li>Request correction or deletion of your data.</li>
                <li>Withdraw consent for newsletter communications at any time.</li>
                <li>Lodge a complaint with your local data protection authority.</li>
            </ul>
            <p>To exercise any of these rights, please contact us at the email below.</p>
        </div>

        <div class="legal-section" id="children">
            <h2><span class="legal-num">8</span> Children's Privacy</h2>
            <p>DAILYdRIVE is not directed at children under the age of 13. We do not knowingly collect personal information from children. If you believe a child has provided us with personal data, please contact us and we will delete it promptly.</p>
        </div>

        <div class="legal-section" id="changes">
            <h2><span class="legal-num">9</span> Changes to This Policy</h2>
            <p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with a revised "Last updated" date. We encourage you to review this page periodically.</p>
        </div>

        <div class="legal-section" id="contact">
            <h2><span class="legal-num">10</span> Contact Us</h2>
            <p>If you have any questions about this Privacy Policy, please reach out:</p>
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
            <a href="{{ route('terms') }}" style="color:var(--brand);">Terms &amp; Conditions</a>
            <a href="{{ route('home') }}" style="color:var(--muted);">← Back to Home</a>
        </div>

    </div>
</div>

@endsection
