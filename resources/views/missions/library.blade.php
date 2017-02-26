<script>
    $(document).ready(function(e) {
        $('.mission-item').click(function(event) {
            var caller = $(this);
            var id = caller.data('id');
            
            $.ajax({
                type: 'POST',
                url: '{{ url('/hub/missions/show-mission') }}',
                data: {'id': id},
                success: function(data) {
                    openBigWindow(data);
                }
            });

            event.preventDefault();
        });
    });
</script>

<div class="missions-pinned">
    <div class="missions-pinned-headers">
        <h2>Next Operation</h2>
        <h2>Past Operation</h2>
    </div>

    <div class="missions-pinned-groups">
        <ul class="mission-group mission-group-pinned mission-group-center">
            @foreach (\App\Operation::nextWeek()->missions as $item)
                @include('missions.mission-item', ['mission' => $item->mission])
            @endforeach
        </ul>

        <ul class="mission-group mission-group-pinned mission-group-center">
            @foreach (\App\Operation::lastWeek()->missions as $item)
                @include('missions.mission-item', ['mission' => $item->mission])
            @endforeach
        </ul>
    </div>
</div>

<h2 class="mission-section-heading">My Missions</h2>

<ul class="mission-group">
    @foreach (auth()->user()->missions() as $mission)
        @include('missions.mission-item', [
            'mission' => $mission,
            'ignore_new_banner' => true
        ])
    @endforeach
</ul>

<h2 class="mission-section-heading">New Missions</h2>

<ul class="mission-group">
    @foreach (\App\Mission::allNew() as $mission)
        @include('missions.mission-item', ['mission' => $mission])
    @endforeach
</ul>

<h2 class="mission-section-heading">Past Missions</h2>

<ul class="mission-group">
    @foreach (\App\Mission::allPast() as $mission)
        @include('missions.mission-item', ['mission' => $mission])
    @endforeach
</ul>
