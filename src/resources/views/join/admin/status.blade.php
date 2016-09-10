<script>
    $(document).ready(function() {
        $('.jr-status-menu').dropit();

        reload = function() {
            $.ajax({
                type: 'POST',
                url: '{{ action("JoinController@getStatusView") }}',
                data: {
                    "id": {{ $jr->id }}
                },
                success: function(data) {
                    $('#status').html(data);
                }
            });
        }

        setStatus = function(id) {
            $.ajax({
                type: 'POST',
                url: '{{ action("JoinController@setStatus") }}',
                data: {
                    "join_request_id": {{ $jr->id }},
                    "new_status_id": id
                },
                success: function(data) {
                    reload();
                }
            });
        }

        $('#new_status').keyup(function(event) {
            var $this = $(this);
            var text = $this.val();
            var code = event.keyCode || event.which;

            // Do nothing if there is no text
            if (text.length === 0) return;

            if (code == 13) {
                // Enter key was pressed

                // Disable the input to prevent multiple insertions
                $this.prop("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: '{{ action("JoinController@createStatus") }}',
                    data: {"text": text},
                    success: function(data) {
                        var newStatusID = data;
                        setStatus(newStatusID);
                    }
                });
            }
        });
    });
</script>

<div class="jr-status-menu {{ strtolower($jr->status->permalink) }}">
    <div>
        <a href="javascript:void(0)" data-id="{{ $jr->status->id }}" data-text="{{ $jr->status->text }}">{{ $jr->status->text }}<i class="fa fa-sort"></i></a>
        <ul>
            @foreach ($joinStatuses as $status)
                @unless ($status->id == $jr->status->id)
                    <li><a href="javascript:void(0)" onclick="javascript:setStatus({{ $status->id }})">{{ $status->text }}</a></li>
                @endunless
            @endforeach
            <li class="separator"></li>
            <li><input type="text" name="status" id="new_status" placeholder="New status" maxlength="12"></li>
        </ul>
    </div>
</div>