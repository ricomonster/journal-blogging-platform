@extends('installer.layout')
@section('title')
    Sign up
@stop
@section('body')
    <section class="account-page installer-page">
        <div class="content">
            <h1 class="hero-title">Journal</h1>
            <form method="post" action="/installer/create_account" autocomplete="off">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <input type="text" name="name" class="form-control"
                    placeholder="Full name" value="{{ old('name') }}"/>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control"
                    placeholder="Email" value="{{ old('email') }}"/>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control"
                    placeholder="Password">
                </div>

                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <button type="submit" class="btn btn-primary btn-block">
                    Log in
                </button>
            </form>
        </div>
    </section>
@stop
