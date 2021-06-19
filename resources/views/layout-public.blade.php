<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
        <meta name="viewport" content="width=device-width">

        <title>
            @if (trim($__env->yieldContent('title')))
                @yield('title')
                &mdash;
            @endif
            {{ env('SITE_NAME_PUBLIC', 'ARCOMM') }}
        </title>

        <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
        <script type="text/javascript" src="{{ url('/js/select2.full.min.js') }}"></script>

        <link rel="icon" type="image/png" href="{{ url('/images/favicon.png') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="{{ url('/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <link rel="stylesheet" href="{{ url('/css/landing-page.css') }}">
        <link rel="stylesheet" href="{{ mix('/css/public.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}">

        @yield('head')
    </head>

    <body class="landing-page">
        <main id="app">
            <nav class="navbar navbar-transparent navbar-top" role="navigation">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{ url('/') }}" class="navbar-brand">
                            <div class="logo-container">
                                <div class="logo">
                                    <img src="{{ url('/images/logo-white-full.png') }}" alt="Logo">
                                </div>
                            </div>
                        </a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="{{ url('/roster') }}">Roster</a></li>
                        <li><a href="{{ url('/media') }}">Media</a></li>
                        <li><a href="{{ url('join') }}">Join</a></li>

                        @if (!auth()->guest() && auth()->user()->isMember())
                            <li><a href="{{ url('/hub') }}">Hub</a></li>
                        @endif

                        @if (auth()->guest())
                            <li>
                                <a href="{{ url('/steamauth') }}" style="padding-top: 8px">
                                    <img style="width: 81px" src="{{ url('/images/steam.png') }}" alt="Steam">
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>

            @yield('content')
        </main>
    </body>

    @yield('scripts')
</html>
