<?php

namespace App\Http\Controllers\API;

use App\Models\Mission;
use App\Http\Controllers\Controller;
use App\Http\Requests\MissionDownloadRequest;

class MissionDownloadController extends Controller
{
    /**
     * Download the given mission with the given format.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MissionDownloadRequest $request, Mission $mission)
    {
        return $request->handle($mission);
    }
}
