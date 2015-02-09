<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Journal</title>
    <link href="/vendor/stylesheets/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/stylesheets/font-awesome.min.css" rel="stylesheet">
    <link href="/vendor/stylesheets/bootstrap-notify.css" rel="stylesheet">
    <style>
        /* Get Font from Google Fonts */
        @import url(http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,300,700);
        body {
            background-color: #ffffff;
            color: rgba(0,0,0,0.8);
            font-family: "Open Sans", sans-serif;
            letter-spacing: -0.02em;
            font-weight: 400;
            font-style: normal;
        }

        .form-group { margin-bottom: 25px; }
        .form-control { color: rgba(0,0,0,0.8); font-size: 16px; height: 30px; padding: 0; }
        .form-control, .form-control:focus {
            border-radius: 0;
            border-color: transparent;
            box-shadow: none;
            -moz-box-shadow: none;
            -webkit-box-shadow: none;
        }

        .alert { border-radius: 0; }
        .container {
            position: absolute;
            top: 15px;
            right: 15px;
            bottom: 0;
            left: 15px;
            padding: 0;
        }

        .login-box {
            max-width: 560px;
            height: 90%;
            margin: 0 auto;
            padding: 0;
            display: table;
        }

        #login_form {
            display: table-cell;
            margin: auto;
            width: 300px;
            vertical-align: middle;
        }

        .hero-title {
            font-weight: 700;
            font-style: normal;
            font-size: 50px;
            letter-spacing: -0.04em;
            line-height: 1;
            color: rgba(0,0,0,0.8);
            margin: 0 0 40px;
            outline: 0;
            word-break: break-all;
            text-align: center;
        }
        /* Notification styling */
        .notifications { position: fixed; width: 300px; }
        .notifications.top-right { right: 10px; top: 25px; }
        .notifications > div { position: relative; z-index: 9999; margin: 5px 0px; }
        .notifications > .alert { color: rgba(255,255,255,0.9); }
        .notifications > .alert.alert-danger { background: #e25440; border-color: #e25440; }
        .notifications > .alert.alert-success { background: #9fbb58; border-color: #9fbb58; }
        .notifications > .alert > .close { color: #ffffff; opacity: 1; }
        .btn { border-radius: 30px; }
        @media (max-width: 767px) {
            .btn { width: 100%; }
            .notifications { right: 0; left: 0; width: 100%; }
            .notifications.top-right { top: -5px; }
        }
    </style>
</head>
<body>
<main class="container">
    <section class="login-box">
        <form action="post" id="login_form">
            <h1 class="hero-title">Journal</h1>

            <div class="form-group">
                <input type="email" name="email" class="form-control"
                       placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control"
                       placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                Log in
            </button>
        </form>
    </section>
</main>
<div class="notifications top-right"></div>

<script src="/vendor/javascript/jquery.min.js"></script>
<script src="/vendor/javascript/bootstrap.min.js"></script>
<script src="/vendor/javascript/bootstrap-notify.js"></script>
<script>
    (function($) {
        $('#login_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            // disable button
            form.find('button[type="submit"]').attr('disabled', 'disabled')
                    .addClass('btn-disabled');
            $.ajax({
                type : 'post',
                url : '/api/v1/auth/login',
                data : form.serialize(),
                dataType : 'json'
            }).done(function(response) {
                if(response.data.url) {
                    // redirect if there's no error
                    window.location.href = response.data.url;
                }
            }).error(function(error) {
                if(error.responseJSON.errors) {
                    $('.top-right').notify({
                        type : 'danger',
                        message: { text: error.responseJSON.errors.message },
                        fadeOut: { enabled: true, delay: 10000 }
                    }).show();
                }

                form.find('button[type="submit"]').removeAttr('disabled')
                        .addClass('btn-disabled');
            });
        });
    })(jQuery);
</script>
</body>
</html>
