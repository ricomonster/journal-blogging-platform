<!DOCTYPE html>
<html lang="en" >
<head>
    <title>Journal - @yield('title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,400italic" rel="stylesheet" type="text/css"> -->

    <link rel="stylesheet" href="{{ asset('/vendor/css/bootstrap.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/css/font-awesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/screen.css') }}"/>
    @yield('css')
</head>

<body class="sidebar">
    @include('admin.partials.sidebar')

    <div class="main-content">
        @yield('content')
    </div>

    <script src="{{asset('/vendor/js/jquery.js')}}"></script>
    <script src="{{asset('/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('/vendor/js/vue.js')}}"></script>
    <script src="{{asset('/vendor/js/vue-resource.js')}}"></script>
    <script src="{{asset('/vendor/js/vue-strap.js')}}"></script>
    <!-- Journal Globals -->
    <script>
        Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

        window.Journal = {
            // Current User ID
            userId : {!! Auth::user() ? Auth::id() : 'null' !!}
        }
    </script>
    @yield('footer.js')
</body>
</html>
