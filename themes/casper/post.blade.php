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
    <link href="{{ theme_path('assets/css/awesome.css') }}" rel="stylesheet"/>
    <style>
        .post-template .post-wrapper .post-header { padding: 30px 0; text-align: center; }
        .post-template .post-wrapper .post-header .post-title {
            display: inline-block;
            font-family: 'Open Sans', sans-serif;
            font-size: 50px;
            font-weight: bold;
            letter-spacing: -1px;
            margin: 10px 0;
            padding: 25px 0 0;
            text-shadow: 0 1px 6px rgba(0,0,0,0.1);
        }

        .post-template .post-wrapper .post-footer {
            border-top: #EBF2F6 1px solid;
            margin: 40px 0 30px 0;
            padding: 30px 0 0 0;
            position: relative;
        }

        .post-template .post-wrapper .post-footer .author h4 { font-family: 'Open Sans', sans-serif; font-size: 18px; font-weight: bold; }
        .post-template .post-wrapper .post-footer .author p.author-description { margin: 5px 0; font-size: 14px; }
        .post-template .post-wrapper .post-footer .author .author-meta { list-style: none; margin: 0; padding: 0; }
        .post-template .post-wrapper .post-footer .author .author-meta li { display: inline-block; font-style: italic; }
        .post-template .post-wrapper .post-footer .author .author-meta li a {
            color: #9EABB3;
            font-family: 'Noto Serif', serif;
            font-size: 14px;
        }

        .post-template .post-wrapper .post-footer .author .author-meta li a:hover { color: #428bca; }
        .post-template .post-wrapper .post-footer .share h4 { font-size: 18px; }
        .post-template .post-wrapper .post-footer .share a { color: #BBC7CC; font-size: 26px; margin-right: 15px; }
        .post-template .post-wrapper .post-footer .share a:hover { color: #50585D; }
    </style>
</head>
<body class="post-template">
    <main class="container content">
        <article class="post-wrapper">
            <header class="post-header">
                <h1 class="post-title">{{ $post->title }}</h1>
                <section class="post-meta">
                    <time class="post-date">
                        {{ date('d F Y', strtotime($post->published_at)) }}
                    </time>
                </section>
            </header>

            <section class="post-content">
                {!! markdown($post->content) !!}
            </section>
            <footer class="post-footer row">
                <section class="author col-md-9">
                    <h4>
                        <a href="/author/{{ $post->author->slug }}">
                            {{ $post->author->name }}
                        </a>
                    </h4>
                    <p class="author-description">
                        {{ $post->author->biography }}
                    </p>
                    <ul class="author-meta">
                        <li>
                            <a href="{{ $post->author->website }}">
                                {{ $post->author->website }}
                            </a>
                        </li>
                    </ul>
                </section>
                <section class="share col-md-3">
                    <h4>Share this post</h4>
                    <a class="fa fa-facebook-square"
                       href="https://www.facebook.com/sharer/sharer.php?u={{ $post->permalink }}"
                       onclick="window.open(this.href, 'facebook-share','width=580,height=296');return false;"></a>

                    <a class="fa fa-twitter-square"
                       href="https://twitter.com/share?text={{ urlencode($post->title) }}&url={{ $post->permalink }}"
                       onclick="window.open(this.href, 'twitter-share', 'width=550,height=235');return false;"></a>

                    <a href="https://plus.google.com/share?url={{ $post->permalink }}"
                       onclick="window.open(this.href, 'google-plus-share', 'width=490,height=530');return false;"
                       class="fa fa-google-plus-square"></a>
                </section>
            </footer>
        </article>
    </main>
    <footer class="site-footer">
        <div class="inner">
            <section class="copyright">
                <a href="/" class="blog-name">{{ $blog->blog_title }}</a> &copy;
                {{ date('Y') }} &bull; All rights reserved.
            </section>
            <section class="poweredby">
                Proudly published with
                <a href="http://github.com/ricomonster/journal-core" target="_blank">Journal</a>
            </section>
        </div>
</footer>
</body>
</html>
