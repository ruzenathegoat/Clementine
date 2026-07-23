@extends('layouts.app')

@section('title', 'Clementpay | Clementine')

@section('content')
<div class="w-full bg-background relative pt-[80px] pb-24 lg:pb-32 overflow-x-hidden">
    
    <!-- 1. Header Section -->
    <header class="w-full px-6 md:px-12 py-16 md:py-24 border-b border-primary relative">
        <div class="max-w-screen-2xl mx-auto flex flex-col lg:flex-row justify-between items-start lg:items-end gap-12">
            
            <div class="flex flex-col relative z-10 w-full lg:w-3/5">
                <h1 class="font-h1 text-[clamp(4rem,10vw,7rem)] leading-[0.85] tracking-tighter text-primary uppercase mb-6 clementpay-header-elem" style="opacity: 0; transform: translateY(20px);">
                    Clementpay
                </h1>
                <p class="font-body-md text-base md:text-lg text-primary max-w-xl leading-relaxed clementpay-header-elem" style="opacity: 0; transform: translateY(20px);">
                    Your private treasury protocol. Manage your allocations and review provenance transaction records for all acquisitions.
                </p>
            </div>
            
            <div class="flex flex-col items-start lg:items-end z-10 clementpay-header-elem" style="opacity: 0; transform: translateY(20px);">
                <span class="font-mono text-xs uppercase tracking-widest text-primary/60 mb-3">Authorized Balance</span>
                <div class="font-mono text-4xl md:text-6xl tracking-tight text-primary">
                    ${{ number_format(auth()->user()->clementpay_balance, 2) }}
                </div>
            </div>
            
        </div>
    </header>

    <!-- 2. Main Content -->
    <div class="w-full max-w-screen-2xl mx-auto px-6 md:px-12 mt-12 md:mt-24">
        <div class="flex flex-col lg:flex-row gap-16 xl:gap-24 items-start">
            
            <!-- Left: Allocation Input (Top-up) -->
            <!-- Fixed width on desktop to prevent squishing -->
            <div class="w-full lg:w-[400px] shrink-0 flex flex-col clementpay-fade-up" style="opacity: 0;">
                <h2 class="font-h1 text-3xl md:text-4xl uppercase tracking-tight text-primary mb-4">Allocate Funds</h2>
                <p class="font-body-md text-sm text-primary/70 mb-12 leading-relaxed">
                    Secure your balance via authenticated QRIS or Virtual Account gateways. Minimum allocation is $100.
                </p>

                <form action="{{ route('clementpay.topup') }}" method="POST" class="flex flex-col w-full">
                    @csrf
                    
                    <div class="flex flex-col gap-3 mb-12">
                        <label for="amount" class="font-mono text-xs uppercase tracking-widest text-primary/80">Amount (USD)</label>
                        <div class="relative w-full border-b border-primary/30 focus-within:border-primary transition-colors duration-300">
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 font-mono text-primary/40 text-xl">$</span>
                            <input type="number" name="amount" id="amount" min="100" step="1" required placeholder="100.00" 
                                   class="w-full pl-8 py-4 text-xl focus:outline-none focus:ring-0 border-none bg-transparent font-mono text-primary placeholder:text-primary/20 rounded-none">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-background font-label-caps text-sm uppercase tracking-[0.2em] py-5 hover:bg-black transition-all duration-200 ease-out active:scale-[0.97] group">
                        <span class="flex items-center justify-center gap-3">
                            Authorize Transfer
                            <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform duration-300">arrow_forward</span>
                        </span>
                    </button>
                </form>
            </div>

            <!-- Right: Audit Log (Transaction History) -->
            <div class="w-full flex-1 min-w-0">
                <div class="flex justify-between items-end border-b border-primary pb-4 mb-8 clementpay-fade-up" style="opacity: 0;">
                    <h2 class="font-h1 text-2xl md:text-3xl uppercase tracking-tight text-primary">Audit Log</h2>
                    <span class="font-mono text-xs uppercase tracking-widest text-primary/40">Ref. {{ now()->format('Y.m.d') }}</span>
                </div>

                <div class="flex flex-col w-full">
                    @forelse($transactions as $index => $tx)
                    <div class="group flex flex-col sm:flex-row sm:items-center justify-between py-6 border-b border-primary/10 hover:border-primary/40 transition-colors duration-300 clementpay-tx-row" style="opacity: 0; transform: translateY(15px);">
                        
                        <div class="flex flex-col gap-2 mb-3 sm:mb-0 min-w-0 pr-4">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-sm font-bold uppercase tracking-widest {{ $tx->status === 'success' ? 'text-primary' : 'text-primary/50' }}">
                                    {{ $tx->type }}
                                </span>
                                <span class="flex items-center gap-1.5 font-mono text-[10px] uppercase tracking-widest {{ $tx->status === 'success' ? 'text-green-600' : 'text-primary/50' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $tx->status === 'success' ? 'bg-green-500' : 'bg-primary/30' }}"></span>
                                    {{ $tx->status }}
                                </span>
                            </div>
                            <span class="font-body-md text-base text-primary/80 truncate">{{ $tx->description }}</span>
                            <span class="font-mono text-xs text-primary/40">{{ $tx->created_at->format('M d, Y H:i:s') }}</span>
                        </div>

                        <div class="flex flex-col sm:items-end shrink-0">
                            <span class="font-mono text-xl md:text-2xl {{ $tx->amount > 0 ? 'text-primary' : 'text-primary/50' }}">
                                {{ $tx->amount > 0 ? '+' : '' }}${{ number_format(abs($tx->amount), 2) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="py-24 flex flex-col items-center justify-center text-center clementpay-fade-up" style="opacity: 0;">
                        <span class="font-mono text-sm uppercase tracking-widest text-primary/40">No transaction records found.</span>
                    </div>
                    @endforelse
                </div>

                @if($transactions->hasPages())
                    <div class="mt-12 pt-6 clementpay-fade-up" style="opacity: 0;">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap === 'undefined') return;
    
    const easeOut = "power2.out"; 

    gsap.to('.clementpay-header-elem', {
        y: 0, 
        opacity: 1, 
        duration: 0.8, 
        stagger: 0.1, 
        ease: easeOut,
        delay: 0.1
    });

    gsap.to('.clementpay-fade-up', {
        opacity: 1, 
        duration: 0.6, 
        stagger: 0.1,
        ease: easeOut,
        delay: 0.3
    });

    gsap.to('.clementpay-tx-row', {
        y: 0,
        opacity: 1, 
        duration: 0.5, 
        stagger: 0.05, 
        ease: easeOut, 
        delay: 0.4
    });
});
</script>
@endsection
