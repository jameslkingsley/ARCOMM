<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            @yield('title')
            &mdash;
            {{ env('SITE_NAME', 'ARCHUB') }}
        </title>

        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" type="image/png" href="{{ url('/images/favicon.png') }}">
        <script type="text/javascript" src="{{ url('/js/app.js') }}"></script>
        <link rel="stylesheet" href="{{ url('/css/app.css') }}">

        @yield('head')
    </head>

    <body>
        <header>
            @yield('header')

            <script>
                $(document).ready(function(e) {
                    var activeNav = $('.nav-link[data-page="{{ (empty(Request::segment(2))) ? "missions" : Request::segment(2) }}"]');
                    activeNav.addClass('active');
                    $('.subnav')
                        .css('padding-top', activeNav.offset().top)
                        .animate({
                            'left': '80px'
                        }, 500, 'easeInOutCirc');
                });
            </script>

            <div class="nav">
                <a href="{{ url('/') }}" class="nav-link nav-link-disabled">
                    <i class="brand-logo"></i>
                    <span class="brand-text"></span>
                </a>

                {{-- @if (auth()->user()->isAdmin())
                    <a href="{{ url('/hub/applications') }}" class="nav-link" data-page="applications">
                        <i class="glyphicon glyphicon-inbox"></i>
                        <span>Applications</span>
                    </a>
                @endif --}}

                <a href="{{ url('/hub/missions') }}" class="nav-link" data-page="missions">
                    <i class="fa fa-map"></i>
                    <span>Missions</span>
                </a>

                <a href="{{ url('/hub/settings') }}" class="nav-link" data-page="settings">
                    <i class="fa fa-cog"></i>
                    <span>Settings</span>
                </a>

                <a href="https://www.nfoservers.com/donate.pl?force_recipient=1&recipient=fbidude21@yahoo.com" target="_newtab" class="nav-link">
                    <i class="fa fa-usd"></i>
                    <span>Donate</span>
                </a>

                @yield('nav')
            </div>
        </header>

        <main id="app">
            <div class="subnav">
                @yield('subnav')
            </div>

            <div class="archub-content">
                <div class="controls">
                    @yield('controls')
                </div>

                @yield('content')
            </div>
        </main>

        <footer>
            @yield('footer')
        </footer>

        @yield('scripts')
    </body>
</html>
