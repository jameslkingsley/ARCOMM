<div class="mission-notes-item">
    <span
        class="avatar"
        style="background-image: url({{ $note->user->avatar }})">
    </span>

    <span class="username">
        {{ $note->user->username }}
    </span>

    <span class="timestamp">
        @if ($note->isMine())
            <a href="javascript:void(0)" title="Delete note" class="mission-note-delete" data-id="{{ $note->id }}">
                <i class="fa fa-trash"></i>
            </a>
            &mdash;
        @endif

        {{ $note->created_at->diffForHumans() }}
    </span>

    <span class="text">
        {{ $note->text }}
    </span>
</div>
