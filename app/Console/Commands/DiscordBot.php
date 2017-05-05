<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Discord\Discord;
use App\Models\Operations\Operation;
use App\Models\Missions\Mission;
use Log;

class DiscordBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts the Discord chat bot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets the next operation diff for humans.
     *
     * @return string
     */
    public function op()
    {
        $operation = Operation::nextWeek();

        if (!$operation)
            return "I couldn't find an operation for next week. One needs to be created on ARCHUB by an admin.";

        $text = "Next operation is in {$operation->startsIn()}";

        if (!$operation->missions->isEmpty()) {
            $missions = $operation->missions->map(function($item) {
                return "**{$item->mission->display_name}**";
            });

            $text .= "\nMissions: {$missions->implode(', ')}";
        }

        return $text;
    }

    /**
     * Gets a breakdown of the total number of missions.
     *
     * @return string
     */
    public function missions()
    {
        $past = Mission::allPast();
        $new = Mission::allNew();

        return "There are {$new->count()} new missions and {$past->count()} past missions.";
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $command = $this;

        $discord = new Discord([
            'token' => config('services.discord.token')
        ]);

        $discord->on('ready', function($discord) use($command) {
            $discord->on('message', function($message, $discord) use($command) {
                if (starts_with($message->content, '!')) {
                    $parts = explode(' ', substr($message->content, 1));

                    if (method_exists($command, $parts[0])) {
                        $message->channel->sendMessage(
                            $command->{$parts[0]}($parts)
                        );
                    } else {
                        return;
                    }

                    return;
                }

                $bot_id = '309300058669449217';
                $rand = mt_rand() / mt_getrandmax();

                if ($rand > 0.02 && (!str_contains($message, $bot_id) || $message->author->user->id == $bot_id)) return;

                $query = http_build_query([
                    'key' => env('CLEVERBOT_API_KEY'),
                    'input' => $message->content
                ]);

                $json = file_get_contents('https://www.cleverbot.com/getreply?'.$query);
                $obj = json_decode($json);
                $message->channel->sendMessage($obj->output);
            });
        });

        $discord->run();
    }
}
