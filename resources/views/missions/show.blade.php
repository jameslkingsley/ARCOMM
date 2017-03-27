<script>
    $(document).ready(function(e) {
        $('.mission-briefing-nav a').click(function(event) {
            var caller = $(this);
            var locked = caller.hasClass('locked');
            var faction = caller.data('faction');

            if (locked) return;

            $.ajax({
                type: 'POST',
                url: '{{ url('/hub/missions/briefing') }}',
                data: {
                    mission_id: {{ $mission->id }},
                    faction: faction
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

@if ($mission->isMine() || auth()->user()->isAdmin())
    <script>
        $(document).ready(function(e) {
            $('.download-mission').click(function(event) {
                event.preventDefault();
                window.location.href = $(this).data('filepath');
            });

            $('#update-mission').dropzone({
                url: '{{ url('/hub/missions/' . $mission->id . '/update') }}',
                acceptedFiles: '',
                addedfile: function(file) {},
                success: function(file, data) {
                    openMission({{ $mission->id }});
                },
                error: function(file, message) {
                    alert(message);
                }
            });

            $('#delete-mission').click(function(event) {
                event.preventDefault();
                var canDelete = confirm("Are you sure you want to delete this mission?");
                if (canDelete) window.location = $(this).attr('href');
            });
        });
    </script>
@endif

@php
    $mission->briefingFactions = $mission->briefingFactions();
@endphp

<div class="large-panel-content">
    <div class="mission-banner" style="background-image: url({{ $mission->banner() }})">
        <span class="mission-banner-heading {{ (strlen($mission->banner()) == 0) ? 'mission-banner-heading-nograd' : '' }}">
            {{ $mission->display_name }}
        </span>

        <span class="mission-banner-tagline">
            By {{ $mission->user->username }}
        </span>
    </div>

    <div class="mission-nav" style="{{ (!$mission->isMine() && !auth()->user()->isAdmin()) ? 'box-shadow:none' : '' }}">
        @if ($mission->isMine() || auth()->user()->isAdmin())
            <div class="hub-dropdown">
                <a href="javascript:void(0)">Download <i class="fa fa-angle-down"></i></a>
                <ul>
                    <li><a href="javascript:void(0)" class="download-mission" data-filepath="{{ $mission->download('pbo') }}">PBO</a></li>
                    <li><a href="javascript:void(0)" class="download-mission" data-filepath="{{ $mission->download('zip') }}">ZIP</a></li>
                </ul>
            </div>

            <div class="hub-dropdown">
                <a href="javascript:void(0)">Manage <i class="fa fa-angle-down"></i></a>
                <ul>
                    <li>
                        <a
                            href="javascript:void(0)"
                            id="update-mission"
                            title="Replace the mission file with an updated one">
                            Update
                        </a>
                    </li>

                    @if (!$mission->existsInOperation() || auth()->user()->isAdmin())
                        <li>
                            <a
                                href="{{ url('/hub/missions/' . $mission->id . '/delete') }}"
                                id="delete-mission"
                                title="Deletes the mission and all of its media, comments and files">
                                Delete
                            </a>
                        </li>
                    @else
                        <li><span title="You cannot delete this mission as it belongs to an operation">Delete</span></li>
                    @endif
                </ul>
            </div>
        @endif

        @if (auth()->user()->isAdmin())
            <script>
                $(document).ready(function(e) {
                    $('.mission-verification a').click(function(event) {
                        var caller = $(this);

                        $.ajax({
                            type: 'POST',
                            url: '{{ url("/hub/missions/{$mission->id}/set-verification") }}',
                            success: function(data) {
                                if (caller.hasClass('verified')) {
                                    caller.removeClass('verified');
                                    caller.addClass('unverified');
                                } else {
                                    caller.removeClass('unverified');
                                    caller.addClass('verified');
                                    caller.find('em').html(data);
                                }
                            }
                        });

                        event.preventDefault();
                    });
                });
            </script>

            <span class="mission-verification">
                <a
                    href="javascript:void(0)"
                    class="{{ ($mission->verified) ? 'verified' : 'unverified' }}"
                    title="Mark this mission as {{ ($mission->verified) ? 'verified' : 'unverified' }}">

                    <em>
                        @if ($mission->verifiedByUser())
                            Verified by {{ $mission->verifiedByUser()->username }}
                        @endif
                    </em>

                    <i class="fa fa-check"></i>
                </a>
            </span>
        @endif

        <span class="mission-version">
            @if ($mission->isNew())
                PUBLISHED {{ $mission->created_at->diffForHumans() }}
            @else
                LAST PLAYED {{ $mission->last_played->diffForHumans() }}
            @endif
            /
            ARCMF {{ $mission->version() }}
        </span>
    </div>

    <div
        id="mission-content-break"
        class="pull-left full-width"
        style="margin-top: calc({{ (!$mission->isMine() && !auth()->user()->isAdmin()) ? '100vh' : '108vh' }} / 1.618)">
    </div>

    <div class="mission-overview-title">
        {{ $mission->fulltextGamemode() }} on {{ $mission->map->display_name }}
    </div>

    <div class="mission-overview">
        <div class="mission-weather">
            <span class="mission-weather-map">
                {{ $mission->date() }} &mdash; {{ $mission->time() }}
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
        @include('missions.comments.list', ['comments' => $mission->comments])
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
                    event.preventDefault();
                    var canDelete = confirm("Are you sure you want to delete this?");

                    if (canDelete) {
                        var caller = $(this);
                        var id = caller.data('id');
                        
                        $.ajax({
                            type: 'DELETE',
                            url: '{{ url("/hub/missions/comments") }}/' + id,
                            success: function(data) {
                                caller.parents('.mission-comment-item').remove();
                            }
                        });
                    }
                });

                $('#submit-mission-comment').submit(function(event) {
                    $('#submit-mission-comment input[name="published"]').val(1);
                    $('#submit-mission-comment input[type="submit"]').prop('disabled', true);

                    $.ajax({
                        type: 'POST',
                        url: '{{ url('/hub/missions/comments') }}',
                        data: $('#submit-mission-comment').serialize(),
                        success: function(data) {
                            $('#submit-mission-comment input[name="id"]').val(-1);
                            $('#submit-mission-comment textarea[name="text"]').val('');
                            $('#submit-mission-comment input[type="submit"]').val('Publish');
                            $('#submit-mission-comment input[type="submit"]').prop('disabled', false);
                            $('#submit-mission-comment #save-mission-comment').show();

                            $.ajax({
                                type: 'GET',
                                url: '{{ url('/hub/missions/comments?mission_id=' . $mission->id) }}',
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
                        url: '{{ url('/hub/missions/comments') }}',
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
                placeholder="Your mission experience..."
            >{{ (!is_null($mission->draft())) ? $mission->draft()->text : '' }}</textarea>

            <input type="submit" name="post" value="Publish" class="btn hub-btn btn-primary pull-right ml-3">
            <button class="btn hub-btn pull-right" id="save-mission-comment">Save Draft</button>
        </form>
    </div>

    <div class="pull-left full-width" style="margin-bottom: 250px"></div>
</div>

<div class="large-panel-sidebar">
    <script>
        $(document).ready(function(e) {
            $('.mission-media-upload').dropzone({
                url: '{{ url('/hub/missions/media/add-photo?mission_id=' . $mission->id) }}',
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
                    url: '{{ url('/hub/missions/media/delete-photo') }}',
                    data: {
                        'media_id': media,
                        'mission_id': mission
                    },
                    success: function(data) {
                        caller.parents('.mission-media-item').remove();
                    }
                });

                event.stopPropagation();
                event.preventDefault();
            });

            $('.mission-media-video-upload').click(function(event) {
                var caller = $(this);
                var mission_id = caller.data('mission');
                var video_url = prompt("Please enter your YouTube video's full URL");

                if (video_url != null) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ url('/hub/missions/media/add-video') }}',
                        data: {
                            'mission_id': mission_id,
                            'video_url': video_url
                        },
                        success: function(data) {
                            $('.mission-media').append(data);
                        }
                    });
                }

                event.preventDefault();
            });

            $(document).on('click', '.mission-video-item-delete', function(event) {
                var caller = $(this);
                var video = caller.data('video');

                $.ajax({
                    type: 'POST',
                    url: '{{ url('/hub/missions/media/delete-video') }}',
                    data: {
                        'video_id': video
                    },
                    success: function(data) {
                        caller.parents('.mission-media-item-video').remove();
                    }
                });

                event.stopPropagation();
                event.preventDefault();
            });
        });
    </script>

    <h2 class="mt-0">
        Media

        <a href="javascript:void(0)" class="mission-media-video-upload btn btn-primary hub-btn pull-right ml-1" data-mission="{{ $mission->id }}">
            <i class="fa fa-youtube" style="pointer-events: none"></i>
        </a>

        <a href="javascript:void(0)" class="mission-media-upload btn btn-primary hub-btn pull-right">
            <i class="fa fa-picture-o" style="pointer-events: none"></i>
        </a>
    </h2>

    <div class="mission-media">
        @foreach ($mission->videos as $video)
            @include('missions.media.video', [
                'video' => $video
            ])
        @endforeach

        @foreach ($mission->photos() as $media)
            @include('missions.media.photo', [
                'media' => $media,
                'mission' => $mission
            ])
        @endforeach
    </div>
</div>
