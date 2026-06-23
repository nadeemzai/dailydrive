@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="dd-pagination">
    <style>
        .dd-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
        }

        .dd-pagination-info {
            font-size: 0.82rem;
            color: var(--muted);
            white-space: nowrap;
        }

        .dd-pagination-info strong {
            font-weight: 700;
            color: var(--text-2);
        }

        .dd-pagination-pages {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .dd-page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 8px;
            border-radius: var(--r-sm, 8px);
            border: 1px solid var(--border);
            background: var(--bg-card);
            color: var(--text-2);
            font-size: 0.845rem;
            font-weight: 500;
            font-family: inherit;
            text-decoration: none;
            transition: background 0.13s, border-color 0.13s, color 0.13s;
            cursor: pointer;
            line-height: 1;
        }

        .dd-page-btn:hover {
            background: var(--bg-hover, #f5f5f5);
            border-color: var(--brand);
            color: var(--brand);
        }

        .dd-page-btn.is-active {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
            font-weight: 700;
            cursor: default;
        }

        .dd-page-btn.is-disabled {
            opacity: 0.38;
            cursor: not-allowed;
            pointer-events: none;
        }

        .dd-page-btn.is-arrow {
            color: var(--muted);
        }

        .dd-page-btn.is-arrow:hover {
            color: var(--brand);
        }

        .dd-page-dots {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            font-size: 0.9rem;
            color: var(--muted-2);
            letter-spacing: 0.05em;
            user-select: none;
        }

        @media (max-width: 500px) {
            .dd-pagination { justify-content: center; }
            .dd-pagination-info { display: none; }
        }
    </style>

    {{-- Info text: "Showing 1 to 12 of 48 results" --}}
    <div class="dd-pagination-info">
        @if ($paginator->firstItem())
            Showing <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
            of <strong>{{ $paginator->total() }}</strong> results
        @else
            {{ $paginator->count() }} results
        @endif
    </div>

    {{-- Page buttons --}}
    <div class="dd-pagination-pages">

        {{-- Prev arrow --}}
        @if ($paginator->onFirstPage())
            <span class="dd-page-btn is-arrow is-disabled" aria-disabled="true" aria-label="Previous">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="dd-page-btn is-arrow" aria-label="Previous page">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="dd-page-dots" aria-hidden="true">···</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="dd-page-btn is-active" aria-current="page" aria-label="Page {{ $page }}">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="dd-page-btn" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next arrow --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="dd-page-btn is-arrow" aria-label="Next page">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </a>
        @else
            <span class="dd-page-btn is-arrow is-disabled" aria-disabled="true" aria-label="Next">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </span>
        @endif

    </div>
</nav>
@endif
