@extends('layouts.app')

@section('title', 'Clementpay | Clementine')

@section('content')
<div class="w-full bg-background min-h-screen flex flex-col pt-[80px]">
    
    <!-- 1. Header Section -->
    <header class="w-full px-6 md:px-12 py-16 md:py-32 border-b border-primary relative overflow-hidden grid grid-cols-1 md:grid-cols-12 gap-12 items-end">
        <!-- Title and Subtitle -->
        <div class="md:col-span-7 lg:col-span-8 flex flex-col relative z-10">
            <h1 class="font-h1 text-[clamp(3.5rem,8vw,6rem)] leading-[0.9] tracking-tight text-primary uppercase mb-6">
                Clementpay
            </h1>
            <p class="font-body-md text-base md:text-lg text-primary max-w-xl leading-relaxed">
                Your private treasury. Allocate funds and review provenance transaction records for all your acquisitions.
            </p>
        </div>
        
        <!-- Balance Display -->
        <div class="md:col-span-5 lg:col-span-4 flex flex-col items-start md:items-end z-10">
            <span class="font-mono text-xs uppercase tracking-widest text-primary/60 mb-2">Available Balance</span>
            <div class="font-mono text-4xl md:text-5xl lg:text-6xl tracking-tight text-primary">
                ${{ number_format(auth()->user()->clementpay_balance, 2) }}
            </div>
        </div>
    </header>

    <!-- 2. Main Content Grid -->
    <div class="w-full flex-grow grid grid-cols-1 lg:grid-cols-12">
        
        <!-- Left: Allocation Input (Top-up) -->
        <div class="lg:col-span-5 xl:col-span-4 border-b lg:border-b-0 lg:border-r border-primary p-6 md:p-12 flex flex-col">
            <div class="w-full max-w-md mx-auto lg:mx-0">
                <h2 class="font-h1 text-3xl md:text-4xl uppercase tracking-tight text-primary mb-4">Allocate Funds</h2>
                <p class="font-body-md text-sm md:text-base text-primary/70 mb-10 leading-relaxed">
                    Add funds to your balance via secure payment gateways. The minimum allocation is $100.
                </p>

                <form action="{{ route('clementpay.topup') }}" method="POST" class="flex flex-col w-full">
                    @csrf
                    
                    <div class="flex flex-col gap-2 mb-10">
                        <label for="amount" class="font-mono text-xs uppercase tracking-widest text-primary/80">Amount (USD)</label>
                        <div class="relative w-full border-b border-primary/30 focus-within:border-primary transition-colors">
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 font-mono text-primary/50 text-lg md:text-xl">$</span>
                            <input type="number" name="amount" id="amount" min="100" step="1" required placeholder="100.00" class="w-full pl-8 py-4 text-lg md:text-xl focus:outline-none focus:ring-0 border-none bg-transparent font-mono text-primary placeholder:text-primary/20 rounded-none">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-secondary font-label-caps text-sm md:text-base uppercase tracking-[0.2em] py-5 transition-transform duration-150 ease-out active:scale-[0.98] hover:bg-black group">
                        <span class="flex items-center justify-center gap-3">
                            Authorize Transfer
                            <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform duration-300">arrow_forward</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Audit Log (Transaction History) -->
        <div class="lg:col-span-7 xl:col-span-8 p-6 md:p-12 flex flex-col">
            
            <div class="flex justify-between items-end border-b border-primary pb-6 mb-8">
                <h2 class="font-h1 text-3xl md:text-4xl uppercase tracking-tight text-primary">Audit Log</h2>
            </div>

            <div class="flex flex-col w-full">
                @forelse($transactions as $index => $tx)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between py-6 border-b border-primary/10 transition-colors">
                    
                    <!-- Left: TX Info -->
                    <div class="flex flex-col gap-2 mb-3 sm:mb-0">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-sm font-bold uppercase tracking-widest {{ $tx->status === 'success' ? 'text-primary' : 'text-primary/50' }}">
                                {{ $tx->type }}
                            </span>
                            <span class="flex items-center gap-1.5 font-mono text-xs uppercase tracking-widest {{ $tx->status === 'success' ? 'text-green-600' : 'text-primary/50' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $tx->status === 'success' ? 'bg-green-500' : 'bg-primary/30' }}"></span>
                                {{ $tx->status }}
                            </span>
                        </div>
                        <span class="font-body-md text-sm md:text-base text-primary/80">{{ $tx->description }}</span>
                        <span class="font-mono text-xs text-primary/50">{{ $tx->created_at->format('M d, Y H:i') }}</span>
                    </div>

                    <!-- Right: TX Amount -->
                    <div class="flex flex-col sm:items-end">
                        <span class="font-mono text-xl md:text-2xl {{ $tx->amount > 0 ? 'text-primary' : 'text-primary/60' }}">
                            {{ $tx->amount > 0 ? '+' : '' }}${{ number_format(abs($tx->amount), 2) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="py-20 flex flex-col items-center justify-center text-center">
                    <span class="font-body-md text-base text-primary/60">No transaction records found in the audit log.</span>
                </div>
                @endforelse
            </div>

            @if($transactions->hasPages())
                <div class="mt-12 pt-6">
                    {{ $transactions->links() }}
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
