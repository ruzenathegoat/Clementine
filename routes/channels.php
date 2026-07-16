<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('ticket.{ticket_id}', function ($user, $ticket_id) {
    $ticket = \App\Models\Ticket::find($ticket_id);
    if (!$ticket) return false;
    
    return $user->isAdmin() || $user->id === $ticket->user_id;
});
