<!DOCTYPE html>
<html lang="en" >
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

    <!-- Template Stylesheets -->
    @yield('css')

    <!-- Journal Globals -->
    @include('admin.scripts.globals')
</head>

<body id="journal_layout" class="open-sidebar">
    <div id="journal_app" class="full-height">
        <!-- Sidebar -->
        @include('admin.common.sidebar')

        <div class="main-content" v-cloak>
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('assets/app.js') }}"></script>
</body>
</html>
