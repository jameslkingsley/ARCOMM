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
                        {{-- @include('nav.notifications') --}}
                        @include('absence.nav')
                        @include('nav.user')
                    </div>
                </div>
            </nav>

            @if (trim($__env->yieldContent('subnav')))
                <div class="subnav subnav-upper">
                    @yield('subnav')
                </div>
            @endif
        </header>

        <main>
            @if (app('request')->input('403'))
                <div class="alert alert-danger m-b-3 col-md-6 m-x-auto text-xs-center" role="alert">
                    <strong>You're not a mission tester!</strong> You don't have the necessary permissions to access that.
                </div>
            @endif

            @yield('content')

            <div class="dynamic-modal"></div>
        </main>

        <script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js" integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/bootstrap-material-design@4.0.0-beta.4/dist/js/bootstrap-material-design.js" integrity="sha384-3xciOSDAlaXneEmyOo0ME/2grfpqzhhTcM4cE32Ce9+8DW/04AGoTACzQpphYGYe" crossorigin="anonymous"></script>

        <script>
            $(document).ready(function(e) {
                $('body').bootstrapMaterialDesign();
            });
        </script>
    </body>
</html>
