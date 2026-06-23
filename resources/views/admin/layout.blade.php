<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — DAILYdRIVE Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    <style>
        /* ========================================================
           TOKENS
        ======================================================== */
        :root {
            --bg:         #0c1220;
            --bg-2:       #111827;
            --panel:      #161f32;
            --panel-2:    #1c2740;
            --border:     rgba(148,163,184,0.12);
            --border-2:   rgba(148,163,184,0.22);

            --text:       #f1f5f9;
            --text-2:     #cbd5e1;
            --muted:      #8898aa;
            --muted-2:    #506070;

            --brand:      #6366f1;
            --brand-2:    #818cf8;
            --brand-bg:   rgba(99,102,241,0.12);
            --brand-glow: rgba(99,102,241,0.25);

            --green:      #10b981;
            --green-bg:   rgba(16,185,129,0.12);
            --red:        #f43f5e;
            --red-bg:     rgba(244,63,94,0.12);
            --amber:      #f59e0b;
            --amber-bg:   rgba(245,158,11,0.12);
            --sky:        #38bdf8;
            --sky-bg:     rgba(56,189,248,0.12);

            --sidebar-w:  240px;
            --topbar-h:   60px;
            --r-sm: 8px;
            --r:    12px;
            --r-lg: 16px;
            --r-xl: 20px;
        }

        /* ========================================================
           RESET & BASE
        ======================================================== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }
        input, select, textarea, button { font-family: inherit; font-size: inherit; }

        /* ========================================================
           LAYOUT SHELL
        ======================================================== */
        .admin-shell {
            display: grid;
            grid-template-columns: var(--sidebar-w) 1fr;
            grid-template-rows: auto 1fr;
            min-height: 100vh;
        }

        /* ========================================================
           SIDEBAR
        ======================================================== */
        .admin-sidebar {
            grid-row: 1 / 3;
            background: var(--panel);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--border-2) transparent;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .sidebar-logo {
            font-size: 1.1rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 1;
        }
        .sidebar-logo .ld  { color: var(--text); }
        .sidebar-logo .ldrive { color: var(--brand-2); }
        .sidebar-badge {
            display: inline-flex;
            align-items: center;
            margin-top: 6px;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 0.64rem;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            background: var(--brand-bg);
            color: var(--brand-2);
            border: 1px solid rgba(99,102,241,0.2);
        }

        .sidebar-nav {
            padding: 14px 12px;
            flex: 1;
        }
        .sidebar-nav-label {
            font-size: 0.63rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted-2);
            padding: 10px 10px 7px;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--r);
            font-size: 0.86rem;
            font-weight: 500;
            color: var(--muted);
            transition: all 0.14s;
            margin-bottom: 2px;
        }
        .sidebar-link svg { flex-shrink: 0; opacity: 0.7; }
        .sidebar-link:hover {
            background: var(--panel-2);
            color: var(--text-2);
        }
        .sidebar-link:hover svg { opacity: 1; }
        .sidebar-link.active {
            background: var(--brand-bg);
            color: var(--brand-2);
            font-weight: 600;
        }
        .sidebar-link.active svg { opacity: 1; color: var(--brand-2); }

        .sidebar-footer {
            padding: 14px 12px;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }

        /* ========================================================
           TOP BAR
        ======================================================== */
        .admin-topbar {
            background: var(--panel);
            border-bottom: 1px solid var(--border);
            height: var(--topbar-h);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-page-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
        }
        .topbar-breadcrumb {
            font-size: 0.78rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .topbar-breadcrumb span { opacity: 0.4; }

        .topbar-right { display: flex; align-items: center; gap: 10px; }
        .topbar-site-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: var(--r);
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--muted);
            border: 1px solid var(--border);
            transition: all 0.14s;
        }
        .topbar-site-link:hover { border-color: var(--border-2); color: var(--text-2); }

        /* ========================================================
           MAIN CONTENT AREA
        ======================================================== */
        .admin-content {
            padding: 28px;
            min-width: 0;
        }

        /* ========================================================
           PAGE HEADER
        ======================================================== */
        .page-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .page-head-left h1 {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text);
            line-height: 1.2;
        }
        .page-head-left p {
            font-size: 0.82rem;
            color: var(--muted);
            margin-top: 4px;
        }
        .page-head-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

        /* ========================================================
           FLASH MESSAGES
        ======================================================== */
        .flash {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: var(--r);
            margin-bottom: 20px;
            font-size: 0.86rem;
            font-weight: 500;
        }
        .flash-success { background: var(--green-bg); border: 1px solid rgba(16,185,129,0.25); color: #34d399; }
        .flash-error   { background: var(--red-bg);   border: 1px solid rgba(244,63,94,0.25);  color: #fb7185; }
        .flash-info    { background: var(--brand-bg);  border: 1px solid rgba(99,102,241,0.25); color: var(--brand-2); }

        /* ========================================================
           CARDS / PANELS
        ======================================================== */
        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--r-xl);
            overflow: hidden;
        }
        .card-hd {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            gap: 12px;
        }
        .card-hd-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-bd { padding: 20px; }

        /* ========================================================
           STAT CARDS
        ======================================================== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--r-xl);
            padding: 20px;
            position: relative;
            overflow: hidden;
            transition: border-color 0.15s;
        }
        .stat-card:hover { border-color: var(--border-2); }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
        }
        .stat-card.indigo::before { background: linear-gradient(90deg, #6366f1, #818cf8); }
        .stat-card.green::before  { background: linear-gradient(90deg, #10b981, #34d399); }
        .stat-card.amber::before  { background: linear-gradient(90deg, #f59e0b, #fcd34d); }
        .stat-card.sky::before    { background: linear-gradient(90deg, #38bdf8, #7dd3fc); }

        .stat-icon {
            width: 38px; height: 38px;
            border-radius: var(--r);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 14px;
            flex-shrink: 0;
        }
        .stat-card.indigo .stat-icon { background: var(--brand-bg); color: var(--brand-2); }
        .stat-card.green  .stat-icon { background: var(--green-bg); color: var(--green); }
        .stat-card.amber  .stat-icon { background: var(--amber-bg); color: var(--amber); }
        .stat-card.sky    .stat-icon { background: var(--sky-bg);   color: var(--sky); }

        .stat-val {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: var(--text);
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-label {
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--muted);
        }

        /* ========================================================
           DATA TABLE
        ======================================================== */
        .dt-wrap {
            overflow-x: auto;
        }
        .dt-search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
        }
        .dt-search-input {
            flex: 1;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: 8px 12px 8px 36px;
            color: var(--text);
            font-size: 0.84rem;
            outline: none;
            transition: border-color 0.14s;
            max-width: 320px;
            position: relative;
        }
        .dt-search-input:focus { border-color: var(--brand); }
        .dt-search-wrap {
            position: relative;
            flex: 1;
            max-width: 320px;
        }
        .dt-search-wrap svg {
            position: absolute;
            left: 11px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted-2);
            pointer-events: none;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.84rem;
        }
        .data-table thead tr {
            background: rgba(148,163,184,0.04);
        }
        .data-table th {
            padding: 11px 16px;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .data-table td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            color: var(--text-2);
        }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: rgba(148,163,184,0.03); }

        .dt-cell-primary { color: var(--text); font-weight: 600; }
        .dt-cell-muted { font-size: 0.76rem; color: var(--muted); margin-top: 2px; }
        .dt-cell-link { color: var(--text); font-weight: 600; }
        .dt-cell-link:hover { color: var(--brand-2); }

        /* Status badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }
        .badge::before {
            content: '';
            width: 5px; height: 5px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .badge-green  { background: var(--green-bg);  color: #34d399; border: 1px solid rgba(16,185,129,0.2); }
        .badge-green::before  { background: var(--green); }
        .badge-amber  { background: var(--amber-bg);  color: #fcd34d; border: 1px solid rgba(245,158,11,0.2); }
        .badge-amber::before  { background: var(--amber); }
        .badge-red    { background: var(--red-bg);    color: #fb7185; border: 1px solid rgba(244,63,94,0.2); }
        .badge-red::before    { background: var(--red); }
        .badge-indigo { background: var(--brand-bg);  color: var(--brand-2); border: 1px solid rgba(99,102,241,0.2); }
        .badge-indigo::before { background: var(--brand); }
        .badge-sky    { background: var(--sky-bg);    color: #7dd3fc; border: 1px solid rgba(56,189,248,0.2); }
        .badge-sky::before    { background: var(--sky); }
        .badge-muted  { background: rgba(148,163,184,0.08); color: var(--muted); border: 1px solid var(--border); }
        .badge-muted::before  { background: var(--muted-2); }

        /* Table pagination */
        .dt-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 20px;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
        }
        .dt-count { font-size: 0.78rem; color: var(--muted); }
        .dt-footer nav > div:first-child { display: none; }
        .dt-footer nav > div:last-child {
            display: flex; gap: 4px; flex-wrap: wrap;
        }
        .dt-footer nav a,
        .dt-footer nav span[aria-disabled] {
            display: inline-flex !important;
            align-items: center; justify-content: center;
            min-width: 32px; height: 32px;
            padding: 0 8px;
            border-radius: var(--r-sm);
            font-size: 0.78rem; font-weight: 600;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--muted);
            transition: all 0.12s;
        }
        .dt-footer nav a:hover {
            border-color: var(--brand);
            color: var(--brand-2);
            background: var(--brand-bg);
        }
        .dt-footer nav span[aria-current="page"] span {
            display: inline-flex;
            align-items: center; justify-content: center;
            min-width: 32px; height: 32px;
            padding: 0 8px;
            border-radius: var(--r-sm);
            font-size: 0.78rem; font-weight: 700;
            background: var(--brand);
            border: 1px solid var(--brand);
            color: #fff;
        }

        /* ========================================================
           ACTION BUTTONS
        ======================================================== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: var(--r);
            font-size: 0.82rem;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.14s;
            white-space: nowrap;
            line-height: 1;
        }
        .btn-primary {
            background: var(--brand);
            color: #fff;
            border-color: var(--brand);
        }
        .btn-primary:hover {
            background: var(--brand-2);
            border-color: var(--brand-2);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px var(--brand-glow);
        }
        .btn-secondary {
            background: var(--panel-2);
            color: var(--text-2);
            border-color: var(--border-2);
        }
        .btn-secondary:hover {
            background: rgba(148,163,184,0.12);
            color: var(--text);
            border-color: rgba(148,163,184,0.3);
        }
        .btn-success {
            background: var(--green-bg);
            color: var(--green);
            border-color: rgba(16,185,129,0.25);
        }
        .btn-success:hover { background: rgba(16,185,129,0.2); }
        .btn-danger {
            background: var(--red-bg);
            color: var(--red);
            border-color: rgba(244,63,94,0.25);
        }
        .btn-danger:hover { background: rgba(244,63,94,0.2); }
        .btn-sm { padding: 5px 11px; font-size: 0.76rem; }
        .btn-icon { width: 32px; height: 32px; padding: 0; }

        .row-actions { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }

        /* ========================================================
           FORMS
        ======================================================== */
        .form-grid { display: grid; gap: 18px; }
        .form-grid.cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .form-grid.cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }

        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-label {
            font-size: 0.76rem;
            font-weight: 700;
            color: var(--muted);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .form-control {
            width: 100%;
            padding: 10px 13px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--r);
            color: var(--text);
            font-size: 0.88rem;
            outline: none;
            transition: border-color 0.14s, box-shadow 0.14s;
        }
        .form-control:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px var(--brand-glow);
        }
        .form-control::placeholder { color: var(--muted-2); }
        textarea.form-control { min-height: 120px; resize: vertical; }
        select.form-control { cursor: pointer; }

        /* ========================================================
           RESPONSIVE
        ======================================================== */
        @media (max-width: 1000px) {
            .admin-shell { grid-template-columns: 1fr; }
            .admin-sidebar { display: none; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 640px) {
            .admin-content { padding: 16px; }
            .stats-grid { grid-template-columns: 1fr; }
            .form-grid.cols-2, .form-grid.cols-3 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="admin-shell">

    {{-- ====================== SIDEBAR ====================== --}}
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo">
                <span class="ld">DAILY</span><span class="ldrive">dRIVE</span>
            </div>
            <div class="sidebar-badge">Admin Panel</div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-nav-label">Overview</div>
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>

            <div class="sidebar-nav-label" style="margin-top:8px;">Content</div>
            <a href="{{ route('admin.articles.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Articles
            </a>
            <a href="{{ route('admin.archive') }}"
               class="sidebar-link {{ request()->routeIs('admin.archive') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                Archive
            </a>
            <a href="{{ route('admin.sources.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.sources.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                News Sources
            </a>

            <div class="sidebar-nav-label" style="margin-top:8px;">AI / Config</div>
            <a href="{{ route('admin.ai-providers.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.ai-providers.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                AI Providers
            </a>

            <div class="sidebar-nav-label" style="margin-top:8px;">Audience</div>
            <a href="{{ route('admin.subscribers.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Subscribers
            </a>

            <div class="sidebar-nav-label" style="margin-top:8px;">Publish</div>
            <a href="{{ route('articles.index') }}" target="_blank"
               class="sidebar-link">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                View Public Site
            </a>
        </nav>

        <div class="sidebar-footer">
            <div style="font-size:0.76rem; color:var(--text-2); font-weight:600; margin-bottom:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                {{ auth()->user()->name ?? auth()->user()->email }}
            </div>
            <div style="font-size:0.7rem; color:var(--muted-2); margin-bottom:10px;">Super Admin</div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" style="width:100%; padding:6px 12px; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); border-radius:var(--r); color:#f87171; font-size:0.75rem; font-weight:600; cursor:pointer; font-family:inherit; transition:background 0.15s;">
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    {{-- ====================== TOPBAR ====================== --}}
    <div class="admin-topbar">
        <div class="topbar-left">
            <div class="topbar-breadcrumb">
                <a href="{{ route('admin.dashboard') }}" style="color:var(--muted);">Admin</a>
                <span>/</span>
                <span style="color:var(--text-2); font-weight:600;">@yield('title', 'Dashboard')</span>
            </div>
        </div>
        <div class="topbar-right">
            <a href="{{ route('articles.index') }}" target="_blank" class="topbar-site-link">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                Public Site
            </a>
        </div>
    </div>

    {{-- ====================== CONTENT ====================== --}}
    <main class="admin-content">
        @if (session('error'))
            <div class="flash flash-error">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif
        @if (session('status'))
            <div class="flash flash-success">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('status') }}
            </div>
        @endif
        @yield('content')
    </main>

</div>
</body>
</html>
