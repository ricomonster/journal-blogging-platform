<article class="post">
    <header class="post-header">
        <h2 class="post-title">
            <a href="/{{ $post->slug }}">
                {{ $post->title }}
            </a>
        </h2>
    </header>
    <section class="post-excerpt">
        {{ excerpt($post) }}
    </section>
    <footer class="post-meta">
        <a href="">{{ $post->author->name }}</a>
        @if ($post->tags->count() > 0)
        on
        {!! post_tags($post->tags) !!}
        @endif
        <time class="post-date" datetime="{{ date('Y-m-d', $post->published_at) }}">
            {{ date('d F Y', $post->published_at) }}
        </time>
    </footer>
</article>
