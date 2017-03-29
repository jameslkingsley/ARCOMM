<ul class="jr-list">
    @foreach ($users as $user)
        @include('user.admin.item', ['user' => $user])
    @endforeach
</ul>
