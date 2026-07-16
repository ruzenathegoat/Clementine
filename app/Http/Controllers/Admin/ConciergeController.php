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
        $pendingTickets = Ticket::where('tickets.status', 'pending')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->select('tickets.*')
            ->orderByDesc('users.is_vip')
            ->orderBy('tickets.created_at', 'asc')
            ->with('user')
            ->get();
            
        $activeTickets = Ticket::where('tickets.status', 'active')
            ->where('tickets.admin_id', auth()->id())
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->select('tickets.*')
            ->orderByDesc('users.is_vip')
            ->orderBy('tickets.updated_at', 'desc')
            ->with('user')
            ->get();
        
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
        $ticket->load('admin');

        broadcast(new \App\Events\TicketClosed($ticket));

        return redirect()->route('admin.concierge.index')->with('success', 'Ticket resolved.');
    }
}
