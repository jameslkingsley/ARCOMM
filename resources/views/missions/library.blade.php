<ul class="mission-group">
    @foreach (\App\Mission::orderBy('created_at', 'desc')->get() as $mission)
            <li class="mission-item" style="background-image: url({{ url($mission->map()->image) }})">
                <div class="mission-item-inner">
                    <span class="mission-item-title">
                        {{ $mission->display_name }}
                    </span>

                    <span class="mission-item-mode mission-item-mode-{{ $mission->mode }}">
                        {{ $mission->mode }}
                    </span>
                </div>
            </li>
    @endforeach
</ul>
