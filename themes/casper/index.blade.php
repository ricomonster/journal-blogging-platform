<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $blog->blog_description }}">
    <title>{{ $blog->blog_title }}</title>
    <link href="{{ theme_path('assets/css/global.css') }}" rel="stylesheet"/>
    <link href="{{ theme_path('assets/css/screen.css') }}" rel="stylesheet"/>
    <style>
        .home-template .main-header { height: 100%; }
        .home-template .main-header.no-cover { height: 60%; }
    </style>
</head>
<body class="home-template">
    <header {!! ($blog->blog_cover) ?
    'class="main-header" style="background-image: url('.$blog->blog_cover.')"' :
    'class="main-header no-cover"' !!}>
        <nav class="main-nav" style="display: none;">
            <a href="/rss" class="rss-button"><i class="fa fa-rss"></i> RSS</a>
        </nav>
        <div class="vertical">
            <div class="header-content">
                {{-- Logo goes in here --}}
                <h1 class="blog-title">{{ $blog->blog_title }}</h1>
                <h2 class="blog-description">{{ $blog->blog_description }}</h2>
            </div>
        </div>
    </header>
    <main class="container content">
        @if(!$posts->isEmpty())
        @foreach($posts as $key => $post)
            <article class="post-wrapper">
                <header class="post-header">
                    <h2 class="post-title">
                        <a href="{{ $post->permalink }}">{{ $post->title }}</a>
                    </h2>
                </header>
                <section class="post-excerpt post-content">
                    {{ markdown($post->content, true, 50) }}
                </section>
                <footer class="post-footer-meta">
                    <span class="post-meta">
                    <a href="/author/{{ $post->author->slug }}" class="post-author">
                        {{ $post->author->name }}
                    </a>
                        <div class="tags">
                            on
                        </div>
                        <time class="post-date">
                            {{ date('d F Y', strtotime($post->published_at)) }}
                        </time>
                </span>
                </footer>
            </article>
        @endforeach
        @endif
    </main>
    <footer class="site-footer">
        <div class="inner">
            <section class="copyright">
                <a href="/" class="blog-name">{{ $blog->blog_title }}</a> &copy;
                {{ date('Y') }} &bull; All rights reserved.
            </section>
            <section class="poweredby">
                Proudly published with
                <a href="http://github.com/ricomonster/journal-blogging-platform"
                target="_blank">Journal</a>
            </section>
        </div>
    </footer>
</body>
</html>
