@extends('layouts.app')

@section('title', 'Concierge | Clementine')

@section('content')
<div class="min-h-screen bg-[var(--color-background)] text-[var(--color-text-primary)] py-12 sm:py-24 px-4 sm:px-6 lg:px-8 mt-16 md:mt-0 font-[family:var(--font-body-md)] selection:bg-[var(--color-primary)] selection:text-[var(--color-secondary)]">
    <div class="max-w-4xl mx-auto w-full">
        <!-- Macro Typography Header -->
        <div class="mb-12 sm:mb-16 border-b border-[var(--color-border)] pb-6">
            <h1 class="text-6xl md:text-8xl font-[family:var(--font-h1)] uppercase text-[var(--color-primary)] m-0 p-0 leading-none">
                Live <span class="font-serif italic text-copper lowercase tracking-normal">concierge</span>
            </h1>
            <p class="font-[family:var(--font-body-md)] text-sm uppercase text-[var(--color-text-secondary)] mt-6 max-w-lg leading-relaxed">
                Direct access to our dedicated team. Speak with a human, instantly.
            </p>
        </div>

        @if(!$ticket)
            <!-- Ticket Creation Form -->
            <div class="border border-[var(--color-border)] bg-[var(--color-surface)] p-6 sm:p-10 relative">
                <div class="absolute top-0 right-0 bg-[var(--color-primary)] text-[var(--color-secondary)] font-[family:var(--font-body-md)] text-[10px] px-3 py-1 uppercase border-b border-l border-[var(--color-border)]">SYS.REQ.01</div>
                <h2 class="text-2xl sm:text-3xl font-[family:var(--font-h2)] uppercase text-[var(--color-primary)] mb-8">Start a Session</h2>
                
                <form action="{{ route('concierge.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="flex flex-col gap-2">
                        <label for="subject" class="font-[family:var(--font-body-md)] text-xs font-bold uppercase text-[var(--color-text-primary)]">Subject</label>
                        <input type="text" name="subject" id="subject" class="w-full bg-[var(--color-background)] border border-[var(--color-border)] focus:outline-none focus:ring-0 focus:border-[var(--color-primary)] text-[var(--color-text-primary)] py-3 px-4 font-[family:var(--font-body-md)] text-sm" placeholder="e.g. Inquiry about product sizing" required>
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <label for="message" class="font-[family:var(--font-body-md)] text-xs font-bold uppercase text-[var(--color-text-primary)]">Initial Message</label>
                        <textarea name="message" id="message" rows="4" class="w-full bg-[var(--color-background)] border border-[var(--color-border)] focus:outline-none focus:ring-0 focus:border-[var(--color-primary)] text-[var(--color-text-primary)] py-3 px-4 font-[family:var(--font-body-md)] text-sm resize-none" placeholder="How can we help you today?" required></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-[var(--color-primary)] text-[var(--color-secondary)] hover:bg-[var(--color-surface)] hover:text-[var(--color-primary)] border border-[var(--color-border)] font-[family:var(--font-body-md)] font-bold uppercase text-sm py-4 px-8 transition-none mt-4">
                        Request Concierge
                    </button>
                </form>
            </div>
        @else
            <!-- Live Chat Interface -->
            <div class="border border-[var(--color-border)] bg-[var(--color-surface)] flex flex-col h-[650px] relative" 
                 x-data="conciergeChat({{ $ticket->id }}, {{ auth()->id() }})"
                 x-init="initChat">
                
                <!-- Chat Header -->
                <div class="border-b border-[var(--color-border)] p-4 sm:p-6 flex items-start sm:items-center justify-between bg-[var(--color-background)]">
                    <div>
                        <h3 class="font-[family:var(--font-h2)] uppercase text-2xl sm:text-3xl text-[var(--color-primary)]">{{ $ticket->subject }}</h3>
                        <div class="flex flex-wrap items-center gap-3 mt-2">
                            <p class="font-[family:var(--font-body-md)] text-[10px] sm:text-xs text-[var(--color-text-secondary)] uppercase">
                                Status: <span class="bg-[var(--color-primary)] text-[var(--color-secondary)] px-2 py-0.5 ml-1">{{ $ticket->status }}</span>
                            </p>
                            <p x-show="isAdminTyping" class="font-[family:var(--font-body-md)] text-[10px] text-[var(--color-primary)] uppercase animate-pulse" style="display: none;">
                                [Admin is typing...]
                            </p>
                        </div>
                    </div>
                    <div class="w-4 h-4 bg-[var(--color-primary)] border border-[var(--color-border)] animate-pulse" title="Live Connection Active"></div>
                </div>

                <!-- System Message Overlay -->
                <div x-show="closingCountdown !== null" class="absolute inset-0 z-50 bg-[var(--color-background)]/95 backdrop-blur-sm flex flex-col items-center justify-center p-6 text-center border border-[var(--color-primary)] m-4" style="display: none;">
                    <h2 class="font-[family:var(--font-h1)] text-5xl uppercase text-[var(--color-primary)] mb-2">Session Resolved</h2>
                    <p class="font-[family:var(--font-body-md)] text-sm uppercase text-[var(--color-text-secondary)] mb-6" x-text="`Resolved by: ${resolvedBy}`"></p>
                    <div class="bg-[var(--color-primary)] text-[var(--color-secondary)] font-[family:var(--font-body-md)] text-sm px-6 py-4 uppercase border border-[var(--color-border)]">
                        Closing in <span x-text="closingCountdown" class="font-bold text-xl mx-2"></span> sec
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6 bg-[var(--color-background)] relative" id="chat-messages" x-ref="messagesBox">
                    
                    @foreach($ticket->messages as $msg)
                        <div class="flex flex-col relative z-10 {{ $msg->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
                            <span class="font-[family:var(--font-body-md)] text-[10px] text-[var(--color-text-secondary)] uppercase mb-1">{{ $msg->user->name }}</span>
                            <div class="max-w-[85%] sm:max-w-[75%] p-4 {{ $msg->user_id === auth()->id() ? 'bg-[var(--color-primary)] text-[var(--color-secondary)] border border-[var(--color-border)]' : 'bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-text-primary)]' }}">
                                <p class="font-[family:var(--font-body-md)] text-sm leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                            </div>
                        </div>
                    @endforeach
                    
                    <template x-for="msg in newMessages" :key="msg.id">
                        <div class="flex flex-col relative z-10" :class="msg.user_id === userId ? 'items-end' : 'items-start'">
                            <span class="font-[family:var(--font-body-md)] text-[10px] text-[var(--color-text-secondary)] uppercase mb-1" x-text="msg.user.name"></span>
                            <div class="max-w-[85%] sm:max-w-[75%] p-4" :class="msg.user_id === userId ? 'bg-[var(--color-primary)] text-[var(--color-secondary)] border border-[var(--color-border)]' : 'bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-text-primary)]'">
                                <p class="font-[family:var(--font-body-md)] text-sm leading-relaxed whitespace-pre-wrap" x-text="msg.message"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Chat Input -->
                <div class="border-t border-[var(--color-border)] bg-[var(--color-surface)] p-4 sm:p-6 z-20">
                    <form @submit.prevent="sendMessage" class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <textarea x-model="newMessage" @keydown.enter.prevent="sendMessage" rows="2" class="w-full bg-[var(--color-background)] border border-[var(--color-border)] focus:outline-none focus:ring-0 focus:border-[var(--color-primary)] text-[var(--color-text-primary)] py-3 px-4 font-[family:var(--font-body-md)] text-sm resize-none" placeholder="> Enter message..."></textarea>
                        </div>
                        <button type="submit" class="shrink-0 bg-[var(--color-primary)] text-[var(--color-secondary)] hover:bg-[var(--color-background)] hover:text-[var(--color-primary)] border border-[var(--color-border)] font-[family:var(--font-body-md)] font-bold uppercase text-xs py-3 px-8 transition-none h-auto" :disabled="isSending">
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
