@extends('layouts.app')

@section('title', 'Concierge | Clementine')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8 mt-16 md:mt-0 relative">
    
    <div class="mb-12">
        <h1 class="text-4xl md:text-6xl font-display uppercase tracking-tighter mb-4 text-on-background">Live Concierge</h1>
        <p class="text-lg md:text-xl text-[#787774] font-body-md max-w-2xl leading-relaxed">Direct access to our dedicated team. Speak with a human, instantly.</p>
    </div>

    @if(!$ticket)
        <!-- Ticket Creation Form -->
        <div class="bg-surface border border-primary p-8 md:p-12">
            <h2 class="text-2xl font-display uppercase tracking-tight mb-8">Start a Session</h2>
            
            <form action="{{ route('concierge.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="subject" class="block text-sm font-medium uppercase tracking-widest text-[#787774] mb-2">Subject</label>
                    <input type="text" name="subject" id="subject" class="w-full bg-background border-primary focus:border-on-background focus:ring-0 text-on-background py-3 px-4 font-body-md" placeholder="e.g. Inquiry about product sizing" required>
                </div>
                
                <div>
                    <label for="message" class="block text-sm font-medium uppercase tracking-widest text-[#787774] mb-2">Initial Message</label>
                    <textarea name="message" id="message" rows="4" class="w-full bg-background border-primary focus:border-on-background focus:ring-0 text-on-background py-3 px-4 font-body-md resize-none" placeholder="How can we help you today?" required></textarea>
                </div>
                
                <button type="submit" class="w-full bg-on-background text-background hover:bg-primary hover:text-on-background border border-on-background font-display uppercase tracking-widest text-sm py-4 px-8 transition-colors duration-300">
                    Request Concierge
                </button>
            </form>
        </div>
    @else
        <!-- Live Chat Interface -->
        <div class="bg-surface border border-primary flex flex-col h-[600px]" 
             x-data="conciergeChat({{ $ticket->id }}, {{ auth()->id() }})"
             x-init="initChat">
            
            <!-- Chat Header -->
            <div class="border-b border-primary p-6 flex items-center justify-between bg-background">
                <div>
                    <h3 class="font-display uppercase tracking-tight text-lg">{{ $ticket->subject }}</h3>
                    <p class="text-xs text-[#787774] uppercase tracking-widest mt-1">Status: {{ $ticket->status }} <span x-show="isAdminTyping" class="ml-2 text-on-background animate-pulse">(Admin is typing...)</span></p>
                </div>
                <div class="w-3 h-3 rounded-full bg-on-background animate-pulse" title="Live Connection Active"></div>
            </div>

            <!-- Chat Messages -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-surface" id="chat-messages" x-ref="messagesBox">
                @foreach($ticket->messages as $msg)
                    <div class="flex flex-col {{ $msg->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
                        <span class="text-[10px] text-[#787774] uppercase tracking-widest mb-1">{{ $msg->user->name }}</span>
                        <div class="max-w-[80%] p-4 {{ $msg->user_id === auth()->id() ? 'bg-on-background text-background' : 'bg-background border border-primary text-on-background' }}">
                            <p class="font-body-md">{{ $msg->message }}</p>
                        </div>
                    </div>
                @endforeach
                
                <template x-for="msg in newMessages" :key="msg.id">
                    <div class="flex flex-col" :class="msg.user_id === userId ? 'items-end' : 'items-start'">
                        <span class="text-[10px] text-[#787774] uppercase tracking-widest mb-1" x-text="msg.user.name"></span>
                        <div class="max-w-[80%] p-4" :class="msg.user_id === userId ? 'bg-on-background text-background' : 'bg-background border border-primary text-on-background'">
                            <p class="font-body-md" x-text="msg.message"></p>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Chat Input -->
            <div class="border-t border-primary bg-background p-4">
                <form @submit.prevent="sendMessage" class="flex items-end gap-4">
                    <div class="flex-1">
                        <textarea x-model="newMessage" @keydown.enter.prevent="sendMessage" rows="1" class="w-full bg-surface border-primary focus:border-on-background focus:ring-0 text-on-background py-3 px-4 font-body-md resize-none" placeholder="Type your message..."></textarea>
                    </div>
                    <button type="submit" class="shrink-0 bg-on-background text-background hover:bg-primary hover:text-on-background border border-on-background font-display uppercase tracking-widest text-sm py-3 px-6 transition-colors duration-300" :disabled="isSending">
                        <span x-show="!isSending">Send</span>
                        <span x-show="isSending">...</span>
                    </button>
                </form>
            </div>
        </div>
    @endif
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
                            alert('This concierge session has been closed by the admin.');
                            window.location.reload();
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
