<script>
    $(document).ready(function(e) {
        $('.mission-notes-trigger').click(function(event) {
            $(this).addClass('open');
            $('.mission-notes').fadeIn(150);
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
                success: function(data) {
                    $('.mission-notes-list').append(data);
                }
            });

            event.preventDefault();
        });
    });
</script>

<a href="javascript:void(0)" class="mission-notes-trigger">
    <span class="mission-notes-trigger-count"></span>

    <i class="fa fa-commenting"></i>
</a>

<div class="mission-notes">
    <h2>Notes</h2>

    <div class="mission-notes-list">
        @foreach ($mission->notes as $note)
            @include('missions.notes.item', ['note' => $note])
        @endforeach
    </div>

    <div class="mission-notes-submission">
        <form id="mission-notes-form">
            <input type="text" name="text" placeholder="Write a note" class="form-control">
        </form>
    </div>
</div>
