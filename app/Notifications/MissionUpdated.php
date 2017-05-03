<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;
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

    protected $only_discord;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MissionRevision $revision, bool $only_discord = false)
    {
        $this->revision = $revision;
        $this->only_discord = $only_discord;

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
        return $this->only_discord ? [DiscordChannel::class] : ['database'];
    }

    /**
     * Discord message.
     *
     * @return any
     */
    public function toDiscord($notifiable)
    {
        return DiscordMessage::create("**{$this->revision->user->username}** updated the mission **{$this->revision->mission->display_name}**");
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
