@extends('layouts.app')

@section('title', 'Payment Gateway Simulation - Clementine')

@section('content')
<div class="w-full min-h-screen bg-surface-container-lowest flex items-center justify-center py-12 px-4 md:px-12 font-mono">
    
    <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 border-2 border-black bg-white shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">
        
        <!-- Left Panel: Info & Target -->
        <div class="border-b-2 md:border-b-0 md:border-r-2 border-black p-8 md:p-12 flex flex-col justify-between">
            
            <div>
                <div class="flex items-center justify-between border-b-2 border-black pb-4 mb-8">
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] bg-black text-white px-3 py-1">Sandbox_Env</span>
                    <span class="w-3 h-3 bg-red-600 rounded-full animate-pulse border-2 border-black"></span>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter leading-none mb-4">
                    QRIS<br>Terminal
                </h1>
                
                <p class="text-sm border-l-4 border-black pl-4 py-1 mb-12">
                    AWAITING PAYMENT CONFIRMATION.<br>
                    SCAN USING ANY SUPPORTED TERMINAL.
                </p>

                <div class="grid grid-cols-2 gap-4 border-t-2 border-black pt-4 text-xs font-bold uppercase tracking-wider">
                    <div class="flex flex-col gap-1">
                        <span class="text-gray-500">Target</span>
                        <span>{{ $type }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-gray-500">Reference ID</span>
                        <span class="truncate" title="{{ $reference_id }}">{{ substr($reference_id, 0, 12) }}...</span>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t-2 border-black">
                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total Requested</span>
                <div class="text-4xl md:text-5xl font-black tracking-tighter mt-1">
                    ${{ number_format($amount, 2) }}
                </div>
            </div>
            
        </div>

        <!-- Right Panel: QR Display & Simulator -->
        <div class="p-8 md:p-12 flex flex-col items-center justify-center bg-[#F4F4F4]">
            
            <div class="w-full max-w-[320px] aspect-square border-4 border-black bg-white p-6 relative mb-12 flex items-center justify-center">
                <!-- Decorative Corner Markers -->
                <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-black"></div>
                <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-black"></div>
                <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-black"></div>
                <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-black"></div>

                <div class="w-full h-full border-2 border-dashed border-black/30 flex flex-col items-center justify-center bg-gray-100">
                    <span class="material-symbols-outlined text-6xl text-black/50 mb-2">qr_code_scanner</span>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-black/50 text-center">
                        SIMULATED_QR<br>[NOT_SCANNABLE]
                    </span>
                </div>
            </div>

            <div class="w-full">
                <div class="bg-black text-white p-4 text-[10px] font-bold uppercase tracking-[0.2em] flex items-center justify-between mb-2">
                    <span>Developer Actions</span>
                    <span class="material-symbols-outlined text-[14px]">terminal</span>
                </div>
                <form action="{{ route('dummy.qris.success', ['type' => $type, 'reference_id' => $reference_id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="amount" value="{{ $amount }}">
                    <button type="submit" class="w-full border-2 border-black bg-white text-black font-black text-lg py-4 px-6 hover:bg-black hover:text-white transition-all uppercase tracking-widest active:translate-y-1">
                        Trigger Success
                    </button>
                </form>
            </div>

        </div>
        
    </div>
</div>
@endsection
