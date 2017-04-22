
<a href="{{ url('/hub/missions') }}" class="nav-item nav-link active">Missions</a>

@if (auth()->user()->hasPermission('users:all'))
    <a href="{{ url('/hub/users') }}" class="nav-item nav-link">
        Users
    </a>
@endif

{{-- <a href="https://www.nfoservers.com/donate.pl?force_recipient=1&recipient=fbidude21@yahoo.com" target="_newtab" class="nav-item nav-link">
    Donate
</a> --}}
