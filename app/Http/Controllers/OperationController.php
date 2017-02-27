<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operations\OperationMission;

class OperationController extends Controller
{
    /**
     * Deletes the given operation item.
     *
     * @return void
     */
    public function deleteItem(Request $request)
    {
        if ($request->id == -1) return;
        OperationMission::destroy($request->id);
    }

    /**
     * Creates an operation item for the given mission and operation.
     *
     * @return integer
     */
    public function createItem(Request $request)
    {
        $item = new OperationMission();

        $item->operation_id = $request->operation_id;
        $item->mission_id = $request->mission_id;
        $item->play_order = $request->play_order;

        $item->save();

        return $item->id;
    }
}
