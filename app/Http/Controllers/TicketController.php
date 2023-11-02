<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\TicketUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $searched = $request->search;

        if(isset($request->search))
        {
            $tickets = $user->isAdmin ? Ticket::where('status', $searched)->latest()->paginate(5) : $user->tickets()->where('status', $searched)->paginate(5);
        }
        else
        {
            $tickets = $user->isAdmin ? Ticket::latest()->paginate(5) : $user->tickets()->paginate(5);
        }

        return view('ticket.index', compact('tickets','searched'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create([
            'title' => $request->title,
            'description'=> $request->description,
            'user_id' => auth()->id(),
            'status_changed_by_id' => auth()->id() 
        ]);

        if($request->file('attachment'))
        {
            $this->storeAttachment($request, $ticket);
        }

        return response()->redirectToRoute('ticket.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return view('ticket.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return view('ticket.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->except('attachment'));

        if($request->has('status'))
        {
            // $user = User::find($ticket->user_id);
            $ticket->update(['status_changed_by_id' => auth()->id()]);
            $ticket->user->notify(new TicketUpdatedNotification($ticket));
        }

        if($request->file('attachment'))
        {
            if(isset($ticket->attachment))
            {
                Storage::disk('public')->delete($ticket->attachment);
            }
            $this->storeAttachment($request, $ticket);
        }

        return redirect(route('ticket.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        if(isset($ticket->attachment))
        {
            Storage::disk('public')->delete($ticket->attachment);
        }
        
        $ticket->delete();
        return redirect(route('ticket.index'));
    }

    protected function storeAttachment($request, $ticket)
    {
        $ext = $request->file('attachment')->extension();
        $contents = file_get_contents($request->file('attachment'));
        $filename = Str::random(25);
        $path = "attachments/$filename.$ext";
        Storage::disk('public')->put($path, $contents);
        $ticket->update(['attachment' => $path]);
    }
    
}
