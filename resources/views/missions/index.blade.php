@extends('layout')

@section('title')
    Missions
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ url('/js/dropzone.js') }}"></script>

    <script>
        $(document).ready(function(e) {
            $('.subnav-link').click(function(event) {
                var caller = $(this);
                var panel = caller.data('panel');
                var noAjax = caller.data('noajax');

                if (noAjax) return;

                $('.subnav-link').removeClass('active');
                caller.addClass('active');

                $.ajax({
                    type: 'POST',
                    url: '{{ url('/hub/missions/show-panel') }}',
                    data: {'panel': panel},
                    success: function(data) {
                        $('#mission-content').html(data);
                        setUrl('hub/missions/' + panel);
                    }
                });

                event.preventDefault();
            });

            $('#mission-upload-btn').dropzone({
                url: '{{ url('/hub/missions/create') }}',
                acceptedFiles: '',
                addedfile: function(file) {},
                success: function(file, data) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ url('/hub/missions/show-mission') }}',
                        data: {'id': data.trim()},
                        success: function(data) {
                            openBigWindow(data);
                        }
                    });
                }
            });
        });
    </script>
@endsection

@section('subnav')
    <a
        href="javascript:void(0)"
        data-panel="upload"
        data-noajax="true"
        id="mission-upload-btn"
        class="subnav-link {{ ('upload' == $panel) ? 'active' : '' }}"
    >Upload</a>

    <a
        href="javascript:void(0)"
        data-panel="library"
        class="subnav-link {{ ('library' == $panel) ? 'active' : '' }}"
    >Library</a>
@endsection

@section('controls')
@endsection

@section('content')
    <div id="mission-content">
        {!! view('missions.' . $panel) !!}
    </div>
@endsection
