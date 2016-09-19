<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="icon" type="image/png" href="{{ url('/images/favicon.png') }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
        <meta name="viewport" content="width=device-width">

        <title>
            @if (trim($__env->yieldContent('title')))
                @yield('title')
                &mdash;
            @endif
            {{ env('SITE_NAME_PUBLIC', 'ARCOMM') }}
        </title>

        <!-- Bootstrap Stylesheets -->
        <link href="{{ url('/css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ url('/css/landing-page.css') }}" rel="stylesheet">

        <!-- Fonts and icons -->
        {{-- <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"> --}}

        <!-- Public Stylesheet -->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/public.css') }}">

        <!-- jQuery -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0-rc.2/jquery-ui.min.js"></script>

        <!-- Bootstrap Scripts -->
        <script src="{{ url('/js/bootstrap.js') }}" type="text/javascript"></script>

        <!-- Select2 -->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}">
        <script type="text/javascript" src="{{ url('/js/select2.full.min.js') }}"></script>

        @yield('head')
    </head>

    <body class="landing-page landing-page1">
        <nav class="navbar navbar-transparent navbar-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button id="menu-toggle" type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar bar1"></span>
                    <span class="icon-bar bar2"></span>
                    <span class="icon-bar bar3"></span>
                    </button>
                    <a href="{{ url('/') }}">
                        <div class="logo-container">
                            <div class="logo">
                                <img src="{{ url('/images/logo-white-full.png') }}" alt="Logo">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="example" >
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="{{ url('/media') }}">Media</a></li>
                        <li><a href="{{ url('/roster') }}">Roster</a></li>
                        <li><a href="{{ url('/join') }}">Join</a></li>
                        <li><a href="{{ url('/login') }}" style="padding-top: 4px"><img style="width: 81px" src="{{ url('/images/steam.png') }}"></a></li>
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
        @yield('scripts')
    </body>
</html>
