<?php

namespace App\Http\Controllers\API;

use App\Models\Mission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\MediaLibrary\Models\Media;

class MissionMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Mission $mission)
    {
        return $mission->getMedia('images')->map(function ($media) {
            return [
                'id' => $media->id,
                'full_url' => url($media->getUrl()),
                'thumb_url' => url($media->getUrl('thumb')),
            ];
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Mission $mission)
    {
        $mission
            ->addAllMediaFromRequest()
            ->each(function ($file) {
                $file->toMediaCollection('images');
            });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mission $mission, Media $media)
    {
        $this->authorize('forceDelete', $mission);

        $media->delete();
    }
}
