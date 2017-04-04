<script>
    $(document).ready(function(e) {
        $('.mission-notes-trigger').click(function(event) {
            var caller = $(this);

            $.ajax({
                type: 'GET',
                url: '{{ url('/hub/missions/'.$mission->id.'/notes/read-notifications') }}',
                beforeSend: function() {
                    caller.addClass('open');
                    caller.find('.mission-notes-trigger-count').remove();
                    $('.mission-notes').fadeIn(150);
                }
            });

            event.preventDefault();
        });

        $('.mission-notes').offClick(function() {
            $('.mission-notes').fadeOut(150);
            $('.mission-notes-trigger').removeClass('open');
        });

        $(document).on('click', '.mission-note-delete', function(event) {
            var caller = $(this);

            $.ajax({
                type: 'DELETE',
                url: '{{ url('/hub/missions/'.$mission->id.'/notes') }}/' + caller.data('id'),
                success: function(data) {
                    caller.parents('.mission-notes-item').remove();
                }
            });

            event.preventDefault();
        });

        $('#mission-notes-form').submit(function(event) {
            var form = $(this);

            $.ajax({
                type: 'POST',
                url: '{{ url('/hub/missions/'.$mission->id.'/notes') }}',
                data: form.serialize(),
                beforeSend: function() {
                    $('*').blur();
                },
                success: function(data) {
                    $('.mission-notes-list').append(data);
                    $('#mission-notes-empty').remove();
                    form[0].reset();
                }
            });

            event.preventDefault();
        });
    });
</script>

@php
    $notifications = $mission->noteNotifications();
@endphp

<a href="javascript:void(0)" class="mission-notes-trigger">
    @unless ($notifications->isEmpty())
        <span class="mission-notes-trigger-count">
            {{ $notifications->count() > 10 ? '!' : $notifications->count() }}
        </span>
    @endunless

    <i class="fa fa-commenting"></i>
</a>

<div class="mission-notes">
    <h2>Notes</h2>

    <div class="mission-notes-list">
        @if ($mission->notes->isEmpty())
            <p class="m0" id="mission-notes-empty">Submit notes back to the mission maker on how to improve their mission.</p>
        @else
            @foreach ($mission->notes as $note)
                @include('missions.notes.item', ['note' => $note])
            @endforeach
        @endif
    </div>

    <div class="mission-notes-submission">
        <form id="mission-notes-form">
            <input type="text" name="text" placeholder="Write a note" class="form-control">
        </form>
    </div>
</div>
