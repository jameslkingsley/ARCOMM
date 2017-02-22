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

<ul class="mission-group">
    @foreach (\App\Mission::orderBy('created_at', 'desc')->get() as $mission)
            <a
                href="{{ url('/hub/missions/' . $mission->id) }}"
                class="mission-item"
                style="background-image: url({{ url($mission->map->image_2d) }})"
                data-id="{{ $mission->id }}">
                <div class="mission-item-inner">
                    <span class="mission-item-title">
                        {{ $mission->display_name }}
                    </span>

                    <span class="mission-item-mode mission-item-mode-{{ $mission->mode }}">
                        {{ $mission->mode }}
                    </span>
                </div>
            </a>
    @endforeach
</ul>
