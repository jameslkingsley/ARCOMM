<div class="modal fade" id="absence-modal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title">Absence Announcement</h4>
            </div>

            <script>
                $(document).ready(function(e) {
                    $('#absence-modal-submit').click(function(event) {
                        $(this).prop('disabled', true);
                        $('#absence-form').submit();
                        event.preventDefault();
                    });

                    $('#absence-form').submit(function(event) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ url('/hub/absence') }}',
                            data: $('#absence-form').serialize(),
                            success: function(data) {
                                $('#absence-form').parents('.modal-body').html(data);
                            }
                        });

                        event.preventDefault();
                    });

                    $('.absence-delete').click(function(event) {
                        var caller = $(this);
                        var id = caller.data('id');

                        $.ajax({
                            type: 'DELETE',
                            url: '{{ url('/hub/absence') }}/' + id,
                            success: function(data) {
                                caller.parents('p').remove();
                            }
                        });

                        event.preventDefault();
                    });
                });
            </script>

            <div class="modal-body">
                <h6 class="m-b-2">Your absence submissions</h6>

                @if (auth()->user()->absences()->isEmpty())
                    <small class="pull-left w100 m-a-0 text-muted">You haven't posted any absence announcements.</small>
                @else
                    @foreach (auth()->user()->absences() as $absence)
                        <p class="pull-left w100 m-a-0">
                            {{ $absence->operation->starts_at }}
                            <button class="btn btn-primary btn-sm pull-right absence-delete" data-id="{{ $absence->id }}"><i class="fa fa-trash"></i></button>
                        </p>
                    @endforeach
                @endif

                <hr class="m-t-3 m-b-2 p-t-3 pull-left w100" />

                <form method="post" id="absence-form">
                    <div class="form-group">
                        <label>Pick an operation</label>
                        <select name="operation_id" class="form-control">
                            @foreach ($operations as $operation)
                                <option value="{{ $operation->id }}">{{ $operation->starts_at }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Briefly say why you can't make it (optional)</label>
                        <textarea name="reason" class="form-control" rows="5"></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="absence-modal-submit">Submit</button>
            </div>
        </div>
    </div>
</div>
