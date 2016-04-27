@extends('casper.layout')
@section('title', $post->title)

@section('header')
    <header class="main-header post-head {!! ($post->cover_image) ? null : 'no-cover' !!}"
    {!! ($post->cover_image) ? 'style="background-image: url('.$post->cover_image.');"' : null !!}>
        <nav class="main-nav overlay clearfix">
            {!! blog_logo_photo() !!}

            <a class="menu-button" href="#">
                <i class="fa fa-bars"></i>
                <span class="word">Menu</span>
            </a>
        </nav>
    </header>
@endsection

@section('body')
    <article class="post">
        <header class="post-header">
            <h1 class="post-title">
                {{ $post->title }}
            </h1>
            <section class="post-meta">
                <time class="post-date-time" datetime="{{ date('Y-m-d', $post->published_at) }}">
                    {{ date('d F Y', $post->published_at) }}
                </time>
                @if ($post->tags->count() > 0)
                on
                {!! post_tags($post->tags) !!}
                @endif
            </section>
        </header>
        <section class="post-content">
            {!! markdown($post->content) !!}
        </section>
        <footer class="post-footer">
            @if ($post->author->avatar_url)
            <figure class="author-image">
                <a href="/author/{{ $post->author->slug }}" class="img"
                style="background-image: url({{ $post->author->avatar_url }})">
                    <span class="hidden">
                        {{ $post->author->name }}'s Picture
                    </span>
                </a>
            </figure>
            @endif
            <section class="author">
                <h4>
                    <a href="/author/{{ $post->author->slug }}">{{ $post->author->name }}</a>
                </h4>
                <p>
                    @if (empty($post->author->biography))
                    Read <a href="/author/{{ $post->author->slug }}">more posts</a>
                    by this author.
                    @else
                    {{ $post->author->biography }}
                    @endif
                </p>
                <div class="author-meta">
                    @if ($post->author->location)
                    <span class="author-location">
                        <i class="fa fa-map-marker"></i>
                        {{ $post->author->location }}
                    </span>
                    @endif

                    @if ($post->author->website)
                    <span class="author-link">
                        <a href="{{ $post->author->website }}" target="_blank">
                            <i class="fa fa-link"></i>
                            {{ $post->author->website }}
                        </a>
                    </span>
                    @endif
                </div>
            </section>

            <section class="share">
                <h4>Share this post</h4>

                <a class="fa fa-twitter" href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ url($post->slug) }}"
                onclick="window.open(this.href, 'twitter-share', 'width=550,height=235');return false;">
                    <span class="hidden">Twitter</span>
                </a>

                <a class="fa fa-facebook-official" href="https://www.facebook.com/sharer/sharer.php?u={{ url($post->slug) }}"
                onclick="window.open(this.href, 'facebook-share','width=580,height=296');return false;">
                    <span class="hidden">Facebook</span>
                </a>

                <a class="fa fa-google-plus-square" href="https://plus.google.com/share?url={{ url($post->slug) }}"
                onclick="window.open(this.href, 'google-plus-share', 'width=490,height=530');return false;">
                    <span class="hidden">Google Plus</span>
                </a>
            </section>
        </footer>
    </article>
@endsection
