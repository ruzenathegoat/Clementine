@extends('layouts.app')

@section('title', '429 Too Many Requests - Clementine')

@section('content')
<div class="px-lg py-xl max-w-6xl mx-auto w-full min-h-[80vh] flex items-center justify-center">
    <div class="w-full flex flex-col md:flex-row border border-primary bg-background">
        
        <!-- Left Side: Branding -->
        <div class="w-full md:w-1/2 bg-primary text-on-primary p-xl md:p-[60px] flex flex-col justify-between relative overflow-hidden min-h-[400px]">
            <div class="relative z-10 leading-none text-blue-600">
                <x-logo class="w-16 h-16" />
            </div>
            <div class="relative z-10 mt-auto pt-[100px]">
                <p class="font-label-caps text-xs md:text-sm mb-4 tracking-widest text-blue-600">SECURITY LOCKOUT</p>
                <h2 class="font-h1 text-[40px] md:text-[60px] leading-[0.9] tracking-tighter uppercase">
                    Too many attempts.
                </h2>
            </div>
        </div>

        <!-- Right Side: Message -->
        <div class="w-full md:w-1/2 p-xl md:p-[60px] flex flex-col justify-center items-center text-center">
            <span class="material-symbols-outlined text-[60px] mb-4 text-blue-600">gpp_bad</span>
            <h1 class="font-h1 text-4xl mb-4 uppercase tracking-tight">Access Suspended</h1>
            <p class="font-body-md text-sm text-secondary mb-xl">For your security, we have temporarily blocked this IP address due to excessive login attempts. Please wait 5 minutes before trying again.</p>
            
            <a href="{{ url('/') }}" class="w-full bg-primary text-on-primary font-h2 text-xl py-4 px-xl border border-primary hover:bg-background hover:text-primary transition-colors uppercase mt-2 block">
                RETURN TO HOME
            </a>
        </div>
        
    </div>
</div>
@endsection
