@extends('admin.layout')
@section('title')
Appearance
@stop

@section('body')
<style type="text/css">
	.appearance-settings-page .page-header { padding: 5px 0 15px; }
    .appearance-settings-page form .image-wrapper { display: none; margin: 30px 0 10px; padding: 0 20px; }
    .appearance-settings-page form .image-wrapper.active { display: block; }
    .appearance-settings-page form .image-wrapper img { display: block; width: 100%; margin: auto; }
</style>
<section class="main-content appearance-settings-page centered">
	<header class="page-header">
		<h1 class="hero-title">Appearance</h1>
	</header>
	<section class="content-wrapper centralized">
		<form method="post" id="appearance_settings_form" autocomplete="off">
			<div class="form-group">
				<label class="control-label" for="themes">Themes</label>
				<select name="theme" class="form-control">
					<option value="" {{ (!$settings->theme) ? 'selected' : null }}>-- Select your theme --</option>
					@foreach($themes as $key => $theme)
					<option value="{{ $key }}" {{ ($settings->theme == $key) ?
					'selected' : null }}>{{ $theme }}</option>
					@endforeach
				</select>
				<span class="help-block">Select the theme of your blog.</span>
			</div>
			<div class="form-group" id="blog_logo">
				<label class="control-label" for="blog_logo">Blog logo</label>
				<a href="#" class="btn btn-success pull-right upload-image"
                data-setting="blog_logo">Upload image</a>
				<div class="image-wrapper clearfix {{ ($settings->blog_logo) ? 'active' : null }}">
                    <img src="{{ $settings->blog_logo }}"/>
				</div>
				<span class="help-block">Set a logo for your blog.</span>
			</div>
			<div class="form-group" id="blog_cover">
				<label class="control-label" for="blog_cover">Blog cover</label>
				<a href="#" class="btn btn-success pull-right upload-image"
                data-setting="blog_cover">Upload image</a>
				<div class="image-wrapper clearfix {{ ($settings->blog_cover) ? 'active' : null }}">
                    <img src="{{ $settings->blog_cover }}"/>
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
<script src="/vendor/javascript/jquery.form.js"></script>
<script src="{{ asset('assets/javascript/uploader.min.js') }}"></script>
<script type="text/javascript">
	(function($) {
		$('#appearance_settings_form').on('submit', function(e) {
			e.preventDefault();
			var form = $(this);

			// disable button
			form.find('button[type="submit"]').addClass('btn-disable')
				.attr('disabled', 'disabled');

			$.ajax({
				type : 'post',
                url : '/api/v1/settings/update_theme',
                data : {
                    theme : $('select[name="theme"] :selected').val()
                },
                dataType : 'json'
			}).done(function(response) {
                form.find('button[type="submit"]').removeClass('btn-disable')
                    .removeAttr('disabled');

                if (response.data) {
                    // show success message
                    Journal.notification(response.data.message, 'success');
                }
            }).error(function(error) {
                form.find('button[type="submit"]').removeClass('btn-disable')
                    .removeAttr('disabled');

                Journal.notification(error.responseJSON.errors.message, 'danger');
            })
		});

        // handles image upload
        $('#uploader_form').on('submit', function(e
        ) {
            e.preventDefault();
            var form = $(this);

            // disable the buttons
            form.find('.btn').addClass('btn-disabled')
                .attr('disabled', 'disabled');

            // upload
            form.ajaxSubmit({
                url : '/api/v1/settings/upload-image',
                dataType : 'json',
                beforeSend : function() {
                    // check if there's a file
                    var hasFile = $('input[type="files"]').filter(function() {
                                return $.trim(this.value) != ''
                            }).length > 0;

                    // if there's a file to be uploaded, show the progress bar
                    if (hasFile) {
                        $('.image-uploader').show().find('.progress').show();
                    }
                },
                uploadProgress : function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    $('.image-uploader').find('.progress-bar')
                            .css('width', percentVal);
                },
                success : function(response) {
                    if (response.data) {
                        // reset the modal
                        SettingsUploader.resetModal();
                        // apply the image to the appearance page
                        $.each(response.data.settings, function(key, value) {
                            if (value == '') {
                                $('#' + key).find('.image-wrapper').removeClass('active')
                                        .find('img').attr('src', '');
                            }

                            if (value != '') {
                                // find the wrapper and apply the image
                                $('#' + key).find('.image-wrapper').addClass('active')
                                        .find('img').attr('src', value);
                            }
                        });

                        // close the modal
                        $('#settings_uploader_modal').modal('hide');
                    }
                }
            })
        });
	})(jQuery);
</script>
@stop
