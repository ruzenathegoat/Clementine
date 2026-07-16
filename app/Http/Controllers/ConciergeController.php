<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ConciergeController extends Controller
{
    public function index()
    {
        $ticket = Ticket::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'active'])
            ->with(['messages.user', 'admin'])
            ->first();

        return view('concierge.index', compact('ticket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Check for existing active ticket
        $existingTicket = Ticket::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'active'])
            ->first();

        if ($existingTicket) {
            return redirect()->route('concierge.index')->with('error', 'You already have an active request.');
        }

        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'status' => 'pending',
        ]);

        $message = $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return redirect()->route('concierge.index')->with('success', 'Your request has been submitted. A concierge will be with you shortly.');
    }

    public function sendMessage(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
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
}
