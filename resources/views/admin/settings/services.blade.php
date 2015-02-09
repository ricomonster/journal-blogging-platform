@extends('core::admin.layout')
@section('title')
Services
@stop

@section('body')
<section class="main-content">
    <header class="page-header">
        <h1 class="hero-title">Services</h1>
    </header>
    <section class="content-wrapper centralized">
        {{ Form::open(array(
        	'id' => 'services_settings_form',
        	'autocomplete' => 'off',
        	'class' => 'form-contents'))
		}}
            <div class="form-group">
                <label class="control-label" for="google_analytics">
                    Google Analytics Tracking ID
                </label>
                <input type="text" name="google_analytics" class="form-control"
                value="{{ (isset($settings->google_analytics)) ? $settings->google_analytics : null }}"
                placeholder="Google Analytics ID">

                <span class="help-block">Tracking ID from your Google Analytics Account</span>
            </div>

            <div class="form-group">
                <label class="control-label" for="disqus">Disqus</label>
                <input type="text" name="disqus" class="form-control" placeholder="Disqus username"
                value="{{ (isset($settings->disqus)) ? $settings->disqus : null }}">

                <span class="help-block">Your Disqus username for comments.</span>
            </div>

            <div class="form-action">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        {{ Form::close() }}
    </section>
</section>
@stop
@section('footer.js')
<script>
	(function($) {
		$('#services_settings_form').on('submit', function(e) {
			e.preventDefault();
			var $this = $(this);

			$.ajax({
				type : 'post',
				url : '/api/v1/settings/save-services',
				data : $this.serialize(),
				dataType : 'json'
			}).done(function(response) {
				if(response.data) {
					notification(response.data.message, 'success');
				}
			});
		});
	})(jQuery);
</script>
@stop
