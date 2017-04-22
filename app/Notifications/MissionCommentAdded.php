<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Missions\Mission;
use App\Models\Missions\MissionComment;
use App\Models\Portal\User;

class MissionCommentAdded extends Notification
{
    use Queueable;

    /**
     * The comment instance.
     *
     * @var App\Models\Missions\MissionComment
     */
    protected $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MissionComment $comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'comment' => $this->comment
        ];
    }

    /**
     * Gets the computed full URL for the notification.
     *
     * @return string
     */
    public static function link($notification)
    {
        $mission = Mission::findOrFail($notification->data['comment']['mission_id']);

        return "{$mission->url()}/aar#comment-{$notification->data['comment']['id']}";
    }

    /**
     * Gets the computed text for the notification.
     *
     * @return string
     */
    public static function text($notification)
    {
        $sender = User::findOrFail($notification->data['comment']['user_id']);
        $mission = Mission::findOrFail($notification->data['comment']['mission_id']);
        $relation = $mission->isMine() ? 'your' : 'the';

        return "{$sender->username} commented on {$relation} mission {$mission->display_name}";
    }

    /**
     * Gets the full icon URL for the notification.
     *
     * @return string
     */
    public static function icon($notification)
    {
        $sender = User::findOrFail($notification->data['comment']['user_id']);

        return $sender->avatar;
    }

    /**
     * Checks whether the notification still exists.
     *
     * @return boolean
     */
    public static function exists($notification)
    {
        $sender = User::find($notification->data['comment']['user_id']);
        $comment = MissionComment::find($notification->data['comment']['id']);
        $mission = Mission::find($notification->data['comment']['mission_id']);

        if ($mission && $comment && $sender) return true;

        return false;
    }
}
