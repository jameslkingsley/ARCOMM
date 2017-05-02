<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Missions\Mission;
use App\Models\Missions\MissionRevision;
use App\Models\Portal\User;

class MissionUpdated extends Notification
{
    use Queueable;

    /**
     * The revision instance.
     *
     * @var App\Models\Missions\MissionRevision
     */
    protected $revision;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MissionRevision $revision)
    {
        $this->revision = $revision;

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
        return $this->revision;
    }

    /**
     * Gets the computed full URL for the notification.
     *
     * @return string
     */
    public static function link($notification)
    {
        $mission = Mission::findOrFail($notification->data['mission_id']);

        return $mission->url();
    }

    /**
     * Gets the computed text for the notification.
     *
     * @return string
     */
    public static function text($notification)
    {
        $mission = Mission::findOrFail($notification->data['mission_id']);
        $sender = User::findOrFail($notification->data['user_id']);
        $relation = ($mission->user_id == $sender->id) ? 'their' : 'the';

        return "{$sender->username} updated {$relation} mission {$mission->display_name}";
    }

    /**
     * Gets the full icon URL for the notification.
     *
     * @return string
     */
    public static function icon($notification)
    {
        $sender = User::findOrFail($notification->data['user_id']);

        return $sender->avatar;
    }

    /**
     * Checks whether the notification still exists.
     *
     * @return boolean
     */
    public static function exists($notification)
    {
        $mission = Mission::findOrFail($notification->data['mission_id']);
        $sender = User::findOrFail($notification->data['user_id']);

        if ($mission && $sender) return true;

        return false;
    }
}
