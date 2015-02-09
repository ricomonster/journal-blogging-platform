@extends('core::admin.layout')
@section('title')
Upload New Media
@stop

@section('body')
<section class="main-content media-uploader">
	<header class="page-header">
		<h1 class="hero-title">Add Media</h1>
	</header>
	<section class="content-wrapper centralized">
		{{ Form::open(array('id' => 'media_uploader_form', 'class' => 'form-contents', 'files' => true)) }}
			<span class="btn btn-primary btn-file btn-lg">
				Add Image <input type="file" name="files[]" class="media-uploader" multiple>
			</span>
		{{ Form::close() }}

		<div class="progress">
			<div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100">
				<span class="sr-only"></span>
			</div>
		</div>

		<div class="uploaded-lists-wrapper">
			<ul class="uploaded-lists">
			</ul>
		</div>
	</section>
@stop
@section('footer.js')
<script src="{{ asset(journal_path('js/plugins/jquery.form.min.js')) }}"></script>
<script type="text/javascript">
	(function($) {
		$('.media-uploader').on('change', function(e) {
			e.preventDefault();
			console.log('fucker');
			$('#media_uploader_form').ajaxSubmit({
				url : '/api/v1/media/upload',
				dataType : 'json',
				beforeSend: function() {
					$('.progress').show().find('.progress-bar').width('0%');
				},
				uploadProgress: function(event, position, total, percentComplete) {
					var percentVal = percentComplete + '%';
					$('.progress-bar').width(percentVal);
				},
				success: function(response) {
					if(response.data) {
						console.log(response.data);
						$('.uploaded-lists-wrapper').show();

						var mediaLists = $('.uploaded-lists'),
							medias = response.data.media;
						// loop!
						$.each(medias, function(i, data) {
							mediaLists.append('<li class="clearfix"><img src="'+data.thumbnail_location+'" class="media-thumbnail"/>' +
								'<div class="media-details"><a href="/journal/media/'+data.id+'/canvas" ' +
								'class="pull-right">Edit Post</a><h3 class="media-name">'+data.name+'</h3></div></li>');
						});

						return;
					}
				}
			});
		});
	})(jQuery);
</script>
@stop
