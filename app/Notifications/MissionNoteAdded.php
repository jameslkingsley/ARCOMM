<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Missions\MissionNote;

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
        return ['mail', 'database'];
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
            'user_id' => $this->note->user_id,
            'mission_id' => $this->note->mission_id,
            'text' => $this->note->text
        ];
    }
}
