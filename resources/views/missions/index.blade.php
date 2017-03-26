@extends('layout')

@section('title')
    Missions
@endsection

@section('head')
    <script>
        $(document).ready(function(e) {
            $('.subnav-link').click(function(event) {
                var caller = $(this);
                var panel = caller.data('panel');
                var noAjax = caller.data('noajax');
                var openWindow = caller.data('window') || false;

                if (noAjax) return;

                if (!openWindow) {
                    $('.subnav-link').removeClass('active');
                    caller.addClass('active');
                }

                $.ajax({
                    type: 'GET',
                    url: '{{ url('/hub/missions?panel=') }}' + panel,
                    success: function(data) {
                        if (openWindow) {
                            openBigWindow(data, 500, function() {}, function() {
                                $('.subnav-link[data-panel="library"]').click();
                            });

                            return;
                        }

                        $('#mission-content').html(data);
                    }
                });

                event.preventDefault();
            });

            $('#mission-upload-btn').dropzone({
                url: '{{ url('/hub/missions') }}',
                acceptedFiles: '',
                addedfile: function(file) {},
                success: function(file, data) {
                    openMission(data.trim(), function() {
                        $('.subnav-link[data-panel="library"]').click();
                    });
                },
                error: function(file, message) {
                    alert(message);
                }
            });

            $.hubDropdown();
        });
    </script>

    @if (isset($mission))
        <script>
            $(document).ready(function(e) {
                openMission({{ $mission->id }});
            });
        </script>
    @endif
@endsection

@section('subnav')
    <a
        href="javascript:void(0)"
        data-panel="library"
        class="subnav-link active"
    >Library</a>

    <a
        href="javascript:void(0)"
        data-panel="upload"
        data-noajax="true"
        id="mission-upload-btn"
        class="subnav-link"
    >Upload</a>

    @if (auth()->user()->isAdmin())
        <a
            href="javascript:void(0)"
            data-panel="operations"
            data-window="true"
            class="subnav-link"
        >Operations</a>
    @endif
@endsection

@section('controls')
@endsection

@section('content')
    <div id="mission-content">
        @include('missions.library')
    </div>
@endsection
