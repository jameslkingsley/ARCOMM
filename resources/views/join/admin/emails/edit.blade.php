<script>
    $(document).ready(function(e) {
        $('#edit-email-form').submit(function(event) {
            var form = $(this);

            $.ajax({
                type: 'POST',
                url: '{{ url('/hub/applications/api/emails/' . $email->id) }}',
                data: form.serialize(),
                success: function(data) {
                    closePanel();
                    reloadEmails();
                }
            });

            event.preventDefault();
        });

        @if (!$email->locked)
            $('#delete-email').click(function(event) {
                event.preventDefault();
                var canDelete = confirm("Delete this email template?");

                if (canDelete) {
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ url('/hub/applications/api/emails/' . $email->id) }}',
                        success: function(data) {
                            closePanel();
                            reloadEmails();
                        }
                    });
                }
            });
        @endif
    });
</script>

<h3 class="mt-0 mb-5">Edit Email Template</h3>

<form id="edit-email-form">
    {{ method_field('put') }}
    @include('join.admin.emails.form')

    @if (!$email->locked)
        <button class="btn hub-btn pull-right" id="delete-email">
            <i class="fa fa-trash mr-2"></i>
            Delete
        </button>
    @endif
</form>
