<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tag->tag }} - {{ $blog->blog_title }}</title>
    <link href="{{ asset(theme_path('assets/css/screen.css')) }}" rel="stylesheet">
    <link href="{{ asset(theme_path('assets/css/main.css')) }}" rel="stylesheet">
    {{-- Google Analytics Code  --}}
	{{ google_analytics() }}
</head>
<body class="tags-template">
    <header {{ (isset($blog->blog_cover) && !empty($blog->blog_cover)) ?
    'style="background-image : url('.$blog->blog_cover.')" class="site-header"' :
    'class="site-header no-cover"' }}>
    	<nav class="main-nav">
    		<a href="/" class="home-button">Home</a>
			<a href="/rss" class="subscribe-button">RSS</a>
		</nav>
        <div class="align-middle">
            <div class="header-content">
                <h1 class="tag-title hero-title">
                    {{ $tag->tag }}
                </h1>
                <p class="tag-description hero-description">
                    A {{ $posts->count() }}-posts collection
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
