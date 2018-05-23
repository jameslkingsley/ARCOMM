<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use App\Models\Portal\User;
use Illuminate\Console\Command;
use App\Models\Portal\Attendance;
use Illuminate\Support\Facades\DB;
use App\Models\Operations\Operation;
use App\Notifications\AttendanceCollected;

class CollectAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collects attendance data about all members.';

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
        $closestOperation = Operation::orderBy(DB::raw('ABS(DATEDIFF(operations.starts_at, NOW()))'))->first();
        $minutesOver = $closestOperation->starts_at->diffInMinutes(now(), false);

        if ($minutesOver < 60) {
            // Don't collect attendance when we're not
            // actually playing the operation and only
            // if it has been at least 60 minutes over.
            return $this->info('Operation is not being played yet!');
        }

        $steamApiKey = config('steam-api.steamApiKey');
        $steamIds = User::all()->pluck('steam_id')->implode(',');

        $client = new Client(['base_uri' => 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/']);
        $response = $client->get("?steamids={$steamIds}&key={$steamApiKey}");
        $players = collect(json_decode((string) $response->getBody())->response->players);

        $present = collect();
        $absent = collect();

        foreach ($players as $player) {
            if ($user = User::whereSteamId($player->steamid)->first()) {
                if (isset($player->gameextrainfo) && strtolower($player->gameextrainfo) === 'arma 3') {
                    $present->push("{$user->username} marked as present");

                    Attendance::firstOrCreate([
                        'present' => true,
                        'user_id' => $user->id,
                        'operation_id' => $closestOperation->id
                    ]);
                } else {
                    $absent->push("{$user->username} marked as absent");

                    Attendance::firstOrCreate([
                        'present' => false,
                        'user_id' => $user->id,
                        'operation_id' => $closestOperation->id
                    ]);
                }
            }
        }

        staffProxy()->notify(new AttendanceCollected($present, $absent));
    }
}
