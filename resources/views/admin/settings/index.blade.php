@extends('admin.layout')
@section('title')
Settings
@stop

@section('body')
<style type="text/css">
    .general-settings-page .page-header { padding: 10px 0 15px; }
</style>
<section class="main-content general-settings-page centered">
    <header class="page-header">
        <h1 class="hero-title">Settings</h1>
    </header>
    <section class="content-wrapper centralized">
        <form method="post" id="general_settings_form" autocomplete="off">
            <div class="form-group">
                <label class="control-label" for="blog_title">Blog title</label>
                <input type="" name="blog_title" class="form-control" placeholder="Journal"
                value="{{ $settings->blog_title }}"/>
                <span class="help-block">The name of your blog</span>
            </div>
            <div class="form-group">
                <label class="control-label" for="blog_description">Blog description</label>
                <textarea name="blog_description" class="form-control">{{ $settings->blog_description }}</textarea>
                <span class="help-block">Describe what your blog is all about.</span>
            </div>
            <div class="form-group">
                <label class="control-label" for="post_per_page">Post per page</label>
                <input type="text" name="post_per_page" class="form-control" placeholder="5"
                value="{{ $settings->post_per_page }}"/>
                <span class="help-block">Number of posts that a page will show</span>
            </div>
            <div class="form-group">
                <label class="control-label" for="timezone">Timezone</label>
                <span class="help-block">What is your timezone?</span>
            </div>
            <div class="form-action">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </section>
</section>
@stop
@section('footer.js')
<script>
    (function($) {
        $('#general_settings_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            // disable the button
            form.find('button[type="submit"]').attr('disabled', 'disabled')
                .addClass('btn-disabled');

            // ajax request
            $.ajax({
                type : 'post',
                url : '/api/v1/settings/update_general_settings',
                data : form.serialize(),
                dataType : 'json'
            }).done(function(response) {
                // undisable the button
                form.find('button[type="submit"]').removeAttr('disabled')
                    .removeClass('btn-disabled');

                if (response.data) {

                }
            });
        });
    })(jQuery);
</script>
@stop
