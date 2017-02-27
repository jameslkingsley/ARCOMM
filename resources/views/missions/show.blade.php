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

            if (top >= 590) {
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

        $('.mission-briefing-nav a').click(function(event) {
            var caller = $(this);
            var locked = caller.hasClass('locked');
            var faction = caller.data('faction');

            if (locked) return;

            $.ajax({
                type: 'POST',
                url: '{{ url('/hub/missions/show-briefing') }}',
                data: {
                    'mission_id': {{ $mission->id }},
                    'faction': faction
                },
                success: function(data) {
                    $('.mission-briefing-content').html(data);
                    $('.mission-briefing-nav a').removeClass('active');
                    caller.addClass('active');
                }
            });

            event.preventDefault();
        });
    });
</script>

@php
    $mission->briefingFactions = $mission->briefingFactions();
@endphp

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

        @if (!empty($mission->briefingFactions))
            <a href="javascript:void(0)" class="mission-nav-item" data-section="briefing">
                Briefing
            </a>
        @endif

        <a href="javascript:void(0)" class="mission-nav-item" data-section="aar">
            After-Action Report
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

    @if (!empty($mission->briefingFactions))
        <script>
            $(document).ready(function(e) {
                $('.mission-briefing-nav a:first').click();
            });
        </script>

        <h3>Briefing</h3>

        <div class="mission-briefing">
            <div class="mission-briefing-nav">
                @foreach ($mission->briefingFactions as $item)
                    <a
                        href="javascript:void(0)"
                        data-faction="{{ $item->faction }}">
                        {{ $item->name }}
                    </a>
                @endforeach
            </div>

            <div class="mission-briefing-content"></div>
        </div>
    @endif

    <h3 id="aar">After-Action Report</h3>

    <div class="mission-comments">
        @include('missions.comments', ['comments' => $mission->comments])
    </div>

    <div class="mission-comments-form">
        <script>
            $(document).ready(function(e) {
                $(document).on('click', '.mission-comment-control-edit', function(event) {
                    var caller = $(this);
                    var id = caller.data('id');
                    var text = caller.parents('.mission-comment-item').find('.mission-comment-item-text').html();

                    $('#submit-mission-comment input[name="id"]').val(id);
                    $('#submit-mission-comment textarea[name="text"]').val(text.trim());
                    $('#submit-mission-comment input[type="submit"]').val('Save Changes');
                    $('#submit-mission-comment #save-mission-comment').hide();
                    $('#submit-mission-comment textarea[name="text"]').focus();
                    $('.large-panel-container').scrollTop(10000);

                    event.preventDefault();
                });

                $(document).on('click', '.mission-comment-control-delete', function(event) {
                    var caller = $(this);
                    var id = caller.data('id');
                    
                    $.ajax({
                        type: 'POST',
                        url: '{{ url('/hub/missions/delete-comment') }}',
                        data: {'comment_id': id},
                        success: function(data) {
                            caller.parents('.mission-comment-item').remove();
                        }
                    });

                    event.preventDefault();
                });

                $('#submit-mission-comment').submit(function(event) {
                    $('#submit-mission-comment input[name="published"]').val(1);
                    $('#submit-mission-comment input[type="submit"]').prop('disabled', true);

                    $.ajax({
                        type: 'POST',
                        url: '{{ url('/hub/missions/save-comment') }}',
                        data: $('#submit-mission-comment').serialize(),
                        success: function(data) {
                            $('#submit-mission-comment input[name="id"]').val(-1);
                            $('#submit-mission-comment textarea[name="text"]').val('');
                            $('#submit-mission-comment input[type="submit"]').val('Publish');
                            $('#submit-mission-comment input[type="submit"]').prop('disabled', false);
                            $('#submit-mission-comment #save-mission-comment').show();

                            $.ajax({
                                type: 'POST',
                                url: '{{ url('/hub/missions/show-comments') }}',
                                data: {'mission_id': {{ $mission->id }}},
                                success: function(data) {
                                    $('.mission-comments').html(data);
                                }
                            });
                        },
                        error: function() {
                            $('#submit-mission-comment input[type="submit"]').prop('disabled', false);
                        }
                    });

                    event.preventDefault();
                });

                $('#save-mission-comment').click(function(event) {
                    $('#submit-mission-comment input[name="published"]').val(0);

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
            <input type="hidden" name="published" value="0">

            <textarea
                name="text"
                class="form-control hub-form-control mb-3"
                style="color: black"
                rows="10"
                placeholder="Your thoughts on the mission..."
            >{{ (!is_null($mission->draft())) ? $mission->draft()->text : '' }}</textarea>

            <input type="submit" name="post" value="Publish" class="btn hub-btn btn-primary pull-right ml-3">
            <button class="btn hub-btn pull-right" id="save-mission-comment">Save Draft</button>
        </form>
    </div>

    <div class="pull-left full-width" style="margin-bottom: 250px"></div>
</div>

<div class="large-panel-sidebar">
    <h2 class="mt-0">Media</h2>

    <script>
        $(document).ready(function(e) {
            $('.mission-media-upload').dropzone({
                url: '{{ url('/hub/missions/add-media?mission_id=' . $mission->id) }}',
                acceptedFiles: 'image/*',
                addedfile: function(file) {},
                success: function(file, data) {
                    $('.mission-media').append(data);
                }
            });

            $('.mission-media').magnificPopup({
                delegate: '.mission-media-item-image',
                type: 'image',
                gallery: {
                    enabled: true
                }
            });

            $(document).on('click', '.mission-media-item-delete', function(event) {
                var caller = $(this);
                var media = caller.data('media');
                var mission = caller.data('mission');

                $.ajax({
                    type: 'POST',
                    url: '{{ url('/hub/missions/delete-media') }}',
                    data: {
                        'media_id': media,
                        'mission_id': mission
                    },
                    success: function(data) {
                        caller.parents('.mission-media-item').remove();
                    }
                });

                event.preventDefault();
            });
        });
    </script>

    <div class="mission-media">
        <a href="javascript:void(0)" class="mission-media-upload mission-media-item">
            <i class="fa fa-upload" style="pointer-events: none"></i>
        </a>

        @foreach ($mission->getMedia() as $media)
            @include('missions.media-item', [
                'media' => $media,
                'mission' => $mission
            ])
        @endforeach
    </div>
</div>
