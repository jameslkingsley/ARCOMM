<h1 class="mt-0 mb-5">Account</h1>

<script>
    $(document).ready(function(e) {
        $('#update-account').submit(function(event) {
            $.ajax({
                type: 'POST',
                url: '{{ url('/hub/settings') }}',
                data: $('#update-account').serialize(),
                success: function(data) {
                    alert('Changes Saved!');
                }
            });

            event.preventDefault();
        });
    });
</script>

<form method="post" class="col-md-6 p0" id="update-account">
    <div class="form-group">
        <label class="control-label hub-label">Username</label>
        <input class="form-control hub-control" type="text" name="username" value="{{ auth()->user()->username }}" placeholder="Username" maxlength="30">
    </div>

    <div class="form-group">
        <input class="btn hub-btn btn-primary pull-left mt-5" type="submit" value="Save Changes">
    </div>
</form>
