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
			.on('blur', 'input[name="url_of_image"]', this.getImageUrlPreview)
			.on('change', '.file-uploader', this.readUrl)
			.on('click', '.remove-image', this.removeImage)
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

			$('input[name="image_url"]').val($this.val());

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
				$('.image-uploader').removeClass('active')
					.find('.btn-file').hide();
				// show delete button
				$('.remove-image').show();
				// hide upload option
				$('.upload-option').hide();
			};

			reader.readAsDataURL(input.files[0]);
		}
	},
	removeImage : function(e) {
		e.preventDefault();

		// hide this
		$(this).hide();
		// empty the image url hidden input
		$('input[name="image_url"]').val('');
		// empty the other input element
		$('input[name="url_of_image"]').val('');

		// check first the wrappers class
		if ($('.upload-option').hasClass('traditional')) {
			// remove the preview of the image
			$('.image-upload-preview').hide().find('img')
				.attr('src', '');

			// show the button
			$('.image-uploader').addClass('active')
				.find('.btn.btn-file').show();

			// empty the input file
			$('input[name="files"]').replaceWith($('input[name="files"]')
				.val('').clone(true));

			// show upload option
			$('.upload-option').show();

			return;
		}
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
		$('input[name="url_of_image"]').val('');
		$('input[name="image_url"]').val('');
		$('input[name="setting_name"]').val('');

		$('input[name="files"]').replaceWith($('input[name="files"]')
			.val('').clone(true));

		return;
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
			if ($('input[name="url_of_image"]').val().length != 0) {
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
	}
};

SettingsUploader.init();
