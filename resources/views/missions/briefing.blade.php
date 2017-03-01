@if ($mission->isMine())
    <script>
        $(document).ready(function(e) {
            $('#lock-briefing').click(function(event) {
                var caller = $(this);
                var id = caller.data('id');
                var faction = caller.data('faction');
                var locked = caller.data('locked');

                $.ajax({
                    type: 'POST',
                    url: '{{ url('/hub/missions/briefing/update') }}',
                    data: {
                        'mission_id': id,
                        'faction': faction,
                        'locked': locked
                    },
                    success: function(data) {
                        caller.data('locked', (locked == 1) ? 0 : 1);
                        caller.html(
                            (locked == 1) ?
                            'Unlock {{ $faction }} Briefing' :
                            'Lock {{ $faction }} Briefing'
                        );
                    }
                });

                event.preventDefault();
            });
        });
    </script>

    <div class="pull-left full-width">
        <a
            href="javascript:void(0)"
            class="btn hub-btn btn-primary pull-right"
            id="lock-briefing"
            data-faction="{{ $faction }}"
            data-id="{{ $mission->id }}"
            data-locked="{{ ($mission->briefingLocked($faction)) ? '0' : '1' }}">
            {{ ($mission->briefingLocked($faction)) ? 'Unlock' : 'Lock' }} {{ $faction }} Briefing
        </a>
    </div>
@endif

@if ($mission->briefingLocked($faction))
    <p class="mt-0 text-muted">This briefing is locked. Only the mission maker and admins can see it.</p>
@endif

@foreach ($mission->briefing($faction) as $subject)
    <h4 class="mb-0">{{ $subject->title }}</h4>

    @foreach ($subject->paragraphs as $paragraph)
        <p>{!! $paragraph !!}</p>
    @endforeach
@endforeach
