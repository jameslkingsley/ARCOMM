@extends('layout')

@section('title')
    Users
@endsection

@section('head')
    <script>
        $(document).ready(function(e) {
            $(document).on('click', '#users-content ul li a', function(event) {
                var caller = $(this);

                $.ajax({
                    type: 'GET',
                    url: '{{ url('/hub/users') }}/' + caller.data('id'),
                    success: function(data) {
                        openPanel(data);
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
        class="subnav-link active"
    >Permissions</a>
@endsection

@section('controls')
@endsection

@section('content')
    <div id="users-content">
        @include('user.admin.list', ['users' => $users])
    </div>
@endsection
