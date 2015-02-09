@extends('admin.layout')
@section('title')
Appearance
@stop

@section('body')
<style type="text/css">
	.appearance-settings-page .page-header { padding: 20px 0 0; }
</style>
<section class="main-content appearance-settings-page centered">
	<header class="page-header">
		<h1 class="hero-title">Appearance</h1>
	</header>
	<section class="content-wrapper centralized">
		<form method="post" id="appearance_settings_form" autocomplete="off">
			<div class="form-group">
				<label class="control-label" for="blog_logo">Blog logo</label>
				<a href="#" class="btn btn-success pull-right">Upload image</a>
				<div class="form-uploader">

				</div>
				<span class="help-block">Set a logo for your blog.</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="blog_cover">Blog cover</label>
				<a href="#" class="btn btn-success pull-right">Upload image</a>
				<div class="form-uploader">

				</div>
				<span class="help-block">Set a cover image for your blog.</span>
			</div>
			<div class="form-action">
				<button type="submit" class="btn btn-primary">Save Changes</button>
			</div>
		</form>
	</section>
</section>
@stop
@section('modals')
	@include('admin.modal.settingsuploader')
@stop
@section('footer.js')
<script type="text/javascript">
	$('#settings_uploader_modal').modal('show');
</script>
<script type="text/javascript">
	(function($) {
		var readUrl = function(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function (e) {
					// hide the uploader
					$('.btn-file').hide();
					// show the preview
					$('.image-upload-preview').show().find('img')
							.attr('src', e.target.result);
					// hide upload option
					//$('.upload-option').hide();
					// show delete button
					//$('.remove-image').show();
				}

				reader.readAsDataURL(input.files[0]);
			}
		};

		$('.file-uploader').on('change', function() {
			// create preview of the image to be uploaded
			readUrl(this);
		});


	})(jQuery);
</script>
@stop
