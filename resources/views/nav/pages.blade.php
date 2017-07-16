<a href="{{ url('/hub/missions') }}" class="nav-item nav-link hidden-sm-down active">Missions</a>

@if (auth()->user()->hasPermission('operations:all'))
    <a href="{{ url('/hub/operations') }}" class="nav-item nav-link hidden-sm-down">
        Operations
    </a>
@endif

@if (auth()->user()->hasPermission('apps:view'))
    <a href="{{ url('/hub/applications') }}" class="nav-item nav-link hidden-sm-down">
        Applications
    </a>
@endif

@if (auth()->user()->hasPermission('users:all'))
    <a href="{{ url('/hub/users') }}" class="nav-item nav-link hidden-sm-down">
        Users
    </a>
@endif

<li class="nav-item dropdown hidden-sm-down">
    <a class="nav-link dropdown-toggle" style="padding:0" data-toggle="dropdown" href="javascript:void(0)" role="button" aria-haspopup="true" aria-expanded="false">
        Guides
    </a>

    <div class="dropdown-menu">
        @foreach (Storage::disk('guides')->files() as $file)
            <a
                href="{{ url("/guides/$file") }}"
                class="dropdown-item">
                {{ rtrim($file, '.pdf') }}
            </a>
        @endforeach
    </div>
</li>

<li class="nav-item dropdown hidden-sm-down">
    <a class="nav-link dropdown-toggle" style="padding:0" data-toggle="dropdown" href="javascript:void(0)" role="button" aria-haspopup="true" aria-expanded="false">
        Tutorials
    </a>

    <div class="dropdown-menu">
        @foreach (config('arcmf.videos') as $name => $url)
            <a
                href="{{ $url }}"
                class="dropdown-item">
                {{ $name }}
            </a>
        @endforeach
    </div>
</li>

{{-- <a href="https://www.nfoservers.com/donate.pl?force_recipient=1&recipient=fbidude21@yahoo.com" target="_newtab" class="nav-item nav-link">
    Donate
</a> --}}

<li id="nav-pages" class="nav-item dropdown hidden-md-up m-l-0">
    <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" role="button" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-bars"></i>
    </a>

    <div class="dropdown-menu" style="width: 25rem;max-height: 40rem;overflow-y: auto;right:auto;left:0;">
        <div class="list-group">
            <a
                href="{{ url('/hub/missions') }}"
                class="list-group-item list-group-item-action">
                Missions
            </a>

            @if (auth()->user()->hasPermission('operations:all'))
                <a
                    href="{{ url('/hub/operations') }}"
                    class="list-group-item list-group-item-action">
                    Operations
                </a>
            @endif

            @if (auth()->user()->hasPermission('users:all'))
                <a
                    href="{{ url('/hub/users') }}"
                    class="list-group-item list-group-item-action">
                    Users
                </a>
            @endif

            @yield('nav-left-mobile')
        </div>
    </div>
</li>
