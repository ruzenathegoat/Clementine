@extends('admin.layout')

@section('title', 'Concierge Session | Clementine Admin')

@section('content')
<div class="space-y-4 h-[calc(100vh-120px)] flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center border-b border-primary pb-4 shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.concierge.index') }}" class="text-[#787774] hover:text-on-background transition-colors">
                &larr; Back
            </a>
            <div>
                <h1 class="text-2xl font-display uppercase tracking-tight text-on-background flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    {{ $ticket->subject }}
                </h1>
                <p class="text-[#787774] font-body-sm uppercase tracking-widest mt-1">
                    User: {{ $ticket->user->name }} ({{ $ticket->user->email }})
                </p>
            </div>
        </div>
        
        <form action="{{ route('admin.concierge.resolve', $ticket) }}" method="POST" onsubmit="return confirm('Close this session?')">
            @csrf
            <button type="submit" class="border border-red-500 text-red-500 hover:bg-red-500 hover:text-background font-display uppercase tracking-widest text-xs px-4 py-2 transition-colors">
                Resolve & Close
            </button>
        </form>
    </div>

    <!-- Live Chat Interface -->
    <div class="bg-surface border border-primary flex flex-col flex-1 min-h-0" 
         x-data="adminConciergeChat({{ $ticket->id }}, {{ auth()->id() }})"
         x-init="initChat">
        
        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-background" id="chat-messages" x-ref="messagesBox">
            @foreach($ticket->messages as $msg)
                <div class="flex flex-col {{ $msg->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
                    <span class="text-[10px] text-[#787774] uppercase tracking-widest mb-1">{{ $msg->user->name }}</span>
                    <div class="max-w-[70%] p-4 {{ $msg->user_id === auth()->id() ? 'bg-primary text-on-background border border-primary' : 'bg-surface border border-primary text-on-background' }}">
                        <p class="font-body-md">{{ $msg->message }}</p>
                    </div>
                </div>
            @endforeach
            
            <template x-for="msg in newMessages" :key="msg.id">
                <div class="flex flex-col" :class="msg.user_id === userId ? 'items-end' : 'items-start'">
                    <span class="text-[10px] text-[#787774] uppercase tracking-widest mb-1" x-text="msg.user.name"></span>
                    <div class="max-w-[70%] p-4 border border-primary" :class="msg.user_id === userId ? 'bg-primary text-on-background' : 'bg-surface text-on-background'">
                        <p class="font-body-md" x-text="msg.message"></p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Chat Input -->
        <div class="border-t border-primary bg-surface p-4 shrink-0">
            <form @submit.prevent="sendMessage" class="flex items-end gap-4">
                <div class="flex-1 relative">
                    <textarea x-model="newMessage" @keydown.enter.prevent="sendMessage" rows="2" class="w-full bg-background border-primary focus:border-on-background focus:ring-0 text-on-background py-3 px-4 font-body-md resize-none" placeholder="Type response to customer..."></textarea>
                    <div class="absolute right-2 bottom-2 text-xs text-[#787774] font-body-sm">Press Enter to send</div>
                </div>
                <button type="submit" class="shrink-0 bg-on-background text-background hover:bg-primary hover:text-on-background border border-on-background font-display uppercase tracking-widest text-sm py-4 px-8 transition-colors duration-300 h-[74px]" :disabled="isSending">
                    <span x-show="!isSending">Send</span>
                    <span x-show="isSending">...</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminConciergeChat', (ticketId, userId) => ({
            ticketId: ticketId,
            userId: userId,
            newMessage: '',
            newMessages: [],
            isSending: false,
            
            initChat() {
                this.scrollToBottom();
                
                if (typeof window.Echo !== 'undefined') {
                    window.Echo.private(`ticket.${this.ticketId}`)
                        .listen('.message.sent', (e) => {
                            this.newMessages.push(e.message);
                            this.$nextTick(() => {
                                this.scrollToBottom();
                            });
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
                
                fetch(`/admin/concierge/${this.ticketId}/messages`, {
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
@endsection
