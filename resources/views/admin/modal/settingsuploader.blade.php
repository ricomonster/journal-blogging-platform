<style type="text/css">
    #settings_uploader_modal .modal-body { padding: 10px 15px; }
    #settings_uploader_modal .image-upload-preview { display: none; margin-bottom: 15px; }
    #settings_uploader_modal .image-upload-preview img { display: block; margin: auto; width: 100%; }

	#settings_uploader_modal .image-uploader { display: none; padding: 15px 0; }
    #settings_uploader_modal .image-uploader.active { display: block; }
	#settings_uploader_modal .image-uploader .progress { display: none; margin-bottom: 0; }
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

    #settings_uploader_modal .url-image-uploader { display: none; padding: 0 50px; }
    #settings_uploader_modal .url-image-uploader.active { display: block; }
    #settings_uploader_modal .url-image-uploader .form-group {
        border-bottom: 0;
        margin-bottom: 6px;
    }

    #settings_uploader_modal .upload-controls .remove-image { display: none; }
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
            <form enctype="multipart/form-data" id="settings_uploader_form" method="post" autocomplete="off">
                <section class="modal-body">
                    <div class="uploader">
                        <section class="image-upload-preview">
                            <img src=""/>
                        </section>
                        <section class="image-uploader active">
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
                        <section class="url-image-uploader">
                            <div class="form-group">
                                <label class="control-label" for="image_url">Image URL</label>
                                <input type="text" name="image_url" class="form-control"
                                placeholder="http://"/>
                            </div>
                        </section>
                    </div>
                </section>
                <footer class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>

                    <div class="upload-controls pull-right">
                        <input type="hidden" name="setting_name" value=""/>

                        <a href="#" class="btn btn-info upload-option traditional">
                            <i class="fa fa-external-link-square"></i>
                        </a>

                        <a href="#" class="btn btn-danger remove-image">
                            <i class="fa fa-trash-o"></i>
                        </a>

                        <button type="submit" class="btn btn-primary" id="submit_upload">
                            Save Changes
                        </button>
                    </div>
                </footer>
            </form>
        </section>
    </section>
</article>
