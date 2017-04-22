<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Missions\Mission;
use App\Models\Missions\MissionNote;
use App\Models\Portal\User;

class MissionNoteAdded extends Notification
{
    use Queueable;

    /**
     * The note instance.
     *
     * @var App\Models\Missions\MissionNote
     */
    protected $note;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MissionNote $note)
    {
        $this->note = $note;

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
            'note' => $this->note
        ];
    }

    /**
     * Gets the computed full URL for the notification.
     *
     * @return string
     */
    public static function link($notification)
    {
        $mission = Mission::findOrFail($notification->data['note']['mission_id']);

        return "{$mission->url()}/notes";
    }

    /**
     * Gets the computed text for the notification.
     *
     * @return string
     */
    public static function text($notification)
    {
        $sender = User::findOrFail($notification->data['note']['user_id']);
        $mission = Mission::findOrFail($notification->data['note']['mission_id']);
        $relation = $mission->isMine() ? 'your' : 'the';

        return "{$sender->username} added a note to {$relation} mission {$mission->display_name}";
    }

    /**
     * Gets the full icon URL for the notification.
     *
     * @return string
     */
    public static function icon($notification)
    {
        $sender = User::findOrFail($notification->data['note']['user_id']);

        return $sender->avatar;
    }
}
