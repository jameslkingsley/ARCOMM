<div class="mission-comment-item">
    <span
        class="mission-comment-item-avatar"
        style="background-image: url({{ $note->user->avatar }})">
    </span>

    <span class="mission-comment-item-username">
        {{ $note->user->username }}
    </span>

    <span class="mission-comment-item-timestamp">
        {{ $note->created_at->diffForHumans() }}

        &nbsp;&nbsp;&middot;

        @if ($note->isMine())
            <a href="javascript:void(0)" title="Delete note" class="btn btn-sm m-a-0 mission-note-delete" data-id="{{ $note->id }}">
                <i class="fa fa-trash"></i>
            </a>
        @endif
    </span>

    <span class="mission-comment-item-text">
        {!! nl2br(e($note->text)) !!}
    </span>
</div>
