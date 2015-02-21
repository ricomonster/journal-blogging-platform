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
    .user-profile-page .profile-content .avatar-wrapper img { width: 120px; }

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
    <header class="profile-header" style="background-image: url('/images/shared/user-cover.png')">
        <button class="btn btn-default" id="change_cover"><i class="fa fa-camera"></i></button>
    </header>
    <section class="profile-content">
        <figure class="avatar-wrapper">
            <img src="http://41.media.tumblr.com/d7a4552d99639890d00b5e85d9a18673/tumblr_mldfty8fh41qcnibxo1_1280.png" class="user-avatar img-circle img-thumbnail"/>
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
    </section>
</section>
@stop

@section('footer.js')
<script type="text/javascript">
    (function($) {
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
                })

                form.find('button[type="submit"]').removeClass('btn-disable')
                    .removeAttr('disabled');
            });
        });
    })(jQuery);
</script>
@stop
