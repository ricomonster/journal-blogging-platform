<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $blog->blog_title }}</title>
    <link href="{{ asset(theme_path('assets/css/screen.css')) }}" rel="stylesheet">
    <link href="{{ asset(theme_path('assets/css/main.css')) }}" rel="stylesheet">
</head>
<body class="home-template">
    <header {!! ($blog->blog_cover) ?
    'style="background-image : url('.$blog->blog_cover.')" class="site-header"' :
    'class="site-header no-cover"' !!}>
    	<nav class="main-nav">
    		<a href="/rss" class="subscribe-button">RSS</a>
    	</nav>
        <div class="align-middle">
            <div class="header-content">
                <h1 class="blog-title hero-title">
                    {{ $blog->blog_title }}
                </h1>
                <p class="blog-description hero-description">
                    {{ $blog->blog_description }}
                </p>
            </div>
        </div>
    </header>
    <main class="container content">
        @foreach($posts as $post)
        <article class="post-wrapper">
            <header class="post-header">
                <h3 class="post-title">
                    <a href="{{ $post->permalink }}">{{ $post->title }}</a>
                </h3>
            </header>
            <section class="post-excerpt">
                <a href="{{ $post->permalink }}" class="post-excerpt-link">
                    {{ markdown($post->content, true, 50) }}
                </a>
            </section>
            <footer class="post-meta">
                <span class="author-meta post-meta-inline">
                    <a href="/author/{{ $post->author->slug }}">
                        <img src="{{ asset('images/shared/default_avatar.png') }}"
                        class="author-avatar img-circle" width="32">
                        <span class="author-name">{{ $post->author->name }}</span>
                    </a>
                </span>
                <div class="tags post-meta-inline">
                    on
                </div>
                <time class="post-date post-meta-inline">
                    {{ date('F d, Y', strtotime($post->published_at)) }}
                </time>
            </footer>
        </article>
        @endforeach
    </main>

    <nav></nav>

    <footer class="footer clearfix">
        <section class="copyright">
            <a href="/" class="blog-name">{{ $blog->blog_title }}</a> &copy;
            {{ date('Y') }} &bull; All rights reserved.
        </section>
        <section class="poweredby">
            Proudly published with
            <a href="http://github.com/ricomonster/journal-core"
            target="_blank">Journal</a>
        </section>
    </footer>
</body>
</html>
