<script>
    $(document).ready(function(e) {
        setContentTop = function() {
            $('#mission-content-break').css(
                'margin-top',
                $('.mission-nav').offset().top + 50 + 'px'
            );
        }

        $(window).resize(function() {
            setContentTop();
        });

        $('.large-panel-container').scroll(function() {
            var top = $(this).scrollTop();
            var left = $('.mission-nav').offset().left;
            var right = $(window).innerWidth() - ($('.mission-nav').offset().left + $('.mission-nav').outerWidth());

            if (top >= 600) {
                $('.mission-nav').css({
                    'position': 'fixed',
                    'top': 0,
                    'left': left,
                    'right': right
                });
            } else {
                $('.mission-nav').css({
                    'position': 'absolute',
                    'top': 'calc(100vh / 1.618)',
                    'left': 0,
                    'right': 0
                });
            }
        });
    });
</script>

<div class="large-panel-content">
    <div class="mission-banner" style="background-image: url({{ $mission->banner() }})">
        <span class="mission-banner-heading">
            {{ $mission->display_name }}
        </span>

        <span class="mission-banner-tagline">
            By {{ $mission->user->username }}
        </span>
    </div>

    <div class="mission-nav">
        <a href="javascript:void(0)" class="mission-nav-item active" data-section="overview">
            Overview
        </a>

        <a href="javascript:void(0)" class="mission-nav-item" data-section="briefing">
            Briefing
        </a>

        <a href="javascript:void(0)" class="mission-nav-item" data-section="aar">
            After-Action Report
        </a>

        <a href="javascript:void(0)" class="mission-nav-item" data-section="history">
            History
        </a>

        <span class="mission-version">
            ARCMF {{ $mission->version() }}
        </span>
    </div>

    <div id="mission-content-break" class="pull-left full-width" style="margin-top: calc(108vh / 1.618)"></div>

    <div class="mission-overview">
        <div class="mission-weather">
            <span class="mission-weather-map">
                {{ $mission->map->display_name }} &mdash; {{ $mission->date() }} &mdash; {{ $mission->time() }}
            </span>

            <span class="mission-weather-overcast">
                {{ $mission->weather() }}
            </span>

            <span class="mission-weather-image" style="background-image: url('{{ $mission->weatherImage() }}')"></span>
        </div>
    </div>

    <h3>Briefing</h3>

    <div class="mission-briefing">
        <div class="mission-briefing-nav">
            <a href="javascript:void(0)" class="active" data-classname="BLUFOR">BLUFOR</a>
            <a href="javascript:void(0)" data-classname="OPFOR">OPFOR</a>
            <a href="javascript:void(0)" data-classname="INDFOR">INDFOR</a>
            <a href="javascript:void(0)" data-classname="CIVILIAN">CIVILIAN</a>
            <a href="javascript:void(0)" data-classname="GAME_MASTER">GAME MASTER</a>
        </div>

        <div class="mission-briefing-content">
            @foreach ([
                'situation' => 'Situation',
                'mission' => 'Mission'
            ] as $subject => $heading)
                <h4>{{ $heading }}</h4>
                @foreach ($mission->config()->CfgARCMF->Briefing->BLUFOR->$subject as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            @endforeach
        </div>
    </div>

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
