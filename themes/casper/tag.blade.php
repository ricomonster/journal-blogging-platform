<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ $blog->blog_title }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ theme_assets('assets/css/screen.css') }}" rel="stylesheet"/>
    <link href="{{ theme_assets('assets/css/font-awesome.css') }}" rel="stylesheet"/>
</head>
<body class="home-template">
<header class="main-header {{ ($blog->blog_cover) ? null : 'no-cover' }}" {!! ($blog->blog_cover) ? 'style="background-image: url('.$blog->blog_cover.')"' : null !!}>
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
    <div class="vertical">
        <div class="header-content">
            <h1 class="blog-title">{{ ucfirst($tag->tag) }}</h1>
            <h2 class="blog-description">
                A {{ $posts->count() }}-post collection
            </h2>
        </div>
    </div>
</header>
<main class="content">
    @foreach($posts as $post)
        <article class="post">
            <header class="post-header">
                <h2 class="post-title">
                    <a href="{{ $post->permalink }}">{{ $post->title }}</a>
                </h2>
            </header>
            <section class="post-content">{!! markdown($post->content, true, 50) !!}</section>
            <footer class="post-meta">
                <a href="{{ $post->author->permalink }}">{{ $post->author->name }}</a>
                @if($post->tags)
                    on
                @endif
                {!! convert_tags_to_links($post->tags) !!}
                <date class="post-date">{{ $post->published_at }}</date>
            </footer>
        </article>
    @endforeach
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
