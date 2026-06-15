@extends('admin.layout')

@section('title', 'Newsletter Subscribers')

@section('content')

    <div class="page-head">
        <div class="page-head-left">
            <h1>Newsletter Subscribers</h1>
            <p>People who subscribed to DAILYdRIVE updates from the homepage.</p>
        </div>
        <div class="page-head-actions">
            <div style="display:flex; gap:10px;">
                <div style="padding:8px 16px; background:rgba(5,150,105,0.12); border:1px solid rgba(5,150,105,0.25); border-radius:var(--r); font-size:0.8rem; font-weight:700; color:#10b981;">
                    {{ $activeCount }} Active
                </div>
                <div style="padding:8px 16px; background:rgba(100,116,139,0.1); border:1px solid var(--border); border-radius:var(--r); font-size:0.8rem; font-weight:600; color:var(--muted);">
                    {{ $unsubscribedCount }} Unsubscribed
                </div>
            </div>
        </div>
    </div>

    <div class="card">

        <div class="dt-search-bar">
            <div class="dt-search-wrap">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input class="dt-search-input" id="dt-search" type="text" placeholder="Search subscribers…">
            </div>
            <span style="font-size:0.77rem; color:var(--muted); margin-left:auto;">
                {{ $subscribers->total() }} total
            </span>
        </div>

        <div class="dt-wrap">
            <table class="data-table" id="subs-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Subscribed</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($subscribers as $sub)
                    <tr>
                        <td style="color:var(--muted-2); font-size:0.76rem;">{{ $sub->id }}</td>
                        <td>
                            <span class="dt-cell-primary">{{ $sub->email }}</span>
                        </td>
                        <td style="color:var(--muted); font-size:0.82rem;">
                            {{ $sub->name ?: '—' }}
                        </td>
                        <td style="white-space:nowrap; font-size:0.79rem; color:var(--muted);">
                            {{ optional($sub->subscribed_at)->format('M d, Y') ?? '—' }}
                        </td>
                        <td>
                            @if ($sub->unsubscribed_at)
                                <span class="badge badge-muted">Unsubscribed</span>
                            @elseif ($sub->subscribed_at)
                                <span class="badge badge-green">Active</span>
                            @else
                                <span class="badge badge-amber">Pending</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.subscribers.destroy', $sub) }}"
                                  onsubmit="return confirm('Delete this subscriber?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:40px; color:var(--muted);">
                            No subscribers yet — the newsletter form on the homepage will populate this list.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($subscribers->hasPages())
            <div class="dt-footer">
                {{ $subscribers->links() }}
            </div>
        @endif

    </div>

    <script>
    var searchInput = document.getElementById('dt-search');
    var table = document.getElementById('subs-table');
    if (searchInput && table) {
        searchInput.addEventListener('input', function() {
            var q = this.value.toLowerCase();
            table.querySelectorAll('tbody tr').forEach(function(row) {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }
    </script>

@endsection
