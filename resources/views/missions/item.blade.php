<a
    href="{{ url('/hub/missions/' . $mission->id) }}"
    class="mission-item {{ (isset($classes)) ? $classes : '' }}"
    style="background-image: url({{ $mission->thumbnail() }})"
    data-id="{{ $mission->id }}">

    <div class="mission-item-inner">
        <span class="mission-item-title">
            {{ $mission->display_name }}
        </span>

        <span class="mission-item-author">
            By {{ $mission->user->username }}
        </span>

        <span class="mission-item-mode mission-item-mode-{{ $mission->mode }}">
            {{ $mission->mode }}
        </span>

        @if (auth()->user()->hasPermission('mission:verification') && !$mission->verified)
            <span class="mission-item-verified" title="Not verified yet"></span>
        @endif

        @unless ($mission->unreadComments()->isEmpty())
            <span class="mission-notifications" title="New after-action reports available!">
                {{ $mission->unreadComments()->count() }}
            </span>
        @endunless

        {{-- @if (!isset($ignore_new_banner) || !$ignore_new_banner)
            @if ($mission->isNew())
                <span class="mission-item-new-banner">
                    New
                </span>
            @endif
        @endif --}}
    </div>
</a>
