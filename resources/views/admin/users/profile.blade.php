@extends('admin.layout')
@section('title')
{{ $user->name }}
@stop

@section('body')
<style type="text/css">
    .user-profile-page .profile-header {
        position: relative;
        width: auto;
        height: 350px;
        margin: 0;
        background: #fafafa no-repeat center center;
        background-size: cover;
        overflow: hidden;
    }

    .user-profile-page .profile-header #change_cover { margin: 15px 0 0 15px; }
    .user-profile-page .profile-content {
        margin: auto;
        padding-top: 40px;
        position: relative;
        width: 40%;
    }

    .user-profile-page .profile-content .avatar-wrapper {
        left: 50%;
        margin-left: -60px;
        position: absolute;
        top: -78px;
    }

    .user-profile-page .profile-content .avatar-wrapper .avatar {
        position: relative;
        width: 120px;
        height: 120px;
        padding-bottom: 30%;
        margin: 1.66% auto;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        border-radius: 50%;
        border: 3px solid #ddd;
    }
    .user-profile-page .profile-content #name {
        border-bottom: none;
        margin-bottom: 5px;
        padding-bottom: 0;
    }

    .user-profile-page .profile-content #name input[name="name"] {
        font-size: 40px;
        font-weight: bold;
        height: 60px;
        letter-spacing: -1px;
        text-align: center;
    }

    .user-profile-page .profile-content #biography {
        border-bottom: none;
        margin-bottom: 15px;
        padding-bottom: 0;
    }

    .user-profile-page .profile-content #biography textarea { text-align: center; }
</style>
<section class="main-content user-profile-page">
    <header class="profile-header image-wrapper {{ ($user->cover_url) ? 'active' : null }}"
    style="background-image: url('{{ ($user->cover_url) ? $user->cover_url : '/images/shared/user-cover.png' }}')" id="cover_url">
        <a href="#" class="btn btn-default show-modal" id="change_cover" data-setting="cover_url">
            <i class="fa fa-camera"></i>
        </a>
    </header>
    <section class="profile-content">
        <figure class="avatar-wrapper show-modal" id="avatar_url" data-setting="avatar_url">
            <div class="avatar image-wrapper {{ ($user->avatar_url) ? 'active' : null }}"
            style="background-image: url('{{ ($user->avatar_url) ? $user->avatar_url : '/images/shared/default_avatar.png' }}')"></div>
        </figure>
        <form method="post" id="user_profile_form">
            <div class="form-group" id="name">
                <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                placeholder="What's your name?"/>
            </div>
            <div class="form-group" id="biography">
                <textarea name="biography" class="form-control"
                placeholder="Tell something about yourself">{{ $user->biography }}</textarea>
            </div>
            <div class="form-group" id="email">
                <label class="control-label" for="email">E-mail</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}"/>
                <span class="help-block">This will be used for logging in</span>
            </div>
            <div class="form-group">
                <label class="control-label" for="slug">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ $user->slug }}"/>
                <span class="help-block">/author/<span class="slug">{{ $user->slug }}</span></span>
            </div>
            <div class="form-group">
                <label class="control-label" for="website">Website</label>
                <input type="text" name="website" class="form-control" value="{{ $user->website }}"/>
                <span class="help-block">Have a website or blog other than this one? Link it!</span>
            </div>
            <div class="form-group">
                <label class="control-label" for="location">Location</label>
                <input type="text" name="location" class="form-control" value="{{ $user->location }}"/>
                <span class="help-block">Where do you live?</span>
            </div>
            <div class="form-action">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>

        <form method="post" id="user_password_form">
            <div class="form-group">
                <label class="control-label" for="current_password">Old password</label>
                <input type="password" name="current_password" class="form-control"/>
                <span class="help-block">What's your current password?</span>
            </div>
            <div class="form-group">
                <label class="control-label" for="new_password">New password</label>
                <input type="password" name="new_password" class="form-control"/>
                <span class="help-block">Your new password?</span>
            </div>
            <div class="form-group">
                <label class="control-label" for="repeat_password">Repeat password</label>
                <input type="password" name="repeat_password" class="form-control"/>
                <span class="help-block">Now, repeat your password?</span>
            </div>
            <div class="form-action">
                <button type="submit" class="btn btn-danger">Change password</button>
            </div>
        </form>
    </section>
</section>
@stop
@section('modals')
    @include('admin.modal.uploader')
@stop
@section('footer.js')
<script src="/vendor/javascript/jquery.form.js"></script>
<script src="{{ asset('assets/javascript/profile-uploader.min.js') }}"></script>
<script type="text/javascript">
    (function($) {
        // handle user update details
        $('#user_profile_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            // disable button
            form.find('button[type="submit"]').addClass('btn-disable')
                .attr('disabled', 'disabled');

            $.ajax({
                type : 'post',
                url : '/api/v1/users/update_account?id={{ $user->id }}',
                data : form.serialize(),
                dataType : 'json'
            }).done(function(response) {
                if (response.data) {
                    form.find('button[type="submit"]').removeClass('btn-disable')
                        .removeAttr('disabled');

                    Journal.notification(response.data.message, 'success');
                }
            }).error(function(error) {
                var errors = error.responseJSON.errors.message;
                // this will loop the fields
                $.each(errors, function(i, messages) {
                    // loop again
                    $.each(messages, function(k, message) {
                        Journal.notification(message, 'danger');
                    });
                });

                form.find('button[type="submit"]').removeClass('btn-disable')
                    .removeAttr('disabled');
            });
        });

        // handle password update
        $('#user_password_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            // disable button
            form.find('button[type="submit"]').addClass('btn-disable')
                .attr('disabled', 'disabled');

            $.ajax({
                type : 'post',
                url : '/api/v1/users/change_password?id={{ $user->id }}',
                data : form.serialize(),
                dataType : 'json'
            }).done(function(response) {
                if (response.data) {
                    form.find('button[type="submit"]')
                        .removeClass('btn-disable')
                        .removeAttr('disabled');

                    Journal.notification(response.data.message, 'success');
                }
            }).error(function(error) {
                var errors = error.responseJSON.errors.message;
                // this will loop the fields
                $.each(errors, function(i, messages) {
                    // loop again
                    $.each(messages, function(k, message) {
                        Journal.notification(message, 'danger');
                    });
                });

                form.find('button[type="submit"]').removeClass('btn-disable')
                        .removeAttr('disabled');
            })
        });

        // uploader
        $('#uploader_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            // disable the buttons
            form.find('.btn').addClass('btn-disabled')
                .attr('disabled', 'disabled');

            // upload
            form.ajaxSubmit({
                url : '/api/v1/users/upload_image?id={{ $user->id }}',
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
                        var user = response.data.user,
                            avatarUrl,
                            coverUrl;

                        // check first if cover url or avatar url are empty
                        if (user.cover_url) {
                            coverUrl = user.cover_url;
                            $('#cover_url').addClass('active');
                        } else {
                            coverUrl = '/images/shared/user-cover.png';
                            $('#cover_url').removeClass('active');
                        }

                        if (user.avatar_url) {
                            console.log('yes');
                            // remove active class
                            $('#avatar_url').find('.image-wrapper').addClass('active');
                            avatarUrl = user.avatar_url;
                        } else {
                            console.log('nope');
                            $('#avatar_url').find('.image-wrapper').removeClass('active');
                            avatarUrl = '/images/shared/default_avatar.png'
                        }

                        // reset the modal
                        ProfileUploader.resetModal();

                        // apply image for cover url
                        $('#cover_url').css('background-image', 'url('+coverUrl+')');
                        // apply image avatar url
                        $('#avatar_url').find('.avatar.image-wrapper')
                            .css('background-image', 'url('+avatarUrl+')');

                        // close the modal
                        $('#uploader_modal').modal('hide');
                    }
                }
            })

        });
    })(jQuery);
</script>
@stop
