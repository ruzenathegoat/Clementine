@extends('layouts.app')

@section('title', 'Payment Gateway Simulation - Clementine')

@section('content')
<div class="px-lg py-xl max-w-2xl mx-auto w-full min-h-[80vh] flex flex-col items-center justify-center">
    
    <div class="w-full flex flex-col border border-primary bg-background p-8 md:p-12 shadow-[8px_8px_0px_0px_rgba(20,20,20,1)]">
        <div class="flex items-center gap-3 mb-8 pb-8 border-b border-outline-variant">
            <span class="w-2 h-2 bg-copper rounded-full animate-pulse"></span>
            <span class="font-label-caps text-xs uppercase tracking-widest font-bold text-copper">QRIS Sandbox Gateway</span>
        </div>
        
        <div class="text-center mb-10">
            <h1 class="font-h2 text-3xl mb-2">Scan to Pay</h1>
            <p class="font-body-md text-sm text-secondary">Use any supported e-wallet or banking app to scan</p>
        </div>

        <div class="flex flex-col items-center justify-center mb-10">
            <!-- Dummy QR Code placeholder using UI Avatars or a simple CSS pattern -->
            <div class="w-64 h-64 border-4 border-primary bg-white p-4 relative mb-4">
                <div class="w-full h-full border-2 border-dashed border-primary/50 flex flex-col items-center justify-center bg-surface-variant">
                    <span class="material-symbols-outlined text-6xl text-primary/30">qr_code_scanner</span>
                    <span class="font-label-caps text-[10px] uppercase tracking-widest text-primary/50 mt-4 text-center">SIMULATED<br>QR CODE</span>
                </div>
                <!-- Target markers -->
                <div class="absolute top-2 left-2 w-4 h-4 border-t-2 border-l-2 border-primary"></div>
                <div class="absolute top-2 right-2 w-4 h-4 border-t-2 border-r-2 border-primary"></div>
                <div class="absolute bottom-2 left-2 w-4 h-4 border-b-2 border-l-2 border-primary"></div>
                <div class="absolute bottom-2 right-2 w-4 h-4 border-b-2 border-r-2 border-primary"></div>
            </div>
            
            <div class="font-h1 text-4xl tracking-wider text-primary mt-4">
                ${{ number_format($amount, 2) }}
            </div>
            <div class="font-label-caps text-xs tracking-widest uppercase text-on-surface-variant mt-2">
                REF: {{ $reference_id }}
            </div>
        </div>

        <div class="mt-auto border border-primary p-6 bg-surface-container-lowest">
            <div class="flex items-start gap-3 mb-6">
                <span class="material-symbols-outlined text-copper">developer_mode</span>
                <div>
                    <h3 class="font-label-caps text-xs font-bold uppercase tracking-widest text-primary">Developer Actions</h3>
                    <p class="text-[11px] font-body-md text-on-surface-variant mt-1">Simulate successful payment response from gateway.</p>
                </div>
            </div>
            
            <form action="{{ route('dummy.qris.success', ['type' => $type, 'reference_id' => $reference_id]) }}" method="POST">
                @csrf
                <input type="hidden" name="amount" value="{{ $amount }}">
                <button type="submit" class="w-full border border-primary bg-primary text-on-primary font-h2 text-lg py-4 px-6 hover:opacity-90 transition-opacity uppercase tracking-widest">
                    Simulate Payment Success
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
