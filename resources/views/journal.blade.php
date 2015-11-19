<!DOCTYPE html>
<html lang="en" >
<head>
    <title>Journal</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,400italic" rel="stylesheet" type="text/css">

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('/vendor/stylesheets/bootstrap.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/stylesheets/font-awesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/stylesheets/angular-toastr.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/stylesheets/codemirror.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/stylesheets/ngprogress-lite.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/screen.css') }}"/>
    <!-- endinject -->

    <!-- inject:js -->
    <script type="text/javascript" src="{{ asset('/vendor/javascript/codemirror.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/showdown.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/angular.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/angular-animate.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/angular-sanitize.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/angular-ui-router.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/ui-bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/ui-bootstrap-tpls.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/ui-codemirror.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/angular-local-storage.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/angular-toastr.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/angular-toastr.tpls.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/angular-moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/ng-file-upload-shim.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/ng-file-upload.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/javascript/ngprogress-lite.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/scripts/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/scripts/controllers.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/scripts/directives.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/scripts/providers.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/scripts/services.js') }}"></script>
    <!-- endinject -->
</head>

<body ng-app="Journal">
    <div ui-view="header_content" class="auto-height"></div>
    <div ui-view="sidebar_content" class="auto-height"></div>
    <div ui-view class="auto-height"></div>
</body>
</html>
