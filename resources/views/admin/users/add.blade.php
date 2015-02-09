@extends('admin.layout')
@section('title')
Users
@stop

@section('body')
<style type="text/css">
	.user-add-page .page-header { padding: 20px 0 0; }
</style>
<section class="main-content user-add-page centered">
	<header class="page-header">
		<h1 class="hero-title">Add new User</h1>
	</header>
	<section class="content-wrapper">
		<form method="post" id="user_add_form" autocomplete="off">
			<div class="form-group">
				<label class="control-label" for="email">E-mail</label>
				<input type="email" name="email" class="form-control" placeholder="johndoe@mail.com"/>
				<span class="help-block">E-mail address of the user</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="password">Password</label>
				<input type="password" name="password" class="form-control"/>
				<span class="help-block">Password of the user</span>
			</div>
			<div class="form-group">
				<label class="control-label" for="name">Name</label>
				<input type="name" name="name" class="form-control" placeholder="John Doe"/>
				<span class="help-block">Repeat the password of the user.</span>
			</div>
			<div class="form-action">
				<button type="submit" class="btn btn-primary">Add User</button>
			</div>
		</form>
	</section>
</section>
@stop
@section('footer.js')
<script type="text/javascript">
	(function($) {
		var parseErrors = function(errors) {
			// get the field
			$.each(errors, function(i, error) {
				// set error state
				$('input[name="'+i+'"]').parent().addClass('has-error');
				// loop the error
				$.each(error, function(k, value) {
					// show in notification
				})
			});
		};

		$('#user_add_form').on('submit', function(e) {
			e.preventDefault();
			var form = $(this);

			// disable button
			form.find('button[type="submit"]').attr('disabled', 'disabled')
				.addClass('btn-disabled');
			// remove has-error class
			form.find('.has-error').removeClass('has-error');

			// submit form
			$.ajax({
				data : form.serialize(),
				dataType : 'json',
				type : 'post',
				url : '/api/v1/users/create'
			}).done(function(response) {
				if (response.data) {
					// reset submit button
					form.find('button[type="submit"]').removeAttr('disabled')
						.removeClass('btn-disabled');
					// clear fields
					form.find('input').val('');
				}
			}).error(function(error) {
				if (error.responseJSON.errors.message) {
					// parse the errors
					parseErrors(error.responseJSON.errors.message);
				}

				form.find('button[type="submit"]').removeAttr('disabled')
					.removeClass('btn-disabled');
			});
		})
	})(jQuery);
</script>
@stop
