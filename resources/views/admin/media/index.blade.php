@extends('core::admin.layout')
@section('title')
Media Library
@stop

@section('body')
<section class="main-content">
	<header class="page-header">
		<h1 class="hero-title">Media</h1>
	</header>
	<section class="content-wrapper centralized">
		<ul class="media-lists">
			@foreach($medias as $key => $media)
			<li>
				<a href="/journal/media/{{ $media->id }}/canvas">
					<img src="{{ $media->thumbnail_location }}" class="media-thumbnail"/>
					<div class="media-details">
						<h3>{{ $media->name }}</h3>
						<span class="media-mime text-muted">{{ $media->mime_type }}</span>
					</div>
					<div class="clearfix"></div>
				</a>
			</li>
			@endforeach
		</ul>
	</section>
</section>
@stop
@section('footer.js')
<script>
	(function($) {

	})(jQuery);
</script>
@stop
