<style type="text/css">
	#settings_uploader_modal .modal-body .uploader img { display: block; margin: auto; width: 600px; }
	#settings_uploader_modal .image-uploader { padding: 15px 0; }
	#settings_uploader_modal .image-uploader .progress { display: none; }
	#settings_uploader_modal .image-uploader .btn {
		margin-left: 1px;
		overflow: hidden;
		position: relative;
	}

	#settings_uploader_modal .image-uploader .btn input[type="file"] {
		background: #fff;
		cursor: inherit;
		display: block;
		filter: alpha(opacity=0);
		font-size: 999px;
		min-height: 100%;
		min-width: 100%;
		opacity: 0;
		outline: 0;
		position: absolute;
		right: 0;
		text-align: right;
		top: 0;
	}
</style>
<article class="modal fade" id="settings_uploader_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <section class="modal-dialog modal-lg" style="width: 650px;">
        <section class="modal-content">
            <header class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">Upload Image</h4>
            </header>
			<section class="modal-body">
				<div class="uploader">
					<section class="image-upload-preview">
						<img src="https://cdn1.vox-cdn.com/thumbor/58_Ny8vbE-48vMr4jaCSIlhwQPA=/357x0:2039x1121/800x536/cdn0.vox-cdn.com/uploads/chorus_image/image/45639858/DSC_2499.0.jpg"/>
					</section>
					<section class="image-uploader">
						<div class="progress upload-progress">
							<div class="progress-bar progress-bar-striped" aria-valuemin="0" aria-valuemax="100">
								<span class="sr-only"></span>
							</div>
						</div>
						<span class="btn btn-primary btn-file btn-lg">
							Add Image
							<input type="file" name="files" class="file-uploader">
						</span>
					</section>
					<section class="url-image-uploader"></section>
				</div>
			</section>
			<footer class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>

				<div class="upload-controls pull-right">
					<a href="#" class="btn btn-info upload-option traditional">
						<i class="fa fa-external-link-square"></i>
					</a>

					<a href="#" class="btn btn-danger remove-image">
						<i class="fa fa-trash-o"></i>
					</a>

					<button type="submit" class="btn btn-primary upload-image">
						Save Changes
					</button>
				</div>
			</footer>
        </section>
    </section>
</article>
