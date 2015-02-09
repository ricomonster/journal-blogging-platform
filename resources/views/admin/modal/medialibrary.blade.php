<article class="modal fade" id="media_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <section class="modal-dialog">
        <section class="modal-content">
            <header class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </header>
            <section class="modal-body">
				<nav class="media-options">
					<ul class="nav nav-tabs" role="tablist">
						<li class="active">
							<a href="#insert_media" role="tab" class="select-tab"
							data-toggle="tab">Insert Media</a>
						</li>
						<li>
							<a href="#media_library" role="tab" class="select-tab"
							data-toggle="tab">Media Library</a>
						</li>
						<li>
							<a href="#insert_from_url" role="tab" class="select-tab"
							data-toggle="tab">Insert from URL</a>
						</li>
                    </ul>
				</nav>
				<div class="tab-content">
					<section class="tab-pane active" id="insert_media">
						<header class="modal-page-header">
							<h2 class="hero-title">Insert Media</h2>
						</header>
						<section class="media-pane-content">
							{{ Form::open(array('id' => 'insert_media_form', 'files' => true)) }}
								<span class="btn btn-primary btn-file btn-lg">
									Select Files <input type="file" name="files[]" class="media-uploader" multiple>
								</span>
							{{ Form::close() }}
						</section>
					</section>
					<section class="tab-pane" id="media_library">
						<header class="modal-page-header">
							<h2 class="hero-title">Media Library</h2>
						</header>
						<section class="media-pane-content">
							<ul class="media-lists"></ul>
						</section>
					</section>
					<section class="tab-pane" id="insert_from_url">
						<header class="modal-page-header">
							<h2 class="hero-title">Insert from URL</h2>
						</header>
						<section class="media-pane-content">
							{{ Form::open(array('id' => 'insert_from_url_form')) }}
								<div class="form-group">
									<input type="text" name="media_url" class="form-control input-lg"
									placeholder="http://"/>
								</div>
							{{ Form::close() }}

							<div class="insert-from-url-preview row">
								<div class="col-md-5 image-preview thumbnail">
									<img src="" class="image-from-url"/>
								</div>
								<div class="col-md-7 image-details">
									<div class="form-group">
										<label class="control-label">Alt Text</label>
										<input type="text" name="alt_text" class="form-control"/>
									</div>
								</div>
							</div>
						</section>
					</section>
				</div>
            </section>
            <footer class="modal-footer">
            	<div class="progress media-library-progress">
					<div class="progress-bar progress-bar-striped" role="progressbar"
					aria-valuemin="0" aria-valuemax="100">
						<span class="sr-only"></span>
					</div>
				</div>

            	<button type="button" class="btn btn-default" data-dismiss="modal">
            		Cancel
            	</button>
				<button type="button" class="btn btn-primary btn-disabled"
				id="insert_into_posts" disabled>Insert into Post</button>
            </footer>
        </section>
    </section>
</article>
