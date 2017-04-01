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

<a href="javascript:void(0)" class="btn hub-btn btn-primary pull-left mt-0 mb-5" id="create-email">
    <i class="fa fa-plus mr-3"></i>
    Create
</a>

<ul class="hub-list">
    @foreach ($emails as $email)
        <li>
            <a href="javascript:void(0)" class="hub-list-item email-item" data-id="{{ $email->id }}">
                <h1>{{ $email->subject }}</h1>
                <p>{{ substr($email->content, 0, 500) }}</p>
            </a>
        </li>
    @endforeach
</ul>
