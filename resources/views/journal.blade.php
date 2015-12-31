<!DOCTYPE html>
<html lang="en" >
<head>
    <title>Journal</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
    {{--<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,700" rel="stylesheet" type="text/css">--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,400italic" rel="stylesheet" type="text/css">--}}

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('/vendor/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/css/angular-toastr.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/css/codemirror.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/css/ngprogress-lite.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/css/screen.css') }}"/>
    <!-- endinject -->

    <!-- inject:js -->
    <script type="text/javascript" src="{{ asset('/vendor/js/codemirror.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/showdown.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/angular.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/angular-animate.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/angular-sanitize.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/angular-ui-router.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/ui-bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/ui-bootstrap-tpls.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/ui-codemirror.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/angular-local-storage.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/angular-toastr.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/angular-toastr.tpls.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/angular-moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/ng-file-upload-shim.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/ng-file-upload.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/js/ngprogress-lite.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/js/controllers.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/js/directives.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/js/providers.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/js/services.js') }}"></script>
    <!-- endinject -->
</head>

<body ng-app="Journal" ng-class="{ 'sidebar' : loggedIn }">
    <div ui-view="sidebar" class="auto-height"></div>
    <div ui-view class="main-content"></div>
</body>
</html>
