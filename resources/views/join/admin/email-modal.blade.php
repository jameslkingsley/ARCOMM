<form method="post">
    <div class="form-group p-t-0">
        <select name="template" class="form-control">
            <option value="-1" selected="true">Select Preset (optional)</option>
            @foreach ($emails as $email)
                <option value="{{ $email->id }}">
                    {{ $email->subject }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group p-t-0">
        <input name="subject" type="text" class="form-control" placeholder="Subject">
    </div>

    <div class="form-group">
        <textarea name="body" class="form-control" placeholder="Contents" rows="10"></textarea>
    </div>
</form>
