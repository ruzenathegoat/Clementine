@extends('layouts.app')

@section('title', 'Clementpay | Clementine')

@section('content')
<div class="w-full bg-background min-h-screen flex flex-col pt-[80px]">
    
    <!-- 1. Header Section -->
    <header class="w-full px-lg md:px-2xl py-2xl md:py-4xl border-b border-primary relative overflow-hidden flex flex-col md:flex-row md:items-end justify-between gap-12">
        <div class="flex flex-col relative z-10 w-full md:w-2/3">
            <h1 class="font-h1 text-[clamp(3.5rem,8vw,7rem)] leading-[0.85] tracking-tight uppercase text-primary mb-6 clementpay-reveal" style="opacity: 0;">
                CLEMENTPAY
            </h1>
            <p class="font-body-md text-xs md:text-sm text-primary/60 max-w-md uppercase tracking-[0.1em] leading-relaxed clementpay-reveal" style="opacity: 0;">
                Private Treasury Protocol. Manage your allocations and provenance transaction records.
            </p>
        </div>
        
        <!-- Balance Display -->
        <div class="flex flex-col items-start md:items-end z-10 clementpay-reveal" style="opacity: 0;">
            <span class="font-mono text-[9px] uppercase tracking-[0.3em] text-primary/50 mb-3 block">AUTHORIZED BALANCE</span>
            <div class="font-mono text-3xl md:text-5xl tracking-tight text-primary">
                ${{ number_format(auth()->user()->clementpay_balance, 2) }}
            </div>
            <div class="w-full md:w-48 h-[1px] bg-primary/20 mt-4"></div>
        </div>
    </header>

    <!-- 2. Main Content Grid -->
    <div class="w-full flex-grow grid grid-cols-1 lg:grid-cols-[40%_60%]">
        
        <!-- Left: Allocation Input (Top-up) -->
        <div class="border-b lg:border-b-0 lg:border-r border-primary/20 p-lg md:p-2xl flex flex-col bg-[#FAFAFA]">
            <div class="w-full max-w-sm flex flex-col clementpay-fade-up" style="opacity: 0;">
                
                <!-- Terminal Header -->
                <div class="flex justify-between items-center border-b border-primary pb-3 mb-8">
                    <span class="font-mono text-[10px] uppercase tracking-widest text-primary">DEPOSIT PROTOCOL</span>
                    <span class="font-mono text-[9px] text-primary/40">SYS.PAY.01</span>
                </div>
                
                <h2 class="font-h1 text-2xl uppercase tracking-tight text-primary mb-2">ALLOCATE FUNDS</h2>
                <p class="font-body-md text-xs text-primary/50 mb-10 leading-relaxed">
                    Secure your balance via authenticated QRIS or Virtual Account gateways. Minimum allocation is $100.00.
                </p>

                <form action="{{ route('clementpay.topup') }}" method="POST" class="flex flex-col w-full">
                    @csrf
                    
                    <div class="flex flex-col gap-3 mb-8">
                        <label for="amount" class="font-mono text-[9px] uppercase tracking-[0.2em] text-primary/70">AMOUNT (USD)</label>
                        <div class="relative w-full border border-primary/30 focus-within:border-primary transition-colors bg-white">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-mono text-primary/50 text-sm">$</span>
                            <input type="number" name="amount" id="amount" min="100" step="1" required placeholder="100.00" class="w-full pl-10 pr-4 py-4 text-sm focus:outline-none focus:ring-0 border-none bg-transparent font-mono text-primary placeholder:text-primary/20">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-white font-mono text-[10px] uppercase tracking-[0.25em] py-5 hover:bg-black transition-colors duration-300 relative group overflow-hidden">
                        <span class="relative z-10 flex items-center justify-center gap-4">
                            <span>AUTHORIZE TRANSFER</span>
                            <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Audit Log (Transaction History) -->
        <div class="p-lg md:p-2xl flex flex-col bg-background">
            
            <div class="flex justify-between items-end border-b border-primary/20 pb-4 mb-8 clementpay-fade-up" style="opacity: 0;">
                <h2 class="font-mono text-[10px] uppercase tracking-[0.3em] text-primary/60">AUDIT LOG</h2>
                <span class="font-mono text-[9px] text-primary/30">REF. {{ now()->format('Y.m.d') }}</span>
            </div>

            <div class="flex flex-col w-full">
                @forelse($transactions as $index => $tx)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between py-6 border-b border-primary/10 hover:bg-[#FAFAFA] transition-colors px-4 -mx-4 clementpay-tx-row" style="opacity: 0;">
                    
                    <!-- Left: TX Info -->
                    <div class="flex flex-col gap-2 mb-4 sm:mb-0">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-[10px] font-bold uppercase tracking-widest {{ $tx->status === 'success' ? 'text-primary' : 'text-primary/40' }}">
                                {{ $tx->type }}
                            </span>
                            <!-- Status pip -->
                            <span class="flex items-center gap-1 font-mono text-[8px] uppercase tracking-widest {{ $tx->status === 'success' ? 'text-green-600' : 'text-primary/40' }}">
                                <span class="w-1 h-1 rounded-full {{ $tx->status === 'success' ? 'bg-green-500' : 'bg-primary/30' }}"></span>
                                {{ $tx->status }}
                            </span>
                        </div>
                        <span class="font-body-md text-xs text-primary/60">{{ $tx->description }}</span>
                        <span class="font-mono text-[9px] text-primary/40">{{ $tx->created_at->format('M d, Y H:i:s') }}</span>
                    </div>

                    <!-- Right: TX Amount -->
                    <div class="flex flex-col sm:items-end gap-1">
                        <span class="font-mono text-lg {{ $tx->amount > 0 ? 'text-primary' : 'text-primary/50' }}">
                            {{ $tx->amount > 0 ? '+' : '' }}${{ number_format(abs($tx->amount), 2) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="py-16 flex flex-col items-center justify-center text-center border border-primary/10 bg-[#FAFAFA] mt-4 clementpay-fade-up" style="opacity: 0;">
                    <span class="material-symbols-outlined text-primary/20 text-3xl mb-4">receipt_long</span>
                    <span class="font-mono text-[10px] text-primary/50 uppercase tracking-widest">No transaction records found in the audit log.</span>
                </div>
                @endforelse
            </div>

            @if($transactions->hasPages())
                <div class="mt-12 border-t border-primary/20 pt-6 clementpay-fade-up" style="opacity: 0;">
                    {{ $transactions->links() }}
                </div>
            @endif

        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap === 'undefined') return;

    // Header reveal
    gsap.fromTo('.clementpay-reveal', 
        { y: 30, opacity: 0 },
        { y: 0, opacity: 1, duration: 1, stagger: 0.15, ease: 'expo.out', delay: 0.1 }
    );

    // Fade up sections
    gsap.fromTo('.clementpay-fade-up',
        { y: 20, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: 'power2.out', delay: 0.3 }
    );

    // TX Rows stagger
    gsap.fromTo('.clementpay-tx-row',
        { y: 15, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.6, stagger: 0.05, ease: 'power2.out', delay: 0.5 }
    );
});
</script>
@endsection
