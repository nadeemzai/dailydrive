<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO Meta --}}
    <title>
        @hasSection('seo_title')
            @yield('seo_title')
        @else
            @yield('title', 'DAILYdRIVE')
        @endif
    </title>
    <meta name="description" content="@yield('meta_description', 'Your daily dose of AI-powered tech news — fresh articles from the best sources, every day.')">
    @hasSection('meta_keywords')
        <meta name="keywords" content="@yield('meta_keywords')">
    @endif
    <meta name="author" content="@yield('meta_author', 'Farhan')">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="DAILYdRIVE">
    <meta property="og:title" content="@hasSection('seo_title')
@yield('seo_title')@else@yield('title', 'DAILYdRIVE')
@endif">
    <meta property="og:description" content="@yield('meta_description', 'AI-powered tech news.')">
    <meta property="og:url" content="{{ url()->current() }}">
    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@hasSection('seo_title')
@yield('seo_title')@else@yield('title', 'DAILYdRIVE')
@endif">
    <meta name="twitter:description" content="@yield('meta_description', 'AI-powered tech news.')">
    @hasSection('og_image')
        <meta name="twitter:image" content="@yield('og_image')">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <style>
        /* ================================================
           DESIGN TOKENS
        ================================================ */
        :root {
            --white: #ffffff;
            --bg: #f5f7fa;
            --bg-card: #ffffff;
            --bg-hover: #f0f2f8;
            --border: #e1e5ed;
            --border-2: #c9cfe0;

            --text: #0d1117;
            --text-2: #2d3748;
            --muted: #60697d;
            --muted-2: #9aa3b4;

            --brand: #5046e4;
            --brand-2: #7c74f0;
            --brand-bg: #eeeeff;
            --brand-bg-2: #f3f2ff;

            --red: #e53e3e;
            --red-bg: #fff5f5;
            --amber: #d97706;
            --amber-bg: #fffbeb;
            --green: #059669;
            --green-bg: #ecfdf5;

            --nav-h: 60px;
            --cat-h: 44px;

            --r-xs: 4px;
            --r-sm: 8px;
            --r: 12px;
            --r-lg: 18px;
            --r-xl: 24px;
            --r-2xl: 32px;

            --shadow-xs: 0 1px 3px rgba(0, 0, 0, 0.07), 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.07), 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 2px 6px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 12px 40px rgba(0, 0, 0, 0.1), 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* ================================================
           RESET & BASE
        ================================================ */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            line-height: 1.6;
            font-size: 15px;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        img {
            display: block;
            max-width: 100%;
        }

        .container {
            width: min(1200px, calc(100% - 40px));
            margin: 0 auto;
        }

        /* ================================================
           TOP ACCENT BAR
        ================================================ */
        .accent-topbar {
            height: 3px;
            background: linear-gradient(90deg, var(--brand) 0%, #a78bfa 50%, #818cf8 100%);
            position: sticky;
            top: 0;
            z-index: 201;
        }

        /* ================================================
           TOP NAVBAR  (Row 1: logo | nav links | login)
        ================================================ */
        .topnav {
            position: sticky;
            top: 3px;
            z-index: 200;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-xs);
        }

        .topnav-inner {
            display: flex;
            align-items: center;
            height: var(--nav-h);
        }

        /* Logo */
        .site-logo {
            display: flex;
            align-items: center;
            gap: 0;
            font-size: 1.35rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 1;
            flex-shrink: 0;
        }

        .logo-daily {
            color: var(--text);
        }

        .logo-drive {
            color: var(--brand);
        }

        .logo-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--red);
            margin: 0 1px 6px;
            flex-shrink: 0;
        }

        /* Center nav links (text links) */
        .main-nav {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
        }

        .main-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: var(--r);
            color: var(--muted);
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.14s, background 0.14s, border-color 0.14s;
            position: relative;
            border: 1px solid transparent;
        }

        .main-nav-link svg {
            width: 16px;
            height: 16px;
            opacity: 0.85;
        }

        .main-nav-link:hover {
            color: var(--text);
            background: var(--bg-hover);
            border-color: var(--border);
        }

        .main-nav-link.is-active {
            color: var(--brand);
            background: var(--brand-bg);
            border-color: rgba(80, 70, 228, 0.12);
        }

        /* Nav right side */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        /* Search icon button */
        .nav-search-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: var(--r);
            border: 1.5px solid var(--border);
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            transition: all 0.14s;
        }

        .nav-search-btn:hover {
            background: var(--bg-hover);
            color: var(--text);
            border-color: var(--border-2);
        }

        /* Subscribe button */
        .btn-subscribe {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border-radius: var(--r);
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--white);
            background: var(--brand);
            border: 2px solid var(--brand);
            transition: background 0.14s, transform 0.1s;
            cursor: pointer;
            font-family: inherit;
            white-space: nowrap;
        }

        .btn-subscribe:hover {
            background: var(--brand-2);
            border-color: var(--brand-2);
            transform: translateY(-1px);
        }

        /* Search overlay */
        .search-overlay {
            position: fixed;
            inset: 0;
            background: rgba(13, 17, 23, 0.55);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 999;
            display: flex;
            align-items: flex-start;
            padding-top: 90px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }

        .search-overlay.is-open {
            opacity: 1;
            pointer-events: all;
        }

        .search-overlay-box {
            width: min(640px, calc(100% - 40px));
            margin: 0 auto;
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--r-xl);
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 6px 8px;
        }

        .search-icon-wrap {
            color: var(--muted-2);
            padding: 0 8px;
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .search-overlay-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 1.05rem;
            font-family: inherit;
            color: var(--text);
            background: transparent;
            padding: 10px 8px;
            min-width: 0;
        }

        .search-overlay-input::placeholder {
            color: var(--muted-2);
        }

        .search-overlay-submit {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: var(--r-lg);
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.14s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .search-overlay-submit:hover {
            background: var(--brand-2);
        }

        .search-overlay-close {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: var(--r-sm);
            cursor: pointer;
            color: var(--muted);
            flex-shrink: 0;
            transition: all 0.14s;
        }

        .search-overlay-close:hover {
            background: var(--bg-hover);
            color: var(--text);
        }

        /* ================================================
           CATEGORY BAR  (Row 2: horizontal category scroll)
        ================================================ */
        .catbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: calc(var(--nav-h) + 3px);
            z-index: 190;
            box-shadow: 0 1px 0 0 var(--border);
        }

        .catbar-inner {
            display: flex;
            align-items: center;
            gap: 6px;
            height: 52px;
            overflow-x: auto;
            scrollbar-width: none;
            padding: 0 2px;
        }

        .catbar-inner::-webkit-scrollbar {
            display: none;
        }

        /* "Explore:" label */
        .catbar-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted-2);
            white-space: nowrap;
            padding-right: 10px;
            border-right: 1.5px solid var(--border);
            margin-right: 4px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .cat-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 0.81rem;
            font-weight: 600;
            color: var(--muted);
            white-space: nowrap;
            border: 1.5px solid transparent;
            background: transparent;
            transition: all 0.15s;
            flex-shrink: 0;
            position: relative;
        }

        .cat-pill:hover {
            color: var(--text);
            background: var(--bg-hover);
            border-color: var(--border);
        }

        .cat-pill.is-active {
            color: var(--brand);
            background: var(--brand-bg);
            border-color: rgba(80, 70, 228, 0.25);
            font-weight: 700;
        }

        .cat-pill-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--border-2);
            flex-shrink: 0;
            transition: background 0.15s;
        }

        .cat-pill.is-active .cat-pill-dot {
            background: var(--brand);
        }

        .cat-pill-count {
            display: inline-flex;
            align-items: center;
            padding: 1px 6px;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 700;
            background: var(--bg);
            color: var(--muted-2);
            border: 1px solid var(--border);
            line-height: 1.5;
            transition: all 0.15s;
        }

        .cat-pill.is-active .cat-pill-count {
            background: rgba(80, 70, 228, 0.12);
            color: var(--brand);
            border-color: rgba(80, 70, 228, 0.2);
        }

        /* Divider between All and sources */
        .catbar-sep {
            width: 1px;
            height: 20px;
            background: var(--border);
            flex-shrink: 0;
            margin: 0 4px;
        }

        /* Category pill icons */
        .cat-pill svg {
            width: 11px;
            height: 11px;
            flex-shrink: 0;
            opacity: 0.55;
            transition: opacity 0.15s;
        }

        .cat-pill:hover svg,
        .cat-pill.is-active svg {
            opacity: 1;
        }

        /* ================================================
           SITE MAIN
        ================================================ */
        .site-main {
            padding: 0 0 80px;
        }

        /* ================================================
           SHARED BADGE / TAG COMPONENTS
        ================================================ */
        .tag {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .tag-source {
            background: var(--brand-bg);
            color: var(--brand);
            border: 1px solid rgba(80, 70, 228, 0.15);
        }

        .tag-ai {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid rgba(5, 150, 105, 0.15);
        }

        .tag-featured {
            background: var(--red-bg);
            color: var(--red);
            border: 1px solid rgba(229, 62, 62, 0.15);
        }

        .tag-new {
            background: var(--amber-bg);
            color: var(--amber);
            border: 1px solid rgba(217, 119, 6, 0.15);
        }

        /* ================================================
           SECTION HEADING
        ================================================ */
        .sec-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 36px 0 22px;
        }

        .sec-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text);
        }

        .sec-title::before {
            content: '';
            display: block;
            width: 4px;
            height: 22px;
            border-radius: 2px;
            background: var(--brand);
            flex-shrink: 0;
        }

        .sec-count {
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--muted-2);
        }

        .see-all-link {
            font-size: 0.83rem;
            font-weight: 600;
            color: var(--brand);
            display: flex;
            align-items: center;
            gap: 3px;
            transition: gap 0.14s;
        }

        .see-all-link:hover {
            gap: 7px;
        }

        /* ================================================
           ARTICLE CARDS  (shared)
        ================================================ */
        .acard {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            position: relative;
        }

        .acard::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--brand), var(--brand-2));
            opacity: 0;
            transition: opacity 0.2s;
        }

        .acard:hover::after {
            opacity: 1;
        }

        .acard:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 45px rgba(80, 70, 228, 0.1), 0 4px 12px rgba(0, 0, 0, 0.06);
            border-color: rgba(80, 70, 228, 0.2);
        }

        .acard-img {
            position: relative;
            overflow: hidden;
            background: var(--bg);
            flex-shrink: 0;
        }

        .acard-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.45s ease;
        }

        .acard-img::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(13, 17, 23, 0.25) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .acard:hover .acard-img::after {
            opacity: 1;
        }

        .acard:hover .acard-img img {
            transform: scale(1.06);
        }

        .acard-no-img {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--muted-2);
            background: linear-gradient(135deg, #f0f2f8, #e8ecf5);
        }

        .acard-body {
            padding: 18px 20px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex: 1;
        }

        .acard-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }

        .acard-title {
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.42;
            letter-spacing: -0.01em;
            color: var(--text);
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .acard:hover .acard-title {
            color: var(--brand);
        }

        .acard-excerpt {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.65;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }

        .acard-foot {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-top: 12px;
            border-top: 1px solid var(--border);
            color: var(--muted-2);
            font-size: 0.79rem;
            margin-top: auto;
        }

        .acard-foot-sep {
            opacity: 0.5;
        }

        .acard-read-time {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            margin-left: auto;
            font-size: 0.74rem;
            font-weight: 600;
            color: var(--muted-2);
        }

        /* ================================================
           HERO CAROUSEL
        ================================================ */
        .carousel-wrap {
            position: relative;
            width: 100%;
            height: 520px;
            overflow: hidden;
            background: #0d1117;
        }

        .carousel-slides {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .cslide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.65s ease;
            pointer-events: none;
        }

        .cslide.is-active {
            opacity: 1;
            pointer-events: all;
        }

        .cslide-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cslide-fallback {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        }

        .cslide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(105deg,
                    rgba(0, 0, 0, 0.82) 0%,
                    rgba(0, 0, 0, 0.55) 42%,
                    rgba(0, 0, 0, 0.18) 72%,
                    rgba(0, 0, 0, 0.04) 100%);
        }

        .cslide-content {
            position: absolute;
            top: 50%;
            left: 8%;
            transform: translateY(-50%);
            max-width: 600px;
            color: #fff;
            z-index: 2;
        }

        .cslide-tag {
            display: inline-flex;
            align-items: center;
            padding: 4px 11px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            background: var(--brand);
            color: #fff;
            margin-bottom: 16px;
        }

        .cslide-title {
            font-size: clamp(1.6rem, 3.5vw, 2.8rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 14px;
        }

        .cslide-excerpt {
            font-size: 0.98rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.72);
            margin-bottom: 24px;
            max-width: 52ch;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .cslide-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 24px;
            border-radius: var(--r);
            background: var(--white);
            color: var(--text);
            font-size: 0.875rem;
            font-weight: 700;
            transition: background 0.14s, transform 0.12s;
        }

        .cslide-cta:hover {
            background: #f0f2ff;
            color: var(--brand);
            transform: translateX(3px);
        }

        /* Carousel controls */
        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1.5px solid rgba(255, 255, 255, 0.22);
            color: #fff;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }

        .carousel-btn:hover {
            background: rgba(255, 255, 255, 0.28);
        }

        .carousel-btn.prev {
            left: 24px;
        }

        .carousel-btn.next {
            right: 24px;
        }

        /* Carousel dots */
        .carousel-dots {
            position: absolute;
            bottom: 22px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            display: flex;
            gap: 7px;
        }

        .cdot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            border: none;
            cursor: pointer;
            transition: background 0.15s, transform 0.15s;
            padding: 0;
        }

        .cdot.is-active {
            background: #fff;
            transform: scale(1.3);
        }

        /* Carousel progress bar */
        .carousel-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: var(--brand);
            z-index: 11;
            width: 0%;
            transition: width 0.1s linear;
        }

        /* ================================================
           EDITORIAL GRID  (top section: big + 2 stacked)
        ================================================ */
        .editorial-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .editorial-stack {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Big card (editorial-main) */
        .acard-big .acard-img {
            aspect-ratio: 16/9;
        }

        .acard-big .acard-body {
            padding: 22px 24px 24px;
        }

        .acard-big .acard-title {
            font-size: 1.22rem;
            -webkit-line-clamp: 3;
        }

        /* Small stacked cards */
        .acard-small {
            display: flex;
            flex-direction: row;
            align-items: stretch;
            min-height: 110px;
        }

        .acard-small .acard-img {
            width: 130px;
            min-height: 100%;
            flex-shrink: 0;
            border-radius: 0;
        }

        .acard-small .acard-img img {
            border-radius: 0;
        }

        .acard-small .acard-body {
            padding: 14px 16px;
            gap: 7px;
        }

        .acard-small .acard-title {
            font-size: 0.9rem;
            -webkit-line-clamp: 2;
        }

        .acard-small .acard-excerpt {
            display: none;
        }

        /* ================================================
           REGULAR 3-COLUMN GRID
        ================================================ */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .articles-grid .acard-img {
            aspect-ratio: 16/9;
        }

        /* 4-column override for latest news (12 articles) */
        .home-latest-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        @media (max-width: 1060px) {
            .home-latest-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }

        @media (max-width: 640px) {
            .home-latest-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 420px) {
            .home-latest-grid { grid-template-columns: 1fr; }
        }

        /* ================================================
           EMPTY STATE
        ================================================ */
        .empty-state {
            text-align: center;
            padding: 70px 32px;
            background: var(--bg-card);
            border: 1.5px dashed var(--border-2);
            border-radius: var(--r-xl);
            color: var(--muted);
            grid-column: 1 / -1;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 14px;
            opacity: 0.45;
        }

        .empty-state-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-2);
            margin-bottom: 8px;
        }

        .empty-state-desc {
            font-size: 0.875rem;
            max-width: 40ch;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* ================================================
           PAGINATION
        ================================================ */
        .pagination-wrap {
            padding-top: 44px;
            display: flex;
            justify-content: center;
        }

        .pagination-wrap nav>div:first-child {
            display: none;
        }

        .pagination-wrap nav>div:last-child {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-wrap span[aria-disabled],
        .pagination-wrap a {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 10px;
            border-radius: var(--r-sm);
            font-size: 0.84rem;
            font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--bg-card);
            color: var(--muted);
            transition: all 0.14s;
        }

        .pagination-wrap a:hover {
            border-color: var(--brand);
            color: var(--brand);
            background: var(--brand-bg);
        }

        .pagination-wrap span[aria-current="page"] span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 10px;
            border-radius: var(--r-sm);
            font-size: 0.84rem;
            font-weight: 700;
            background: var(--brand);
            border: 1.5px solid var(--brand);
            color: #fff;
        }

        /* ================================================
           ARTICLE SHOW PAGE
        ================================================ */
        .detail-wrap {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 28px;
            align-items: start;
            padding-top: 32px;
        }

        .article-panel {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--r-xl);
            overflow: hidden;
        }

        .article-hero {
            width: 100%;
            aspect-ratio: 21/9;
            max-height: 480px;
            object-fit: cover;
        }

        .article-hd {
            padding: 30px 34px 0;
        }

        .article-breadcrumb {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.79rem;
            color: var(--muted-2);
            margin-bottom: 18px;
        }

        .article-breadcrumb a {
            color: var(--muted);
            transition: color 0.14s;
        }

        .article-breadcrumb a:hover {
            color: var(--brand);
        }

        .article-breadcrumb-sep {
            opacity: 0.4;
        }

        .article-title {
            font-size: clamp(1.6rem, 3.2vw, 2.5rem);
            font-weight: 800;
            line-height: 1.18;
            letter-spacing: -0.03em;
            color: var(--text);
            margin: 14px 0 20px;
        }

        .article-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            padding: 14px 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            margin-bottom: 30px;
            font-size: 0.83rem;
            color: var(--muted);
        }

        .article-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .article-meta-sep {
            color: var(--border-2);
        }

        .article-meta a {
            color: var(--brand);
        }

        .article-bd {
            padding: 0 34px 34px;
        }

        /* Article content typography */
        .article-body {
            font-size: 1.03rem;
            line-height: 1.86;
            color: var(--text-2);
        }

        .article-body>*+* {
            margin-top: 1.25em;
        }

        .article-body h2,
        .article-body h3,
        .article-body h4 {
            color: var(--text);
            letter-spacing: -0.025em;
            margin-top: 2.2em;
            margin-bottom: 0.4em;
        }

        .article-body h2 {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .article-body h3 {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .article-body h4 {
            font-size: 1.05rem;
            font-weight: 700;
        }

        .article-body p {
            margin: 0;
        }

        .article-body a {
            color: var(--brand);
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .article-body a:hover {
            text-decoration: none;
        }

        .article-body ul,
        .article-body ol {
            padding-left: 1.6em;
        }

        .article-body li+li {
            margin-top: 0.4em;
        }

        .article-body strong {
            color: var(--text);
            font-weight: 700;
        }

        .article-body em {
            font-style: italic;
        }

        .article-body blockquote {
            border-left: 3px solid var(--brand);
            padding: 12px 20px;
            background: var(--brand-bg-2);
            border-radius: 0 var(--r-sm) var(--r-sm) 0;
            color: var(--text-2);
            font-style: italic;
        }

        .article-body code {
            background: #f4f6fc;
            border: 1px solid var(--border);
            padding: 2px 6px;
            border-radius: var(--r-xs);
            font-size: 0.88em;
            font-family: ui-monospace, 'Cascadia Code', Consolas, monospace;
            color: #c7254e;
        }

        .article-body pre {
            background: #1a1f2e;
            padding: 20px 22px;
            border-radius: var(--r);
            overflow-x: auto;
            color: #e2e8f0;
        }

        .article-body pre code {
            background: none;
            border: none;
            padding: 0;
            color: inherit;
        }

        .article-body img {
            width: 100%;
            border-radius: var(--r);
            border: 1px solid var(--border);
        }

        .article-body hr {
            border: none;
            border-top: 1px solid var(--border);
            margin: 2.5em 0;
        }

        .article-body table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        .article-body th,
        .article-body td {
            padding: 8px 12px;
            border: 1px solid var(--border);
            text-align: left;
        }

        .article-body th {
            background: var(--bg);
            font-weight: 700;
        }

        /* Review block */
        .review-block {
            margin-top: 30px;
            border: 1.5px solid rgba(251, 191, 36, 0.25);
            border-radius: var(--r-lg);
            overflow: hidden;
            background: var(--amber-bg);
        }

        .review-block-hd {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(251, 191, 36, 0.18);
            background: rgba(251, 191, 36, 0.06);
        }

        .review-label {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--amber);
        }

        .rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 999px;
            background: rgba(251, 191, 36, 0.15);
            border: 1.5px solid rgba(251, 191, 36, 0.3);
            font-weight: 800;
            font-size: 0.92rem;
            color: #92400e;
        }

        .review-block-bd {
            padding: 18px 20px;
        }

        .review-summary-text {
            color: var(--muted);
            line-height: 1.75;
            font-size: 0.91rem;
            margin-bottom: 16px;
        }

        .pros-cons-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 14px;
        }

        .pc-col-title {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 10px;
        }

        .pros .pc-col-title {
            color: var(--green);
        }

        .cons .pc-col-title {
            color: var(--red);
        }

        .pc-list {
            list-style: none;
            padding: 0;
        }

        .pc-list li {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            font-size: 0.87rem;
            color: var(--muted);
            padding: 5px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            line-height: 1.55;
        }

        .pc-list li:last-child {
            border-bottom: none;
        }

        .pc-icon {
            flex-shrink: 0;
            font-size: 0.75rem;
            margin-top: 3px;
            font-weight: 800;
        }

        .pros .pc-icon {
            color: var(--green);
        }

        .cons .pc-icon {
            color: var(--red);
        }

        .verdict-box {
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(0, 0, 0, 0.07);
            border-radius: var(--r);
            font-size: 0.88rem;
            line-height: 1.7;
            color: var(--text-2);
        }

        .verdict-box strong {
            color: var(--amber);
        }

        /* FAQ block */
        .faq-block {
            margin-top: 30px;
            border: 1.5px solid rgba(80, 70, 228, 0.15);
            border-radius: var(--r-lg);
            overflow: hidden;
        }

        .faq-block-hd {
            padding: 16px 20px;
            background: var(--brand-bg-2);
            border-bottom: 1px solid rgba(80, 70, 228, 0.1);
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .faq-hd-label {
            font-size: 0.73rem;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: var(--brand);
        }

        .faq-hd-title {
            font-size: 0.98rem;
            font-weight: 700;
            color: var(--text);
        }

        .faq-list {
            padding: 10px 16px 14px;
            display: grid;
            gap: 6px;
        }

        .faq-item {
            border: 1px solid var(--border);
            border-radius: var(--r);
            background: var(--bg-card);
            overflow: hidden;
        }

        .faq-q {
            padding: 13px 15px;
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--text-2);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            user-select: none;
            transition: background 0.12s;
        }

        .faq-q:hover {
            background: var(--bg-hover);
        }

        .faq-toggle {
            flex-shrink: 0;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--brand-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand);
            font-size: 14px;
            font-weight: 500;
            line-height: 1;
            transition: transform 0.22s, background 0.15s;
        }

        .faq-a {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-a-inner {
            padding: 2px 15px 14px;
            color: var(--muted);
            font-size: 0.875rem;
            line-height: 1.78;
        }

        .faq-item.open .faq-a {
            max-height: 600px;
        }

        .faq-item.open .faq-toggle {
            transform: rotate(45deg);
            background: var(--brand);
            color: #fff;
        }

        /* ================================================
           SIDEBAR
        ================================================ */
        .sidebar-sticky {
            position: sticky;
            top: calc(var(--nav-h) + var(--cat-h) + 3px + 20px);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .scard {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            overflow: hidden;
        }

        .scard-hd {
            padding: 12px 18px;
            border-bottom: 1px solid var(--border);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            background: var(--bg);
        }

        .scard-bd {
            padding: 14px 18px;
        }

        .info-row {
            display: flex;
            flex-direction: column;
            gap: 2px;
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.84rem;
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-row:first-child {
            padding-top: 0;
        }

        .info-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--muted-2);
        }

        .info-val {
            color: var(--text-2);
            font-weight: 600;
        }

        .info-val.brand {
            color: var(--brand);
        }

        .action-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 12px;
            border-radius: var(--r);
            border: 1.5px solid var(--border);
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-2);
            margin-bottom: 7px;
            transition: all 0.14s;
            gap: 8px;
        }

        .action-link:last-child {
            margin-bottom: 0;
        }

        .action-link:hover {
            border-color: var(--brand);
            background: var(--brand-bg);
            color: var(--brand);
        }

        .action-link-arr {
            opacity: 0.4;
            font-size: 0.85rem;
        }

        .related-item {
            display: flex;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
            color: inherit;
        }

        .related-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .related-item:first-child {
            padding-top: 0;
        }

        .related-thumb {
            width: 60px;
            height: 45px;
            object-fit: cover;
            border-radius: var(--r-sm);
            flex-shrink: 0;
            border: 1px solid var(--border);
        }

        .related-thumb-ph {
            width: 60px;
            height: 45px;
            border-radius: var(--r-sm);
            flex-shrink: 0;
            background: var(--bg);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: var(--muted-2);
        }

        .related-info {
            flex: 1;
            min-width: 0;
        }

        .related-title {
            font-size: 0.81rem;
            font-weight: 600;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: var(--text-2);
            transition: color 0.14s;
        }

        .related-item:hover .related-title {
            color: var(--brand);
        }

        .related-date {
            font-size: 0.74rem;
            color: var(--muted-2);
            margin-top: 3px;
        }

        /* ================================================
           READING PROGRESS BAR
        ================================================ */
        .reading-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--brand) 0%, #a78bfa 60%, #f472b6 100%);
            z-index: 9999;
            width: 0%;
            transition: width 0.05s linear;
        }

        /* ================================================
           SHARE BUTTONS
        ================================================ */
        .share-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 0;
            border-top: 1px solid var(--border);
            margin-top: 28px;
            flex-wrap: wrap;
        }

        .share-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--muted-2);
            margin-right: 4px;
        }

        .share-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: var(--r);
            font-size: 0.8rem;
            font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--bg-card);
            color: var(--text-2);
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }

        .share-btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .share-btn.twitter:hover {
            background: #1da1f2;
            border-color: #1da1f2;
            color: #fff;
        }

        .share-btn.linkedin:hover {
            background: #0a66c2;
            border-color: #0a66c2;
            color: #fff;
        }

        .share-btn.copy-link:hover {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
        }

        .share-btn.copied {
            background: var(--green);
            border-color: var(--green);
            color: #fff;
        }

        /* ================================================
           TRENDING BADGE
        ================================================ */
        .trending-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 3;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            background: rgba(229, 62, 62, 0.9);
            backdrop-filter: blur(4px);
            color: #fff;
        }

        /* ================================================
           NEWSLETTER STRIP
        ================================================ */
        .newsletter-strip {
            background: #000000;
            padding: 52px 0 48px;
        }

        .newsletter-inner {
            max-width: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
        }

        .newsletter-text-side {
            flex: 1;
            min-width: 0;
        }

        .newsletter-form-side {
            flex-shrink: 0;
        }

        .newsletter-heading {
            font-size: clamp(1.35rem, 2.4vw, 1.8rem);
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.02em;
            line-height: 1.25;
            margin-bottom: 0;
        }

        .newsletter-sub {
            display: none;
        }

        .newsletter-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .newsletter-input {
            width: 260px;
            padding: 11px 16px;
            border-radius: 8px;
            border: 1.5px solid #2a2a2a;
            background: #0a0a0a;
            color: #f9fafb;
            font-size: 0.9rem;
            outline: none;
            font-family: inherit;
            transition: border-color 0.15s;
        }

        .newsletter-input::placeholder {
            color: #555;
        }

        .newsletter-input:focus {
            border-color: #4f46e5;
        }

        .newsletter-btn {
            padding: 11px 24px;
            border-radius: 8px;
            background: #4f46e5;
            color: #fff;
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            white-space: nowrap;
            font-family: inherit;
            transition: background 0.15s;
        }

        .newsletter-btn:hover {
            background: #4338ca;
        }

        .newsletter-divider {
            border: none;
            border-top: 1px solid #1a1a1a;
            margin: 0;
        }

        @media (max-width: 700px) {
            .newsletter-inner {
                flex-direction: column;
                align-items: flex-start;
                gap: 24px;
            }

            .newsletter-form-side {
                width: 100%;
            }

            .newsletter-form {
                width: 100%;
            }

            .newsletter-input {
                flex: 1;
                width: auto;
            }
        }

        /* ================================================
           FOOTER
        ================================================ */
        .site-footer {
            background: #000000;
            color: #6b7280;
            border-top: 1px solid #1a1a1a;
            padding: 52px 0 28px;
            margin-top: 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.8fr 1fr 1fr 1fr;
            gap: 48px;
            padding-bottom: 40px;
            border-bottom: 1px solid #1a1a1a;
        }

        .footer-brand .footer-brand-name {
            font-size: 1.3rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            margin-bottom: 10px;
            line-height: 1;
        }

        .footer-brand-name .ld {
            color: #fff;
        }

        .footer-brand-name .ldrive {
            color: #7c74f0;
        }

        .footer-tagline {
            font-size: 0.83rem;
            color: #4b5563;
            line-height: 1.65;
            max-width: 32ch;
            margin-top: 6px;
            margin-bottom: 18px;
        }

        .footer-social-links {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-social-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #111;
            border: 1px solid #222;
            color: #9ca3af;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
            cursor: pointer;
        }

        .footer-social-btn:hover {
            background: #1f2937;
            color: #fff;
            border-color: #374151;
        }

        .footer-social-btn.ig:hover {
            background: #c13584;
            border-color: #c13584;
            color: #fff;
        }

        .footer-social-btn.fb:hover {
            background: #1877f2;
            border-color: #1877f2;
            color: #fff;
        }

        .footer-social-btn.tw:hover {
            background: #1d9bf0;
            border-color: #1d9bf0;
            color: #fff;
        }

        .footer-social-btn.yt:hover {
            background: #ff0000;
            border-color: #ff0000;
            color: #fff;
        }

        .footer-col-title {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #ffffff;
            margin-bottom: 18px;
        }

        .footer-col-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-col-links a,
        .footer-col-links span {
            font-size: 0.84rem;
            font-weight: 400;
            color: #6b7280;
            transition: color 0.14s, padding-left 0.14s;
            display: flex;
            align-items: flex-start;
            gap: 6px;
        }

        .footer-col-links a:hover {
            color: #d1d5db;
            padding-left: 3px;
        }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding-top: 22px;
            flex-wrap: wrap;
        }

        .footer-copy {
            font-size: 0.79rem;
            color: #374151;
        }

        .footer-bottom-links {
            display: flex;
            gap: 18px;
        }

        .footer-bottom-links a {
            font-size: 0.79rem;
            color: #374151;
            transition: color 0.14s;
        }

        .footer-bottom-links a:hover {
            color: #7c74f0;
        }

        .footer-ai-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(80, 70, 228, 0.12);
            border: 1px solid rgba(80, 70, 228, 0.2);
            font-size: 0.71rem;
            font-weight: 700;
            color: #7c74f0;
            letter-spacing: 0.04em;
        }

        @media (max-width: 900px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 32px;
            }
        }

        @media (max-width: 560px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 28px;
            }
        }

        /* ================================================
           RESPONSIVE
        ================================================ */
        @media (max-width: 1060px) {
            .articles-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .detail-wrap {
                grid-template-columns: 1fr;
            }

            .sidebar-sticky {
                position: static;
            }
        }

        @media (max-width: 820px) {
            .topnav-inner {
                grid-template-columns: auto 1fr auto;
            }

            .main-nav {
                gap: 0;
            }

            .main-nav-link {
                padding: 5px 9px;
                font-size: 0.82rem;
            }

            .editorial-grid {
                grid-template-columns: 1fr;
            }

            .acard-small {
                flex-direction: column;
                height: auto;
            }

            .acard-small .acard-img {
                width: 100%;
                height: 160px;
            }

            .acard-small .acard-excerpt {
                display: block;
            }

            .pros-cons-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .container {
                width: calc(100% - 28px);
            }

            .articles-grid {
                grid-template-columns: 1fr;
            }

            .topnav-inner {
                grid-template-columns: 1fr auto;
            }

            .main-nav {
                display: none;
            }

            .carousel-wrap {
                height: 320px;
            }

            .cslide-title {
                font-size: 1.4rem;
            }

            .cslide-excerpt {
                display: none;
            }

            .cslide-content {
                left: 5%;
                max-width: 90%;
            }

            .article-hd {
                padding: 22px 20px 0;
            }

            .article-bd {
                padding: 0 20px 24px;
            }
        }

        /* ================================================
           FEATURED HORIZONTAL CARD
        ================================================ */
        .featured-hcard {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--r-xl);
            overflow: hidden;
            min-height: 340px;
            transition: box-shadow 0.22s, border-color 0.22s;
            color: inherit;
            position: relative;
        }

        .featured-hcard:hover {
            box-shadow: 0 16px 50px rgba(80, 70, 228, 0.12), var(--shadow-lg);
            border-color: rgba(80, 70, 228, 0.25);
        }

        .fhcard-img {
            position: relative;
            overflow: hidden;
            background: var(--bg);
        }

        .fhcard-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.48s ease;
        }

        .featured-hcard:hover .fhcard-img img {
            transform: scale(1.04);
        }

        .fhcard-img-ph {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            background: linear-gradient(135deg, #eef2ff 0%, #f3f2ff 100%);
            color: var(--border-2);
            min-height: 340px;
        }

        .fhcard-body {
            padding: 30px 32px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .fhcard-tags {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .fhcard-title {
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1.22;
            letter-spacing: -0.03em;
            color: var(--text);
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.14s;
        }

        .featured-hcard:hover .fhcard-title {
            color: var(--brand);
        }

        .fhcard-excerpt {
            font-size: 0.9rem;
            color: var(--muted);
            line-height: 1.72;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }

        .fhcard-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
        }

        .fhcard-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--muted-2);
            font-size: 0.81rem;
        }

        .fhcard-meta-sep {
            opacity: 0.4;
        }

        .fhcard-cta {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 18px;
            border-radius: var(--r);
            background: var(--brand);
            color: #fff;
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
            transition: background 0.14s, gap 0.14s;
        }

        .featured-hcard:hover .fhcard-cta {
            background: var(--brand-2);
            gap: 11px;
        }

        /* ================================================
           LATEST NEWS SECTION
        ================================================ */
        .latest-section {
            padding-top: 48px;
        }

        /* ================================================
           SOURCE CATEGORY STRIPS (full-width)
        ================================================ */
        .source-strip {
            padding: 44px 0 48px;
            border-top: 1px solid var(--border);
        }

        .source-strip-alt {
            background: var(--bg);
        }

        .source-strip-hd {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }

        .source-name-block {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .source-accent-line {
            width: 4px;
            height: 26px;
            border-radius: 3px;
            background: linear-gradient(180deg, var(--brand) 0%, var(--brand-2) 100%);
            flex-shrink: 0;
        }

        .source-title {
            font-size: 1.18rem;
            font-weight: 800;
            letter-spacing: -0.028em;
            color: var(--text);
        }

        .source-count-badge {
            display: inline-flex;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 0.71rem;
            font-weight: 700;
            background: var(--bg-hover);
            color: var(--muted);
            border: 1px solid var(--border);
        }

        .source-view-all {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--brand);
            padding: 6px 14px;
            border-radius: var(--r);
            border: 1.5px solid rgba(80, 70, 228, 0.22);
            transition: all 0.15s;
            white-space: nowrap;
        }

        .source-view-all:hover {
            background: var(--brand-bg);
            border-color: var(--brand);
            gap: 9px;
        }

        /* 4-column source grid */
        .source-4grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        /* Mini card */
        .acard-mini .acard-img {
            aspect-ratio: 16/9;
        }

        .acard-mini .acard-title {
            font-size: 0.875rem;
            -webkit-line-clamp: 2;
        }

        .acard-mini .acard-excerpt {
            display: none;
        }

        .acard-mini .acard-body {
            padding: 12px 14px;
            gap: 8px;
        }

        .acard-mini .acard-foot {
            font-size: 0.76rem;
            padding-top: 9px;
        }

        /* ================================================
           CATEGORY FILTER PAGE — filter bar
        ================================================ */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            padding: 14px 18px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--r-xl);
            margin-bottom: 28px;
        }

        .filter-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--muted-2);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding-right: 10px;
            border-right: 1px solid var(--border-2);
            margin-right: 4px;
            white-space: nowrap;
        }

        /* ================================================
           CATEGORY CARDS (below hero banner)
        ================================================ */
        .cat-cards-wrap {
            padding: 28px 0 0;
        }

        .cat-cards-row {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            scrollbar-width: none;
            padding-bottom: 4px;
        }

        .cat-cards-row::-webkit-scrollbar {
            display: none;
        }

        .cat-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 18px 18px 14px;
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--r-lg);
            min-width: 106px;
            flex-shrink: 0;
            text-decoration: none;
            color: var(--text-2);
            transition: all 0.16s;
            box-shadow: var(--shadow-xs);
        }

        .cat-card:hover {
            border-color: var(--brand);
            color: var(--brand);
            background: var(--brand-bg-2);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(80, 70, 228, 0.13);
        }

        .cat-card.is-active {
            border-color: var(--brand);
            background: var(--brand-bg);
            color: var(--brand);
        }

        .cat-card-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: var(--r);
            background: var(--bg);
            transition: background 0.15s;
        }

        .cat-card:hover .cat-card-icon,
        .cat-card.is-active .cat-card-icon {
            background: rgba(80, 70, 228, 0.1);
        }

        .cat-card-icon svg {
            width: 20px;
            height: 20px;
        }

        .cat-card-name {
            font-size: 0.76rem;
            font-weight: 700;
            text-align: center;
            line-height: 1.3;
        }

        .cat-card-count {
            font-size: 0.68rem;
            color: var(--muted-2);
            font-weight: 600;
        }

        .cat-card:hover .cat-card-count,
        .cat-card.is-active .cat-card-count {
            color: var(--brand-2);
        }

        /* ================================================
           RESPONSIVE EXTRAS
        ================================================ */
        @media (max-width: 1060px) {
            .source-4grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .featured-hcard {
                grid-template-columns: 1fr;
            }

            .fhcard-img {
                min-height: 240px;
            }
        }

        @media (max-width: 640px) {
            .source-4grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .source-strip {
                padding: 28px 0 30px;
            }

            .fhcard-body {
                padding: 20px 18px;
            }

            .fhcard-title {
                font-size: 1.2rem;
            }

            .fhcard-cta {
                display: none;
            }
        }

        @media (max-width: 420px) {
            .source-4grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── Bookmark button on cards ── */
        .acard { position: relative; }
        .acard-bookmark {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255,255,255,0.92);
            border: 1px solid rgba(0,0,0,0.1);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--muted);
            transition: all 0.15s;
            box-shadow: 0 1px 4px rgba(0,0,0,0.12);
            z-index: 2;
        }
        .acard-bookmark:hover { background: #fff; color: var(--brand); border-color: var(--brand); transform: scale(1.08); }
        .acard-bookmark.bookmarked { background: var(--brand); color: #fff; border-color: var(--brand); }
        .acard-bookmark.bookmarked svg { fill: #fff; stroke: #fff; }

        /* ── Toast notification ── */
        .eng-toast {
            position: fixed;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: #1a1a2e;
            color: #fff;
            padding: 10px 20px;
            border-radius: 999px;
            font-size: 0.83rem;
            font-weight: 600;
            white-space: nowrap;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.22s, transform 0.22s;
            pointer-events: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
        }
        .eng-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

        /* ── Bookmark panel ── */
        .bm-panel {
            position: fixed;
            top: 0; right: 0; bottom: 0;
            width: min(340px, 90vw);
            background: var(--bg-card);
            border-left: 1px solid var(--border);
            box-shadow: -8px 0 32px rgba(0,0,0,0.12);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.28s cubic-bezier(.4,0,.2,1);
        }
        .bm-panel.open { transform: translateX(0); }
        .bm-panel-hd {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
            border-bottom: 1px solid var(--border);
            font-weight: 800;
            font-size: 0.95rem;
        }
        .bm-panel-close {
            width: 28px; height: 28px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: var(--bg);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: var(--muted);
        }
        .bm-panel-close:hover { background: var(--bg-hover); color: var(--text); }
        .bm-panel-list { flex: 1; overflow-y: auto; padding: 8px 0; }
        .bm-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
        }
        .bm-item:hover { background: var(--bg-hover); }
        .bm-item-img {
            width: 52px; height: 40px;
            border-radius: 6px;
            object-fit: cover;
            flex-shrink: 0;
            background: var(--bg);
        }
        .bm-item-info { flex: 1; min-width: 0; }
        .bm-item-title {
            font-size: 0.81rem;
            font-weight: 600;
            color: var(--text-2);
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .bm-item-cat { font-size: 0.7rem; color: var(--muted); margin-top: 2px; }
        .bm-item-rm {
            flex-shrink: 0;
            background: none; border: none;
            color: var(--muted-2);
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            font-size: 0.85rem;
            transition: color 0.12s;
        }
        .bm-item-rm:hover { color: var(--red); }
        .bm-empty {
            text-align: center;
            padding: 48px 24px;
            color: var(--muted);
            font-size: 0.84rem;
        }
        .bm-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.35);
            z-index: 999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.28s;
        }
        .bm-overlay.open { opacity: 1; pointer-events: all; }

        /* Bookmark nav badge */
        .bm-nav-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 10px;
            border-radius: var(--r-sm);
            border: 1px solid var(--border);
            background: var(--bg-card);
            color: var(--muted);
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.13s;
        }
        .bm-nav-btn:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-bg); }
        .bm-badge {
            position: absolute;
            top: -5px; right: -5px;
            min-width: 16px; height: 16px;
            padding: 0 4px;
            background: var(--brand);
            color: #fff;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 800;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .bm-badge.has-items { display: flex; }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    {{-- Reading progress bar (only on article pages) --}}
    @hasSection('seo_title')
        <div id="reading-progress" class="reading-progress"></div>
    @endif

    {{-- Accent top bar --}}
    <div class="accent-topbar"></div>

    {{-- ============================================================
         NAVBAR ROW 1 — Logo | Nav Links | Search + Subscribe
    ============================================================ --}}
    {{-- @php
        $navIcons = [
            'Technology'             => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/></svg>',
            'Artificial Intelligence'=> '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
            'Business'               => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>',
            'Security'               => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
            'Science'                => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3h6v11l3.5 6H5.5L9 14V3z"/><line x1="6" y1="3" x2="18" y2="3"/></svg>',
            'Environment'            => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8C8 10 5.9 16.17 3.82 19.07L8 17"/><circle cx="12" cy="12" r="10"/></svg>',
            'Health'                 => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
            'Gaming'                 => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/><rect x="2" y="6" width="20" height="12" rx="2"/></svg>',
            'Policy'                 => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
            'Other'                  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>',
        ];
        $topNavCats = \App\Models\Article::whereNotNull('ai_generated_at')
            ->whereNotNull('category')
            ->select('category', \Illuminate\Support\Facades\DB::raw('count(*) as cnt'))
            ->groupBy('category')
            ->orderByDesc('cnt')
            ->limit(4)
            ->pluck('category');
    @endphp --}}
    <header class="topnav">
        <div class="container">
            <div class="topnav-inner">

                {{-- Logo --}}
                <a class="site-logo" href="{{ route('home') }}">
                    <span class="logo-daily">DAILY</span><span class="logo-dot"></span><span
                        class="logo-drive">dRIVE</span>
                </a>

                {{-- Center nav: text links --}}
                <nav class="main-nav" aria-label="Main navigation">
                    <a href="{{ route('home') }}"
                        class="main-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                    <a href="{{ route('articles.index') }}"
                        class="main-nav-link {{ request()->routeIs('articles.*') ? 'is-active' : '' }}">Browse Articles</a>
                    <a href="{{ route('about') }}"
                        class="main-nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}">About Us</a>
                </nav>

                {{-- Right: Search + Subscribe --}}
                <div class="nav-right">
                    {{-- Bookmarks button --}}
                    <button class="bm-nav-btn" onclick="openBookmarks()" title="Saved articles">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                        Saved
                        <span class="bm-badge" id="bm-nav-badge"></span>
                    </button>
                    <button class="nav-search-btn" id="search-open" aria-label="Search articles">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                    </button>
                    <button class="btn-subscribe"
                        onclick="document.getElementById('newsletter').scrollIntoView({behavior:'smooth'})">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        Subscribe Free
                    </button>
                </div>

            </div>
        </div>
    </header>

    {{-- ============================================================
         CATEGORY BAR (horizontal scroll of popular categories)
    ============================================================ --}}
    {{-- @php
        $catList = \App\Models\Article::whereNotNull('ai_generated_at')
            ->whereNotNull('category')
            ->select('category', \Illuminate\Support\Facades\DB::raw('count(*) as cnt'))
            ->groupBy('category')
            ->orderByDesc('cnt')
            ->pluck('category');
        $currentCategory = request('category');
    @endphp
    <div class="catbar">
        <div class="container">
            <div class="catbar-inner">
                <div class="catbar-label">Explore</div>
                <a href="{{ route('home') }}" class="cat-pill {{ !$currentCategory ? 'is-active' : '' }}">
                    <span class="cat-pill-dot"></span>All
                </a>
                @foreach ($catList as $c)
                    <a href="{{ route('home', ['category' => $c]) }}" class="cat-pill {{ $currentCategory === $c ? 'is-active' : '' }}">
                        <span class="cat-pill-dot"></span>{{ $c }}
                    </a>
                @endforeach
                <div class="catbar-sep"></div>
                <a href="{{ route('articles.index') }}" class="cat-pill">All Articles</a>
            </div>
        </div>
    </div> --}}

    {{-- ============================================================
         MAIN CONTENT
    ============================================================ --}}
    <main class="site-main">
        @yield('content')
    </main>

    {{-- ============================================================
         NEWSLETTER STRIP
    ============================================================ --}}
    <div class="newsletter-strip" id="newsletter">
        <div class="container">
            <div class="newsletter-inner">
                <div class="newsletter-text-side">
                    <h2 class="newsletter-heading">Stay Updated on Pakistan’s Tech Scene
                    </h2>
                    <p style="color: white; ">Fast news, deep insights, and the next big opportunities in tech.
</p>
                </div>
                <div class="newsletter-form-side">
                    @if (session('newsletter_status') === 'subscribed')
                        <div
                            style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:#111; border:1.5px solid #222; border-radius:8px; color:#d1fae5; font-size:0.875rem; font-weight:600;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#34d399"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            You're subscribed!
                        </div>
                    @elseif (session('newsletter_status') === 'already_subscribed')
                        <div
                            style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:#111; border:1.5px solid #222; border-radius:8px; color:#9ca3af; font-size:0.875rem; font-weight:600;">
                            Already subscribed — check your inbox!
                        </div>
                    @else
                        <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                            @csrf
                            <input class="newsletter-input" type="email" name="email"
                                placeholder="Email address" required>
                            <button class="newsletter-btn" type="submit">Subscribe</button>
                        </form>
                        @error('email')
                            <div style="margin-top:8px; font-size:0.8rem; color:#f87171;">{{ $message }}</div>
                        @enderror
                    @endif
                </div>
            </div>
        </div>
    </div>
    <hr class="newsletter-divider">

    {{-- ============================================================
         FOOTER
    ============================================================ --}}
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="footer-brand-name">
                        <span class="ld">DAILY</span><span class="ldrive">dRIVE</span>
                    </div>
                    <p class="footer-tagline">Delivering trusted news, valuable insights, and fresh perspectives every
                        day.</p>
                    <div class="footer-social-links">
                        <a href="#" class="footer-social-btn ig" aria-label="Instagram">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" />
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                            </svg>
                        </a>
                        <a href="#" class="footer-social-btn fb" aria-label="Facebook">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                        </a>
                        <a href="#" class="footer-social-btn tw" aria-label="Twitter / X">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                        <a href="#" class="footer-social-btn yt" aria-label="YouTube">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z" />
                                <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#000" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div>
                    <div class="footer-col-title">On Our Site</div>
                    <nav class="footer-col-links">
                        <a href="{{ route('about') }}">About</a>
                        <a href="{{ route('articles.index') }}">Articles</a>
                        <a href="#newsletter">Subscribe</a>
                    </nav>
                </div>
                <div>
                    <div class="footer-col-title">Resources</div>
                    <nav class="footer-col-links">
                        <a href="{{ route('articles.index', ['category' => 'Artificial Intelligence']) }}">AI News</a>
                        <a href="{{ route('articles.index', ['category' => 'Technology']) }}">Technology</a>
                        <a href="{{ route('privacy') }}">Privacy Policy</a>
                        <a href="{{ route('terms') }}">Terms of Use</a>
                    </nav>
                </div>
                <div>
                    <div class="footer-col-title">Contact</div>
                    <div class="footer-col-links">
                        <span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" style="flex-shrink:0;margin-top:2px">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                            hello@dailydrive.com
                        </span>
                        {{-- <a href="/admin">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" style="flex-shrink:0;margin-top:2px">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <path d="M3 9h18M9 21V9" />
                            </svg>
                            Admin Panel
                        </a> --}}
                        {{-- <a href="/admin/news-sources">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" style="flex-shrink:0;margin-top:2px">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="2" y1="12" x2="22" y2="12" />
                                <path
                                    d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                            </svg>
                            News Sources
                        </a> --}}
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <span class="footer-copy">&copy; {{ date('Y') }} DAILYdRIVE. All rights reserved.</span>
                <nav class="footer-bottom-links">
                    <a href="{{ route('privacy') }}">Privacy</a>
                    <a href="{{ route('terms') }}">Terms</a>
                </nav>
            </div>
        </div>
    </footer>

    {{-- ============================================================
         SEARCH OVERLAY
    ============================================================ --}}
    <div class="search-overlay" id="search-overlay" role="dialog" aria-modal="true" aria-label="Search">
        <form class="search-overlay-box" action="{{ route('articles.index') }}" method="GET">
            <span class="search-icon-wrap">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
            </span>
            <input class="search-overlay-input" id="search-input" type="text" name="search"
                value="{{ request('search') }}" placeholder="Search articles, topics, categories…"
                autocomplete="off">
            <button class="search-overlay-submit" type="submit">
                Search
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <polyline points="12 5 19 12 12 19" />
                </svg>
            </button>
            <button type="button" class="search-overlay-close" id="search-close" aria-label="Close search">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </form>
    </div>

    {{-- ============================================================
         JAVASCRIPT
    ============================================================ --}}
    <script>
        // ---- FAQ ACCORDION ----
        document.querySelectorAll('.faq-q').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var item = btn.closest('.faq-item');
                var isOpen = item.classList.contains('open');
                document.querySelectorAll('.faq-item.open').forEach(function(i) {
                    i.classList.remove('open');
                });
                if (!isOpen) item.classList.add('open');
            });
        });

        // ---- HERO CAROUSEL ----
        (function() {
            var slides = document.querySelectorAll('.cslide');
            var dots = document.querySelectorAll('.cdot');
            var bar = document.querySelector('.carousel-progress');
            if (!slides.length) return;

            var current = 0;
            var timer = null;
            var INTERVAL = 5000;
            var startTime = null;
            var rafId = null;

            function goTo(idx) {
                slides[current].classList.remove('is-active');
                if (dots[current]) dots[current].classList.remove('is-active');
                current = (idx + slides.length) % slides.length;
                slides[current].classList.add('is-active');
                if (dots[current]) dots[current].classList.add('is-active');
                resetTimer();
            }

            function tick(ts) {
                if (!startTime) startTime = ts;
                var pct = Math.min(((ts - startTime) / INTERVAL) * 100, 100);
                if (bar) bar.style.width = pct + '%';
                if (pct < 100) {
                    rafId = requestAnimationFrame(tick);
                } else {
                    goTo(current + 1);
                }
            }

            function resetTimer() {
                cancelAnimationFrame(rafId);
                startTime = null;
                rafId = requestAnimationFrame(tick);
            }

            var prevBtn = document.querySelector('.carousel-btn.prev');
            var nextBtn = document.querySelector('.carousel-btn.next');
            if (prevBtn) prevBtn.addEventListener('click', function() {
                goTo(current - 1);
            });
            if (nextBtn) nextBtn.addEventListener('click', function() {
                goTo(current + 1);
            });
            dots.forEach(function(dot, i) {
                dot.addEventListener('click', function() {
                    goTo(i);
                });
            });

            resetTimer();
        })();

        // ---- SEARCH OVERLAY ----
        (function() {
            var overlay = document.getElementById('search-overlay');
            var openBtn = document.getElementById('search-open');
            var closeBtn = document.getElementById('search-close');
            var input = document.getElementById('search-input');
            if (!overlay || !openBtn) return;

            function open() {
                overlay.classList.add('is-open');
                document.body.style.overflow = 'hidden';
                if (input) setTimeout(function() {
                    input.focus();
                }, 60);
            }

            function close() {
                overlay.classList.remove('is-open');
                document.body.style.overflow = '';
            }

            openBtn.addEventListener('click', open);
            if (closeBtn) closeBtn.addEventListener('click', close);
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) close();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') close();
            });
        })();

        // ---- NAVBAR SHADOW ON SCROLL ----
        (function() {
            var nav = document.querySelector('.topnav');
            if (!nav) return;
            window.addEventListener('scroll', function() {
                if (window.scrollY > 4) {
                    nav.style.boxShadow = '0 2px 16px rgba(0,0,0,0.10)';
                } else {
                    nav.style.boxShadow = '';
                }
            }, {
                passive: true
            });
        })();

        // ---- READING PROGRESS BAR ----
        (function() {
            var bar = document.getElementById('reading-progress');
            if (!bar) return;
            window.addEventListener('scroll', function() {
                var doc = document.documentElement;
                var scrolled = doc.scrollTop || document.body.scrollTop;
                var total = doc.scrollHeight - doc.clientHeight;
                bar.style.width = (total > 0 ? (scrolled / total) * 100 : 0) + '%';
            }, {
                passive: true
            });
        })();

        // ---- COPY LINK SHARE BUTTON ----
        document.querySelectorAll('.share-btn.copy-link').forEach(function(btn) {
            btn.addEventListener('click', function() {
                navigator.clipboard.writeText(window.location.href).then(function() {
                    btn.classList.add('copied');
                    btn.textContent = '✓ Copied!';
                    setTimeout(function() {
                        btn.classList.remove('copied');
                        btn.innerHTML =
                            '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg> Copy Link';
                    }, 2200);
                });
            });
        });
    </script>

    {{-- ═══════════════════════════════════════════
         BOOKMARK PANEL
    ═══════════════════════════════════════════ --}}
    <div class="bm-overlay" id="bm-overlay" onclick="closeBookmarks()"></div>
    <div class="bm-panel" id="bm-panel">
        <div class="bm-panel-hd">
            <span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px;margin-right:5px;"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                Saved Articles
            </span>
            <button class="bm-panel-close" onclick="closeBookmarks()">&#x2715;</button>
        </div>
        <div class="bm-panel-list" id="bm-panel-list"></div>
    </div>

    {{-- ═══════════════════════════════════════════
         TOAST
    ═══════════════════════════════════════════ --}}
    <div class="eng-toast" id="eng-toast"></div>

    <script>
    // ─── TOAST ───────────────────────────────────
    function showToast(msg) {
        var t = document.getElementById('eng-toast');
        t.textContent = msg;
        t.classList.add('show');
        clearTimeout(t._timer);
        t._timer = setTimeout(function() { t.classList.remove('show'); }, 2400);
    }

    // ─── BOOKMARKS ───────────────────────────────
    function getBookmarks() {
        try { return JSON.parse(localStorage.getItem('dd_bookmarks') || '[]'); }
        catch(e) { return []; }
    }
    function saveBookmarks(list) {
        localStorage.setItem('dd_bookmarks', JSON.stringify(list));
    }
    function isBookmarked(slug) {
        return getBookmarks().some(function(b) { return b.slug === slug; });
    }

    function toggleBookmark(evt, slug, title, image, category) {
        if (evt) evt.preventDefault();
        var list = getBookmarks();
        var idx  = list.findIndex(function(b) { return b.slug === slug; });
        if (idx === -1) {
            list.unshift({ slug: slug, title: title, image: image, category: category });
            saveBookmarks(list);
            showToast('✓ Saved to bookmarks');
        } else {
            list.splice(idx, 1);
            saveBookmarks(list);
            showToast('Removed from bookmarks');
        }
        syncBookmarkButtons(slug);
        updateBadge();
        renderBookmarkPanel();
    }

    function syncBookmarkButtons(slug) {
        var on = isBookmarked(slug);
        document.querySelectorAll('[data-slug="' + slug + '"]').forEach(function(btn) {
            btn.classList.toggle('bookmarked', on);
        });
    }

    function updateBadge() {
        var count = getBookmarks().length;
        var badge = document.getElementById('bm-nav-badge');
        if (!badge) return;
        badge.textContent = count > 99 ? '99+' : count;
        badge.classList.toggle('has-items', count > 0);
    }

    function renderBookmarkPanel() {
        var list = getBookmarks();
        var el   = document.getElementById('bm-panel-list');
        if (!el) return;
        if (list.length === 0) {
            el.innerHTML = '<div class="bm-empty">No saved articles yet.<br>Tap the bookmark icon on any article.</div>';
            return;
        }
        el.innerHTML = list.map(function(b) {
            var imgHtml = b.image
                ? '<img class="bm-item-img" src="' + b.image + '" alt="" loading="lazy">'
                : '<div class="bm-item-img" style="background:var(--bg-hover);display:flex;align-items:center;justify-content:center;font-size:1.2rem;">📰</div>';
            return '<div class="bm-item">' +
                '<a href="/articles/' + b.slug + '" style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;" onclick="closeBookmarks()">' +
                imgHtml +
                '<div class="bm-item-info"><div class="bm-item-title">' + b.title + '</div>' +
                '<div class="bm-item-cat">' + (b.category || '') + '</div></div></a>' +
                '<button class="bm-item-rm" onclick="toggleBookmark(null,\'' + b.slug + '\',\'' + b.title.replace(/'/g,"\\'") + '\',\'' + (b.image||'').replace(/'/g,"\\'") + '\',\'' + (b.category||'') + '\')" title="Remove">&#x2715;</button>' +
                '</div>';
        }).join('');
    }

    function openBookmarks() {
        renderBookmarkPanel();
        document.getElementById('bm-panel').classList.add('open');
        document.getElementById('bm-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeBookmarks() {
        document.getElementById('bm-panel').classList.remove('open');
        document.getElementById('bm-overlay').classList.remove('open');
        document.body.style.overflow = '';
    }

    // ─── INIT on page load ───────────────────────
    document.addEventListener('DOMContentLoaded', function() {
        updateBadge();
        // Set bookmarked state for all bookmark buttons on page
        document.querySelectorAll('[data-slug]').forEach(function(btn) {
            if (isBookmarked(btn.dataset.slug)) btn.classList.add('bookmarked');
        });
    });
    </script>

    @yield('page_scripts')

</body>

</html>
