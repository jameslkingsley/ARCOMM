<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            @if (trim($__env->yieldContent('title')))
                {{ trim($__env->yieldContent('title')) }}
                —
            @endif
            {{ env('SITE_NAME', 'ARCHUB') }}
        </title>

        <link href="{{ url('/images/favicon.png') }}" rel="icon" type="image/png"></link>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="{{ url('/css/bootstrap.min.css') }}">
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet"></link>

        <script src="{{ mix('/js/app.js') }}" type="text/javascript"></script>

        {{-- Laravel Mentions --}}
        <script type="text/javascript" src="{{ url('/js/laravel-mentions.js') }}"></script>
        <link rel="stylesheet" type="text/css" href="{{ url('/css/laravel-mentions.css') }}">

        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>

        @yield('head')
    </head>

    <body class="bd-docs">
        <div
            class="banner @yield('banner-classes')"
            style="background-image: url(@yield('banner'))">
            <div class="banner-fade-top"></div>
            <div class="banner-fade-bottom"></div>
        </div>

        <header
            @if (trim($__env->yieldContent('header-color')))
                class="header-colored header-{{ trim($__env->yieldContent('header-color')) }}"
            @endif>

            <nav class="navbar navbar-light">
                <div class="nav navbar-nav w-100">
                    <div class="pull-left">
                        @include('nav.brand')
                        @include('nav.pages')
                        @yield('nav-left')
                    </div>

                    <div class="pull-right">
                        @yield('nav-right')
                        @include('nav.notifications')
                        @include('nav.user')
                    </div>
                </div>
            </nav>

            @if (trim($__env->yieldContent('subnav')))
                <div class="subnav">
                    @yield('subnav')
                </div>
            @endif
        </header>

        <main>
            @yield('content')
        </main>

        <script src="https://cdn.rawgit.com/FezVrasta/bootstrap-material-design/dist/dist/bootstrap-material-design.iife.min.js"></script>

        <script>
            $(document).ready(function(e) {
                $('body').bootstrapMaterialDesign();
            });
        </script>
    </body>
</html>
