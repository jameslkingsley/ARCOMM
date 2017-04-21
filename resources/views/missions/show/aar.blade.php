<div class="mission-comments">
    @if ($mission->comments->isEmpty())
        <p class="text-xs-center p-y-3 text-muted">There are no after-action reports yet. Be the first to submit one!</p>
    @else
        @include('missions.comments.list', ['comments' => $mission->comments])
    @endif
</div>

<div class="mission-comments-form pull-left w-100">
    <script>
        $(document).ready(function(e) {
            $(document).on('click', '.mission-comment-control-edit', function(event) {
                var caller = $(this);
                var id = caller.data('id');

                $.ajax({
                    type: 'GET',
                    url: '{{ url('/hub/missions/comments') }}/' + id + '/edit',
                    success: function(data) {
                        $('#submit-mission-comment input[name="id"]').val(id);
                        $('#submit-mission-comment textarea[name="text"]').val(data);
                        $('#submit-mission-comment input[type="submit"]').val('Save Changes');
                        $('#submit-mission-comment #save-mission-comment').hide();
                        $('#submit-mission-comment textarea[name="text"]').focus();
                        $('.mission-container').scrollTop(10000);
                    }
                });

                event.preventDefault();
            });

            $(document).on('click', '.mission-comment-control-delete', function(event) {
                event.preventDefault();
                var canDelete = confirm("Are you sure you want to delete this?");

                if (canDelete) {
                    var caller = $(this);
                    var id = caller.data('id');
                    
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ url("/hub/missions/comments") }}/' + id,
                        success: function(data) {
                            caller.parents('.mission-comment-item').remove();
                        }
                    });
                }
            });

            $('#submit-mission-comment').submit(function(event) {
                $('#submit-mission-comment input[name="published"]').val(1);
                $('#submit-mission-comment input[type="submit"]').prop('disabled', true);

                $.ajax({
                    type: 'POST',
                    url: '{{ url('/hub/missions/comments') }}',
                    data: $('#submit-mission-comment').serialize(),
                    success: function(data) {
                        $('#submit-mission-comment input[name="id"]').val(-1);
                        $('#submit-mission-comment textarea[name="text"]').val('');
                        $('#submit-mission-comment input[type="submit"]').val('Publish');
                        $('#submit-mission-comment input[type="submit"]').prop('disabled', false);
                        $('#submit-mission-comment #save-mission-comment').show();

                        $.ajax({
                            type: 'GET',
                            url: '{{ url('/hub/missions/comments?mission_id=' . $mission->id) }}',
                            success: function(data) {
                                $('.mission-comments').html(data);
                            }
                        });
                    },
                    error: function() {
                        $('#submit-mission-comment input[type="submit"]').prop('disabled', false);
                    }
                });

                event.preventDefault();
            });

            $('#save-mission-comment').click(function(event) {
                $('#submit-mission-comment input[name="published"]').val(0);

                $.ajax({
                    type: 'POST',
                    url: '{{ url('/hub/missions/comments') }}',
                    data: $('#submit-mission-comment').serialize(),
                    success: function(data) {
                        $('#submit-mission-comment input[name="id"]').val(data.trim());
                    }
                });

                event.preventDefault();
            });
        });
    </script>

    <form method="post" id="submit-mission-comment">
        <input type="hidden" name="id" value="{{ (!is_null($mission->draft())) ? $mission->draft()->id : '-1' }}">
        <input type="hidden" name="mission_id" value="{{ $mission->id }}">
        <input type="hidden" name="published" value="0">

        <textarea
            name="text"
            class="form-control m-b-3 m-t-3"
            id="mission-aar-textarea"
            rows="6"
            placeholder="Your mission experience..."
        >{{ (!is_null($mission->draft())) ? $mission->draft()->text : '' }}</textarea>

        <button type="submit" class="btn btn-raised btn-primary pull-right m-l-3 m-r-3">Publish</button>
        <button class="btn pull-right" id="save-mission-comment">Save Draft</button>
    </form>
</div>
