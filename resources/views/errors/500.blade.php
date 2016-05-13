<!DOCTYPE html>
<html lang="en" >
<head>
    <title>Journal</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,400italic" rel="stylesheet" type="text/css">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/screen.css') }}"/>
</head>

<body class="500-page">
<div id="server_error_page">
    <header class="page-header clearfix">
        <h1 class="page-title">Journal</h1>
    </header>
    <section class="content">
        <div class="message">
            <h1 class="title">Whoops! Something went wrong.</h1>
            <p class="subtext">
                @if ($type == 'pdo')
                    We cannot establish a database connection and the following might help you
                    figure out what is the error:
                @else
                    Please check the following that might caused this error:
                @endif
            </p>

            <ul class="debug-error-tips">
                @if ($type == 'pdo')
                <li>Invalid database credentials. (Incorrect host, username, or password)</li>
                <li>Database does not exists.</li>
                @endif
            </ul>
        </div>
    </section>
</div>
</body>
</html>
