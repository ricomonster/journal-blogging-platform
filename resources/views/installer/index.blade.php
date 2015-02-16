@extends('installer.layout')
@section('title')
    Welcome
@stop
@section('css')
<style type="text/css">
    .container .welcome-page.installer-page .content { width: 500px; }
    .container .welcome-page.installer-page .content .btn { margin-top: 20px; }
</style>
@stop
@section('body')
    <section class="welcome-page installer-page">
        <div class="content">
            <h1 class="hero-title">Journal</h1>
            <div class="message">
                <p>You're just a few steps away on creating your blog using Journal.</p>
                <p>Please configure your database within <code>app/config/database.php</code>
                    and make sure that the <code>app/storage/</code> is rewritable.</p>
            </div>
            <a href="#" class="btn btn-primary">Proceed</a>
        </div>
    </section>
@stop
