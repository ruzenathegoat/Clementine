<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Events\TicketClosed;

#[Signature('app:close-idle-tickets')]
#[Description('Close active concierge tickets that have been idle for more than 10 minutes')]
class CloseIdleTickets extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $idleTickets = Ticket::where('status', 'active')
            ->where('updated_at', '<=', now()->subMinutes(10))
            ->get();

        if ($idleTickets->isEmpty()) {
            $this->info('No idle tickets found.');
            return;
        }

        foreach ($idleTickets as $ticket) {
            $ticket->update(['status' => 'resolved']);
            $ticket->load('admin');
            
            // Broadcast event so frontend auto-closes
            broadcast(new TicketClosed($ticket));
            
            $this->info("Ticket ID {$ticket->id} has been automatically closed due to inactivity.");
        }

        $this->info('Idle tickets processing completed.');
    }
}
