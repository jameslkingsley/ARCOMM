@php
    use App\Models\Operations\Operation;
    use App\Models\Missions\Mission;
@endphp

@php
    $myMissions = auth()->user()->missions();
    $nextOperation = Operation::nextWeek();

    if ($nextOperation) {
        $nextOperationMissions = $nextOperation->missions;
    }

    $prevOperation = Operation::lastWeek();
    $newMissions = Mission::allNew();
    $pastMissions = Mission::allPast();
@endphp

<div class="missions-pinned">
    <div class="missions-pinned-groups">
        @if ($nextOperation)
            @if (!$nextOperationMissions->isEmpty())
                <ul class="mission-group mission-group-pinned" data-title="Next Operation &mdash; {{ $nextOperation->startsIn() }}">
                    @foreach ($nextOperationMissions as $item)
                        @include('missions.item', ['mission' => $item->mission, 'classes' => 'mission-item-pinned', 'pulse' => true])
                    @endforeach
                </ul>
            @else
                <ul class="mission-group mission-group-pinned" style="height: 252px"></ul>
            @endif
        @endif

        @if ($prevOperation)
            <ul class="mission-group mission-group-pinned" data-title="Past Operation">
                @foreach ($prevOperation->missions as $item)
                    @include('missions.item', ['mission' => $item->mission, 'ignore_new_banner' => true])
                @endforeach
            </ul>
        @endif
    </div>
</div>

@if (auth()->user()->hasPermission('mission:see_new'))
    @if (!$newMissions->isEmpty())
        <ul class="mission-group" data-title="New Missions">
            @foreach ($newMissions as $mission)
                @include('missions.item', ['mission' => $mission])
            @endforeach
        </ul>
    @endif
@endif

<ul class="mission-group" data-title="My Missions">
    @if (!$myMissions->isEmpty())
        @foreach ($myMissions as $mission)
            @include('missions.item', [
                'mission' => $mission,
                'ignore_new_banner' => true
            ])
        @endforeach
    @else
        <p class="mission-section-label">You haven't uploaded any missions!<br />Upload your mission PBO files via the upload button on the left.</p>
    @endif
</ul>

@if (!$pastMissions->isEmpty())
    <ul class="mission-group" data-title="Past Missions">
        @foreach ($pastMissions as $mission)
            @include('missions.item', ['mission' => $mission, 'ignore_new_banner' => true])
        @endforeach
    </ul>
@endif
