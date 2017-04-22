@extends('layout')

@section('title')
    Missions
@endsection

@section('header-color')
    primary
@endsection

@section('head')
    <script>
        $(document).ready(function(e) {
            $('.subnav-link').click(function(event) {
                var caller = $(this);
                var panel = caller.data('panel');
                var noAjax = caller.data('noajax');

                if (noAjax) return;

                $.ajax({
                    type: 'GET',
                    url: '{{ url('/hub/missions?panel=') }}' + panel,
                    success: function(data) {
                        $('.subnav-link').removeClass('active');
                        caller.addClass('active');
                        $('#mission-content').html(data);
                    }
                });

                event.preventDefault();
            });

            $('#mission-upload-btn').dropzone({
                url: '{{ url('/hub/missions') }}',
                acceptedFiles: '',
                addedfile: function(file) {},
                sending: function() {
                    $('#mission-upload-btn').prepend('<i class="fa fa-spin fa-refresh m-r-1"></i>');
                },
                success: function(file, data) {
                    $('#mission-upload-btn').find('i.fa').remove();
                    window.location = data.trim();
                },
                error: function(file, message) {
                    $('#mission-upload-btn').find('i.fa').remove();
                    alert(message);
                }
            });
        });
    </script>
@endsection

@section('nav-right')
    <a
        href="javascript:void(0)"
        data-panel="upload"
        data-noajax="true"
        id="mission-upload-btn"
        class="nav-item nav-link"
    >Upload</a>
@endsection

@section('subnav')
    <a
        href="javascript:void(0)"
        data-panel="library"
        class="subnav-link active ripple"
    >Library</a>

    @if (auth()->user()->hasPermission('operations:all'))
        <a
            href="javascript:void(0)"
            data-panel="operations"
            data-window="true"
            class="subnav-link ripple"
        >Operations</a>
    @endif
@endsection

@section('content')
    <div id="mission-content">
        @include('missions.library')
    </div>
@endsection
