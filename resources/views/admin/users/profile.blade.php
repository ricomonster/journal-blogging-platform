@extends('core::admin.layout')
@section('title')
{{ $user->name }}
@stop

@section('body')
<section class="main-content profile-page">
	<aside class="profile-controls">
		@if(Auth::user()->id == $user->id)
		<a href="#" class="btn btn-info edit-profile">Edit Profile</a>
		<a href="#" class="btn btn-success show-upload-image" data-setting="cover_url">Change cover</a>
		<a href="#" class="btn btn-danger exit-edit">Exit</a>
		@endif

		@if(Auth::user()->role == 1 && Auth::user()->id != $user->id)
		<div class="dropdown change-role-wrapper">
			<a class="btn btn-success dropdown-toggle" id="change_role" data-toggle="dropdown">
				Change Role
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="change_role">
				<li role="presentation" {{ ($user->role == 1) ? ' class="active"' : null }}>
					<a role="menuitem" tabindex="-1" href="#" data-id="{{ $user->id }}"
					class="change-role" data-role="administrator">Administrator</a>
				</li>
				<li role="presentation" {{ ($user->role == 2) ? ' class="active"' : null }}>
					<a role="menuitem" tabindex="-1" href="#" data-id="{{ $user->id }}"
					class="change-role" data-role="editor">Editor</a>
				</li>
			</ul>
		</div>
		<a href="#" class="btn btn-danger deactivate-user" data-toggle="modal"
		data-target="#deactivate_user_modal">
			Deactivate User
		</a>
		@endif
	</aside>
	<header class="profile-header" id="cover_url">
		<div class="header-image-wrapper">
			<div class="header-image" style="background-image: url('{{ (empty($user->cover_url)) ?
			asset(journal_path('img/shared/default_cover.jpg')) : $user->cover_url }}')"></div>
		</div>
	</header>
	<section class="content-wrapper centralized">
		{{ Form::open(array(
			'id' => 'profile_edit_form', 'class' => 'form-contents', 'autocomplete' => 'off'))
		}}
			<figure class="avatar-wrapper" id="avatar_url">
				<img src="{{ (empty($user->avatar_url)) ?
				asset(journal_path('img/shared/default_avatar.png')) : $user->avatar_url }}"
				class="img-circle img-thumbnail avatar-image show-upload-image"
				data-setting="avatar_url"/>
				<input type="hidden" name="avatar_url" value="{{ (empty($user->avatar_url)) ?
				null : $user->avatar_url }}"/>
			</figure>

			<div class="form-group hero-title-wrapper">
				<h2 class="hero-title profile-content profile-name"
				id="profile_name">{{ $user->name }}</h2>
				<input type="text" name="name" class="form-control input-lg"
				value="{{ $user->name }}"/>
			</div>

			<div class="profile-details user-role">
				@if($user->role == 1)
				<span class="label label-success">Administrator</span>
				@else
				<span class="label label-info">Editor</span>
				@endif
			</div>

			<div class="form-group">
				<p class="profile-biography profile-content"
				id="profile_biography">{{ $user->biography }}</p>
				<textarea name="biography" class="form-control">{{ $user->biography }}</textarea>
			</div>

			<div class="form-group">
				<small class="profile-label">Email</small>
				<p class="profile-email profile-content"
				id="profile_email">{{ $user->email }}</p>
				<input type="email" name="email" class="form-control" value="{{ $user->email }}"/>
			</div>

			<div class="form-group">
				<small class="profile-label">Website</small>
				<p class="profile-website profile-content">
					<a href="{{ $user->website }}" id="profile_website">{{ $user->website }}</a>
				</p>
				<input type="text" name="website" class="form-control" value="{{ $user->website }}"/>
			</div>

			<div class="profile-details">
				<small class="profile-label">Last Login</small>
				<p class="profile-last-login profile-content">
					@if($user->last_login)
					<time>{{ date('F d, Y @ h:i', strtotime($user->login_at)) }}</time>
					@else
					Never logged in
					@endif
				</p>
			</div>

			<div class="form-action">
				<input type="hidden" name="cover_url" value="{{ (empty($user->cover_url)) ?
				null : $user->cover_url }}"/>

				<button type="submit" class="btn btn-primary">Save Changes</button>
			</div>
		{{ Form::close() }}

		<div class="clearfix"></div>

		@if(Auth::user()->id == $user->id)
		{{ Form::open(array(
			'id' => 'password_edit_form', 'class' => 'form-contents', 'autocomplete' => 'off'))
		}}
			<div class="form-group">
				<label class="control-label profile-label">Current Password</label>
				<input type="password" name="current_password" class="form-control"/>
			</div>

			<div class="form-group">
				<label class="control-label profile-label">New Password</label>
				<input type="password" name="new_password" class="form-control"/>
			</div>

			<div class="form-group">
				<label class="control-label profile-label">Repeat New Password</label>
				<input type="password" name="repeat_password" class="form-control"/>
			</div>

			<div class="form-action">
				<button type="submit" class="btn btn-primary">Update Password</button>
			</div>
		{{ Form::close() }}
		@endif
	</section>
</section>
@stop

@section('modals')
@include('core::admin.modal.settingsuploader')
@if(Auth::user()->role == 1 && Auth::user()->id != $user->id)
@include('core::admin.modal.deactivatemodal')
@endif
@stop

@section('footer.js')
@if(Auth::user()->id == $user->id)
<script src="{{ asset(journal_path('js/plugins/jquery.form.min.js')) }}"></script>
<script src="{{ asset(journal_path('js/sitefunc/uploader-modal.min.js')) }}"></script>
<script>
	(function() {
		var defaultAvatarImage = "{{ asset(journal_path('img/shared/default_avatar.png')) }}",
			defaultCoverImage = "{{ asset(journal_path('img/shared/default_cover.jpg')) }}";

		$('.edit-profile').on('click', function(e) {
			e.preventDefault();
			$('.main-content').addClass('edit-state');
			return;
		});

		$('.exit-edit').on('click', function(e) {
			e.preventDefault();
			$('.main-content').removeClass('edit-state');
			return;
		});

		// upload and save image settings
		$('#settings_uploader').on('submit', function(e) {
			e.preventDefault();
			var $this = $(this);

			// find submit button and disable it
			$this.find('button[type="submit"]').addClass('btn-disabled')
				.attr('disabled', 'disabled');
			// trigger ajax upload
			$this.ajaxSubmit({
				url : '/api/v1/user/{{ $user->id }}/upload-images',
				dataType : 'json',
				beforeSend: function() {
					var hasFile = $('input[type=file]').filter(function(){
						return $.trim(this.value) != ''
					}).length  > 0 ;

					// show progress bar if there is a file to be uploaded
					if(hasFile) {
						$('.image-upload-progress').show();
					}
				},
				uploadProgress: function(event, position, total, percentComplete) {
					var percentVal = percentComplete + '%';
					$('.image-upload-progress').find('.progress-bar')
						.css('width', percentVal);
				},
				success : function(response) {
					if(response.data) {
						var user = response.data.user;

						if($('#settings_modal').find('input[name="setting_type"]').val() == 'cover_url') {
							$('input[name="cover_url"]').val(user.cover_url);

							var cover = (user.cover_url == null || user.cover_url == '') ?
								defaultCoverImage : user.cover_url;

							$('#cover_url').find('.header-image')
								.css('background-image', 'url('+cover+')');
						} else {
							// avatar
							$('input[name="avatar_url"]').val(user.avatar_url);
							var avatar = (user.avatar_url == null || user.avatar_url == '') ?
								defaultAvatarImage : user.avatar_url;

							$('#avatar_url').find('img').attr('src', avatar);
						}

						// close modal
						$('#settings_modal').modal('hide');
						// reset contents
						resetToDefaultModal();
						// notification
						notification('Saved', 'success');
					}
				},
				error : function(errors) {
					// reset the button
					$this.find('button[type="submit"]').removeClass('btn-disabled')
						.removeAttr('disabled');
					notification(errors.responseJSON.errors.message, 'danger');
				}
			})
		});

		// submiting the form
		$('#profile_edit_form').on('submit', function(e) {
			e.preventDefault()
			var $this = $(this);

			$this.find('button[type="submit"]').attr('disabled', 'disabled')
				.addClass('btn-disabled');

			$.ajax({
				type : 'post',
				url : '/api/v1/user/{{ $user->id }}/update_profile',
				data : {
					id 			: $this.find('input[name="id"]').val(),
					email 		: $this.find('input[name="email"]').val(),
					name 		: $this.find('input[name="name"]').val(),
					website 	: $this.find('input[name="website"]').val(),
					biography 	: $this.find('textarea[name="biography"]').val()
				},
				dataType : 'json'
			}).done(function(response) {
				if(response.data) {
					var user = response.data.user;
					// apply the changes
					$('#profile_email').text(user.email);
					$('#profile_name').text(user.name)
					$('#profile_website').text(user.website).attr('href', user.website);
					$('#profile_biography').text(user.biography);

					notification(response.data.message, 'success');

					$this.find('button[type="submit"]').removeAttr('disabled')
						.removeClass('btn-disabled');

					$('.main-content').removeClass('edit-state');
				}
			}).error(function(errors) {
				if(errors.responseJSON) {
					$.each(errors.responseJSON.errors.message, function(i, data) {
						notification(data, 'danger');
					});
				}

				$this.find('button[type="submit"]').removeAttr('disabled')
					.removeClass('disabled');
			});

			return;
		});

		$('#password_edit_form').on('submit', function(e) {
			e.preventDefault();
			var $this = $(this);

			$this.find('.has-error').removeClass('has-error');
			$this.find('button[type="submit"]').attr('disabled', 'disabled')
				.addClass('btn-disabled');

			$.ajax({
				type : 'post',
				url : '/api/v1/user/{{ $user->id }}/update_password',
				data : $this.serialize(),
				dataType : 'json'
			}).done(function(response) {
				if(response.data) {
					// clear fields
					$this.find('input').val('');
					$this.find('button[type="submit"]').removeAttr('disabled')
						.removeClass('disabled');

					notification(response.data.message, 'success');
				}
			}).error(function(errors) {
				if(errors.responseJSON) {
					$.each(errors.responseJSON.errors.message, function(i, data) {
						$('input[name="'+i+'"]').parent().addClass('has-error');
						notification(data, 'danger');
					});
				}

				$this.find('button[type="submit"]').removeAttr('disabled')
					.removeClass('disabled');
			});

			return;
		});
	})(jQuery);
</script>
@endif
@if(Auth::user()->role == 1 && Auth::user()->id != $user->id)
<script type="text/javascript">
	(function($) {
		var changeRoleDropdown = $('.change-role-wrapper').find('.dropdown-menu');

		$('.change-role').on('click', function(e) {
			e.preventDefault();
			var $this = $(this);

			// set active to inactive
			changeRoleDropdown.find('li.active').removeClass('active');

			$.ajax({
				type : 'post',
				url : '/api/v1/user/{{ $user->id }}/change_role',
				data : {
					role : ($this.data('role') == 'administrator') ? 1 : 2
				},
				dataType : 'json'
			}).done(function(response) {
				if(response.data) {
					var user = response.data.user;
					if(user.role == 1) {
						$('.profile-details.user-role').find('.label')
							.removeClass('label-info')
							.addClass('label-success')
							.text('Administrator');
					} else {
						$('.profile-details.user-role').find('.label')
							.removeClass('label-success')
							.addClass('label-info')
							.text('Editor');
					}

					// update role label
					notification(response.data.message, 'success');
				}
			}).error(function(error) {
				notification(error.responseJSON.errors.message, 'danger');
		   	});
		});

		// deactivates the user
		$('#deactivate_user').on('click', function(e) {
			e.preventDefault();
			var $this = $(this);
				userId = $this.parent().find('input[name="id"]').val();

			// disable the button
			$this.addClass('btn-disabled').attr('disabled', 'disabled');
			// send to server
			$.ajax({
				type : 'post',
				url : '/api/v1/user/{{ $user->id }}/deactivate',
				dataType : 'json'
			}).done(function(response) {
				if(response.data) {
					// update the role of the user to deactivated
					$('.profile-details.user-role').find('.label')
						.removeClass('label-info label-success')
						.addClass('label-default')
						.text('Deactivated');
					// hide modal
					$('#deactivate_user_modal').modal('hide');

					notification(response.data.message, 'success');
				}
			}).error(function(error) {
				notification(error.responseJSON.errors.message, 'danger');
			});
		});
	})(jQuery);
</script>
@endif
@stop
