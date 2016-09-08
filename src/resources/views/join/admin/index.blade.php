@extends('layout')

@section('title')
    Join Requests
@endsection

@section('scripts')
    <script>
        $(document).ready(function(e) {
            $('.status-filter').click(function(event) {
                var $this = $(this);
                var statusKey = $(this).data("status");

                $.ajax({
                    type: 'GET',
                    url: '{{ action("JoinController@items") }}',
                    data: {"statusKey": statusKey},
                    beforeSend: function() {},
                    success: function(data) {
                        $('#join-requests').html(data);
                        $('.status-filter').removeClass("active");
                        $this.addClass("active");
                    }
                });

                event.preventDefault();
            });

            $('.status-filter').first().click();
        });
    </script>
@endsection

@section('subnav')
    @foreach (\App\JoinRequest::StatusList as $key => $value)
        <a href="javascript:void(0)" data-status="{{ $key }}" class="status-filter">{{ $key }}</a>
    @endforeach
@endsection

@section('controls')
    {{-- <form method="post">
        <input type="text" name="query" class="form-control" placeholder="Search">
    </form> --}}
@endsection

@section('content')
    <div id="join-requests"></div>
@endsection