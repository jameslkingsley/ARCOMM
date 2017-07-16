<h1 class="mt-0 mb-5">Notifications</h1>

@php
    use App\Models\Portal\User;
    use App\Models\Missions\Mission;
@endphp

<ul class="jr-list">
    @foreach (auth()->user()->missionNotifications() as $notification)
        <li>
            <a href="javascript:void(0)" class="jr-item mission-item-lite" data-id="{{ $notification->data['comment']['mission_id'] }}">
                <h1>
                    {{ User::findOrFail($notification->data['comment']['user_id'])->username }}
                    commented on your mission '{{ Mission::findOrFail($notification->data['comment']['mission_id'])->display_name }}'
                    {{ $notification->created_at->diffForHumans() }}
                </h1>

                <p>
                    {{ substr($notification->data['comment']['text'], 0, 300) }}
                </p>
            </a>
        </li>
    @endforeach
</ul>
