<!DOCTYPE html>
<html lang="en">
<head>
    <title>
        @unless (empty($title))
            {{ $title }} &mdash;
        @endunless
        {{ env('SITE_NAME', 'ARCHUB') }}
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.min.css" />
</head>

<body>
    <header>
        @yield('header')
    </header>
    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>
    <footer>
        @yield('footer')
    </footer>
    @yield('scripts')
</body>
</html>
