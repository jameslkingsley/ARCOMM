@extends('layout')

@section('title')
    Users
@endsection

@section('header-color')
    primary
@endsection

@section('head')
    <script>
        $(document).ready(function(e) {
            $(document).on('click', '#users-content .list-group-item', function(event) {
                var caller = $(this);

                $.ajax({
                    type: 'GET',
                    url: '{{ url('/hub/users') }}/' + caller.data('id'),
                    success: function(data) {
                        $('.user-permissions-modal').find('.modal-body').html(data);
                        $('.user-permissions-modal').modal('show');
                    }
                });

                event.preventDefault();
            });

            $('#user-permissions-modal-submit').click(function(event) {
                $('#permission-form').submit();
                event.preventDefault();
            });
        });
    </script>
@endsection

@section('content')
    <div class="container" id="users-content">
        <h5 class="m-t-0 m-b-2">{{ $nonMembers->count() }} Members Awaiting Purge</h5>

        <div style="margin-bottom: 5rem" class="card">
            <div class="list-group">
                @foreach ($nonMembers as $user)
                    <li class="list-group-item list-group-item-action">
                        <img src="{{ $user->avatar }}" class="img-circle m-r-2" width="32">
                        {{ $user->username }}
                    </li>
                @endforeach
            </div>
        </div>

        @if ($unregistered->isNotEmpty())
            <h5 class="m-t-4 m-b-2">{{ $unregistered->count() }} Unregistered Members</h5>

            <div style="margin-bottom: 5rem" class="card">
                <div class="list-group">
                    @foreach ($unregistered as $user)
                        <li class="list-group-item list-group-item-action">
                            <img src="{{ $user->avatarUrl }}" class="img-circle m-r-2" width="32">
                            {{ $user->personaName }}
                        </li>
                    @endforeach
                </div>
            </div>
        @endif

        <h5 class="m-t-3 m-b-2">{{ $users->count() }} Users</h5>

        <div class="card m-b-3">
            @include('user.admin.list', ['users' => $users])
        </div>
    </div>

    <div class="modal fade user-permissions-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title">Edit User Permissions</h4>
                </div>

                <div class="modal-body"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="user-permissions-modal-submit">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
