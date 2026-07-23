@extends('layouts.app')

@section('title', 'Clementpay | Clementine')

@section('content')
<div class="w-full bg-background relative pt-[80px]" id="clementpay-section">
    
    <!-- Top Telemetry Bar -->
    <div class="flex justify-between items-center px-6 md:px-xl py-lg border-b border-primary/20 font-mono text-[10px] uppercase tracking-widest text-primary z-30 bg-background relative">
        <span class="cpay-top-label">[ PRIVATE TREASURY PROTOCOL ]</span>
        <span class="cpay-top-version hidden sm:inline-block text-primary/50">SYS.CPAY.2.0</span>
    </div>

    <!-- Secondary Protocol Strip -->
    <div class="flex justify-center items-center px-6 md:px-xl py-3 border-b border-primary/20 font-mono text-[9px] uppercase tracking-widest text-primary/60 z-30 bg-[#FAFAFA] relative">
        BALANCE STATUS: <span class="cpay-status-text ml-3 font-medium text-primary">AUTHORIZED</span>
    </div>
    
    <!-- Main Grid Container (Exact % to prevent blowout) -->
    <div class="w-full grid grid-cols-1 lg:grid-cols-[40%_60%] bg-background min-h-[800px]">
        
        <!-- LEFT SIDE: Treasury Info & Allocation -->
        <div class="flex flex-col relative border-b lg:border-b-0 lg:border-r border-primary/20 bg-background w-full">
            
            <!-- Hero Typography Area -->
            <div class="flex flex-col p-6 md:p-3xl xl:p-3xl border-b border-primary/20 w-full">
                <h1 class="cpay-headline font-h1 text-[clamp(4.5rem,10vw,7.5rem)] leading-[0.8] tracking-tight uppercase text-primary mb-12">
                    <span class="cpay-word block" style="opacity: 0; letter-spacing: 0.2em; transform: translateY(20px);">CLEMENT</span>
                    <span class="cpay-word block" style="opacity: 0; letter-spacing: 0.2em; transform: translateY(20px);">PAY.</span>
                </h1>
                
                <div class="cpay-manifesto font-body-md text-[13px] md:text-sm text-primary/80 uppercase tracking-[0.15em] leading-loose max-w-sm">
                    <div class="cpay-line" style="opacity: 0; transform: translateY(10px);">SECURE LEDGER.</div>
                    <div class="cpay-line" style="opacity: 0; transform: translateY(10px);">PRIVATE ACQUISITIONS.</div>
                    <div class="cpay-line mt-6" style="opacity: 0; transform: translateY(10px);">AUTHORIZE YOUR FUNDS</div>
                    <div class="cpay-line" style="opacity: 0; transform: translateY(10px);">TO ACCESS THE DROP.</div>
                </div>
            </div>

            <!-- Allocation Form Area -->
            <div class="flex flex-col p-6 md:p-3xl xl:p-3xl bg-[#FAFAFA] flex-grow justify-center relative w-full z-20">
                <!-- BG Grid pattern -->
                <div class="absolute inset-0 pointer-events-none opacity-30" style="background-size: 10% 10%; background-image: linear-gradient(to right, #e5e5e5 1px, transparent 1px), linear-gradient(to bottom, #e5e5e5 1px, transparent 1px);"></div>
                
                <div class="w-full flex justify-center lg:justify-start">
                    <form action="{{ route('clementpay.topup') }}" method="POST" class="flex flex-col w-full relative z-30 max-w-md cpay-form pointer-events-auto" style="opacity: 0;">
                        @csrf
                        
                        <div class="flex items-center gap-3 mb-8">
                            <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                            <h2 class="font-mono text-xs uppercase tracking-[0.2em] text-primary">ALLOCATE FUNDS</h2>
                        </div>
                        
                        <div class="flex flex-col gap-3 mb-12 w-full">
                            <label for="amount" class="font-mono text-[9px] uppercase tracking-widest text-primary/60">AMOUNT (USD)</label>
                            <div class="relative w-full border-b border-primary/20 focus-within:border-primary transition-colors duration-300">
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 font-mono text-primary/40 text-xl md:text-2xl pointer-events-none">$</span>
                                <input type="number" name="amount" id="amount" min="100" step="1" required placeholder="100.00" 
                                       class="w-full pl-8 md:pl-10 py-4 text-xl md:text-2xl focus:outline-none focus:ring-0 border-none bg-transparent font-mono text-primary placeholder:text-primary/20 rounded-none relative z-10">
                            </div>
                        </div>
                        
                        <button type="submit" class="bg-primary text-background border border-primary font-label-caps text-xs uppercase tracking-[0.2em] py-5 hover:bg-transparent hover:text-primary transition-all duration-300 ease-out active:scale-[0.98] group flex items-center justify-center gap-4 w-full shrink-0 relative z-20 pointer-events-auto">
                            <span>AUTHORIZE TRANSFER</span>
                            <span class="material-symbols-outlined text-[14px] transform group-hover:translate-x-2 transition-transform duration-300">arrow_forward</span>
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
        
        <!-- RIGHT SIDE: Balance & Audit Log -->
        <div class="flex flex-col relative bg-background w-full overflow-hidden">
            
            <!-- Balance Metric Area -->
            <div class="p-6 md:p-3xl xl:p-3xl border-b border-primary/20 flex flex-col justify-center min-h-[250px] md:min-h-[300px] cpay-balance-area w-full" style="opacity: 0;">
                <span class="font-mono text-[10px] uppercase tracking-[0.3em] text-primary/50 mb-6 block">CURRENT BALANCE</span>
                <div class="font-mono text-[clamp(3.5rem,8vw,7rem)] tracking-tighter text-primary leading-none w-full break-words">
                    ${{ number_format(auth()->user()->clementpay_balance, 2) }}
                </div>
            </div>

            <!-- Audit Log Header -->
            <div class="flex justify-between items-center px-6 md:px-xl py-6 border-b border-primary/20 bg-background cpay-log-header w-full" style="opacity: 0;">
                <h3 class="font-mono text-xs uppercase tracking-[0.2em] text-primary">AUDIT LOG</h3>
                <span class="font-mono text-[9px] uppercase tracking-widest text-primary/40">RECORDS: {{ $transactions->total() }}</span>
            </div>

            <!-- Transactions List -->
            <div class="flex flex-col w-full flex-grow relative overflow-hidden">
                @forelse($transactions as $index => $tx)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between px-6 md:px-xl py-8 border-b border-primary/10 hover:border-primary/30 transition-colors duration-300 cpay-tx-row w-full" style="opacity: 0; transform: translateY(10px);">
                    
                    <!-- Left: TX Info -->
                    <div class="flex flex-col gap-2 min-w-0 pr-4 w-full sm:w-auto flex-grow">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-[10px] font-bold uppercase tracking-[0.2em] {{ $tx->status === 'success' ? 'text-primary' : 'text-primary/50' }}">
                                {{ $tx->type }}
                            </span>
                            <span class="flex items-center gap-1.5 font-mono text-[9px] uppercase tracking-widest {{ $tx->status === 'success' ? 'text-[#00B050]' : 'text-primary/50' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $tx->status === 'success' ? 'bg-[#00B050]' : 'bg-primary/30' }}"></span>
                                {{ $tx->status }}
                            </span>
                        </div>
                        <span class="font-body-md text-[13px] md:text-sm text-primary/80 break-words w-full max-w-md">{{ $tx->description }}</span>
                        <span class="font-mono text-[9px] text-primary/40 tracking-widest">{{ $tx->created_at->format('Y.m.d H:i:s') }}</span>
                    </div>

                    <!-- Right: TX Amount -->
                    <div class="flex flex-col sm:items-end mt-4 sm:mt-0 shrink-0">
                        <span class="font-mono text-lg md:text-xl {{ $tx->amount > 0 ? 'text-primary' : 'text-primary/50' }}">
                            {{ $tx->amount > 0 ? '+' : '' }}${{ number_format(abs($tx->amount), 2) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="flex-grow flex flex-col items-center justify-center py-32 text-center cpay-tx-row w-full" style="opacity: 0;">
                    <div class="w-[1px] h-12 bg-primary/20 mb-6"></div>
                    <span class="font-mono text-[10px] uppercase tracking-[0.2em] text-primary/40">NO RECORDS FOUND IN LEDGER</span>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="px-6 md:px-xl py-lg border-t border-primary/20 cpay-pagination w-full" style="opacity: 0;">
                    {{ $transactions->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap === 'undefined') return;
    
    const tl = gsap.timeline({
        delay: 0.1
    });

    // 1. Stagger Headline Words
    tl.to('.cpay-word', {
        y: 0,
        opacity: 1,
        letterSpacing: '0em',
        duration: 0.8,
        stagger: 0.1,
        ease: 'power3.out'
    }, 0);

    // 2. Stagger Manifesto Lines
    tl.to('.cpay-line', {
        y: 0,
        opacity: 1,
        duration: 0.6,
        stagger: 0.05,
        ease: 'power2.out'
    }, 0.4);

    // 3. Form Reveal
    tl.to('.cpay-form', {
        opacity: 1,
        duration: 0.8,
        ease: 'power2.out'
    }, 0.6);

    // 4. Balance Area Reveal
    tl.to('.cpay-balance-area', {
        opacity: 1,
        duration: 1,
        ease: 'power2.out'
    }, 0.3);

    // 5. Audit Log Header
    tl.to('.cpay-log-header', {
        opacity: 1,
        duration: 0.5,
        ease: 'power2.out'
    }, 0.5);

    // 6. Stagger TX Rows
    tl.to('.cpay-tx-row', {
        y: 0,
        opacity: 1,
        duration: 0.5,
        stagger: 0.05,
        ease: 'power2.out'
    }, 0.6);

    // 7. Pagination
    if (document.querySelector('.cpay-pagination')) {
        tl.to('.cpay-pagination', {
            opacity: 1,
            duration: 0.5,
            ease: 'none'
        }, 0.8);
    }
});
</script>
@endsection
