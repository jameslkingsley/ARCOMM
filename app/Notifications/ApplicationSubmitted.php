<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use App\Channels\DiscordChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationSubmitted extends Notification
{
    use Queueable;

    /**
     * The application instance.
     *
     * @var \App\Models\Application
     */
    protected $application;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', DiscordChannel::class];
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
            ->subject('Thank you for your application!')
            ->greeting('Thank you for your application!')
            ->line('Your application has been submitted and is awaiting review. Applications are usually reviewed within 2-3 days of submission. Once your application is reviewed you will receive an additional email as to whether or not it was accepted or declined.');
    }

    /**
     * Get the config for the Discord message.
     *
     * @return array
     */
    public function toDiscord($notifiable)
    {
        return [
            'channel' => config('services.discord.staff_id'),
            'url' => url("/admin/resources/applications/{$this->application->id}"),
            'message' => "**{$this->application->name}** submitted an application",
        ];
    }
}
