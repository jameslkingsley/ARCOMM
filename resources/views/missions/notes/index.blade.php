<script>
    $(document).ready(function(e) {
        var runConvert = function() {
            $('.mission-comment-item-text').each(function(idx, span) {
                var jspan = $(span);

                if (!jspan.hasClass('md-converted')) {
                    span.innerHTML = window.mdconvert.makeHtml(span.innerText);
                    jspan.addClass('md-converted');
                }
            });
        };

        runConvert();

        $(document).on('click', '.mission-note-delete', function(event) {
            var caller = $(this);

            $.ajax({
                type: 'DELETE',
                url: '{{ url('/hub/missions/'.$mission->id.'/notes') }}/' + caller.data('id'),
                success: function(data) {
                    caller.parents('.mission-comment-item').remove();
                }
            });

            event.preventDefault();
        });

        $('#submit-mission-comment').submit(function(event) {
            var form = $(this);

            $.ajax({
                type: 'POST',
                url: '{{ url('/hub/missions/'.$mission->id.'/notes') }}',
                data: form.serialize(),
                beforeSend: function() {
                    $('*').blur();
                },
                success: function(data) {
                    $('.mission-comments').append(data);
                    $('.has-mentions').html('');

                    runConvert();

                    form[0].reset();
                }
            });

            event.preventDefault();
        });

        new Mentions({
            input: '.has-mentions',
            mentions: '#mentions',
            pools: [{
                trigger: '@',
                pool: 'users',
                display: 'username',
                reference: 'id'
            }, {
                trigger: '#',
                pool: 'missions',
                display: 'display_name',
                reference: 'id'
            }]
        });

        $('.mission-comments .mention-node[data-object*="missions"]').each(function(i, node) {
            node = $(node);
            text = node.html();
            id = node.data('object').split(':')[1];
            node.replaceWith('<a href="{{ url('/hub/missions') }}/' + id + '" class="mention-node">' + text + '</a>');
        });

        $('.mission-comments .mission-comment-item[id="' + window.location.hash.substr(1) + '"]').addClass('highlight');
    });
</script>

<div class="mission-comments">
    @foreach ($mission->notes as $note)
        @include('missions.notes.item', ['note' => $note])
    @endforeach
</div>

<div class="mission-comments-form pull-left w-100">
    <form method="post" id="submit-mission-comment">
        <input type="hidden" name="mentions" id="mentions" value="">
        <textarea class="form-control" id="submit-mission-comment-text" name="text" style="display:none"></textarea>

        <span
            id="submit-mission-comment-text-real"
            class="form-control-editable has-mentions mission-aar-textarea form-control m-b-3 m-t-3"
            contenteditable="plaintext-only"
            placeholder="Write a note..."
            for="#submit-mission-comment-text"></span>

        <a
            class="pull-left m-l-3"
            style="font-weight:600"
            href="https://docs.google.com/document/d/1vxyQSLEjB23ZDdZGHClXrOxN90SHbu26bEUXjq7fqLE/edit"
            target="_newtab">
            Mission Checklist
        </a>

        <button type="submit" class="btn btn-raised btn-primary pull-right m-r-3">Submit</button>
    </form>
</div>
