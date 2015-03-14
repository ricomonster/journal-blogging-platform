<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ $post->title .' &#8212; '. $blog->blog_title }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ theme_assets('assets/css/screen.css') }}" rel="stylesheet"/>
    <link href="{{ theme_assets('assets/css/font-awesome.css') }}" rel="stylesheet"/>
</head>
<body class="post-template">
    <header class="main-header no-cover">
        <nav class="site-nav clearfix">
            <a href="/" class="back-button">
                <i class="fa fa-angle-left"></i>
                Home
            </a>
            <a href="#" class="rss-button">
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
                    <date class="post-date">{{ $post->published_at }}</date>
                    @if($post->tags)
                    on
                    @endif
                    {!! convert_tags_to_links($post->tags) !!}
                </section>
            </header>
            <section class="post-content">{!! markdown($post->content) !!}</section>
            <footer class="post-footer clearfix">
                <figure class="author-avatar">
                    <a href="#" class="img" style="background-image: url('{{ $post->author->avatar_url }}')"></a>
                </figure>
                <section class="author">
                    <h4>
                        <a>{{ $post->author->name }}</a>
                    </h4>
                    <p>{{ $post->author->biography }}</p>
                    <div class="author-meta">
                        <span class="author-location">
                            <i class="fa fa-map-marker"></i>
                            {{ $post->author->location }}
                        </span>
                        <span class="author-website">
                            <i class="fa fa-link"></i>
                            <a href="{{ $post->author->website }}" target="_blank">{{ $post->author->website }}</a>
                        </span>
                    </div>
                </section>
                <section class="share">
                    <h4>Share</h4>
                    <a href="#"><i class="fa fa-facebook-square"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>
                    <a href="#"><i class="fa fa-google-plus-square"></i></a>
                </section>
            </footer>
        </article>
    </main>
    <footer class="site-footer clearfix">
        <section class="blog">
            <a href="#">{{ $blog->blog_title }}</a> &copy; {{ date('Y') }}
        </section>
        <section class="platform">
            Proudly published with <a href="#" target="_blank">Journal</a>
        </section>
    </footer>
</body>
</html>
