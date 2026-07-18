@extends('admin.layout')

@section('title', 'Concierge Session | Clementine Admin')

@section('content')
<div class="space-y-4 h-[calc(100vh-120px)] flex flex-col pb-6">
    <!-- Header Area -->
    <div class="scroll-reveal flex flex-col sm:flex-row sm:items-end justify-between gap-6 shrink-0 border-b border-[#EAEAEA] pb-6">
        <div class="space-y-2">
            <a href="{{ route('admin.concierge.index') }}" class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-widest text-[#787774] hover:text-[#111111] transition-colors mb-2">
                <i class="ph-light ph-arrow-left"></i> Back to Triage
            </a>
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-[#346538] animate-pulse"></span>
                <h1 class="font-serif text-4xl md:text-5xl tracking-tight leading-none text-[#111111]">{{ $ticket->subject }}</h1>
            </div>
            <p class="text-xs font-mono text-[#787774] uppercase tracking-widest mt-1">
                User: {{ $ticket->user->name }} ({{ $ticket->user->email }})
            </p>
        </div>
        
        <form action="{{ route('admin.concierge.resolve', $ticket) }}" method="POST" onsubmit="return confirm('Close this session?')" class="shrink-0">
            @csrf
            <button type="submit" class="admin-button-island !bg-[#C62828] hover:!bg-[#B71C1C] transition-haptic active:scale-95">
                <span>Resolve & Close</span>
                <div class="admin-button-island-icon group-hover:translate-x-1 transition-haptic">
                    <i class="ph-light ph-check-circle text-white"></i>
                </div>
            </button>
        </form>
    </div>

    <!-- Live Chat Interface -->
    <div class="admin-outer-shell flex flex-col flex-1 min-h-0" 
         x-data="adminConciergeChat({{ $ticket->id }}, {{ auth()->id() }})"
         x-init="initChat">
        
        <div class="admin-inner-core flex flex-col h-full bg-[#FBFBFA]">
            <!-- Chat Messages -->
            <div class="flex-1 overflow-y-auto p-8 space-y-6" id="chat-messages" x-ref="messagesBox">
                @foreach($ticket->messages as $msg)
                    <div class="flex flex-col {{ $msg->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
                        <span class="text-[10px] font-mono text-[#787774] uppercase tracking-widest mb-1">{{ $msg->user->name }}</span>
                        <div class="max-w-[70%] p-5 {{ $msg->user_id === auth()->id() ? 'bg-[#111111] text-white rounded-l-2xl rounded-tr-2xl' : 'bg-white border border-[#EAEAEA] text-[#111111] rounded-r-2xl rounded-tl-2xl shadow-sm' }}">
                            <p class="font-sans text-sm md:text-base leading-relaxed">{{ $msg->message }}</p>
                        </div>
                    </div>
                @endforeach
                
                <template x-for="msg in newMessages" :key="msg.id">
                    <div class="flex flex-col" :class="msg.user_id === userId ? 'items-end' : 'items-start'">
                        <span class="text-[10px] font-mono text-[#787774] uppercase tracking-widest mb-1" x-text="msg.user.name"></span>
                        <div class="max-w-[70%] p-5" :class="msg.user_id === userId ? 'bg-[#111111] text-white rounded-l-2xl rounded-tr-2xl' : 'bg-white border border-[#EAEAEA] text-[#111111] rounded-r-2xl rounded-tl-2xl shadow-sm'">
                            <p class="font-sans text-sm md:text-base leading-relaxed" x-text="msg.message"></p>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Chat Input -->
            <div class="border-t border-[#EAEAEA] bg-white p-4 shrink-0 rounded-b-[calc(2rem-8px)]">
                <form @submit.prevent="sendMessage" class="flex items-end gap-4 relative">
                    <div class="flex-1">
                        <textarea x-model="newMessage" @keydown.enter.prevent="sendMessage" rows="1" class="w-full bg-transparent border-0 focus:ring-0 text-[#111111] py-4 px-4 font-sans text-base resize-none placeholder:text-[#A0A0A0]" placeholder="Type your response to the customer..."></textarea>
                    </div>
                    <button type="submit" class="admin-button-island hover:bg-[#333333] transition-haptic active:scale-95 shrink-0 mb-2 mr-2" :disabled="isSending">
                        <span x-show="!isSending">Send</span>
                        <span x-show="isSending">...</span>
                        <div class="admin-button-island-icon transition-haptic" x-show="!isSending">
                            <i class="ph-light ph-paper-plane-right text-white"></i>
                        </div>
                    </button>
                </form>
            </div>
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
