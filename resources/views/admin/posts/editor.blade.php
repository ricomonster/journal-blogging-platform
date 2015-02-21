@extends('admin.layout')
@section('title')
Editor
@stop

@section('css')
<link href="/vendor/stylesheets/codemirror.css" rel="stylesheet">
<style>
	.post-editor-page .title-wrapper { padding: 0 5px; }
	.post-editor-page .title-wrapper input[name="title"] {
		font-size: 40px;
		font-weight: bold;
		height: 60px;
		letter-spacing: -1px;
	}

	.post-editor-page .editor-section {
		background: #fff;
		border-top: #f6f6f6 1px solid;
		bottom: 50px;
		padding: 15px;
		position: absolute;
		top: 115px;
		width: 50%;
	}

	.post-editor-page .editor-section.entry-markdown { left: 0; }
	.post-editor-page .editor-section.entry-preview { border-left: #f6f6f6 1px solid; right: 0; }

	.post-editor-page .editor-section .floating-header .header-title { float: left; }
	.post-editor-page .entry-markdown .editor-content #the_editor { display: none; }
	.post-editor-page .entry-markdown .editor-content .CodeMirror {
		bottom: 0;
		color: #3c4043;
		height: auto;
		left: 0;
		position: absolute;
		right: 0;
		top: 40px;
	}

	.post-editor-page .entry-markdown .editor-content .CodeMirror pre {
		font-family: Inconsolata, monospace;
		font-size: 1.6rem;
		line-height: 1.56em;
		padding: 0 40px;
	}

	.post-editor-page .entry-preview .floating-header .word-count { float: right; }
	.post-editor-page .entry-preview .entry-preview-content {
		-moz-hyphens: auto;
		-moz-user-select: none;
		-ms-hyphens: auto;
		-ms-user-select: none;
		-webkit-hyphens: auto;
		-webkit-user-select: none;
		bottom: 0;
		cursor: default;
		font-size: 1.8rem;
		hyphens: auto;
		left: 0;
		line-height: 1.5em;
		overflow: auto;
		padding: 0 40px 40px;
		position: absolute;
		right: 0;
		top: 40px;
		user-select: none;
		word-break: break-all;
	}

	.post-editor-page #rendered_markdown .embedded-javascript,
	.post-editor-page #rendered_markdown .embedded-iframe {
		background: #f9f9f9;
		border: none;
		font-size: 16px;
		font-weight: bold;
		margin: 15px 0;
		padding: 80px 20px;
		text-align: center;
	}

	.post-editor-page #rendered_markdown img {
        display: block;
        height: auto;
        margin: 0 auto;
        max-width: 100%;
    }

	.post-editor-page #rendered_markdown code { white-space: normal; }
	.post-editor-page #rendered_markdown em { font-family: 'Noto Serif', serif; font-size: 16px; }

	.post-editor-page .editor-publish-bar {
		-webkit-transform: translateZ(0);
		background: transparent;
		bottom: 0;
		height: 50px;
		left: 0;
		padding: 0;
		position: fixed;
		right: 0;
		transform: translateZ(0);
		z-index: 900;
	}

	.post-editor-page .editor-publish-bar .container-fluid { padding-top: 4px; }

	.post-editor-page .editor-publish-bar .post-tags {
		height: 100%;
		padding: 4px 15px 5px;
	}

	.post-editor-page .post-tags .post-tag-icon { float: left; padding: 9px 0 0; }
	.post-editor-page .post-tags .post-tags-wrapper {
		-moz-transition: width 0.2s linear;
		-webkit-overflow-scrolling: touch;
		-webkit-transition: width 0.2s linear;
		display: inline-block;
		height: 40px;
		max-width: 75%;
		overflow-x: hidden;
		overflow-y: hidden;
		padding: 3px 5px;
		position: relative;
		transition: width 0.2s linear;
		vertical-align: top;
		white-space: nowrap;
		width: auto;
	}

	.post-editor-page .post-tags .post-tags-wrapper .tags-wrapper { -webkit-overflow-scrolling: touch; padding: 5px 0; white-space: nowrap; }
	.post-editor-page .post-tags .post-tags-wrapper .tags-wrapper span.tag {
		background-color: #cccccc;
		color: #777777;
		font-size: 14px;
		margin-right: 5px;
		padding: 3px 2px 3px 7px;
	}

	.post-editor-page .post-tags .post-tags-wrapper .tags-wrapper span.tag .remove-tag {
		color: #777777;
		margin-left: 5px;
	}

	.post-editor-page .post-tags .post-tags-input { display: inline-block; padding: 5px 0; }
	.post-editor-page .post-tags .post-tags-input #tag_input { display: inline-block; vertical-align: top; }
	.post-editor-page .post-tags .post-tags-input .dropdown-menu { bottom: calc(100%); left: auto; top: auto; }

	.post-editor-page .editor-publish-bar .post-controls { text-align: right; }
	.post-editor-page .editor-publish-bar .post-controls .show-media-modal,
	.post-editor-page .editor-publish-bar .post-controls .show-settings { border-radius: 50px; padding: 10px 15px; }
	.post-editor-page .editor-publish-bar .post-controls .show-media-modal i,
	.post-editor-page .editor-publish-bar .post-controls .show-settings i { margin: 0; }

	/* Editor Sidebar */
	.editor-sidebar {
		background-color: #fafafa;
		border-left: 1px solid rgba(0,0,0,0.15);
		bottom: 0;
		outline: none;
		position: absolute;
		right: 0;
		top: 0;
		z-index: 10000;
	}

	.editor-sidebar .editor-sidebar-content {
		-khtml-opacity: .66;
		-moz-opacity: .66;
		-moz-transform: translateX(0px);
		-moz-transition: -moz-transform 200ms,opacity 200ms;
		-ms-transform: translateX(0px);
		-o-transform: translateX(0px);
		-o-transition: -o-transform 200ms,opacity 200ms;
		-webkit-overflow-scrolling: touch;
		-webkit-transform: translateX(0px);
		-webkit-transition: -webkit-transform 200ms,opacity 200ms;
		display: none;
		filter: alpha(opacity=66);
		height: 100%;
		opacity: .66;
		overflow: auto;
		padding: 15px;
		transform: translateX(0px);
		transition: transform 200ms, opacity 200ms;
		width: 280px;
	}

	.editor-sidebar .editor-sidebar-content .sidebar-header { text-align: center; }
	.editor-sidebar .editor-sidebar-content .sidebar-header .close-sidebar { color: #cccccc; display: none;  }
	.editor-sidebar .editor-sidebar-content .sidebar-header h3 { color: #333332; font-size: 22px; font-weight: 400; }
	.editor-sidebar .editor-sidebar-content .sidebar-controls {}

	.sidebar-controls label { font-size: 16px; }
	.sidebar-controls .form-control { background-color: #fafafa; color: #333332; }
	.sidebar-controls .post-date select { display: inline-block; width: 65px;}
	.sidebar-controls .post-date input { display: inline-block; }
	.sidebar-controls .post-date input[name="day"] { width: 20px; }
	.sidebar-controls .post-date input[name="year"] { width: 45px; }
	.sidebar-controls .post-date input[name="hour"] { width: 20px; }
	.sidebar-controls .post-date input[name="minute"] { width: 20px; }
	.sidebar-controls .post-date .publish-date-seperator.at,
	.sidebar-controls .post-date .publish-date-seperator.colon { padding: 0 3px; }

	.sidebar-controls .feature-image-controls .show-media-modal { margin-top: 5px; }

	.sidebar-controls .post-etc-controls .show-media-modal { display: none; margin-bottom: 10px; }
</style>
@stop

@section('body')
<section class="main-content post-editor-page">
	<header class="form-group title-wrapper">
		<input type="text" name="title" class="form-control input-lg"
		placeholder="Your post title" value="{{ ($post) ? $post->title : null }}"
		autocomplete="off"/>
	</header>
	<section class="editor-section entry-markdown">
		<header class="floating-header">
			<span class="header-title">Markdown</span>
		</header>
		<section class="editor-content">
			<textarea name="content" id="the_editor">{{ ($post) ? $post->content : null }}</textarea>
		</section>
	</section>
	<section class="editor-section entry-preview">
		<header class="floating-header">
			<span class="header-title">Preview</span>
			<span class="word-count">0 words</span>
		</header>
		<section class="entry-preview-content">
			<div class="rendered-markdown" id="rendered_markdown"></div>
		</section>
	</section>
	<footer class="editor-publish-bar">
		<div class="container-fluid">
			<input type="hidden" name="author_id" value="{{ Auth::user()->id }}"/>
			<input type="hidden" name="post_id" value="{{ ($post) ? $post->id : null }}"/>
			<input type="hidden" name="status" value="{{ ($post) ? $post->status : 2 }}"/>

			<div class="row">
				<section class="post-tags col-md-9">
					<div class="post-tag-icon">
						<i class="fa fa-tag"></i>
					</div>

					<div class="post-tags-wrapper">
						<div class="tags-wrapper"></div>
					</div>

					<div class="post-tags-input">
						<input type="text" id="tag_input" class="form-control"/>
						<ul class="dropdown-menu post-tags-suggestions" role="menu"></ul>
					</div>

					<input type="hidden" name="tags" value="{{ ($post) ?
					implode("','", $post->tags) : null }}"/>
				</section>

				<section class="post-controls col-md-3">
					{{--<a href="#" data-toggle="modal" data-target="#media_modal"--}}
					   {{--class="btn btn-default show-media-modal">--}}
						{{--<i class="fa fa-camera-retro"></i>--}}
					{{--</a>--}}
					<a href="#" class="show-settings btn btn-default">
						<i class="fa fa-gear"></i>
					</a>
					@if($post && $post->status == 1)
						<div class="btn-group dropup post-status">
							<button type="submit" class="btn btn-info"
									id="submit_post">Update Post</button>
							<button type="button" class="btn btn-info dropdown-toggle"
									data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only"></span>
							</button>
							<ul class="dropdown-menu dropdown-menu-right" role="menu">
								<li>
									<a href="#" class="set-status" data-status="3">Unpublish Post</a>
								</li>
								<li class="active">
									<a href="#" class="set-status" data-status="4">Update Post</a>
								</li>
							</ul>
						</div>
					@endif
					@if(!$post || ($post && $post->status == 2))
						<div class="btn-group dropup post-status">
							<button type="submit" class="btn btn-primary"
									id="submit_post">Save as Draft</button>
							<button type="button" class="btn btn-primary dropdown-toggle"
									data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only"></span>
							</button>
							<ul class="dropdown-menu dropdown-menu-right" role="menu">
								<li>
									<a href="#" class="set-status" data-status="1">Publish Now</a>
								</li>
								<li class="active">
									<a href="#" class="set-status" data-status="2">Save as Draft</a>
								</li>
							</ul>
						</div>
					@endif
				</section>
			</div>
		</div>
	</footer>
</section>
@section('modals')
	@include('admin.plugins.editorsidebar')
	@include('admin.modal.deletemodal')
@stop
@stop
@section('footer.js')
<script src="/vendor/javascript/codemirror.js"></script>
<script src="/vendor/javascript/showdown.js"></script>
<script src="/javascript/editor.min.js"></script>
<script src="/javascript/tags.min.js"></script>
<script type="text/javascript">
	$('.show-settings').on('click', function(e) {
		// check if the body is set to toggle the sidebar
		if($('.editor-sidebar').hasClass('open')) {
			$('body').removeClass('opened-editor-sidebar');
			$('.editor-sidebar').removeClass('open');
			return;
		}

		$('body').addClass('opened-editor-sidebar');
		$('.editor-sidebar').addClass('open');
		return;
	});

	$('.close-sidebar').on('click', function() {
		$('body').removeClass('opened-editor-sidebar');
		$('.editor-sidebar').removeClass('open');
		return;
	});
</script>
@stop
