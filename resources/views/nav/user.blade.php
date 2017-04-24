<li class="nav-item dropdown hidden-sm-down">
    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)" role="button" aria-haspopup="true" aria-expanded="false">
        {{ auth()->user()->username }}
    </a>

    <div class="dropdown-menu">
        <a
            href="{{ url('/hub/settings') }}"
            class="dropdown-item">
            Settings
        </a>
    </div>
</li>
