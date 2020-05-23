<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>{{ (setting('site_title')) ? setting('site_title') : config('app.name', 'ZipFile') }}</title>
        <meta name="keywords" content="{{setting('site_keywords')}}">
        <meta name="description" content="{{setting('site_desc')}}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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

        <meta property="og:image:height" content="300">
        <meta property="og:image:width" content="573">
        <meta property="og:description" content="ZipFile is a php application, which is designed for uploading and sharing files to anyone very easily and securely. Very simple steps are needed to share files with your users.">
        <meta property="og:title" content="ZipFileMe">
        <meta property="og:url" content="https://codecanyon.net/item/zipfileme-file-sharing-made-easy/21568775">
        <meta property="og:image" content="https://image.ibb.co/k9xLBJ/zipfile_banner.png/og-image.jpg">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap-theme.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/min/dropzone.min.css" />
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha256-uKEg9s9/RiqVVOIWQ8vq0IIqdJTdnxDMok9XhiqnApU=" crossorigin="anonymous" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" integrity="sha256-R91pD48xW+oHbpJYGn5xR0Q7tMhH4xOrWn1QqMRINtA=" crossorigin="anonymous" />

        <link rel="stylesheet" href="/css/main.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script src="/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>

        <!-- Top extra script-->
        {!! setting('extra_top_header') !!}

    </head>

    <body class="zipfileme-body">
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

		@yield('content')

		<script>
		    window.appMaxFileSize = '{{ config('app.app_max_file_size') }}';
		    window.appMaxFileQty = '{{ config('app.app_max_file_qty') }}';
            window.appUploadTimeOut = '{{ config('app.app_upload_timeout') }}';
            window.appHost = '{{ config("app.url") }}';
            window.sendEmail = '{{ config("app.app_send_email_after_upload") }}';
		</script>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/min/dropzone.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/filesize/3.6.1/filesize.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" integrity="sha256-yNbKY1y6h2rbVcQtf0b8lq4a+xpktyFc3pSYoGAY1qQ=" crossorigin="anonymous"></script>

        @yield('custome-js')

        <script src="/js/main.js?v={{ strtoupper(substr(md5(rand(0, 1000000)), 0, 8)).'-'.rand(10000,99999) }}"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','{{setting('site_google_an_id')}}','auto');ga('send','pageview');
        </script>

        <!-- Bottom extra script-->
        {!! setting('extra_bottom_header') !!}

    </body>
</html>
