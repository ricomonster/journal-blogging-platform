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
					<option value="" selected>-- Select your theme --</option>
					@foreach($themes as $key => $theme)
					<option value="{{ $key }}">{{ $theme }}</option>
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
<script type="text/javascript">
    //$('#settings_uploader_modal').modal('show');

    var SettingsUploader = {
        /**
         * Initializes the settings uploader script
         */
        init : function() {
            this.bindEvents();
        },
        /**
         * Listen for events to be triggered
         */
        bindEvents : function() {
            $(document)
                .on('blur', 'input[name="image_url"]', this.getImageUrlPreview)
                .on('change', '.file-uploader', this.readUrl)
                .on('click', '.upload-option', this.setUploadOption)
                .on('click', '.upload-image', this.showModalUploader)
                .on('submit', '#settings_uploader_form', this.submitUpload);
        },
        /**
         * Listens for url to be inputted
         */
        getImageUrlPreview : function() {
            var $this = $(this);

            // check if input is empty
            if ($this.val().length != 0) {
                $('.image-upload-preview').find('img')
                    .attr('src', '');

                SettingsUploader.previewImageUrl($this.val());
            }

            return;
        },
        /**
         * Shows the preview of the image based on the given url
         */
        previewImageUrl : function(url) {
            $('.image-upload-preview').find('img').attr('src', url).load(function() {
                // check if image is valid
                if(this.complete || typeof this.naturalWidth != "undefined" || this.naturalWidth != 0) {
                    // show preview wrapper
                    $('.image-upload-preview').show();
                }
            });

            return;
        },
        /**
         * Read the file and shows a preview
         */
        readUrl : function() {
            var input = this;

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    // show the preview
                    $('.image-upload-preview').show().find('img')
                            .attr('src', e.target.result);
                    // hide upload option and the uploader
                    $('.image-uploader').hide().find('.btn-file').hide();
                    // show delete button
                    $('.remove-image').show();
                };

                reader.readAsDataURL(input.files[0]);
            }
        },
        /**
         * Shows the image uploader and initializes the necessary data
         */
        showModalUploader : function(e) {
            e.preventDefault();
            var $this = $(this),
                    modal = $('#settings_uploader_modal'),
                    settingType = $this.data('setting'),
                    imageUrl = '';

            // reset contents of modal
            SettingsUploader.resetModal();

            // set necessary info in the modal
            modal.find('input[name="setting_name"]').val(settingType);

            // check if there's an image set
            if ($this.siblings('.image-wrapper').hasClass('active')) {
                // get image
                imageUrl = $this.siblings('.image-wrapper').find('img').attr('src');
                // set necessary details and info in the modal
                // show the image
                $('.image-upload-preview').show().find('img')
                    .attr('src', imageUrl);
                $('input[name="image_url"]').val(imageUrl);

                $('.image-uploader').removeClass('active');
                $('.url-image-uploader').removeClass('active');

                // show remove image
                $('.remove-image').show();
                // hide upload option
                $('.upload-option').hide();
            }

            // show modal
            modal.modal('show');
            return;
        },
        /**
         * Selects which method to use in uploading an image
         */
        setUploadOption : function(e) {
            e.preventDefault();
            var $this = $(this);

            // hide preview
            $('.image-upload-preview').hide().find('img').attr('src', '');

            // check if there's a class of traditional
            if ($this.hasClass('traditional')) {
                // hide uploader
                $('.image-uploader').removeClass('active');
                // show url input
                $('.url-image-uploader').addClass('active');
                // remove traditional class and update contents
                $this.removeClass('traditional').addClass('url-image')
                    .find('i.fa').removeClass('fa-external-link-square')
                    .addClass('fa-camera-retro');

                // check if input is empty
                if ($('input[name="image_url"]').val().length != 0) {
                    SettingsUploader.previewImageUrl($('input[name="image_url"]').val());
                }

                return;
            }

            // show uploader
            $('.image-uploader').addClass('active');
            // hide url input
            $('.url-image-uploader').removeClass('active');
            // remove url-image class and update contents
            $this.removeClass('url-image').addClass('traditional')
                .find('i.fa').removeClass('fa-camera-retro')
                .addClass('fa-external-link-square');
            return;
        },
        /**
         * Submits the content of the form
         */
        submitUpload : function(e) {
            e.preventDefault();
            var form = $(this);

            // disable the buttons
            //form.find('.btn').addClass('btn-disabled').attr('disabled', 'disabled');

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
                            // find the wrapper and apply the image
                            $('#' + key).find('.image-wrapper').addClass('active')
                                .find('img').attr('src', value);
                        });

                        // close the modal
                        $('#settings_uploader_modal').modal('hide');
                    }
                }
            })
        },
        /**
         * Reset the modal
         */
        resetModal : function() {
            $('.image-upload-preview').hide().find('img')
                .attr('src', '');

            $('.image-uploader').addClass('active')
                .find('.progress-bar').css('width', 0);

            $('.url-image-uploader').removeClass('active');

            $('.upload-option').removeClass('url-image').addClass('traditional')
                .find('i.fa').removeClass('fa-camera-retro')
                .addClass('fa-external-link-square');

            // empty inputs
            $('input[name="image_url"]').val('');
            $('input[name="setting_name"]').val('');

            $('input[name="files"]').replaceWith($('input[name="files"]')
                .val('').clone(true));

            return;
        }
    };

    SettingsUploader.init();
</script>
@stop
