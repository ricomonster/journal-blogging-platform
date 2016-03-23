<!DOCTYPE html>
<html lang="en" >
<head>
    <title>Journal &#8212; Login</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
    <!-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,400italic" rel="stylesheet" type="text/css"> -->

    <link rel="stylesheet" href="{{ asset('/vendor/css/bootstrap.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/css/font-awesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/screen.css') }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style type="text/css">
        #login_page {}
        #login_page .page-content { height: 100%; margin: 0 !important; position: relative; }
        #login_page #login_form {
            bottom: 50%;
            left: 50%;
            margin: -130px -170.5px;
            position: absolute;
            top: 50%;
            width: 350px;
        }

        #login_page #login_form h1 { margin: 10px 0 15px; text-align: center; }
        #login_page #login_form .form-actions .btn { margin-bottom: 20px; }
        #login_page #login_form .form-actions .forgot-password { text-align: center; }
    </style>
</head>

<body>
    <div class="main-content">
        <div id="login_page" class="full-height">
            <section class="page-content">
                <form id="login_form" method="post" action="{{url('journal/auth/authenticate')}}">
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

                        <button type="submit" class="btn btn-primary btn-block">
                            Login
                        </button>

                        <p class="forgot-password">
                            <a>Forgot your Password?</a>
                        </p>
                    </div>
                </form>
            </section>
        </div>
    </div>
</body>
</html>
