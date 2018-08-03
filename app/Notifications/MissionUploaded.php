<?php

namespace App\Notifications;

use App\Models\Mission;
use Illuminate\Bus\Queueable;
use App\Channels\DiscordChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class MissionUploaded extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The mission that was verified.
     *
     * @var \App\Models\Mission
     */
    protected $mission;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Mission $mission)
    {
        $this->mission = $mission;
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
        return [
            'channel' => config('services.discord.channel_id'),
            'url' => url("/hub/missions/{$this->mission->ref}"),
            'message' => "**{$this->mission->user->name}** submitted the mission **{$this->mission->name}**",
        ];
    }
}
