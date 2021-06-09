<script>
    $(document).ready(function(e) {
        $('.mission-briefing-nav a').click(function(event) {
            var caller = $(this);
            var locked = caller.hasClass('locked');
            var faction = caller.data('faction');

            if (locked) return;

            $.ajax({
                type: 'GET',
                url: "{{ url('/share/'.$mission->id.'/briefing') }}/" + faction,
                success: function(data) {
                    $('.mission-briefing-content').html(data);
                    $('.mission-briefing-nav a').removeClass('active');
                    caller.addClass('active');
                }
            });

            event.preventDefault();
        });

        $('.mission-briefing-nav a:first').click();
    });
</script>

<div class="mission-briefing">
    <div class="mission-briefing-nav">
        @foreach ($mission->briefingFactions() as $item)
            <a
                href="javascript:void(0)"
                class="ripple"
                data-faction="{{ $item->faction }}">
                {{ $item->name }}
            </a>
        @endforeach
    </div>

    <div class="mission-briefing-content"></div>
</div>
