<script>
    $(document).ready(function(e) {
        $('#create-email-form').submit(function(event) {
            var form = $(this);

            $.ajax({
                type: 'POST',
                url: '{{ url('/hub/applications/api/emails') }}',
                data: form.serialize(),
                success: function(data) {
                    closePanel();
                    reloadEmails();
                }
            });

            event.preventDefault();
        });
    });
</script>

<h3 class="mt-0 mb-5">Create Email Template</h3>

<form id="create-email-form">
    @include('join.admin.emails.form')
</form>
