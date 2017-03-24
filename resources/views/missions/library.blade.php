@php
    use App\Models\Operations\Operation;
    use App\Models\Missions\Mission;
@endphp

<script>
    $(document).ready(function(e) {
        $('.mission-item').click(function(event) {
            event.preventDefault();

            var caller = $(this);
            var id = caller.data('id');

            if (caller.hasClass('spinner')) {
                return;
            }
            
            $.ajax({
                type: 'GET',
                url: '{{ url("/hub/missions") }}/' + id,
                beforeSend: function() {
                    caller.missionSpinner(true);
                },
                success: function(data) {
                    openBigWindow(data, 500, function() {
                        caller.missionSpinner(false);
                    }, function() {
                        setUrl('hub/missions');
                    });

                    setUrl('hub/missions/' + id);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(textStatus);
                }
            });
        });
    });
</script>

@php
    $myMissions = auth()->user()->missions();

    $nextOperation = Operation::nextWeek();
    $nextOperationMissions = $nextOperation->missions;
    $prevOperation = Operation::lastWeek();

    $newMissions = Mission::allNew();
    $pastMissions = Mission::allPast();
@endphp

<div class="missions-pinned">
    <div class="missions-pinned-headers">
        @if ($nextOperation)
            <h2>Next Operation &mdash; {{ $nextOperation->startsIn() }}</h2>
        @endif

        @if ($prevOperation)
            <h2>Past Operation</h2>
        @endif
    </div>

    <div class="missions-pinned-groups">
        @if ($nextOperation)
            @if (!$nextOperationMissions->isEmpty())
                <ul class="mission-group mission-group-pinned mission-group-center">
                    @foreach ($nextOperationMissions as $item)
                        @include('missions.item', ['mission' => $item->mission])
                    @endforeach
                </ul>
            @else
                <p class="mission-section-label">No missions have been picked yet! Come back later.</p>
            @endif
        @endif

        @if ($prevOperation)
            <ul class="mission-group mission-group-pinned mission-group-center">
                @foreach ($prevOperation->missions as $item)
                    @include('missions.item', ['mission' => $item->mission, 'ignore_new_banner' => true])
                @endforeach
            </ul>
        @endif
    </div>
</div>

<h2 class="mission-section-heading">My Missions</h2>

<ul class="mission-group">
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

@if (auth()->user()->isAdmin())
    @if (!$newMissions->isEmpty())
        <h2 class="mission-section-heading">New Missions</h2>

        <ul class="mission-group">
            @foreach ($newMissions as $mission)
                @include('missions.item', ['mission' => $mission])
            @endforeach
        </ul>
    @endif

    @if (!$pastMissions->isEmpty())
        <h2 class="mission-section-heading">Past Missions</h2>

        <ul class="mission-group">
            @foreach ($pastMissions as $mission)
                @include('missions.item', ['mission' => $mission, 'ignore_new_banner' => true])
            @endforeach
        </ul>
    @endif
@endif
