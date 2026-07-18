@extends('admin.layout')

@section('title', 'Concierge Triage | Clementine Admin')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header Area -->
    <div class="scroll-reveal flex flex-col sm:flex-row sm:items-end justify-between gap-6">
        <div class="space-y-2">
            <div class="inline-flex items-center admin-badge bg-[#E1F3FE] text-[#1F6C9F]">Live Triage</div>
            <h1 class="font-serif text-5xl md:text-6xl tracking-tight leading-none text-[#111111]">Concierge Command</h1>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-[#FDEBEC] text-[#C62828] p-4 font-mono text-sm tracking-wide mb-4">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="bg-[#EDF3EC] text-[#346538] p-4 font-mono text-sm tracking-wide mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Active Tickets -->
        <div class="space-y-6">
            <h2 class="font-serif text-3xl text-[#111111] flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-[#346538] animate-pulse"></span>
                My Active Sessions
            </h2>
            
            @if($activeTickets->isEmpty())
                <div class="admin-outer-shell">
                    <div class="admin-inner-core p-8 text-center text-[#787774] font-mono uppercase tracking-widest text-sm">
                        No active sessions.
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($activeTickets as $ticket)
                        <div class="admin-outer-shell group">
                            <div class="admin-inner-core p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <h3 class="font-serif text-2xl text-[#111111]">{{ $ticket->subject }}</h3>
                                    <p class="text-xs font-mono text-[#787774] uppercase tracking-widest mt-2">
                                        User: {{ $ticket->user->name }} 
                                        @if($ticket->user->is_vip)
                                            <span class="admin-badge bg-[#FBF3DB] text-[#B8860B] ml-2">VIP</span>
                                        @endif
                                        <br>Started: {{ $ticket->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <a href="{{ route('admin.concierge.show', $ticket) }}" class="admin-button-island group hover:bg-[#333333] transition-haptic active:scale-95 shrink-0">
                                    <span>Open Chat</span>
                                    <div class="admin-button-island-icon group-hover:translate-x-1 transition-haptic">
                                        <i class="ph-light ph-arrow-right text-white"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Pending Queue -->
        <div class="space-y-6">
            <h2 class="font-serif text-3xl text-[#787774] flex items-center gap-3">
                Pending Queue ({{ $pendingTickets->count() }})
            </h2>
            
            @if($pendingTickets->isEmpty())
                <div class="admin-outer-shell">
                    <div class="admin-inner-core p-8 text-center text-[#787774] font-mono uppercase tracking-widest text-sm">
                        Queue is empty.
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($pendingTickets as $ticket)
                        <div class="admin-outer-shell group">
                            <div class="admin-inner-core p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <h3 class="font-serif text-2xl text-[#111111]">{{ $ticket->subject }}</h3>
                                    <p class="text-xs font-mono text-[#787774] uppercase tracking-widest mt-2">
                                        User: {{ $ticket->user->name }}
                                        @if($ticket->user->is_vip)
                                            <span class="admin-badge bg-[#FBF3DB] text-[#B8860B] ml-2">VIP</span>
                                        @endif
                                        <br>Waited: {{ $ticket->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <form action="{{ route('admin.concierge.accept', $ticket) }}" method="POST" class="shrink-0">
                                    @csrf
                                    <button type="submit" class="admin-button-island group hover:bg-[#333333] transition-haptic active:scale-95">
                                        <span>Accept</span>
                                        <div class="admin-button-island-icon group-hover:translate-x-1 transition-haptic">
                                            <i class="ph-light ph-check text-white"></i>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
