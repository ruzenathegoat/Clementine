@extends('layouts.app')

@section('title', 'Concierge | Clementine')

@section('content')
<div class="min-h-screen bg-[#F4F4F0] text-[#111111] py-12 sm:py-24 px-4 sm:px-6 lg:px-8 mt-16 md:mt-0 font-sans selection:bg-[#E61919] selection:text-white">
    <div class="max-w-3xl mx-auto w-full">
        <!-- Macro Typography Header -->
        <div class="mb-12 sm:mb-16 border-b-[2px] border-[#111111] pb-6">
            <h1 class="text-[clamp(3rem,10vw,6rem)] font-black uppercase tracking-tighter leading-[0.85] text-[#111111] m-0 p-0 break-words">
                Live<br>Concierge
            </h1>
            <p class="font-mono text-xs sm:text-sm uppercase tracking-widest text-[#111111] mt-6 max-w-[40ch] leading-relaxed">
                Direct access to our dedicated team. Speak with a human, instantly.
            </p>
        </div>

        @if(!$ticket)
            <!-- Ticket Creation Form -->
            <div class="border-[2px] border-[#111111] bg-[#EAE8E3] p-6 sm:p-10 relative shadow-[4px_4px_0px_0px_rgba(17,17,17,1)]">
                <div class="absolute top-0 right-0 bg-[#111111] text-[#F4F4F0] font-mono text-[10px] px-3 py-1 uppercase tracking-widest border-b-[2px] border-l-[2px] border-[#111111]">SYS.REQ.01</div>
                <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tight mb-8 text-[#111111]">Start a Session</h2>
                
                <form action="{{ route('concierge.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="flex flex-col gap-2">
                        <label for="subject" class="font-mono text-[10px] font-bold uppercase tracking-widest text-[#111111]">Subject [String]</label>
                        <input type="text" name="subject" id="subject" class="w-full bg-[#F4F4F0] border-[1px] border-[#111111] focus:outline-none focus:ring-0 focus:border-[#E61919] text-[#111111] py-3 px-4 font-mono text-sm rounded-none transition-none" placeholder="e.g. Inquiry about product sizing" required>
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <label for="message" class="font-mono text-[10px] font-bold uppercase tracking-widest text-[#111111]">Initial Message [Text]</label>
                        <textarea name="message" id="message" rows="4" class="w-full bg-[#F4F4F0] border-[1px] border-[#111111] focus:outline-none focus:ring-0 focus:border-[#E61919] text-[#111111] py-3 px-4 font-mono text-sm resize-none rounded-none transition-none" placeholder="How can we help you today?" required></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#E61919] text-[#F4F4F0] hover:bg-[#111111] border-[2px] border-[#111111] font-mono font-bold uppercase tracking-widest text-sm py-4 px-8 transition-none rounded-none mt-4">
                        Request Concierge
                    </button>
                </form>
            </div>
        @else
            <!-- Live Chat Interface -->
            <div class="border-[2px] border-[#111111] bg-[#EAE8E3] flex flex-col h-[650px] relative shadow-[4px_4px_0px_0px_rgba(17,17,17,1)]" 
                 x-data="conciergeChat({{ $ticket->id }}, {{ auth()->id() }})"
                 x-init="initChat">
                
                <!-- Chat Header -->
                <div class="border-b-[2px] border-[#111111] p-4 sm:p-6 flex items-start sm:items-center justify-between bg-[#F4F4F0]">
                    <div>
                        <h3 class="font-black uppercase tracking-tight text-lg sm:text-xl text-[#111111]">{{ $ticket->subject }}</h3>
                        <div class="flex flex-wrap items-center gap-3 mt-2">
                            <p class="font-mono text-[10px] sm:text-xs text-[#111111] uppercase tracking-widest">
                                Status: <span class="bg-[#111111] text-[#F4F4F0] px-2 py-0.5 ml-1">{{ $ticket->status }}</span>
                            </p>
                            <p x-show="isAdminTyping" class="font-mono text-[10px] text-[#E61919] uppercase tracking-widest animate-pulse" style="display: none;">
                                [Admin is typing...]
                            </p>
                        </div>
                    </div>
                    <div class="w-4 h-4 bg-[#E61919] border-[2px] border-[#111111] animate-pulse" title="Live Connection Active"></div>
                </div>

                <!-- System Message Overlay -->
                <div x-show="closingCountdown !== null" class="absolute inset-0 z-50 bg-[#F4F4F0]/95 backdrop-blur-sm flex flex-col items-center justify-center p-6 text-center border-[2px] border-[#E61919] m-4" style="display: none;">
                    <span class="font-mono text-4xl mb-4">⚠️</span>
                    <h2 class="font-black text-2xl uppercase tracking-tighter text-[#111111] mb-2">Session Resolved</h2>
                    <p class="font-mono text-sm uppercase tracking-widest text-[#111111] mb-6" x-text="`Resolved by: ${resolvedBy}`"></p>
                    <div class="bg-[#111111] text-[#F4F4F0] font-mono text-xs px-4 py-3 uppercase tracking-widest border-[2px] border-[#111111]">
                        Closing in <span x-text="closingCountdown" class="text-[#E61919] font-bold text-lg mx-2"></span> sec
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6 bg-[#F4F4F0] relative" id="chat-messages" x-ref="messagesBox">
                    <!-- Subtle structural lines -->
                    <div class="absolute inset-0 pointer-events-none opacity-[0.03]" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 1px, #111 1px, #111 2px); background-size: 100% 4px;"></div>
                    
                    @foreach($ticket->messages as $msg)
                        <div class="flex flex-col relative z-10 {{ $msg->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
                            <span class="font-mono text-[9px] text-[#111111] uppercase tracking-widest mb-1">{{ $msg->user->name }}</span>
                            <div class="max-w-[85%] sm:max-w-[75%] p-3 sm:p-4 rounded-none {{ $msg->user_id === auth()->id() ? 'bg-[#111111] text-[#F4F4F0] border-[1px] border-[#111111]' : 'bg-[#EAE8E3] border-[1px] border-[#111111] text-[#111111]' }}">
                                <p class="font-mono text-xs sm:text-sm leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                            </div>
                        </div>
                    @endforeach
                    
                    <template x-for="msg in newMessages" :key="msg.id">
                        <div class="flex flex-col relative z-10" :class="msg.user_id === userId ? 'items-end' : 'items-start'">
                            <span class="font-mono text-[9px] text-[#111111] uppercase tracking-widest mb-1" x-text="msg.user.name"></span>
                            <div class="max-w-[85%] sm:max-w-[75%] p-3 sm:p-4 rounded-none" :class="msg.user_id === userId ? 'bg-[#111111] text-[#F4F4F0] border-[1px] border-[#111111]' : 'bg-[#EAE8E3] border-[1px] border-[#111111] text-[#111111]'">
                                <p class="font-mono text-xs sm:text-sm leading-relaxed whitespace-pre-wrap" x-text="msg.message"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Chat Input -->
                <div class="border-t-[2px] border-[#111111] bg-[#EAE8E3] p-4 sm:p-6 z-20">
                    <form @submit.prevent="sendMessage" class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <textarea x-model="newMessage" @keydown.enter.prevent="sendMessage" rows="2" class="w-full bg-[#F4F4F0] border-[1px] border-[#111111] focus:outline-none focus:ring-0 focus:border-[#E61919] text-[#111111] py-3 px-4 font-mono text-sm resize-none rounded-none transition-none" placeholder="> Enter transmission..."></textarea>
                        </div>
                        <button type="submit" class="shrink-0 bg-[#E61919] text-[#F4F4F0] hover:bg-[#111111] border-[2px] border-[#111111] font-mono font-bold uppercase tracking-widest text-xs py-3 px-8 transition-none rounded-none h-auto" :disabled="isSending">
                            <span x-show="!isSending">Transmit</span>
                            <span x-show="isSending" class="animate-pulse" style="display: none;">Wait...</span>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

@if($ticket)
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('conciergeChat', (ticketId, userId) => ({
            ticketId: ticketId,
            userId: userId,
            newMessage: '',
            newMessages: [],
            isSending: false,
            isAdminTyping: false,
            closingCountdown: null,
            resolvedBy: '',
            
            initChat() {
                this.scrollToBottom();
                
                if (typeof window.Echo !== 'undefined') {
                    window.Echo.private(`ticket.${this.ticketId}`)
                        .listen('.message.sent', (e) => {
                            this.newMessages.push(e.message);
                            this.$nextTick(() => {
                                this.scrollToBottom();
                            });
                        })
                        .listen('.ticket.closed', (e) => {
                            this.resolvedBy = (e.ticket && e.ticket.admin && e.ticket.admin.name) ? e.ticket.admin.name : 'Admin';
                            this.closingCountdown = 5;
                            const interval = setInterval(() => {
                                this.closingCountdown--;
                                if (this.closingCountdown <= 0) {
                                    clearInterval(interval);
                                    window.location.reload();
                                }
                            }, 1000);
                        });
                } else {
                    console.error('Laravel Echo is not loaded.');
                }
            },
            
            scrollToBottom() {
                const box = this.$refs.messagesBox;
                if (box) {
                    box.scrollTop = box.scrollHeight;
                }
            },
            
            sendMessage() {
                if (this.newMessage.trim() === '') return;
                
                this.isSending = true;
                
                fetch(`/concierge/${this.ticketId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: this.newMessage })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.newMessages.push(data.message);
                        this.newMessage = '';
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    }
                })
                .catch(error => console.error('Error:', error))
                .finally(() => {
                    this.isSending = false;
                });
            }
        }));
    });
</script>
@endif
@endsection
