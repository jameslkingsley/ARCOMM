<!DOCTYPE html>
<html>
    <head>
        <title>
            @unless (empty($title))
                {{ $title }} &mdash; 
            @endunless
            {{ env('SITE_NAME', 'ARCHUB') }}
        </title>
        <link rel="stylesheet" href="{{ url('css/app.css') }}">
    </head>

    <body>
        <header>
            @yield('header')
        </header>
        <main>
            @yield('content')
        </main>
        <footer>
            @yield('footer')
        </footer>
    </body>
</html>
