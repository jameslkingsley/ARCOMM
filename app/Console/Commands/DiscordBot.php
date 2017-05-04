<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Discord\Discord;
use App\Models\Operations\Operation;

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $discord = new Discord([
            'token' => config('services.discord.token')
        ]);

        $discord->on('ready', function($discord) {
            $discord->on('message', function($message, $discord) {
                $rand = mt_rand() / mt_getrandmax();

                if ($rand > 0.05 && (!str_contains($message, '309300058669449217') || $message->author->user->id == '309300058669449217')) return;

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
