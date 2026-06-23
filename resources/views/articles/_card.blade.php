<article class="acard">
    <a href="{{ route('articles.show', $article) }}" style="display:contents;">
        <div class="acard-img">
            @if ($article->image_url)
                <img src="{{ $article->image_url }}" alt="{{ $article->displayTitle() }}" loading="lazy">
            @else
                <div class="acard-no-img">&#128240;</div>
            @endif
        </div>
        <div class="acard-body">
            <div class="acard-meta-row">
                @if ($article->category)
                    <span class="tag tag-source">{{ $article->category }}</span>
                @endif
            </div>
            <h3 class="acard-title">{{ $article->displayTitle() }}</h3>
            @if ($article->displayExcerpt())
                <p class="acard-excerpt">{{ $article->displayExcerpt() }}</p>
            @endif
            <div class="acard-foot">
                <span>{{ optional($article->published_at)->format('M d, Y') ?? optional($article->scraped_at)->format('M d, Y') }}</span>
                <div style="display:flex;align-items:center;gap:10px;margin-left:auto;">
                    @if($article->views > 0)
                    <span class="acard-read-time" title="{{ number_format($article->views) }} views">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        {{ $article->views >= 1000 ? number_format($article->views/1000, 1).'k' : $article->views }}
                    </span>
                    @endif
                    @if($article->likes > 0)
                    <span class="acard-read-time" title="{{ number_format($article->likes) }} likes">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        {{ $article->likes >= 1000 ? number_format($article->likes/1000, 1).'k' : $article->likes }}
                    </span>
                    @endif
                    <span class="acard-read-time">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        {{ $article->estimatedReadMinutes() }}m
                    </span>
                </div>
            </div>
        </div>
    </a>
    {{-- Bookmark button (outside the <a> to prevent navigation) --}}
    <button class="acard-bookmark"
            onclick="toggleBookmark(event,'{{ $article->slug }}','{{ addslashes($article->displayTitle()) }}','{{ addslashes($article->image_url ?? '') }}','{{ $article->category }}')"
            title="Bookmark"
            data-slug="{{ $article->slug }}">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
    </button>
</article>
