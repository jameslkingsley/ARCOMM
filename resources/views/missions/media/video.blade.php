<a
    href="{{ $video->url() }}"
    target="_newtab"
    class="mission-media-item mission-media-item-video"
    style="background-image: url({{ $video->info()->snippet->thumbnails->standard->url }})">

    @if ($video->isMine() || auth()->user()->hasPermission('mission_media:delete'))
        <span class="fa fa-trash mission-video-item-delete" data-video="{{ $video->id }}"></span>
    @endif
</a>
