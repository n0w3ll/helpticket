<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Ticket;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($ticket_id)
    {
        $comments = Comment::where('ticket_id', $ticket_id)->get();
        return view('ticket.comments', compact('comments','ticket_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->user()->associate($request->user());
        $ticket = Ticket::find($request->ticket_id);
        $ticket->comments()->save($comment);

        return back()->with('success','Successfully posted comment');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
