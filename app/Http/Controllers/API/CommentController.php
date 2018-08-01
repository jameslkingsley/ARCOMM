<?php

namespace App\Http\Controllers\API;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mission = Comment::getActualClassNameForMorph($request->type)
            ::findOrFail($request->id);

        return $mission->comments()
            ->whereCollection($request->collection ?: null)
            ->orderBy('created_at')
            ->with('user')
            ->get();

        return $mission->afterActionReports->load('user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Comment::class);

        $mission = Comment::getActualClassNameForMorph($request->commentable_type)
            ::findOrFail($request->commentable_id);

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
    public function update(Request $request, Comment $comment)
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
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();
    }
}
