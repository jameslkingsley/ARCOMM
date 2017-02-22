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

        <!-- jQuery -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0-rc.2/jquery-ui.min.js"></script>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ url('/css/font-awesome.min.css') }}">

        <!-- Dropit -->
        <script src="{{ url('/js/dropit.js') }}"></script>

        <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery.waitforimages/1.5.0/jquery.waitforimages.min.js"></script>

        <!-- Main -->
        <link rel="stylesheet" href="{{ url('/css/app.css') }}">
        <script src="{{ url('/js/all.js') }}"></script>
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

                @if(auth()->user()->isAdmin())
                    <a href="{{ url('/hub/applications') }}" class="nav-link" data-page="applications">
                        <i class="glyphicon glyphicon-inbox"></i>
                        <span>Applications</span>
                    </a>
                @endif

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

        <main>
            <div class="subnav">
                @yield('subnav')
            </div>

            <div class="content">
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
