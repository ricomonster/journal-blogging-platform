@extends('admin.layout')
@section('title') Posts @stop

@section('body')
<style type="text/css">
	.post-list-page .page-header { padding: 5px 20px 0 20px; }
	.post-list-page .page-header .hero-title { float: left; }
	.post-list-page .content-wrapper {
		-webkit-overflow-scrolling: touch;
		background: #fff;
		border-top: 1px solid #f6f6f6;
		bottom: 0;
		left: 0;
		overflow-x: hidden;
		overflow-y: auto;
		position: absolute;
		right: 0;
		top: 115px;
	}

	.post-list-page .content-wrapper .list-of-posts {
		background: #fff;
		border-right: #f6f6f6 1px solid;
		bottom: 0;
		left: 0;
		padding: 15px;
		position: absolute;
		top: 0;
		width: 33%;
	}

	.list-of-posts .post-lists-stream {
		bottom: 0;
		left: 0;
		overflow: auto;
        padding-top: 40px;
		position: absolute;
		right: 0;
		top: 0;
	}

	.list-of-posts .post-lists-stream .post-wrapper { margin: 0; padding: 0; position: relative; }
	.list-of-posts .post-lists-stream .post-wrapper.active {
		background: #f6f6f7;
		box-shadow: #e8eaeb 0 0 0,rgba(0,0,0,0.06) 7px 0 0 inset,#e8eaeb 1px 0 0 inset;
	}

	.post-lists-stream .post-wrapper .post-hyperlink { display: block; padding: 10px 20px; }
	.post-lists-stream .post-wrapper .post-hyperlink .post-title {
		color: rgba(0,0,0,0.8);
		font-size: 18px;
		font-weight: normal;
		line-height: 24px;
		margin: 0 0 5px;
	}

	.post-list-page .content-wrapper .post-preview-wrapper {
		background: #fff;
		bottom: 0;
		padding: 15px;
		position: absolute;
		right: 0;
		top: 0;
		width: 67%;
	}

	.post-preview-wrapper .post-wrapper { display: none; }
	.post-preview-wrapper .post-wrapper.active { display: block; }

	.post-preview-wrapper .post-wrapper .floating-header { text-transform: none; }
	.post-preview-wrapper .post-wrapper .floating-header .header-title { float: left; }
	.post-preview-wrapper .post-wrapper .floating-header .post-controls { float: right; }
	.post-preview-wrapper .post-wrapper .floating-header .post-controls .dropdown a { color: #aaa9a2; font-size: 18px; }
	.post-preview-wrapper .post-wrapper .floating-header .post-controls .dropdown ul li a {
		color: #333;
		font-size: 14px;
	}

	.post-preview-wrapper .post-wrapper .preview-wrapper {
		-moz-hyphens: auto;
		-ms-hyphens: auto;
		-webkit-hyphens: auto;
		bottom: 0;
		hyphens: auto;
		left: 0;
		overflow: auto;
		padding: 60px 7%;
		position: absolute;
		right: 0;
		top: 0;
        word-wrap: break-word;
        word-break: normal;
	}

	.post-preview-wrapper .post-wrapper .preview-wrapper .rendered-markdown {
		font-size: 1.1em;
		line-height: 1.5em;
		position: relative;
	}

	.post-preview-wrapper .post-wrapper .preview-wrapper .rendered-markdown img {
        display: block;
        height: auto;
        margin: 0 auto;
        max-width: 100%;
    }

	.post-preview-wrapper .post-wrapper .preview-wrapper .rendered-markdown code { white-space: normal; }
	.post-preview-wrapper .post-wrapper .preview-wrapper .rendered-markdown em { font-family: 'Noto Serif', serif; font-size: 16px; }
</style>
<section class="main-content post-list-page">
	<header class="page-header">
		<h1 class="hero-title">Posts</h1>
		<a href="/journal/posts/editor" class="pull-right btn btn-primary">Write a new Post</a>
	</header>
	<div class="content-wrapper">
		<section class="list-of-posts">
			<header class="floating-header">
				<span class="header-title">All Posts</span>
			</header>
			<section class="post-lists-stream">
				@foreach($posts as $key => $post)
				<article class="post-wrapper {{ ($key == 0) ? 'active' : null }}"
				data-post-id="{{ $post->id }}">
					<a href="#" class="post-hyperlink">
						<header class="post-title">{{ $post->title }}</header>
						<section class="post-meta">
							<span class="post-status">
								@if($post->status == 1)
								<time class="date-published text-muted">Published {{ convert_readable_time($post->published_at) }}</time>
								@endif
								@if($post->status == 2)
								<span class="draft text-danger">Draft</span>
								@endif
							</span>
						</section>
					</a>
				</article>
				@endforeach
			</section>
		</section>
		<section class="post-preview-wrapper">
			@foreach($posts as $key => $post)
			<article class="post-wrapper {{ ($key == 0) ? 'active' : null }}"
			data-post-id="{{ $post->id }}">
				<header class="floating-header">
					<div class="header-title">
						<span class="post-state">
							@if($post->status == 1)
							Published by
							@endif
							@if($post->status == 2)
							Written By
							@endif
						</span>
						<span class="post-author-name">{{ $post->author->name }}</span>
					</div>
					<div class="post-controls">
						<div class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
								<i class="fa fa-gear"></i>
							</a>
							<ul class="dropdown-menu dropdown-menu-right" role="menu">
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="/journal/posts/editor/{{$post->id}}">Edit</a>
								</li>
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="#">Preview</a>
								</li>
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="#"
									class="delete-this-post" data-post-id="{{ $post->id }}">Remove</a>
								</li>
							</ul>
						</div>
					</div>
				</header>
				<section class="preview-wrapper">
					<div class="rendered-markdown">
						{!! markdown($post->content) !!}
					</div>
				</section>
			</article>
			@endforeach
		</section>
	</div>
</section>
@include('admin.modal.deletemodal')
@stop
@section('footer.js')
<script type="text/javascript">
	(function($) {
		// show post to be previewed
		$('.post-hyperlink').on('click', function(e) {
			e.preventDefault();
			var $this = $(this),
				id = $this.parent().data('post-id');

			// set inactive the current active wrappers
			$('.post-wrapper').removeClass('active');
			// find wrapper
			$('.post-wrapper[data-post-id="'+id+'"]').addClass('active');

			return;

		});

		// show modal to delete post
		$('.delete-this-post').on('click', function(e) {
			e.preventDefault();
			var $this 	= $(this),
				id 		= $this.data('post-id'),
				title 	= $('.post-wrapper[data-post-id="'+id+'"]').find('.post-title').text(),
				modal 	= $('#delete_posts_modal');

			// show modal and attach needed data
			modal.modal('show').find('.post-title')
				.text(title);

			modal.find('input[name="post_id"]').val(id);

			return;
		});
	})(jQuery);
</script>
@stop
