<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ $post->title .' &#8212; '. $title }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ theme_assets('assets/css/screen.css') }}" rel="stylesheet"/>
    <link href="{{ theme_assets('assets/css/font-awesome.css') }}" rel="stylesheet"/>

    {!! $journal_head !!}
</head>
<body class="post-template">
    <header class="main-header {{ ($post->featured_image) ? null : 'no-cover' }}"
    {!! ($post->featured_image) ? 'style="background-image: url('.$post->featured_image.')"' : null !!}>
        <nav class="site-nav clearfix">
            <a href="{{url('/')}}" class="back-button">
                <i class="fa fa-angle-left"></i>
                Home
            </a>
            <a href="{{url('rss')}}" class="rss-button">
                <i class="fa fa-rss"></i>
                Subscribe
            </a>
        </nav>
    </header>
    <main class="content">
        <article class="post">
            <header class="post-header">
                <h1 class="post-title">{{ $post->title }}</h1>
                <section class="post-meta">
                    <date class="post-date">{{ post_date_time($post) }}</date>
                    @if($post->tags->count() > 0)
                    on
                    @endif
                    {!! get_post_tags($post) !!}
                </section>
            </header>
            <section class="post-content">{!! markdown($post->markdown) !!}</section>
            <footer class="post-footer clearfix">
                @if($post->author->avatar_url)
                <figure class="author-avatar">
                    <a href="{{ $post->author->permalink }}" class="img-wrapper">
                        <img src="{{$post->author->avatar_url}}"/>
                    </a>
                </figure>
                @endif
                <section class="author"
                {!! (!$post->author->avatar_url) ? 'style="margin-left: 0;"' : null !!}>
                    <h4>
                        <a href="{{ $post->author->permalink }}">{{ $post->author->name }}</a>
                    </h4>
                    <p>{{ $post->author->biography }}</p>
                    <div class="author-meta">
                        @if($post->author->location)
                        <span class="author-location">
                            <i class="fa fa-map-marker"></i>
                            {{ $post->author->location }}
                        </span>
                        @endif
                        @if($post->author->website)
                        <span class="author-website">
                            <i class="fa fa-link"></i>
                            <a href="{{ $post->author->website }}" target="_blank">{{ $post->author->website }}</a>
                        </span>
                        @endif
                    </div>
                </section>
                <section class="share">
                    <h4>Share</h4>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url($post->permalink) }}"
                       onclick="window.open(this.href, 'facebook-share','width=580,height=296');return false;" class="fa fa-facebook-square"></a>
                    <a href="https://twitter.com/share?text={{ urlencode($post->title) }}&url={{ url($post->permalink) }}"
                       onclick="window.open(this.href, 'twitter-share', 'width=550,height=235');return false;" class="fa fa-twitter"></a>
                    <a href="https://plus.google.com/share?url={{ url($post->permalink) }}"
                       onclick="window.open(this.href, 'google-plus-share', 'width=490,height=530');return false;" class="fa fa-google-plus-square"></a>
                </section>
            </footer>
        </article>
    </main>
    <footer class="site-footer clearfix">
        <section class="blog">
            <a href="{{url('/')}}">{{ $title }}</a> &copy; {{ date('Y') }}
        </section>
        <section class="platform">
            Proudly published with <a href="https://github.com/ricomonster/journal-blogging-platform" target="_blank">Journal</a>
        </section>
    </footer>
</body>
</html>
