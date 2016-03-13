@extends('installer.layout')
@section('title', 'Welcome')

@section('css')
<style type="text/css">
    #installer_welcome {}
</style>
@endsection

@section('content')
<div id="installer_welcome">
    <header class="subheader">
        <h3>Welcome</h3>
    </header>
    <section class="content">
        <p>
            You're just a few steps away in using Journal.
        </p>
        <p>
            Click the button below to start the installation process.
        </p>

        <a href="/installer/database" class="btn btn-primary">Let's go</a>
    </section>
</div>
@endsection
