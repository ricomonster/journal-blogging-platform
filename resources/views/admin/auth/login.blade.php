<!DOCTYPE html>
<html lang="en" >
<head>
    <title>Journal &#8212; Login</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,400italic" rel="stylesheet" type="text/css">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/screen.css') }}"/>

    <!-- Journal Globals -->
    @include('admin.scripts.globals')
</head>

<body id="journal_layout">
    <div id="journal_app" class="main-content">
        <journal-login inline-template>
            <div id="login_page" class="full-height">
                <section class="page-content">
                    <form id="login_form" method="post" action="{{url('journal/auth/authenticate')}}"
                    autocomplete="off" v-on:submit="login">
                        <h1>Journal</h1>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope fa-fw"></i>
                                </span>
                                <input type="email" name="email" class="form-control"
                                placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-unlock-alt fa-fw"></i>
                                </span>
                                <input type="password" name="password" class="form-control"
                                placeholder="Password"/>
                            </div>
                        </div>
                        <div class="form-actions">
                            @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                                @endforeach
                            </ul>
                            @endif

                            @if (session()->get('error_message'))
                            <div class="alert alert-danger">
                                <p>{{session()->get('error_message')}}</p>
                            </div>
                            @endif

                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

                            <button type="submit" class="btn btn-primary btn-block"
                            v-button-loader="processing">
                                Login
                            </button>

                            <p class="forgot-password">
                                <a>Forgot your Password?</a>
                            </p>
                        </div>
                    </form>
                </section>
            </div>
        </journal-login>
    </div>

    <script src="{{ asset('assets/app.js') }}"></script>
</body>
</html>
