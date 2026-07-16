<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ConciergeController extends Controller
{
    public function index()
    {
        $pendingTickets = Ticket::where('status', 'pending')->with('user')->orderBy('created_at', 'asc')->get();
        $activeTickets = Ticket::where('status', 'active')->where('admin_id', auth()->id())->with('user')->orderBy('updated_at', 'desc')->get();
        
        return view('admin.concierge.index', compact('pendingTickets', 'activeTickets'));
    }

    public function accept(Ticket $ticket)
    {
        if ($ticket->status !== 'pending') {
            return back()->with('error', 'Ticket is no longer pending.');
        }

        $ticket->update([
            'status' => 'active',
            'admin_id' => auth()->id(),
        ]);

        return redirect()->route('admin.concierge.show', $ticket);
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->status !== 'active' || $ticket->admin_id !== auth()->id()) {
            return redirect()->route('admin.concierge.index')->with('error', 'You cannot view this ticket.');
        }

        $ticket->load('messages.user');

        return view('admin.concierge.show', compact('ticket'));
    }

    public function sendMessage(Request $request, Ticket $ticket)
    {
        if ($ticket->admin_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['message' => 'required|string']);

        $message = $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['success' => true, 'message' => $message->load('user')]);
    }

    public function resolve(Ticket $ticket)
    {
        if ($ticket->admin_id !== auth()->id()) {
            abort(403);
        }

        $ticket->update(['status' => 'resolved']);

        broadcast(new \App\Events\TicketClosed($ticket));

        return redirect()->route('admin.concierge.index')->with('success', 'Ticket resolved.');
    }
}
