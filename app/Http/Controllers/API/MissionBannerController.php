<?php

namespace App\Http\Controllers\API;

use App\Models\Mission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MissionBannerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Mission $mission)
    {
        $this->authorize('update', $mission);

        $mission
            ->addAllMediaFromRequest()
            ->each(function ($file) {
                $file->toMediaCollection('banner');
            });
    }
}
