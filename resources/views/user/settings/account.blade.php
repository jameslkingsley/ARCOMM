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

        $('#avatar-sync').click(function(event) {
            $.ajax({
                type: 'GET',
                url: '{{ url('/hub/settings/avatar-sync') }}',
                success: function(data) {
                    $('.user-avatar').css('background-image', 'url(' + data.trim() + ')');
                }
            });

            event.preventDefault();
        });
    });
</script>

<div
    class="user-avatar user-avatar-medium pull-left mr-3"
    style="background-image: url({{ auth()->user()->avatar }})">
</div>

<a href="javascript:void(0)" class="btn hub-btn btn-primary pull-left" id="avatar-sync">Sync From Steam</a>

<div class="pull-left full-width mb-5 mt-5"></div>

<form method="post" class="col-md-6 p0" id="update-account">
    <div class="form-group">
        <label class="control-label hub-label">Username</label>
        <input class="form-control hub-control" type="text" name="username" value="{{ auth()->user()->username }}" placeholder="Username" maxlength="30">
    </div>

    <div class="form-group">
        <input class="btn hub-btn btn-primary pull-left mt-5" type="submit" value="Save Changes">
    </div>
</form>
