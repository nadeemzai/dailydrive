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
                <span class="acard-read-time">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    {{ $article->estimatedReadMinutes() }}m
                </span>
            </div>
        </div>
    </a>
</article>
