<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Models\Portal\Gallery;
use App\Models\Portal\User;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galleries = Gallery::all();
        return view('media.index', compact('galleries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Record 1 is the default gallery
        if (auth()->user()->isAdmin()) {
            $gallery = Gallery::find(1);
            $gallery->addMedia($request->file('file'))->toCollection('images');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($gallery_id, $media_id)
    {
        Gallery::find($gallery_id)->deleteMedia($media_id);
    }

    /**
     * Deletes the given gallery media item by form input.
     *
     * @return void
     */
    public function deletePhoto(Request $request)
    {
        self::destroy($request->gallery_id, $request->media_id);
    }
}
