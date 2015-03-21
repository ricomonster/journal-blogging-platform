<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') &lsaquo; Journal</title>
    <link href="/vendor/stylesheets/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/stylesheets/font-awesome.min.css" rel="stylesheet">
    <link href="/vendor/stylesheets/bootstrap-notify.css" rel="stylesheet">
    <link href="/assets/stylesheets/screen.css" rel="stylesheet">
    <style type="text/css">
        .container {
            position: absolute;
            top: 15px;
            right: 15px;
            bottom: 0;
            left: 15px;
            padding: 0;
        }

        .container .installer-page {
            max-width: 560px;
            height: 90%;
            margin: 0 auto;
            padding: 0;
            display: table;
        }

        .container .installer-page .content {
            margin: auto;
            width: 350px;
            vertical-align: middle;
            text-align: center;
            padding-top: 70px;
        }

        .form-group { border-bottom: 0; margin-bottom: 25px; padding-bottom: 0; }
    </style>
    @yield('css')
</head>
<body>
    <main class="container">
        @yield('body')
    </main>
    <aside class="notifications top-right"></aside>

    <script src="/vendor/javascript/jquery.min.js"></script>
    <script src="/vendor/javascript/bootstrap.min.js"></script>
    <script src="/vendor/javascript/bootstrap-notify.js"></script>
    @yield('footer.js')
</body>
</html>

