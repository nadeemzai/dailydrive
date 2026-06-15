@extends('layouts.public')

@section('title', 'Unsubscribed — DAILYdRIVE')

@section('content')
<div class="container" style="padding: 80px 0; text-align: center;">
    <div style="max-width: 480px; margin: 0 auto; background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); padding: 48px 40px;">
        <div style="font-size: 2.5rem; margin-bottom: 18px;">✓</div>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text); margin-bottom: 10px;">You've been unsubscribed</h1>
        <p style="font-size: 0.9rem; color: var(--muted); line-height: 1.7; margin-bottom: 28px;">
            You will no longer receive newsletter emails from DAILYdRIVE. You can re-subscribe anytime from the homepage.
        </p>
        <a href="{{ route('home') }}"
           style="display:inline-flex; align-items:center; gap:7px; padding:10px 24px; background:var(--brand); color:#fff; border-radius:var(--r); font-size:0.875rem; font-weight:700; text-decoration:none;">
            ← Back to Home
        </a>
    </div>
</div>
@endsection
