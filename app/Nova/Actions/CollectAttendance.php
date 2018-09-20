<?php

namespace App\Nova\Actions;

use GameQ\GameQ;
use App\Models\User;
use App\Models\Mission;
use App\Models\Attendance;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CollectAttendance extends Action // implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $operations)
    {
        $users = User::all();
        $server = $this->queryServer();

        foreach ($operations as $operation) {
            foreach ($users as $user) {
                foreach ($server->players as $player) {
                    $name = $player->name;

                    $name = preg_replace('(\[.*\])', '', $name);
                    $name = preg_replace('(\d)', '', $name);
                    $name = str_slug(strtolower(trim($name)), '-');

                    $hubName = str_slug(strtolower(trim($user->name)));

                    if (str_contains($hubName, $name)) {
                        Attendance::updateOrCreate([
                            'user_id' => $user->id,
                            'operation_id' => $operation->id,
                            'mission_id' => (int) $fields->mission,
                        ]);

                        break;
                    }
                }
            }
        }
    }

    /**
     * Gets the Arma server details.
     *
     * @return mixed
     */
    public function queryServer()
    {
        $query = (new GameQ)
            ->addServer([
                'id' => 'main',
                'type' => 'armedassault3',
                'host' => config('arma.server_ip') . ':' . config('arma.server_port'),
                'options' => [
                    'debug' => true,
                    'timeout' => 10
                ]
            ]);

        $results = $this->toObject($query->process()['main']);

        // Brute force the data as sometimes it doesn't respond
        while (!isset($results->map)) {
            $results = $this->toObject($query->process()['main']);

            sleep(1);
        }

        return $results;
    }

    /**
     * Converts the given array to object (recursive).
     *
     * @return object
     */
    public function toObject($data)
    {
        return json_decode(json_encode($data));
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $missions = [];

        foreach (Mission::all() as $mission) {
            $missions[$mission->id] = $mission->name;
        }

        return [
            Select::make('Mission')->options($missions)
        ];
    }
}
