@extends('layouts.app')

@section('title', 'Clementpay - Clementine')

@section('content')
<div class="px-lg py-xl max-w-4xl mx-auto w-full min-h-[80vh]">
    <div class="flex flex-col gap-12">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 border-b border-primary pb-8">
            <div class="flex flex-col gap-2">
                <h1 class="font-h1 text-4xl uppercase tracking-widest">Clementpay</h1>
                <p class="font-body-md text-sm text-secondary">Manage your Clementine digital currency and view transaction history.</p>
            </div>
            <div class="flex flex-col items-end gap-1 text-right">
                <span class="font-label-caps text-[10px] uppercase tracking-widest text-on-surface-variant">Available Balance</span>
                <span class="font-h2 text-3xl md:text-4xl text-primary">${{ number_format(auth()->user()->clementpay_balance, 2) }}</span>
            </div>
        </div>

        <!-- Top-up Section -->
        <div class="bg-surface border border-outline-variant p-8 flex flex-col md:flex-row gap-8 justify-between items-center">
            <div class="flex flex-col gap-2 flex-1">
                <h2 class="font-h2 text-xl uppercase tracking-widest">Top Up Balance</h2>
                <p class="font-body-md text-sm text-on-surface-variant">Add funds to your Clementpay wallet securely via QRIS or Virtual Account.</p>
            </div>
            
            <form action="{{ route('clementpay.topup') }}" method="POST" class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                @csrf
                <div class="flex flex-col gap-1">
                    <label for="amount" class="sr-only">Top Up Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant font-body-md">$</span>
                        <input type="number" name="amount" id="amount" min="100" step="1" required placeholder="100.00" class="w-full pl-8 p-3 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white font-body-md">
                    </div>
                </div>
                <button type="submit" class="bg-primary text-white font-label-caps text-xs font-bold uppercase tracking-widest px-8 py-3 hover:opacity-80 transition-opacity border border-primary">
                    Top Up
                </button>
            </form>
        </div>

        <!-- Transactions History -->
        <div class="flex flex-col gap-6">
            <h2 class="font-label-caps text-sm uppercase tracking-widest font-bold">Transaction History</h2>
            
            <div class="border border-outline-variant bg-surface-container-lowest">
                @forelse($transactions as $tx)
                <div class="flex items-center justify-between p-6 border-b border-outline-variant last:border-b-0">
                    <div class="flex flex-col gap-1">
                        <span class="font-label-caps text-xs font-bold uppercase tracking-widest text-primary">{{ $tx->type }}</span>
                        <span class="font-body-md text-xs text-on-surface-variant">{{ $tx->description }}</span>
                        <span class="font-body-md text-[10px] text-secondary mt-1">{{ $tx->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="flex flex-col items-end gap-1 text-right">
                        <span class="font-headline-md text-lg font-bold {{ $tx->amount > 0 ? 'text-primary' : 'text-red-500' }}">
                            {{ $tx->amount > 0 ? '+' : '' }}${{ number_format(abs($tx->amount), 2) }}
                        </span>
                        <span class="font-label-caps text-[10px] uppercase tracking-widest {{ $tx->status === 'success' ? 'text-green-600' : 'text-secondary' }}">{{ $tx->status }}</span>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-sm font-body-md text-on-surface-variant">
                    No transactions found.
                </div>
                @endforelse
            </div>
            
            @if($transactions->hasPages())
                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
