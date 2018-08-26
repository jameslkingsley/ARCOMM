<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationStatusChanged extends Notification
{
    use Queueable;

    /**
     * The application instance.
     *
     * @var \App\Models\Application
     */
    protected $application;

    /**
     * Email template instance.
     *
     * @var \App\Models\EmailTemplate|null
     */
    protected $template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Application $application, $template)
    {
        $this->application = $application;
        $this->template = $template;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->template ? ['mail'] : [];
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
            ->subject($this->template->subject)
            ->greeting($this->template->subject)
            ->line($this->template->body);
    }
}
