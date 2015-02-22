<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $author->name }} - {{ $blog->blog_title }}</title>
    <link href="{{ asset(theme_path('assets/css/screen.css')) }}" rel="stylesheet">
    <link href="{{ asset(theme_path('assets/css/main.css')) }}" rel="stylesheet">
    <style>
        .author-header { margin-bottom: 40px; }
        .author-header.no-cover { padding-top: 80px; }
        .author-header .image-wrapper {
            position: relative;
            height: 350px;
            overflow: hidden;
            -webkit-transform: translate3d(0,0,0);
            -o-transform: translate3d(0, 0, 0);
            -ms-transform: translate3d(0, 0, 0);
            -moz-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            -webkit-transition: height 0.25s linear;
            -o-transition: height 0.25s linear;
            -ms-transition: height 0.25s linear;
            -moz-transition: height 0.25s linear;
            transition: height 0.25s linear;
        }

        .author-header.no-cover .image-wrapper { display: none; }

        .image-wrapper .header-image-cover {
            background: transparent 50% 50% no-repeat;
            display: block;
            height: 100%;
            opacity: 1;
            position: relative;
            -webkit-transition: opacity 0.15s ease;
            -o-transition: opacity 0.15s ease;
            -ms-transition: opacity 0.15s ease;
            -moz-transition: opacity 0.15s ease;
            transition: opacity 0.15s ease;
            background-size: cover;
            margin: 0 -1px;
        }

        .header-content {
            margin-left: auto;
            margin-right: auto;
            padding: 1px 20px 30px;
            position: relative;
            z-index: 2;
            max-width: 702px;
            min-width: 270px;
            text-align: center;
        }

        .author-avatar-wrapper {
            left: 50%;
            margin-left: -57px;
            position: absolute;
            top: -78px;
        }

        .author-header.no-cover .author-avatar-wrapper { top : 0; }
        .hero-title.author-title {
            font-weight: 700;
            font-style: normal;
            font-size: 50px;
            letter-spacing: -0.04em;
            line-height: 1;
            color: rgba(0,0,0,0.8);
            margin-top: 50px;
            margin-bottom: 8px;
            outline: 0;
            word-break: break-word;
        }

        .author-header.no-cover .hero-title.author-title { margin-top: 130px; }
        .hero-description.author-biography {
            color: rgba(0,0,0,0.6);
            font-size: 18px;
            outline: 0;
            word-break: break-word;
            letter-spacing: -0.02em;
        }
    </style>

    {{-- Google Analytics Code  --}}
	{{ google_analytics() }}
</head>
<body class="author-template">
    <header class="author-header {{ (!empty($author->cover_url)) ?
    null : 'no-cover' }}">
    	<nav class="main-nav">
    		<a href="/" class="home-button">Home</a>
			<a href="/rss" class="subscribe-button">RSS</a>
		</nav>
        <div class="image-wrapper">
            <div class="header-image-cover"
            style="background-image : url('{{ (empty($author->cover_url)) ?
            '': $author->cover_url }}')"></div>
        </div>
        <div class="header-content author-details">
            <figure class="author-avatar-wrapper">
                <img src="{{ asset(journal_path('img/shared/default_avatar.png')) }}"
                class="author-avatar img-circle" width="120">
            </figure>
            <div class="author-details-group">
                <h1 class="author-title hero-title">
                    {{ $author->name }}
                </h1>
                <p class="author-biography hero-description">
                    {{ $author->biography }}
                </p>
                <div class="author-meta">
                    @if(!empty($author->website))
                    <span class="author-website">
                        <a href="{{ $author->website }}">
                            {{ $author->website }}
                        </a>
                    </span>
                    @endif
                    <span class="author-stats">{{ $posts->count() }} posts</span>
                </div>
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
                    {{ $post->getTrimmedHtmlContentAttribute() }}
                </a>
            </section>
            <footer class="post-meta">
                <span class="author-meta post-meta-inline">
                    <a href="/author/{{ $post->author->slug }}">
                        <img src="{{ asset(journal_path('img/shared/default_avatar.png')) }}"
                        class="author-avatar img-circle" width="32">
                        <span class="author-name">{{ $post->author->name }}</span>
                    </a>
                </span>
                @if($post->tags->count() > 0)
				<div class="tags post-meta-inline">
					on {{ $post->getImplodedTagsAttribute(',', true) }}
				</div>
				@endif
                <time class="post-date post-meta-inline">
                    {{ date('F d, Y', strtotime($post->publish_date)) }}
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
