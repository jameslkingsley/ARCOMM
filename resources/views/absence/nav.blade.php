<script>
    $(document).ready(function(e) {
        $('#absence-opener').click(function(event) {
            $.ajax({
                type: 'GET',
                url: '{{ url('/hub/absence/create') }}',
                success: function(data) {
                    $('.dynamic-modal').html(data);
                    $('.dynamic-modal').find('.modal').modal('show');
                }
            });

            event.preventDefault();
        });
    });
</script>

<a href="javascript:void(0)" class="nav-link nav-item hidden-sm-down m-l-3" id="absence-opener">
    Post Absence
</a>
