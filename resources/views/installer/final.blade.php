@extends('installer.layout')
@section('title', 'Success')

@section('css')
<style type="text/css">
    #installer_success {}
</style>
@endsection

@section('content')
<div id="installer_success">
    <header class="subheader">
        <h3>Success!</h3>
    </header>
    <section class="content">
        <p>
            You have successfully installed Journal. Click the button so you can
            login and start blogging.
        </p>

        <br/>

        <table class="table">
            <tbody>
                <tr>
                    <th>Email</th>
                    <td>{{$user->email}}</td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td>Your chosen password</td>
                </tr>
            </tbody>
        </table>

        <a href="/journal/login" class="btn btn-primary">Login</a>
    </section>
</div>
@endsection
