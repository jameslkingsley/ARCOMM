<?php

namespace App\Channels;

use RestCord\DiscordClient;
use Illuminate\Notifications\Notification;

class DiscordChannel
{
    /**
     * Constructor method.
     *
     * @return void
     */
    public function __construct(DiscordClient $discord)
    {
        $this->discord = $discord;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $config = (object) $notification->toDiscord($notifiable);
        $channelIds = [$config->channel];

        if (isset($config->dm) && $config->dm) {
            $channelIds[] = $this->discord->user->createDm([
                'recipient_id' => (int) $config->dm
            ])->id;
        }

        foreach ($channelIds as $channelId) {
            $this->discord->channel->createMessage([
                'channel.id' => (int) $channelId,
                'embed' => [
                    'url' => $config->url,
                    'title' => $config->message,
                ]
            ]);
        }
    }
}
