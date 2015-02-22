<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="{{ $post->author->name }}">
    <title>{{ $post->title }}</title>
    <link href="{{ asset(theme_path('assets/css/screen.css')) }}" rel="stylesheet">
    <link href="{{ asset(theme_path('assets/css/main.css')) }}" rel="stylesheet">
    <link href="{{ asset(theme_path('assets/css/font-awesome.min.css')) }}" rel="stylesheet">
    <style>
    	.main-nav .home-button,
        .main-nav .subscribe-button { color: rgba(0,0,0,0.6); border: transparent 1px solid; }
        .main-nav a:hover { background: none; border-color: #bfc8cd; color: rgba(0,0,0,0.6); }

        .content { border-top: 0; max-width: 740px; padding-top: 120px; }
        .post-header { margin-bottom: 80px; }
		.post-header .hero-title { color: rgba(0,0,0,0.8); font-size: 70px; }

		.post-wrapper { border-bottom: 0; }
		.post-content {
			font-family: 'Droid Serif', serif;
            font-weight: 400;
            font-style: normal;
            font-size: 20px;
            letter-spacing: 0.01rem;
            line-height: 1.5;
            margin-bottom: 80px;
		}

		.post-content h1,.post-content h2, .post-content h3, .post-content h4 {
			color: rgba(0,0,0,0.44);
			font-family: 'Open Sans', sans-serif;
			margin-bottom: 10px;
			letter-spacing: -0.02em;
            font-weight: 300;
            font-style: normal;
            font-size: 30px;
            margin-left: -1.5px;
            line-height: 1.2;
            margin-top: 20px;
            margin-bottom: 10px;
		}

		.post-content h1 { font-size: 38px; }
		.post-content h2 { font-size: 32px; }
		.post-content h3 { font-size: 28px; }

		.post-content p { margin-bottom: 30px; }
		.post-content img { width: 100%; }
		.post-content code { white-space: normal; }

		.post-footer { border-top: 1px solid rgba(0,0,0,0.15); margin-bottom: 20px; }
		.post-footer h4 {
			font-weight: 700;
            font-style: normal;
            padding: 12px 0 28px;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: .1em;
            color: rgba(0,0,0,0.3);
            margin-top: 10px;
		}

		.author-wrapper .author-avatar-wrapper { float: left; width: 80px; }
		.author-wrapper .author-avatar-wrapper .avatar-link { height: 80px; width: 80px; }
		.author-wrapper .author-avatar-wrapper .avatar-link .avatar-image { width: 100%; }

		.author-wrapper .author-details { margin-left: 100px; }
		.author-wrapper .author-details .author-name {
			font-size: 18px;
            color: rgba(0,0,0,0.8);
            line-height: 1.1;
            margin-bottom: 4px;
		}

		.author-wrapper .author-details .author-name a { text-decoration: none; }
		.author-name a:hover, .author-name a:active,
		.author-name a:focus{ color:#57ad68; text-decoration:none; }
		.author-wrapper .author-details .author-description {
			font-size: 14px;
			line-height: 1.3;
			margin-bottom: 8px;
		}

		.author-wrapper .author-details .author-meta {
			list-style: none;
			margin: 0;
			padding: 0;
		}

		.author-wrapper .author-details .author-meta li {
			display: inline-block;
			font-size: 14px;
			line-height: 1.3;
			padding: 0 10px 5px 0;
		}

		.author-wrapper .author-details .author-meta li a { color: rgba(0,0,0,0.8); }
		.author-wrapper .author-details .author-meta li a:hover { text-decoration: underline; }

		.share { text-align: right; }
		.share a { color: rgba(0,0,0,0.44); font-size: 26px; margin-left: 10px; }
		.share a:hover { color: rgba(0,0,0,0.6); text-decoration: none; }

		@media (max-width: 990px) {
			.post-footer .author { float: none; }
			.post-footer .share { float: none; text-align: left; }
			.post-footer .share a { margin-left: 0; margin-right: 10px; }
		}
    </style>
</head>
<body class="post-template">
	<nav class="main-nav">
		<a href="/" class="home-button">Home</a>
		<a href="/rss" class="subscribe-button">RSS</a>
	</nav>

    <main class="container content">
        <article class="post-wrapper">
        	<header class="post-header">
				<h2 class="post-title hero-title">{{ $post->title }}</h2>
			</header>
            <section class="post-content">
				{!! markdown($post->content) !!}
			</section>
            <footer class="post-footer row">
				<section class="author col-md-9">
					<h4>Written By</h4>
					<div class="author-wrapper">
						<div class="author-avatar-wrapper">
							<a href="/author/{{ $post->author->slug }}" class="avatar-link">
								<img src="{{ asset('images/shared/default_avatar.png') }}"
								class="avatar-image img-circle">
							</a>
						</div>
						<div class="author-details">
							<h3 class="author-name">
								<a href="/author/{{ $post->author->slug }}">
									{{ $post->author->name }}
								</a>
							</h3>
							<div class="author-description">{{ $post->author->biography }}</div>
							<ul class="author-meta">
								<li>
									<a href="{{ $post->author->website }}">
										{{ $post->author->website }}
									</a>
								</li>
							</ul>
						</div>
					</div>
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
