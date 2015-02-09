@extends('core::admin.layout')
@section('title')
Media
@stop

@section('body')
<section class="main-content">
	{{ Form::open(array('id' => 'media_form', 'autocomplete' => 'off')) }}
		<section class="media-preview media-section active">
			<header class="floating-header media-tab">
				<section class="header-title">Preview</section>
			</header>
			<section class="media-content">
				<img src="{{ $media->file_location }}" class="media-image"/>
			</section>
		</section>
		<section class="media-details media-section">
			<header class="floating-header media-tab">
				<section class="header-title">Details</section>
			</header>
			<section class="media-details-content">
				<div class="details-wrapper">
					<span class="details-title">
						<i class="fa fa-calendar"></i> Uploaded:
					</span>
					<span class="details-value">{{ date('M d, Y @ H:i', strtotime($media->date_uploaded)) }}</span>
				</div>
				<div class="form-group details-wrapper">
					<label class="control-label" for="media_title">Title</label>
					<input type="text" name="media_title" class="form-control"
					value="{{ $media->name }}"/>
				</div>

				<div class="form-group details-wrapper">
					<label class="control-label" for="caption">Caption</label>
					<textarea name="caption" class="form-control"></textarea>
				</div>

				<div class="form-group">
					<label class="control-label" for="alternative_text">Alt Text</label>
					<textarea name="alternative_text" class="form-control"></textarea>
				</div>

				<div class="details-wrapper">
					<span class="details-title">File name:</span>
					<span class="details-value">{{ $media->file_name }}</span>
				</div>

				<div class="details-wrapper">
					<span class="details-title">File Type:</span>
					<span class="details-value">{{ strtoupper($media->file_extension) }}</span>
				</div>

				@if($media->width || $media->height)
				<div class="details-wrapper">
					<span class="details-title">Dimensions:</span>
					<span class="details-value">
						{{ $media->width.' x '.$media->height }}
					</span>
				</div>
				@endif

				<div class="form-action">
					<input type="hidden" name="id" value="{{ $media->id }}"/>
					<button type="submit" class="btn btn-primary">Save Changes</button>

					<a href="#" class="btn btn-danger" id="delete_media">
						<i class="fa fa-trash"></i> Delete
					</a>
				</div>
			</section>
		</section>
	{{ Form::close() }}
</section>

@section('modals')
@include('core::admin.modal.deletemedia')
@stop
@stop
@section('footer.js')
<script type="text/javascript">
	(function($) {
		$('#media_form').on('submit', function(e) {
			e.preventDefault();
			var $this = $(this);

			// disable submit button
			$this.find('button[type="submit"]').attr('disabled', 'disabled')
				.addClass('btn-disabled');

			// ajax
			$.ajax({
				type : 'post',
				url : '/api/v1/media/update',
				data : $this.serialize(),
				dataType : 'json'
			}).done(function(response) {
				if(response.data) {
					$this.find('button[type="submit"]').removeAttr('disabled')
						.removeClass('btn-disabled');
					notification(response.data.message, 'success');
				}
			}).error(function(errors) {
				if(errors.responseJSON) {
					notification(errors.responseJSON.errors.message, 'danger');
				}

				$this.find('button[type="submit"]').removeAttr('disabled')
					.removeClass('btn-disabled');
			});
		});

		// show delete media modal
		$('#delete_media').on('click', function(e) {
			e.preventDefault();
			var $this = $(this),
				modal = $('#delete_media_modal'),
				mediaId = $('input[name="id"]').val(),
				mediaTitle = $('input[name="media_title"]').val();

			// show modal
			modal.modal('show').find('.media-title').text(mediaTitle);
			modal.find('input[name="media_id"]').val(mediaId);

			return;
		});

		// delete media
		$('#delete_now').on('click', function(e) {
			e.preventDefault();
			var $this 	= $(this),
				modal 	= $('#delete_media_modal'),
				mediaId = modal.find('input[name="media_id"]').val();

			// trigger ajax call
			$.ajax({
				type : 'post',
				url : '/api/v1/media/delete',
				data : {
					id : mediaId
				},
				dataType : 'json'
			}).done(function(response) {
				if (response.data) {
					// redirect page
					window.location.href = '/journal/media';
				}
			}).error(function(error) {
				notification(error.responseJSON.errors.message, 'danger');
			});
		});

		// in responsive mode
		$('.media-tab').on('click', function() {
			var $this = $(this);

			// remove current active tab
			$('.media-section.active').removeClass('active');
			// set to active
			$this.parent('.media-section').addClass('active');
		})
	})(jQuery);
</script>
@stop
