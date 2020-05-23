<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ZipFile') }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=oLdrndpRdr">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=oLdrndpRdr">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=oLdrndpRdr">
    <link rel="manifest" href="/site.webmanifest?v=oLdrndpRdr">
    <link rel="mask-icon" href="/safari-pinned-tab.svg?v=oLdrndpRdr" color="#5bbad5">
    <link rel="shortcut icon" href="/favicon.ico?v=oLdrndpRdr">
    <meta name="apple-mobile-web-app-title" content="ZipFileMe">
    <meta name="application-name" content="ZipFileMe">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="/css/admin.css" rel="stylesheet">

    <link rel="stylesheet" href="/css/main.css">
    <link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.bootstrap.min.css" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
</head>
<body class="zipfileme-body">
    <div id="app">
        <div class="container">
        @yield('content')
        </div>
    </div>

</body>
@stack('scripts')
</html>
