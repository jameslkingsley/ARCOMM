<script>
    $(document).ready(function(e) {
        $('#mission-share').click(function(event) {
            var caller = $(this);
            alert("{{ url('/share/'.$mission->id) }}");
            event.preventDefault();
        });
    });
</script>

<li class="nav-item">
    <a id="mission-share" class="nav-link" href="javascript:void(0)">
        Share
    </a>
</li>
