<div class="large-panel-content">
    <div class="mission-banner" style="background-image: url({{ $mission->banner() }})">
        <span class="mission-banner-heading">
            {{ $mission->display_name }}
        </span>
    </div>

    <h1 class="mt-0 mb-0" style="margin-top: calc(100vh / 1.618)">
        {{ $mission->display_name }}
    </h1>

    <p class="text-muted">
        By {{ $mission->user->username }}

        @if(!is_null($mission->last_played))
            &mdash;
            <span title="{{ $mission->last_played }}">Last played {{ $mission->last_played->diffForHumans() }}</span>
        @endif
    </p>

    <h3>Briefing</h3>
    <p>{!! $mission->summary !!}</p>

    <h3>After-Action Report</h3>

    <div class="mission-comments">
        @foreach ($mission->comments as $comment)
            @if($comment->published)
                <div class="mission-comment-item">
                    <span
                        class="mission-comment-item-avatar"
                        style="background-image: url({{ $comment->user->avatar }})">
                    </span>

                    <span class="mission-comment-item-username">
                        {{ $comment->user->username }}
                    </span>

                    <span class="mission-comment-item-timestamp">
                        {{ $comment->created_at->diffForHumans() }}
                    </span>

                    <span class="mission-comment-item-text">
                        {{ $comment->text }}
                    </span>
                </div>
            @endif
        @endforeach
    </div>

    <div class="mission-comments-form">
        <script>
            $(document).ready(function(e) {
                $('#submit-mission-comment').submit(function(event) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ url('/hub/missions/publish-comment') }}',
                        data: $('#submit-mission-comment').serialize(),
                        success: function(data) {
                            $('#submit-mission-comment input[name="id"]').val(data.trim());
                        }
                    });

                    event.preventDefault();
                });

                $('#save-mission-comment').click(function(event) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ url('/hub/missions/save-comment') }}',
                        data: $('#submit-mission-comment').serialize(),
                        success: function(data) {
                            $('#submit-mission-comment input[name="id"]').val(data.trim());
                        }
                    });

                    event.preventDefault();
                });
            });
        </script>

        <form method="post" id="submit-mission-comment">
            <input type="hidden" name="id" value="{{ (!is_null($mission->draft())) ? $mission->draft()->id : '-1' }}">
            <input type="hidden" name="mission_id" value="{{ $mission->id }}">

            <textarea
                name="text"
                class="form-control hub-form-control mb-3"
                rows="10"
                placeholder="Your thoughts about the mission..."
            >{{ (!is_null($mission->draft())) ? $mission->draft()->text : '' }}</textarea>

            <input type="submit" name="post" value="Publish" class="btn hub-btn btn-primary pull-right ml-3">
            <button class="btn hub-btn pull-right" id="save-mission-comment">Save Draft</button>
        </form>
    </div>

    <div class="pull-left full-width" style="margin-bottom: 250px"></div>
</div>

<div class="large-panel-sidebar">
    <h2 class="mt-0">Media</h2>

    <div class="mission-media">
        <a href="javascript:void(0)" class="mission-media-upload mission-media-item">
            <i class="fa fa-plus"></i>
        </a>
    </div>
</div>
