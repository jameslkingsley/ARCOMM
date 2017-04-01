<ul class="hub-list">
    @foreach ($users as $user)
        @include('user.admin.item', ['user' => $user])
    @endforeach
</ul>
