<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
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

        <link rel="icon" type="image/png" href="{{ url('/images/favicon.png') }}">
        <link rel="stylesheet" href="{{ url('/css/app.css') }}">
        <link rel="stylesheet" href="{{ url('/css/public.css') }}">

        @yield('head')
    </head>

    <body class="landing-page">
        <h1 style="
            position: fixed;
            top: calc(50% - 35px);
            left: calc(50% - 120px);
            font-size: 64px;
            font-weight: 100;
            letter-spacing: 2px;
            margin: 0;">
            ARC<b>HUB</b>
        </h1>

        <p style="
            position: fixed;
            top: calc(50% - -30px);
            left: calc(50% - 90px);
            font-size: 16px;
            font-weight: 100;
            margin: 0;
            opacity: .88;">
            Sign in through Steam below
        </p>

        <br />

        <a href="{{ url('/steamauth') }}" class="text-center" style="position: fixed;top: calc(50% - -70px);left: calc(50% - 50px);">
            <img style="width: 100px" src="{{ url('/images/steam.png') }}">
        </a>
    </body>
</html>
