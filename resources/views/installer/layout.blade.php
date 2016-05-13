<!DOCTYPE html>
<html lang="en">
<head>
    <title>Journal &#8212; @yield('title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,400italic" rel="stylesheet" type="text/css">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/screen.css') }}"/>

    @yield('css')

    <!-- Journal Globals -->
    <script>
        window.Journal = {
            // CSRF Token
            csrfToken : '{{ csrf_token() }}'
        };
    </script>
</head>
<body id="journal_layout" class="installer">
    <div id="journal_app" class="container" v-cloak>
        <header class="installer-header">
            <h1>Journal</h1>
        </header>

        @yield('content')
    </div>

    <script src="{{ asset('assets/app.js') }}"></script>
    @yield('footer.js')
</body>
</html>
