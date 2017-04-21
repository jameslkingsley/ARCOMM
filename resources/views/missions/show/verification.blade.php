<script>
    $(document).ready(function(e) {
        $('#mission-verification').click(function(event) {
            var caller = $(this);

            $.ajax({
                type: 'POST',
                url: '{{ url("/hub/missions/{$mission->id}/set-verification") }}',
                success: function(data) {
                    if (caller.hasClass('mission-verified')) {
                        caller.removeClass('mission-verified');
                        caller.addClass('mission-unverified');
                    } else {
                        caller.removeClass('mission-unverified');
                        caller.addClass('mission-verified');
                        caller.find('em').html(data);
                    }
                }
            });

            event.preventDefault();
        });
    });
</script>

<li class="nav-item m-r-2">
    <a
        href="javascript:void(0)"
        id="mission-verification"
        class="nav-link {{ ($mission->verified) ? 'mission-verified' : 'mission-unverified' }}"
        title="Mark this mission as {{ ($mission->verified) ? 'verified' : 'unverified' }}">

        <em>
            @if ($mission->verifiedByUser())
                Verified by {{ $mission->verifiedByUser()->username }}
            @endif
        </em>

        <i class="fa fa-check"></i>
    </a>
</li>
