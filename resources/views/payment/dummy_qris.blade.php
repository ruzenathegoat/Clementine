@extends('layouts.app')

@section('title', 'Payment Gateway Simulation - Clementine')

@section('content')
<div class="px-lg py-xl max-w-6xl mx-auto w-full min-h-[80vh] flex flex-col items-center justify-center">
    
    <div class="w-full flex flex-col md:flex-row border border-primary bg-background shadow-sm">
        
        <!-- Left Side: Instructions & QR Code -->
        <div class="w-full md:w-3/5 p-xl md:p-[60px] flex flex-col justify-center border-b md:border-b-0 md:border-r border-primary bg-surface-container-lowest">
            
            <div class="flex items-center gap-3 mb-8">
                <span class="w-2 h-2 bg-copper rounded-full animate-pulse"></span>
                <span class="font-label-caps text-xs uppercase tracking-widest font-bold text-copper">QRIS Sandbox Gateway</span>
            </div>
            
            <h1 class="font-h1 text-4xl mb-2 uppercase tracking-tight">Scan to Pay</h1>
            <p class="font-body-md text-sm text-secondary mb-12">Use any supported e-wallet or banking app to scan the QR code below.</p>

            <div class="flex flex-col items-center justify-center mb-12"
                 x-data="{ 
                    time: 900,
                    get formattedTime() {
                        let minutes = Math.floor(this.time / 60);
                        let seconds = this.time % 60;
                        return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    }
                 }"
                 x-init="setInterval(() => { if (time > 0) time--; }, 1000)">
                <!-- Clean Minimalist QR Code Box -->
                <div class="w-64 h-64 border border-outline-variant bg-white p-4 relative mb-6 relative group">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=CLEMENTINE-QRIS-{{ $reference_id }}" alt="QR Code" class="w-full h-full object-contain">
                    <div class="absolute inset-0 bg-surface/80 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                        <span class="font-label-caps text-xs font-bold uppercase tracking-widest">Sandbox Only</span>
                    </div>
                </div>
                
                <div class="flex flex-col items-center gap-1">
                    <span class="font-label-caps text-[10px] uppercase tracking-widest text-on-surface-variant">QR Code Valid For</span>
                    <span class="font-mono text-xl text-primary font-bold" x-text="formattedTime">15:00</span>
                </div>
            </div>

            <div class="mt-auto grid grid-cols-2 gap-8 border-t border-outline-variant pt-8">
                <div>
                    <p class="font-label-caps text-[10px] uppercase tracking-widest text-on-surface-variant mb-2">Target</p>
                    <p class="font-headline-md text-lg font-bold">{{ $type }}</p>
                </div>
                <div>
                    <p class="font-label-caps text-[10px] uppercase tracking-widest text-on-surface-variant mb-2">Reference ID</p>
                    <p class="font-headline-md text-lg font-bold truncate" title="{{ $reference_id }}">{{ substr($reference_id, 0, 16) }}...</p>
                </div>
            </div>

        </div>

        <!-- Right Side: Developer Actions & Amount -->
        <div class="w-full md:w-2/5 p-xl md:p-[40px] flex flex-col bg-surface-container-lowest justify-between">
            
            <div>
                <h2 class="font-label-caps text-sm uppercase tracking-widest font-bold mb-8 text-on-surface-variant">Payment Details</h2>
                
                <div class="flex flex-col gap-2 mb-8">
                    <span class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Total Amount</span>
                    <div class="font-h1 text-4xl md:text-5xl text-primary tracking-tighter">
                        ${{ number_format($amount, 2) }}
                    </div>
                </div>
                
                <div class="border-l-2 border-primary/20 pl-4 py-2 mb-8">
                    <p class="font-body-md text-sm text-secondary italic">
                        Please ensure the exact amount is paid. This page will automatically update once the transaction is completed.
                    </p>
                </div>
            </div>

            <!-- Developer Simulation Box -->
            <div class="mt-auto border border-primary p-6 bg-background group hover:border-copper transition-colors">
                <div class="flex items-start gap-3 mb-6">
                    <span class="material-symbols-outlined text-copper">terminal</span>
                    <div>
                        <h3 class="font-label-caps text-xs font-bold uppercase tracking-widest text-primary group-hover:text-copper transition-colors">Developer Actions</h3>
                        <p class="text-[11px] font-body-md text-on-surface-variant mt-1">Simulate successful payment response from gateway.</p>
                    </div>
                </div>
                
                <form action="{{ route('dummy.qris.success', ['type' => $type, 'reference_id' => $reference_id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="amount" value="{{ $amount }}">
                    <button type="submit" class="w-full border border-primary bg-primary text-on-primary font-h2 text-lg py-4 px-6 hover:opacity-90 hover:shadow-md transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                        Trigger Success
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </form>
            </div>
            
        </div>
        
    </div>
</div>
@endsection
