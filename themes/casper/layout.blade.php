<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700,800" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Merriweather:300,300italic' rel='stylesheet' type='text/css'>
    <link href="{{ theme_asset('assets/css/font-awesome.min.css') }}" rel="stylesheet"/>
    <link href="{{ theme_asset('assets/css/screen.css') }}" rel="stylesheet"/>

    {!! $journal_head !!}

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="casper {{ $body_class }} nav-closed">
    @include('casper.partials.sidebar')

    <div class="site-wrapper">
        @section('header')
            <header class="main-header {!! (cover_photo()) ? null : 'no-cover' !!}"
            {!! (cover_photo()) ? 'style="background-image: url('.cover_photo().');"' : null !!}>
                <nav class="main-nav overlay clearfix">
                    {!! blog_logo_photo() !!}

                    <a class="menu-button" href="#">
                        <i class="fa fa-bars"></i>
                        <span class="word">Menu</span>
                    </a>
                </nav>

                <div class="vertical">
                    <div class="main-header-content inner">
                        <h1 class="page-title">{{ blog_title() }}</h1>
                        <h2 class="page-description">{{ blog_description() }}</h2>
                    </div>
                </div>
            </header>
        @show

        <main class="content">
            @yield('body')
        </main>

        <footer class="site-footer">
            <section class="copyright">
                <a href="/">{{ blog_title() }}</a> &copy; {{ date('Y') }}
            </section>
            <section class="poweredby">
                Powered by <a href="#">Journal</a>
            </section>
        </footer>
    </div>

    <script src="{{ theme_asset('assets/js/jquery-2.2.3.min.js') }}"></script>
    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                $(".menu-button, .nav-cover, .nav-close").on("click", function(e){
                    e.preventDefault();
                    $("body").toggleClass("nav-opened nav-closed");
                });
            });
        })(jQuery);
    </script>
</body>
</html>
