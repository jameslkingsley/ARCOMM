@extends('layout')

@section('title')
    Missions
@endsection

@section('scripts')
    <script>
        $(document).ready(function(e) {
            $('.subnav-link').click(function(event) {
                var caller = $(this);
                var panel = caller.data('panel');

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
        });
    </script>
@endsection

@section('subnav')
    @foreach (['Create', 'Library'] as $p)
        <a
            href="javascript:void(0)"
            data-panel="{{ strtolower($p) }}"
            class="subnav-link {{ (strtolower($p) == $panel) ? 'active' : '' }}"
        >{{ $p }}</a>
    @endforeach
@endsection

@section('controls')
@endsection

@section('content')
    <div id="mission-content">
        {!! view('missions.' . $panel) !!}
    </div>
@endsection
