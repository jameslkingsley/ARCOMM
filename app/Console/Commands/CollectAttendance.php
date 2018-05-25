<?php

namespace App\Console\Commands;

use GameQ\GameQ;
use App\Models\Portal\User;
use App\Models\Missions\Map;
use Illuminate\Console\Command;
use App\Models\Missions\Mission;
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
    public function handle($runningInConsole = true)
    {
        $users = User::all();
        $closestOperation = Operation::orderBy(DB::raw('ABS(DATEDIFF(operations.starts_at, NOW()))'))->first();
        $minutesOver = $closestOperation->starts_at->diffInMinutes(now(), false);

        if ($minutesOver < 60 && $runningInConsole) {
            // Don't collect attendance when we're not
            // actually playing the operation and only
            // if it has been at least 60 minutes over.
            return $this->info('Operation is not being played yet!');
        }

        $present = collect();
        $absent = collect();

        $query = new GameQ;

        $query->addServer([
            'id' => 'main',
            'type' => 'armedassault3',
            'host' => '108.61.34.58:2302',
            'options' => [
                'debug' => true,
                'timeout' => 10
            ]
        ]);

        $results = json_decode(json_encode($query->process()['main']));

        while (!isset($results->map)) {
            $results = json_decode(json_encode($query->process()['main']));
            sleep(1);
        }

        $missions = [];
        $currentMission = null;

        if ($map = Map::select('id')->whereDisplayName(optional($results)->map)->first()) {
            $missions = Mission::whereMapId($map->id)->get();
        }

        foreach ($missions as $mission) {
            $parts = explode('/', $mission->cloud_pbo);
            $name = str_replace_last(".{$results->map}.pbo", '', end($parts));

            if (strtolower($name) === strtolower($results->gq_gametype)) {
                $currentMission = $mission;
                break;
            }
        }

        foreach ($users as $user) {
            $found = false;

            foreach ($results->players as $player) {
                $name = $player->name;

                if (str_contains($player->name, '[ARC]')) {
                    $name = str_replace('[ARC]', '', $name);
                }

                $name = trim($name);

                if (str_contains(strtolower($user->username), strtolower($name))) {
                    $found = true;
                    $present->push("{$user->username} marked as present");

                    Attendance::firstOrCreate([
                        'present' => true,
                        'user_id' => $user->id,
                        'operation_id' => $closestOperation->id,
                        'mission_id' => optional($currentMission)->id,
                    ]);
                }
            }

            if (!$found) {
                $absent->push("{$user->username} marked as absent");

                Attendance::firstOrCreate([
                    'present' => false,
                    'user_id' => $user->id,
                    'operation_id' => $closestOperation->id,
                    'mission_id' => optional($currentMission)->id,
                ]);
            }
        }

        staffProxy()->notify(new AttendanceCollected($present, $absent));
    }
}
