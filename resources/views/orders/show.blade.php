@extends('layouts.app')

@section('title', 'Order Status - Clementine')

@section('content')
<div class="px-8 md:px-16 py-24 max-w-7xl mx-auto w-full flex flex-col items-center">
    
    @if($order->payment_status === 'paid')
        <!-- Success State - Premium Editorial -->
        <div class="w-full max-w-5xl mx-auto flex flex-col border border-primary bg-background" 
             x-data="{ show: false }" 
             x-init="setTimeout(() => show = true, 100)">
            
            <div class="p-8 md:p-16 flex flex-col items-start border-b border-primary"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 class="transition-all duration-1000 ease-out">
                
                <div class="flex items-center gap-6 mb-12">
                    <div class="w-16 h-16 flex items-center justify-center border border-primary bg-surface">
                        <span class="material-symbols-outlined text-[32px] text-primary">check</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-body-md text-xs font-bold uppercase tracking-widest text-primary/60">Status</span>
                        <span class="font-h2 text-xl md:text-2xl uppercase tracking-widest">Transaction Settled</span>
                    </div>
                </div>
                
                <h1 class="font-h1 text-6xl md:text-8xl uppercase tracking-tighter mb-8 leading-none">ACQUISITION<br>CONFIRMED.</h1>
                
                <p class="font-body-md text-sm text-primary/70 max-w-2xl leading-relaxed">
                    Your allocation has been secured. The official folio and provenance documents have been dispatched to 
                    <span class="text-primary font-bold">{{ $order->contact_email }}</span>.
                </p>
            </div>

            <!-- Bento Data Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 style="transition: all 1000ms cubic-bezier(0.16, 1, 0.3, 1) 150ms;">
                
                <div class="p-8 md:p-12 border-b md:border-b-0 md:border-r border-primary flex flex-col justify-between gap-6">
                    <span class="font-body-md text-xs font-bold uppercase tracking-widest text-primary/60">Reference</span>
                    <span class="font-mono text-sm tracking-wider uppercase bg-surface border border-primary px-3 py-2 w-max text-primary">
                        #{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}
                    </span>
                </div>
                
                <div class="p-8 md:p-12 border-b md:border-b-0 md:border-r border-primary flex flex-col justify-between gap-6">
                    <span class="font-body-md text-xs font-bold uppercase tracking-widest text-primary/60">Settled Amount</span>
                    <span class="font-h2 text-3xl md:text-4xl text-primary">${{ number_format($order->total, 2) }}</span>
                </div>
                
                <a href="{{ route('profile.index') }}" class="p-8 md:p-12 flex flex-col justify-between gap-6 bg-surface hover:bg-primary hover:text-secondary transition-colors group">
                    <span class="font-body-md text-xs font-bold uppercase tracking-widest group-hover:text-secondary/70 text-primary/60 transition-colors">Next Steps</span>
                    <div class="font-h2 text-2xl md:text-3xl flex items-center justify-between">
                        Access Collection
                        <span class="material-symbols-outlined text-[32px] group-hover:translate-x-2 transition-transform">arrow_forward</span>
                    </div>
                </a>
            </div>
        </div>
    @else
        <!-- Pending State: Private Reservation Certificate -->
        <div class="w-full min-h-[85vh] bg-white text-black font-body-md overflow-hidden flex flex-col relative" 
             x-data="reservationCertificate('{{ $order->created_at->toIso8601String() }}')">
             
             <!-- Main Container with Industrial Borders -->
             <div class="flex-grow w-full border border-black/10 flex flex-col md:flex-row relative certificate-grid opacity-0 scale-[0.98]">
                <!-- Vertical Divider -->
                <div class="hidden md:block absolute top-0 left-[60%] w-[1px] h-full bg-black/10 scale-y-0 origin-top vertical-divider"></div>
                
                <!-- LEFT COLUMN: Reservation Info & Payment -->
                <div class="w-full md:w-[60%] p-8 md:p-16 flex flex-col justify-between relative overflow-hidden">
                    
                    <!-- Top Section -->
                    <div>
                        <!-- Phase 2: Status Indicator -->
                        <div class="flex items-center gap-4 mb-16 status-container opacity-0 translate-y-6">
                            <div class="w-2 h-2 bg-black/40 indicator-square"></div>
                            <span class="font-mono text-[10px] uppercase tracking-widest text-black/60">Waiting for Payment</span>
                        </div>
                        
                        <!-- Phase 2: Headline -->
                        <h1 class="font-h1 text-[50px] md:text-[80px] leading-[0.8] tracking-tighter uppercase mb-24 flex flex-col">
                            <div class="overflow-hidden"><div class="headline-line translate-y-[110%]">COMPLETE YOUR</div></div>
                            <div class="overflow-hidden"><div class="headline-line translate-y-[110%]">ORDER</div></div>
                        </h1>
                    </div>
                    
                    <!-- Bottom Section: Payment Details -->
                    @if($order->payment_method === 'virtual_account' && $order->payment_details)
                    <div class="flex flex-col gap-12 payment-details mt-12">
                        <div class="payment-block opacity-0 translate-y-4">
                            <p class="font-mono text-[9px] uppercase tracking-widest text-black/40 mb-3">BANK</p>
                            <p class="font-h1 text-2xl md:text-3xl tracking-tight uppercase">{{ $order->payment_details['bank'] ?? 'Bank' }} Virtual Account</p>
                        </div>
                        
                        <div class="payment-block opacity-0 translate-y-4">
                            <p class="font-mono text-[9px] uppercase tracking-widest text-black/40 mb-3">VIRTUAL ACCOUNT NUMBER</p>
                            <div class="flex flex-wrap items-center gap-6 group">
                                <p class="font-mono text-4xl md:text-6xl tracking-tight va-number flex overflow-hidden" data-va="{{ $order->payment_details['va_number'] ?? '0000000000' }}">
                                    <!-- JS will split and inject spans here -->
                                </p>
                                <button @click="copyVA('{{ $order->payment_details['va_number'] ?? '' }}')" class="copy-btn relative text-black/30 hover:text-black transition-colors opacity-0 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[24px] transform transition-transform duration-300 group-hover:rotate-6">content_copy</span>
                                    <span class="absolute -top-6 left-1/2 -translate-x-1/2 font-mono text-[9px] uppercase tracking-widest opacity-0 transition-opacity duration-300 copied-text pointer-events-none">COPIED</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="payment-block opacity-0 translate-y-4">
                            <p class="font-mono text-[9px] uppercase tracking-widest text-black/40 mb-3">TOTAL AMOUNT</p>
                            <p class="font-h1 text-4xl md:text-5xl tracking-tight uppercase">${{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-24 pt-8 border-t border-black/10 payment-block opacity-0 translate-y-4">
                        <form action="{{ route('orders.simulate_payment', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="primary-btn group relative w-full border border-black bg-white text-black py-6 overflow-hidden flex items-center justify-between px-8 hover:bg-black hover:text-white transition-all duration-300 ease-[cubic-bezier(0.23,1,0.32,1)] active:scale-[0.99]">
                                <div class="flex items-center gap-4 relative z-10">
                                    <span class="material-symbols-outlined text-[16px] text-black/40 group-hover:text-white/60">developer_mode</span>
                                    <span class="font-h1 text-xl tracking-widest uppercase">SIMULATE VA PAYMENT</span>
                                </div>
                                <span class="material-symbols-outlined text-[18px] transform transition-transform duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:translate-x-2 relative z-10">arrow_forward</span>
                            </button>
                        </form>
                        <p class="font-mono text-[9px] uppercase tracking-widest text-black/40 mt-4">LOCAL ENVIRONMENT SANDBOX BYPASS.</p>
                    </div>
                    
                    @elseif($order->payment_method === 'qris')
                    <div class="flex flex-col gap-12 payment-details mt-12">
                        <div class="payment-block opacity-0 translate-y-4 border border-black/10 p-8 text-center bg-[#FAFAFA]">
                            <p class="font-mono text-xs uppercase tracking-widest text-black/60">Open QRIS Gateway to complete payment.</p>
                        </div>
                    </div>
                    <div class="mt-24 pt-8 border-t border-black/10 payment-block opacity-0 translate-y-4">
                        <a href="{{ route('dummy.qris', ['type' => 'order', 'reference_id' => $order->id, 'amount' => $order->total]) }}" class="primary-btn group relative w-full border border-black bg-white text-black py-6 overflow-hidden flex items-center justify-between px-8 hover:bg-black hover:text-white transition-all duration-300 ease-[cubic-bezier(0.23,1,0.32,1)] active:scale-[0.99]">
                            <div class="flex items-center gap-4 relative z-10">
                                <span class="material-symbols-outlined text-[16px] text-black/40 group-hover:text-white/60">qr_code_scanner</span>
                                <span class="font-h1 text-xl tracking-widest uppercase">OPEN QRIS GATEWAY</span>
                            </div>
                            <span class="material-symbols-outlined text-[18px] transform transition-transform duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:translate-x-2 relative z-10">arrow_forward</span>
                        </a>
                        <p class="font-mono text-[9px] uppercase tracking-widest text-black/40 mt-4">LOCAL ENVIRONMENT SANDBOX BYPASS.</p>
                    </div>
                    @else
                    <div class="payment-block opacity-0 translate-y-4 border border-black/10 p-8 text-center bg-[#FAFAFA] mt-12">
                        <p class="font-mono text-xs uppercase tracking-widest text-black/60">Payment instructions are not available.</p>
                    </div>
                    @endif
                </div>
                
                <!-- RIGHT COLUMN: Order Summary -->
                <div class="w-full md:w-[40%] p-8 md:p-16 flex flex-col bg-[#FAFAFA]">
                    
                    <div class="flex justify-between items-start mb-16 border-b border-black/10 pb-8 summary-item opacity-0 translate-y-4">
                        <h2 class="font-mono text-[10px] uppercase tracking-widest text-black/40">ORDER #{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}</h2>
                        
                        <!-- Phase 6: Reservation Timer -->
                        @if(in_array($order->status, ['pending', 'processing']))
                        <div class="flex flex-col items-end gap-1">
                            <span class="font-mono text-[9px] uppercase tracking-widest text-black/40">RESERVATION EXPIRES IN</span>
                            <span class="font-mono text-2xl tracking-widest text-black" x-text="timerDisplay">15:00</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex flex-col gap-10 flex-grow">
                        @foreach($order->items as $item)
                            <div class="flex items-start gap-6 summary-item opacity-0 translate-y-4 group">
                                <div class="w-24 h-24 bg-white border border-black/10 flex-shrink-0 flex items-center justify-center p-2 relative overflow-hidden">
                                    @if($item->product->primaryImage)
                                    <img alt="{{ $item->product->name }}" class="w-full h-full object-cover mix-blend-multiply transition-transform duration-700 group-hover:rotate-2" src="{{ $item->product->primaryImage->url }}">
                                    @endif
                                </div>
                                <div class="flex flex-col justify-center flex-1 pt-1">
                                    <span class="font-h1 text-2xl uppercase tracking-tighter leading-none">{{ $item->product->name }}</span>
                                    <span class="font-mono text-[9px] uppercase tracking-widest text-black/40 mt-3">{{ $item->product->collection->name ?? 'Vault' }}</span>
                                    <div class="flex items-center mt-3">
                                        <span class="font-mono text-[10px] tracking-widest text-black/60">QTY: {{ $item->quantity }}</span>
                                    </div>
                                </div>
                                <div class="flex items-start pt-1">
                                    <span class="font-mono text-sm tracking-widest text-black">${{ number_format($item->price_at_purchase * $item->quantity, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex flex-col gap-5 font-mono text-[10px] uppercase tracking-widest text-black/50 mt-16 pt-8 border-t border-black/10">
                        <div class="flex justify-between summary-item opacity-0 translate-y-4">
                            <span>Subtotal (Excl. Tax)</span>
                            <span class="text-black">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between text-black summary-item opacity-0 translate-y-4">
                            <span>{{ $order->user && $order->user->is_vip ? 'VIP REDUCTION' : 'REDUCTION' }}</span>
                            <span>-${{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                        @endif
                        @if($order->tax > 0)
                        <div class="flex justify-between summary-item opacity-0 translate-y-4">
                            <span>Product Tax</span>
                            <span class="text-black">${{ number_format($order->tax, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between summary-item opacity-0 translate-y-4">
                            <span>Shipping Protocol</span>
                            <span class="text-black">${{ number_format($order->shipping_fee, 2) }}</span>
                        </div>
                        @if($order->shipping_tax > 0)
                        <div class="flex justify-between summary-item opacity-0 translate-y-4">
                            <span>Shipping Tax</span>
                            <span class="text-black">${{ number_format($order->shipping_tax, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-end border-t border-black/10 pt-6 mt-4 summary-item opacity-0 translate-y-4">
                            <span class="text-black/40">TOTAL (INCL. TAX)</span>
                            <span class="font-h1 text-3xl tracking-widest text-black">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                    
                    <!-- Phase 8: Cancel Order -->
                    @if(in_array($order->status, ['pending', 'processing']) && now()->diffInMinutes($order->created_at) <= 15)
                    <div class="mt-16 pt-8 border-t border-black/10 summary-item opacity-0 translate-y-4">
                        <div class="group border border-black/5 p-6 transition-colors duration-300 hover:border-black/20 flex flex-col items-start gap-4">
                            <p class="font-mono text-[9px] uppercase tracking-widest text-black/40 leading-relaxed max-w-xs">
                                Allocation cancellation is permitted within 15 minutes of reservation. If funds were transferred, balance will route to Clementpay.
                            </p>
                            <a href="{{ route('orders.cancel_form', $order->id) }}" class="font-mono text-[10px] font-bold uppercase tracking-widest text-black/40 hover:text-black transition-colors border-b border-transparent hover:border-black/20 pb-1">
                                CANCEL RESERVATION
                            </a>
                        </div>
                    </div>
                    @elseif($order->status === 'cancelled')
                    <div class="mt-16 border-t border-black/10 pt-8 summary-item opacity-0 translate-y-4">
                        <div class="border border-black/10 p-6 text-center bg-black/5">
                            <span class="font-mono text-[10px] font-bold uppercase tracking-widest text-black/50">ORDER CANCELLED</span>
                        </div>
                    </div>
                    @elseif($order->status === 'pending_cancel')
                    <div class="mt-16 border-t border-black/10 pt-8 summary-item opacity-0 translate-y-4">
                        <div class="border border-black/10 p-6 text-center">
                            <span class="font-mono text-[10px] font-bold uppercase tracking-widest text-black/50">CANCELLATION REQUESTED</span>
                        </div>
                    </div>
                    @endif
                </div>
             </div>
        </div>

        <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('reservationCertificate', (createdAtISO) => ({
                timerDisplay: '15:00',
                createdAt: new Date(createdAtISO).getTime(),
                timerInterval: null,

                init() {
                    this.startTimer();
                    setTimeout(() => this.initGSAP(), 150);
                },

                startTimer() {
                    const updateTimer = () => {
                        const now = new Date().getTime();
                        const diff = now - this.createdAt;
                        const minutesPassed = Math.floor(diff / 60000);
                        const secondsPassed = Math.floor((diff % 60000) / 1000);
                        
                        let totalSecondsLeft = (15 * 60) - (minutesPassed * 60 + secondsPassed);
                        
                        if (totalSecondsLeft <= 0) {
                            this.timerDisplay = '00:00';
                            clearInterval(this.timerInterval);
                            return;
                        }
                        
                        const m = Math.floor(totalSecondsLeft / 60);
                        const s = totalSecondsLeft % 60;
                        this.timerDisplay = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                    };
                    
                    updateTimer(); // Initial call
                    this.timerInterval = setInterval(updateTimer, 1000);
                },

                copyVA(vaNumber) {
                    navigator.clipboard.writeText(vaNumber);
                    
                    const numberEl = document.querySelector('.va-number');
                    const copiedText = document.querySelector('.copied-text');
                    
                    // Flash number
                    gsap.to(numberEl, { opacity: 0.2, duration: 0.1, yoyo: true, repeat: 1 });
                    
                    // Show COPIED
                    gsap.fromTo(copiedText, 
                        { opacity: 0, y: 5 }, 
                        { opacity: 1, y: 0, duration: 0.2, ease: 'power2.out' }
                    );
                    
                    setTimeout(() => {
                        gsap.to(copiedText, { opacity: 0, duration: 0.3 });
                    }, 2000);
                },

                initGSAP() {
                    const tl = gsap.timeline();
                    
                    // Phase 1: Grid Outline Reveal
                    tl.to('.certificate-grid', {
                        opacity: 1,
                        scale: 1,
                        duration: 0.9,
                        ease: 'expo.out'
                    })
                    .to('.vertical-divider', {
                        scaleY: 1,
                        duration: 0.9,
                        ease: 'expo.out'
                    }, "-=0.7");
                    
                    // Phase 2: Status Indicator & Headline
                    tl.to('.status-container', {
                        opacity: 1,
                        y: 0,
                        duration: 0.7,
                        ease: 'power2.out'
                    }, "-=0.5")
                    .to('.headline-line', {
                        y: 0,
                        duration: 0.7,
                        stagger: 0.12,
                        ease: 'power3.out'
                    }, "-=0.5");
                    
                    // Phase 3: Payment Details Blocks
                    const blocks = document.querySelectorAll('.payment-block');
                    if(blocks.length > 0) {
                        tl.to(blocks, {
                            opacity: 1,
                            y: 0,
                            duration: 0.7,
                            stagger: 0.15,
                            ease: 'power2.out'
                        }, "-=0.3");
                    }
                    
                    // Phase 4: VA Number Reveal
                    const vaContainer = document.querySelector('.va-number');
                    if (vaContainer) {
                        const va = vaContainer.getAttribute('data-va');
                        vaContainer.innerHTML = ''; // clear
                        
                        // Split into spans
                        for(let i=0; i<va.length; i++) {
                            let span = document.createElement('span');
                            span.innerText = va[i];
                            span.style.opacity = '0';
                            span.style.transform = 'translateX(-10px)';
                            vaContainer.appendChild(span);
                        }
                        
                        tl.to(vaContainer.children, {
                            opacity: 1,
                            x: 0,
                            duration: 1.2,
                            stagger: 0.05,
                            ease: 'power2.out'
                        }, "-=0.8");
                        
                        tl.to('.copy-btn', {
                            opacity: 1,
                            duration: 0.5
                        }, "-=0.4");
                    }
                    
                    // Phase 5: Order Summary Items
                    tl.to('.summary-item', {
                        opacity: 1,
                        y: 0,
                        duration: 0.6,
                        stagger: 0.08,
                        ease: 'power2.out'
                    }, "-=1.0");
                }
            }));
        });
        </script>
    @endif
</div>
@endsection
