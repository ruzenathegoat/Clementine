@extends('layouts.app')

@section('title', 'Concierge | Clementine')

@section('content')
<div class="w-full bg-background min-h-screen flex flex-col pt-[80px]">
    
    <!-- Header Section -->
    <header class="w-full px-lg md:px-2xl py-2xl md:py-4xl border-b border-primary bg-background flex flex-col md:flex-row md:items-end justify-between gap-xl relative overflow-hidden" id="concierge-header">
        <div class="relative z-10 w-full md:w-3/4">
            <h1 class="concierge-headline font-h1 text-[clamp(4rem,8vw,6rem)] text-primary m-0 p-0 leading-[0.85] tracking-tight uppercase" style="font-weight: 400; text-wrap: balance;">
                <span class="concierge-word inline-block" style="opacity: 0; letter-spacing: 0.15em;">PRIVATE</span>
                <span class="concierge-word inline-block" style="opacity: 0; letter-spacing: 0.15em;">CONCIERGE</span>
            </h1>
            <div class="concierge-subhead mt-lg overflow-hidden">
                <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-primary/60 m-0" style="opacity: 0; transform: translateY(100%);">
                    Direct access to our dedicated horology experts. Speak with a human, instantly.
                </p>
            </div>
        </div>
    </header>

    <div class="w-full flex-1 flex flex-col items-center py-2xl px-lg md:px-2xl bg-background">
        @if(!$ticket)
            <!-- Ticket Creation Form -->
            <div class="w-full max-w-2xl mt-xl border border-primary bg-background p-lg sm:p-2xl relative concierge-fade-up" style="opacity: 0; transform: translateY(20px);">
                <div class="absolute top-0 right-0 bg-primary text-background font-mono text-[10px] tracking-[0.2em] px-3 py-1 uppercase border-b border-l border-primary">SYS.REQ.01</div>
                
                <h2 class="font-mono text-[12px] tracking-[0.2em] uppercase text-primary mb-xl">INITIALIZE SESSION</h2>
                
                <form action="{{ route('concierge.store') }}" method="POST" class="flex flex-col gap-xl">
                    @csrf
                    <div class="flex flex-col gap-xs group">
                        <label for="subject" class="font-mono text-[10px] tracking-[0.2em] uppercase text-primary/60 group-focus-within:text-primary transition-colors">SUBJECT</label>
                        <input type="text" name="subject" id="subject" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-primary py-sm px-0 font-body-md text-sm outline-none transition-colors" placeholder="e.g. Inquiry about product sizing" required>
                    </div>
                    
                    <div class="flex flex-col gap-xs group">
                        <label for="message" class="font-mono text-[10px] tracking-[0.2em] uppercase text-primary/60 group-focus-within:text-primary transition-colors">INITIAL MESSAGE</label>
                        <textarea name="message" id="message" rows="4" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-primary py-sm px-0 font-body-md text-sm resize-none outline-none transition-colors" placeholder="How can we assist you with this allocation?" required></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-background hover:bg-background hover:text-primary border border-primary font-mono tracking-[0.2em] uppercase text-[10px] py-md px-lg transition-colors mt-md active:scale-[0.99] duration-150">
                        REQUEST CONCIERGE
                    </button>
                </form>
            </div>
        @else
            <!-- Live Chat Interface -->
            <div class="w-full max-w-6xl mt-xl border border-primary bg-background flex flex-col md:flex-row h-[70vh] min-h-[600px] relative concierge-fade-up" style="opacity: 0; transform: translateY(20px);"
                 x-data="conciergeChat({{ $ticket->id }}, {{ auth()->id() }})"
                 x-init="initChat">
                
                <!-- Sidebar Info -->
                <div class="w-full md:w-[320px] shrink-0 border-b md:border-b-0 md:border-r border-primary bg-background flex flex-col">
                    <div class="p-lg border-b border-primary">
                        <h2 class="font-mono text-[10px] tracking-[0.2em] uppercase text-primary">SESSION PROTOCOL</h2>
                    </div>
                    
                    <div class="p-lg flex flex-col gap-lg flex-1">
                        <div class="flex flex-col gap-1">
                            <span class="font-mono text-[9px] tracking-[0.2em] uppercase text-primary/50">SESSION ID</span>
                            <span class="font-mono text-[12px] text-primary">{{ $ticket->id }}</span>
                        </div>
                        
                        <div class="flex flex-col gap-1">
                            <span class="font-mono text-[9px] tracking-[0.2em] uppercase text-primary/50">SUBJECT</span>
                            <span class="font-h1 text-[1.5rem] leading-none text-primary uppercase">{{ $ticket->subject }}</span>
                        </div>
                        
                        <div class="flex flex-col gap-1">
                            <span class="font-mono text-[9px] tracking-[0.2em] uppercase text-primary/50">STATUS</span>
                            <span class="font-mono text-[10px] tracking-[0.2em] uppercase bg-primary text-background px-2 py-1 self-start">
                                {{ $ticket->status }}
                            </span>
                        </div>
                        
                        <div class="flex flex-col gap-1 mt-auto pt-lg border-t border-primary/20">
                            <span x-show="isAdminTyping" class="font-mono text-[10px] tracking-[0.2em] text-primary uppercase animate-pulse" style="display: none;">
                                [ ADMIN IS TYPING ]
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Chat Transcript Area -->
                <div class="flex-1 flex flex-col relative bg-background overflow-hidden">
                    
                    <div class="border-b border-primary p-md flex justify-between items-center bg-background z-10">
                        <span class="font-mono text-[10px] tracking-[0.2em] uppercase text-primary">LIVE TRANSCRIPT</span>
                        <div class="w-2 h-2 bg-primary animate-pulse" title="Live Connection Active"></div>
                    </div>
                    
                    <!-- System Message Overlay -->
                    <div x-show="closingCountdown !== null" class="absolute inset-0 z-50 bg-background/95 backdrop-blur-sm flex flex-col items-center justify-center p-6 text-center border border-primary m-4" style="display: none;">
                        <h2 class="font-h1 text-[3rem] uppercase text-primary mb-2">SESSION RESOLVED</h2>
                        <p class="font-mono text-[10px] tracking-[0.2em] uppercase text-primary/60 mb-6" x-text="`RESOLVED BY: ${resolvedBy}`"></p>
                        <div class="bg-primary text-background font-mono text-[12px] tracking-[0.2em] px-6 py-4 uppercase border border-primary">
                            CLOSING IN <span x-text="closingCountdown" class="font-bold text-lg mx-2"></span> SEC
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div class="flex-1 overflow-y-auto p-lg md:p-2xl space-y-xl bg-background relative no-scrollbar" id="chat-messages" x-ref="messagesBox">
                        @foreach($ticket->messages as $msg)
                            <div class="flex flex-col gap-1 w-full {{ $msg->user_id === auth()->id() ? 'items-end text-right' : 'items-start text-left' }}">
                                <span class="font-mono text-[9px] tracking-[0.2em] uppercase text-primary/50">
                                    {{ $msg->user->name }} — {{ $msg->created_at->format('H:i') }}
                                </span>
                                <div class="max-w-[85%] sm:max-w-[70%]">
                                    <p class="font-body-md text-[14px] text-primary leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                                </div>
                            </div>
                        @endforeach
                        
                        <template x-for="msg in newMessages" :key="msg.id">
                            <div class="flex flex-col gap-1 w-full" :class="msg.user_id === userId ? 'items-end text-right' : 'items-start text-left'" x-init="$el.style.opacity = 0; gsap.to($el, {opacity: 1, duration: 0.5, ease: 'power2.out'})">
                                <span class="font-mono text-[9px] tracking-[0.2em] uppercase text-primary/50" x-text="`${msg.user.name} — ${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`"></span>
                                <div class="max-w-[85%] sm:max-w-[70%]">
                                    <p class="font-body-md text-[14px] text-primary leading-relaxed whitespace-pre-wrap" x-text="msg.message"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Chat Input -->
                    <div class="border-t border-primary bg-background p-0 z-20">
                        <form @submit.prevent="sendMessage" class="flex flex-col sm:flex-row h-full">
                            <div class="flex-1">
                                <textarea x-model="newMessage" @keydown.enter.prevent="sendMessage" rows="2" class="w-full h-full bg-transparent border-none focus:outline-none focus:ring-0 text-primary py-lg px-lg font-body-md text-[14px] resize-none" placeholder="Enter message..."></textarea>
                            </div>
                            <button type="submit" class="shrink-0 bg-primary text-background hover:bg-background hover:text-primary border-l border-primary font-mono tracking-[0.2em] font-bold uppercase text-[10px] py-lg px-xl transition-colors active:bg-primary/80" :disabled="isSending">
                                <span x-show="!isSending">TRANSMIT PROTOCOL</span>
                                <span x-show="isSending" class="animate-pulse" style="display: none;">WAIT...</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Header sequence
    const tl = gsap.timeline();
    
    tl.fromTo('.concierge-word', 
        { opacity: 0, y: 40, scale: 0.95 },
        { opacity: 1, y: 0, scale: 1, duration: 0.8, stagger: 0.05, ease: "power3.out" }
    )
    .to('.concierge-word', 
        { letterSpacing: "0em", duration: 1.2, ease: "power2.inOut" }, 
        "-=0.6"
    )
    .fromTo('.concierge-subhead p',
        { opacity: 0, y: '100%' },
        { opacity: 1, y: '0%', duration: 0.6, ease: "power2.out" },
        "-=0.8"
    )
    .fromTo('.concierge-fade-up',
        { opacity: 0, y: 20 },
        { opacity: 1, y: 0, duration: 0.6, ease: "power2.out" },
        "-=0.4"
    );
});
</script>

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
