<script>
    $(document).ready(function(e) {
        $('#create-email').click(function(event) {
            $.ajax({
                type: 'GET',
                url: '{{ url('/hub/applications/api/emails/create') }}',
                success: function(data) {
                    openPanel(data);
                }
            });

            event.preventDefault();
        });

        $('.email-item').click(function(event) {
            var caller = $(this);

            $.ajax({
                type: 'GET',
                url: '{{ url('/hub/applications/api/emails') }}/' + caller.data('id') + '/edit',
                success: function(data) {
                    openPanel(data);
                }
            });

            event.preventDefault();
        });
    });
</script>

{{-- <a href="javascript:void(0)" class="btn btn-primary pull-left m-t-0 m-b-5" id="create-email">
    <i class="fa fa-plus m-r-3"></i>
    Create
</a> --}}

<div class="list-group">
    @foreach ($emails as $email)
        <a href="javascript:void(0)" class="list-group-item list-group-item-action email-item jr-item" data-id="{{ $email->id }}">
        <span class="jr-item-title">{{ $email->subject }}</span>
            <br />

            <span class="jr-item-meta">
                {{ substr($email->content, 0, 500) }}
            </span>
        </a>
    @endforeach
</div>
