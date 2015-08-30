<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <title></title>
    <meta name="description" content=""/>
    <meta name="HandheldFriendly" content="True" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="{{asset('themes/bootstrap/assets/css/bootstrap.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('themes/bootstrap/assets/css/screen.css')}}" />
</head>
<body class="{{$bodyClass}}">
    <div class="wrapper">
        @yield('content')

        <footer class="blog-footer clearfix">
            <p>Blog template built for <a href="http://getbootstrap.com">Bootstrap</a> by <a href="https://twitter.com/mdo">@mdo</a>.</p>
            <p>
                <a href="#">Back to top</a>
            </p>
        </footer>
    </div>
</body>
</html>
