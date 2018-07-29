<?php

namespace App\Http\Controllers\API;

use App\Models\Mission;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MissionCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Mission $mission)
    {
        if ($request->has('collection')) {
            return $mission->comments()
                ->whereCollection($request->collection)
                ->orderBy('published_at')
                ->with('user')
                ->get();
        }

        return $mission->afterActionReports->load('user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Mission $mission)
    {
        $this->authorize('create', Comment::class);

        $attributes = $request->validate([
            'text' => 'required|min:1',
            'collection' => 'nullable'
        ]);

        $mission->comments()->save(
            new Comment($attributes)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mission $mission, Comment $comment)
    {
        $this->authorize('update', $comment);

        $attributes = $request->validate([
            'text' => 'required|min:1'
        ]);

        $comment->update($attributes);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mission $mission, Comment $comment)
    {
        $this->authorize('destroy', $comment);
    }
}
