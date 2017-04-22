<div class="list-group pull-left w-100 {{ ($notifications->isEmpty()) ? 'notifications-empty' : '' }}">
    @if ($notifications->isEmpty())
        <p class="text-xs-center text-muted p-y-2 m-b-0">All caught up!</p>
    @else
        @foreach ($notifications as $notification)
            @if ($notification->type::exists($notification))
                <a href="{{ $notification->type::link($notification) }}" class="list-group-item list-group-item-action notification-item">
                    <span class="notification-image" style="background-image: url({{ $notification->type::icon($notification) }})"></span>

                    <p class="list-group-item-text notification-text">
                        {{ $notification->type::text($notification) }}
                    </p>

                    <p class="list-group-item-text notification-subtext">
                        {{ $notification->created_at->diffForHumans() }}
                    </p>
                </a>
            @endif
        @endforeach
    @endif
</div>
