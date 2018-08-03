<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use App\Channels\DiscordChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentCreated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The comment that was created.
     *
     * @var \App\Models\Comment
     */
    protected $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DiscordChannel::class];
    }

    /**
     * Get the config for the Discord message.
     *
     * @return array
     */
    public function toDiscord($notifiable)
    {
        if ($this->comment->collection === 'notes') {
            return [
                'channel' => config('services.discord.channel_id'),
                'dm' => $this->comment->commentable->user->discord_id,
                'url' => url("/hub/missions/{$this->comment->commentable->ref}/notes"),
                'message' => "**{$this->comment->user->name}** added a note to **{$this->comment->commentable->name}**",
            ];
        }

        return [
            'channel' => config('services.discord.channel_id'),
            'url' => url("/hub/missions/{$this->comment->commentable->ref}/aar"),
            'message' => "**{$this->comment->user->name}** commented on **{$this->comment->commentable->name}**",
        ];
    }
}
