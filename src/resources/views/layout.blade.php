<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            @yield('title')
            &mdash;
            {{ env('SITE_NAME', 'ARCHUB') }}
        </title>

        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ url('/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ url('/css/app.css') }}">

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0-rc.2/jquery-ui.min.js"></script>
        <script src="{{ url('/js/all.js') }}"></script>
    </head>

    <body>
        <header>
            @yield('header')
            <div class="nav">
                <a href="{{ url('/') }}" class="nav-link nav-link-disabled">
                    <i class="brand-logo"></i>
                    <span class="brand-text"></span>
                </a>
                <a href="{{ route('admin.join-requests.index') }}" class="nav-link">
                    <i class="glyphicon glyphicon-inbox"></i>
                    <span>Join Requests</span>
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
