<?php

namespace App\Http\Controllers\Missions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Operations\Operation;
use App\Models\Operations\OperationMission;
use \Carbon\Carbon;

class OperationController extends Controller
{
    /**
     * Deletes the given operation item.
     *
     * @return void
     */
    public function removeMission(Request $request)
    {
        if ($request->id == -1) return;
        OperationMission::destroy($request->id);
    }

    /**
     * Creates an operation item for the given mission and operation.
     *
     * @return integer
     */
    public function addMission(Request $request)
    {
        $item = new OperationMission();

        $item->operation_id = $request->operation_id;
        $item->mission_id = $request->mission_id;
        $item->play_order = $request->play_order;

        $item->save();

        return $item->id;
    }

    /**
     * Creates an operation header.
     * Returns the operation item view.
     *
     * @return any
     */
    public function create(Request $request, Operation $operation)
    {
        // Get latest operation date
        $latest = $operation->orderBy('starts_at', 'desc')->first();
        $header = null;

        if ($latest) {
            $header = $operation->create(['starts_at' => $latest->starts_at->addWeek()]);
        } else {
            $header = $operation->create([
                'starts_at' => Carbon::now()
                    ->endOfWeek()
                    ->subDay()
                    ->hour(config('operation.hour'))
                    ->minute(config('operation.minute'))
                    ->second(0)
            ]);
        }
        
        if (isset($request->starts_at) && strlen($request->starts_at) != 0) {
            $header->starts_at = Carbon::parse($request->starts_at);
            $header->save();
        }

        return view('missions.operations.item', ['operation' => $header]);
    }

    /**
     * Destroys the given operation record.
     *
     * @return any
     */
    public function destroy(Request $request, Operation $operation)
    {
        $operation->destroy($request->operation_id);
    }
}
