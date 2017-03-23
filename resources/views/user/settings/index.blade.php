@extends('layout')

@section('title')
    Settings
@endsection

@section('head')
    <script>
        $(document).ready(function(e) {
            $('.subnav-link').click(function(event) {
                var caller = $(this);
                var panel = caller.data('panel');

                $.ajax({
                    type: 'GET',
                    url: '{{ url('/hub/settings?panel=') }}' + panel,
                    success: function(data) {
                        $('#settings-content').html(data);
                    }
                });

                event.preventDefault();
            });
        });
    </script>
@endsection

@section('subnav')
    <a
        href="javascript:void(0)"
        data-panel="general"
        class="subnav-link active"
    >General</a>
@endsection

@section('controls')
@endsection

@section('content')
    <div id="settings-content">
        @include('user.settings.general')
    </div>
@endsection
