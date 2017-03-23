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
    $nextOperation = Operation::nextWeek();
    $prevOperation = Operation::lastWeek();
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
            <ul class="mission-group mission-group-pinned mission-group-center">
                @foreach ($nextOperation->missions as $item)
                    @include('missions.item', ['mission' => $item->mission])
                @endforeach
            </ul>
        @endif

        @if ($prevOperation)
            <ul class="mission-group mission-group-pinned mission-group-center">
                @foreach ($prevOperation->missions as $item)
                    @include('missions.item', ['mission' => $item->mission])
                @endforeach
            </ul>
        @endif
    </div>
</div>

<h2 class="mission-section-heading">My Missions</h2>

<ul class="mission-group">
    @foreach (auth()->user()->missions() as $mission)
        @include('missions.item', [
            'mission' => $mission,
            'ignore_new_banner' => true
        ])
    @endforeach
</ul>

@if (auth()->user()->isAdmin())
    <h2 class="mission-section-heading">New Missions</h2>

    <ul class="mission-group">
        @foreach (Mission::allNew() as $mission)
            @include('missions.item', ['mission' => $mission])
        @endforeach
    </ul>

    <h2 class="mission-section-heading">Past Missions</h2>

    <ul class="mission-group">
        @foreach (Mission::allPast() as $mission)
            @include('missions.item', ['mission' => $mission])
        @endforeach
    </ul>
@endif
