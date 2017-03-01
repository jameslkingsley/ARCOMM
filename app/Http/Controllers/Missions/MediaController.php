<?php

namespace App\Http\Controllers\Missions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Missions\Mission;
use App\Models\Portal\Video;

class MediaController extends Controller
{
    /**
     * Uploads the given media to the given mission.
     *
     * @return view
     */
    public function uploadPhoto(Request $request)
    {
        $mission = Mission::find($request->mission_id);

        $mission
            ->addMedia($request->file('file'))
            ->withCustomProperties(['user_id' => auth()->user()->id])
            ->toCollection('images');

        $media = $mission->getMedia('images')->last();

        return view('missions.media.photo', compact('media', 'mission'));
    }

    /**
     * Deletes the given photo from the given mission.
     *
     * @return void
     */
    public function deletePhoto(Request $request)
    {
        $mission = Mission::find($request->mission_id);
        $media = $mission->media->find($request->media_id);

        if (($media->getCustomProperty('user_id', -1) == auth()->user()->id || auth()->user()->isAdmin())) {
            $mission->deleteMedia($media);
        }
    }

    /**
     * Adds the given video to the given mission.
     *
     * @return view
     */
    public function addVideo(Request $request)
    {
        $video_key = Video::parseUrl($request->video_url);

        $video = new Video();

        $video->user_id = auth()->user()->id;
        $video->mission_id = $request->mission_id;
        $video->video_key = $video_key;

        $video->save();

        return view('missions.media.video', compact('video'));
    }

    /**
     * Removes the given video from the given mission.
     *
     * @return void
     */
    public function removeVideo(Request $request)
    {
        Video::destroy($request->video_id);
    }
}
