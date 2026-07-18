@extends('layouts.app')

@section('title', 'Access Blocked - Clementine')

@section('content')
<div class="w-full min-h-[85vh] flex flex-col md:flex-row border-b border-primary">
    
    <!-- Left Column: Big Graphic / Headline -->
    <div class="w-full md:w-1/2 bg-primary text-white p-xl md:p-[80px] flex flex-col justify-between border-b md:border-b-0 md:border-r border-primary">
        <div>
            <div class="font-h1 text-[80px] md:text-[120px] leading-[0.8] tracking-tighter uppercase mb-8">
                ACCESS<br>HALTED
            </div>
            <div class="font-body-md text-sm opacity-60 uppercase tracking-widest mt-8">
                SECURITY MATRIX INTERCEPT
            </div>
        </div>
        
        <div class="mt-xl md:mt-0">
            <p class="font-body-md text-sm opacity-80 uppercase tracking-widest border-t border-white/20 pt-4">
                ERROR CODE: 403-ANOMALY<br>
                STATUS: PENDING VERIFICATION
            </p>
        </div>
    </div>

    <!-- Right Column: Details & Action -->
    <div class="w-full md:w-1/2 flex flex-col bg-background">
        
        <!-- Top Half: Explanation -->
        <div class="p-xl md:p-[80px] flex-grow flex flex-col justify-center border-b border-primary relative overflow-hidden">
            <!-- Decorative watermark -->
            <div class="absolute -right-10 -bottom-10 text-[200px] opacity-[0.03] rotate-12 pointer-events-none font-h1 leading-none select-none">
                !
            </div>
            
            <h2 class="font-h2 text-4xl md:text-5xl uppercase mb-6 relative z-10">Device Anomaly</h2>
            <p class="font-body-md text-sm text-text-secondary leading-relaxed mb-8 max-w-md relative z-10">
                Our system has intercepted a login attempt from an unrecognized location or hardware signature. To preserve the integrity of your collection, this session has been quarantined.
            </p>
            
            <div class="grid grid-cols-2 gap-0 border border-primary relative z-10 bg-background">
                <div class="p-4 border-r border-b border-primary bg-surface/50">
                    <span class="block font-mono text-[10px] uppercase tracking-widest text-text-secondary mb-1">Action Required</span>
                    <span class="font-body-md text-sm font-bold uppercase">Email Verification</span>
                </div>
                <div class="p-4 border-b border-primary bg-surface/50">
                    <span class="block font-mono text-[10px] uppercase tracking-widest text-text-secondary mb-1">Time Limit</span>
                    <span class="font-body-md text-sm font-bold uppercase">15 Minutes</span>
                </div>
                <div class="p-6 col-span-2">
                    <span class="block font-mono text-[10px] uppercase tracking-widest text-text-secondary mb-2">Instructions</span>
                    <span class="font-body-md text-sm leading-relaxed">We have dispatched a secure authorization link to your registered email address. Please review it and verify your identity to proceed.</span>
                </div>
            </div>
        </div>
        
        <!-- Bottom Half: Actions -->
        <div class="p-xl md:p-[80px] flex flex-col sm:flex-row gap-4 bg-surface">
            <a href="{{ route('login') }}" class="flex-1 flex justify-center items-center bg-primary text-white font-h2 text-xl py-5 px-8 border border-primary hover:bg-background hover:text-primary transition-colors uppercase text-center">
                Return to Login
            </a>
            <a href="{{ route('home') }}" class="flex-1 flex justify-center items-center bg-background text-primary font-h2 text-xl py-5 px-8 border border-primary hover:bg-primary hover:text-white transition-colors uppercase text-center">
                Go to Home
            </a>
        </div>
    </div>
</div>
@endsection
