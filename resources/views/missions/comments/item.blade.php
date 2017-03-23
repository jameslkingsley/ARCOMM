@if ($comment->published)
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

        @if ($comment->isMine())
            <div class="mission-comment-controls">
                <a href="javascript:void(0)" class="mission-comment-control mission-comment-control-edit" data-id="{{ $comment->id }}" title="Edit">
                    <i class="fa fa-pencil"></i>
                </a>

                <a href="javascript:void(0)" class="mission-comment-control mission-comment-control-delete" data-id="{{ $comment->id }}" title="Delete">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        @endif

        <span class="mission-comment-item-text">
            {!! nl2br(e($comment->text)) !!}
        </span>
    </div>
@endif
