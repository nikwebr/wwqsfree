<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ (setting('site_title')) ? setting('site_title') : config('app.name', 'ZipFile') }}</title>

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
    <link href="https://cdn.datatables.net/v/bs/dt-1.10.20/b-1.6.1/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/sc-2.0.1/sp-1.0.1/sl-1.3.1/datatables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/codemirror.min.css" integrity="sha256-vZ3SaLOjnKO/gGvcUWegySoDU6ff33CS5i9ot8J9Czk=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.bundle.min.js"></script>

    <link href="/css/admin.css" rel="stylesheet" >

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- DataTables -->
    <script src="//cdn.datatables.net/v/bs/dt-1.10.20/b-1.6.1/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/sc-2.0.1/sp-1.0.1/sl-1.3.1/datatables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/codemirror.min.js" integrity="sha256-dPTL2a+npIonoK5i0Tyes0txCMUWZBf8cfKRfACRotc=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/mode/htmlmixed/htmlmixed.min.js" integrity="sha256-qfS6ZUe6JhPU75/Sc1ftiWzC2N9IxGEjlRwpKB78Ico=" crossorigin="anonymous"></script>
</head>
<body class="zipfileme-body">
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/admin') }}">
                        {{ config('app.name', 'ZipFileMe') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    Account <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li class="disabled"><a href="#">{{ Auth::user()->name }}</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li class=""><a href="/admin">> Dashboard</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li class=""><a href="/admin/settings">> Settings</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li class=""><a href="/admin/profile">> Profile</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
        @yield('content')
        </div>
    </div>

</body>
@stack('scripts')
</html>
