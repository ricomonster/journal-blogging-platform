<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Journal - @yield('title')</title>

    <link href="{{ asset('vendor/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/screen.css') }}" rel="stylesheet">
    <style type="text/css">
        .installer .container { padding: 20px 0; width: 640px; }
        .installer .installer-header { margin-bottom: 15px; padding: 5px 0 15px; }
        .installer .installer-header h1 { text-align: center;}

        .installer .content { padding: 0 15px; }

        .subheader { border-bottom: 2px solid #e4e4e4; margin-bottom: 20px; }
        .subheader h3 { margin: 5px 0 15px; }
    </style>

    @yield('css')
</head>
<body class="installer">
    <div class="container">
        <header class="installer-header">
            <h1>Journal</h1>
        </header>

        @yield('content')
    </div>

    <script src="{{ asset('vendor/js/vue.js') }}"></script>
    <script src="{{ asset('vendor/js/vue-resource.js') }}"></script>
    @yield('footer.js')
</body>
</html>
