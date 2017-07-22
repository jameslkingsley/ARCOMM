<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\JoinRequests\JoinRequest;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;
use Illuminate\Notifications\Messages\MailMessage;

class JoinRequestReceived extends Notification
{
    use Queueable;

    /**
     * Join request instance.
     *
     * @var App\Models\JoinRequests\JoinRequest
     */
    protected $jr;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(JoinRequest $jr)
    {
        $this->jr = $jr;
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
            //
        ];
    }

    /**
     * Discord message.
     *
     * @return any
     */
    public function toDiscord($notifiable)
    {
        $jrUrl = url('/hub/applications/show/'.$this->jr->id);

        return DiscordMessage::create(
            "**{$this->jr->name}** submitted an application\n{$jrUrl}"
        );
    }
}
