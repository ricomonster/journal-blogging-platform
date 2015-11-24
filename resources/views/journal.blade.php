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
    <link rel="stylesheet" href="{{ asset('/vendor/stylesheets/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/stylesheets/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/stylesheets/codemirror.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/stylesheets/ngprogress-lite.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/screen.css') }}"/>
    <!-- endinject -->

    <!-- inject:js -->
    <script type="text/javascript" src="{{ asset('/vendor/vendor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/journal.js') }}"></script>
    <!-- endinject -->
</head>

<body ng-app="Journal">
    <div ui-view="header_content" class="auto-height"></div>
    <div ui-view="sidebar_content" class="auto-height"></div>
    <div ui-view class="auto-height"></div>
</body>
</html>
