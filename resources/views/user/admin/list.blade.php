<div class="list-group">
    @foreach ($users as $user)
        @include('user.admin.item', ['user' => $user])
    @endforeach
</div>
