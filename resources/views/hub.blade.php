<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

        <script>
            window.App = {
                csrfToken: '{{ csrf_token() }}'
            };

            @if (session('access_token'))
                window.App.access_token = "{{ session('access_token') }}"
            @endif
        </script>
    </head>

    <body>
        <div id="app" v-cloak></div>

        <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
