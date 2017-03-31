<h2 class="mt-0 mb-5">{{ $users->count() }} Users</h2>

<ul class="jr-list">
    @foreach ($users as $user)
        @include('user.admin.item', ['user' => $user])
    @endforeach
</ul>
