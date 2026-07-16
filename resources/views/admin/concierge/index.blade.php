@extends('admin.layout')

@section('title', 'Concierge Triage | Clementine Admin')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-end border-b border-primary pb-4">
        <div>
            <h1 class="text-3xl font-display uppercase tracking-tight text-on-background">Concierge Command</h1>
            <p class="text-[#787774] font-body-sm uppercase tracking-widest mt-1">Live Ticket Triage</p>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-900/50 text-red-200 p-4 border border-red-500 uppercase tracking-widest text-xs font-bold mb-4">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="bg-green-900/50 text-green-200 p-4 border border-green-500 uppercase tracking-widest text-xs font-bold mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Active Tickets -->
        <div>
            <h2 class="text-xl font-display uppercase tracking-tight mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                My Active Sessions
            </h2>
            
            @if($activeTickets->isEmpty())
                <div class="bg-surface border border-primary p-8 text-center text-[#787774] font-body-md uppercase tracking-widest text-sm">
                    No active sessions.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($activeTickets as $ticket)
                        <div class="bg-surface border border-primary p-6 hover:bg-background transition-colors flex justify-between items-center group">
                            <div>
                                <h3 class="font-display uppercase tracking-tight text-lg">{{ $ticket->subject }}</h3>
                                <p class="text-xs text-[#787774] uppercase tracking-widest mt-1">
                                    User: {{ $ticket->user->name }} 
                                    @if($ticket->user->is_vip)
                                        <span class="bg-primary text-background px-1 ml-1 text-[10px] font-bold">VIP</span>
                                    @endif
                                    | Started: {{ $ticket->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('admin.concierge.show', $ticket) }}" class="bg-primary text-on-background px-4 py-2 font-display uppercase tracking-widest text-xs border border-transparent group-hover:border-on-background transition-colors">
                                Open Chat
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Pending Queue -->
        <div>
            <h2 class="text-xl font-display uppercase tracking-tight mb-4 text-[#787774]">
                Pending Queue ({{ $pendingTickets->count() }})
            </h2>
            
            @if($pendingTickets->isEmpty())
                <div class="bg-surface border border-primary p-8 text-center text-[#787774] font-body-md uppercase tracking-widest text-sm">
                    Queue is empty.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($pendingTickets as $ticket)
                        <div class="bg-surface border border-primary p-6 hover:bg-background transition-colors flex justify-between items-center group">
                            <div>
                                <h3 class="font-display uppercase tracking-tight text-lg">{{ $ticket->subject }}</h3>
                                <p class="text-xs text-[#787774] uppercase tracking-widest mt-1">
                                    User: {{ $ticket->user->name }}
                                    @if($ticket->user->is_vip)
                                        <span class="bg-primary text-background px-1 ml-1 text-[10px] font-bold">VIP</span>
                                    @endif
                                    | Waited: {{ $ticket->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <form action="{{ route('admin.concierge.accept', $ticket) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-on-background text-background px-4 py-2 font-display uppercase tracking-widest text-xs border border-transparent hover:bg-background hover:text-on-background hover:border-on-background transition-colors">
                                    Accept
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
