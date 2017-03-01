<?php

namespace App\Http\Controllers\Missions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Operations\Operation;
use App\Models\Operations\OperationMission;

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
}
