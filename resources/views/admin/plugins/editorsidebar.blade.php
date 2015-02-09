<aside class="editor-sidebar">
	<div class="editor-sidebar-content">
		<header class="sidebar-header">
			<a href="#" class="close-sidebar pull-right"><i class="fa fa-close"></i></a>
			<h3>Settings</h3>
		</header>
		<section class="sidebar-controls">
			<div class="form-group feature-image-controls">
				<label class="control-label">Featured Image</label>
				<div class="featured-image-wrapper">

				</div>
				<a href="#" class="show-media-modal btn btn-success btn-block"
				data-target="featured-image">Upload Image</a>
			</div>

			<div class="form-group">
				<label class="control-label">Post URL</label>
				<input type="text" name="slug" class="form-control"
				placeholder="{{ Request::root() }}" value=""/>
			</div>

			<div class="form-group">
				<label class="control-label">Publish Date</label>
				<div class="post-date">
					<select name="month" class="form-control">
						@foreach($months as $key => $month)
						<option value="{{ $key }}">
							{{ $month }}
						</option>
						@endforeach
					</select>
					<input type="text" name="day" class="form-control"
					value="{{ (empty($post)) ?
					date('d', strtotime($datetime)) : date('d', strtotime($post->published_at)) }}">
					<span class="publish-date-seperator comma">,</span>
					<input type="text" name="year" class="form-control"
					value="{{ (empty($post)) ?
					date('Y', strtotime($datetime)) : date('Y', strtotime($post->published_at)) }}">
					<span class="publish-date-seperator at">@</span>
					<input type="text" name="hour" class="form-control"
					value="{{ (empty($post)) ?
					date('H', strtotime($datetime)) : date('H', strtotime($post->published_at)) }}">
					<span class="publish-date-seperator colon">:</span>
					<input type="text" name="minute" class="form-control"
					value="{{ (empty($post)) ?
					date('i', strtotime($datetime)) : date('i', strtotime($post->published_at)) }}">
				</div>
			</div>

			<div class="post-etc-controls">
				<a href="#" data-toggle="modal" data-target="#media_modal"
				class="btn btn-default btn-block show-media-modal">
					<i class="fa fa-camera-retro"></i>
					Add Media
				</a>

				<a href="#" class="delete-this-post text-danger"
				{{ (empty($post)) ? 'style="display: none;' : null }}>
					<i class="fa fa-trash-o"></i> Delete this post
				</a>
			</div>
		</section>
	</div>
</aside>
