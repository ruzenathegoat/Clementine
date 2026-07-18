@extends('layouts.app')

@section('title', 'Verify Your Identity - Clementine')

@section('content')
<div class="w-full min-h-[100dvh] flex flex-col md:flex-row bg-[#FBFBFA] relative overflow-hidden">
    
    <!-- Left Side: Editorial Typography -->
    <div class="w-full md:w-1/2 flex flex-col justify-center px-6 md:px-16 py-24 md:py-40 z-10">
        <div class="gsap-reveal opacity-0 translate-y-12">
            <!-- Micro Eyebrow Tag -->
            <div class="inline-flex items-center px-3 py-1 mb-8 rounded-full border border-[#EAEAEA] bg-white text-[10px] font-bold uppercase tracking-[0.2em] text-[#787774]">
                Status: Pending Verification
            </div>
        </div>

        <h1 class="gsap-reveal opacity-0 translate-y-12 font-h1 text-[60px] md:text-[80px] lg:text-[110px] leading-[0.85] tracking-tighter uppercase text-[#111111] mb-8">
            VERIFY<br>YOUR<br>IDENTITY.
        </h1>
        
        <p class="gsap-reveal opacity-0 translate-y-12 font-body-md text-base md:text-lg text-[#2F3437] max-w-md leading-[1.6]">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
        </p>
    </div>

    <!-- Right Side: The Form & Actions -->
    <div class="w-full md:w-1/2 flex items-center justify-center p-6 md:p-16 z-10 bg-white md:bg-transparent relative">
        
        <!-- Outer Shell -->
        <div class="gsap-card opacity-0 translate-y-16 rotate-0 md:-rotate-2 w-full max-w-[420px] rounded-[2rem] p-2 border border-[#EAEAEA] bg-[#F9F9F8] shadow-[0_4px_24px_rgba(0,0,0,0.02)]">
            <!-- Inner Core -->
            <div class="rounded-[calc(2rem-8px)] bg-white border border-[#EAEAEA] p-8 md:p-10 flex flex-col relative overflow-hidden shadow-[inset_0_1px_1px_rgba(255,255,255,0.8)]">
                
                <!-- Watermark -->
                <div class="absolute -right-8 -top-8 text-[#F9F9F8] text-[120px] font-h1 opacity-50 pointer-events-none select-none">
                    ?
                </div>

                <div class="font-mono text-xs text-[#787774] mb-10 uppercase tracking-wider">
                    Security Check // {{ now()->format('y.m.d') }}
                </div>

                @if (session('success'))
                    <div class="mb-8 p-4 bg-[#F9F9F8] border border-[#EAEAEA] font-body-md text-sm text-[#111111] font-bold">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex flex-col gap-6 mb-12">
                    <div class="flex flex-col border-b border-[#EAEAEA] pb-4">
                        <span class="font-mono text-[10px] text-[#787774] uppercase tracking-widest mb-1">Email Sent To</span>
                        <span class="font-body-md text-sm font-bold text-[#111111] truncate">{{ auth()->user()->email ?? 'NEW MEMBER' }}</span>
                    </div>
                </div>

                <!-- Resend Button -->
                <form method="POST" action="{{ route('verification.send') }}" class="w-full mb-4">
                    @csrf
                    <button type="submit" class="group relative w-full flex items-center justify-between rounded-full bg-[#111111] text-white pl-6 pr-2 py-2 transition-all duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] active:scale-[0.98] hover:bg-[#222222]">
                        <span class="font-body-md text-xs font-bold uppercase tracking-widest">Resend Email</span>
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-1 group-hover:-translate-y-[1px] group-hover:scale-105">
                            <span class="material-symbols-outlined text-[18px]">send</span>
                        </div>
                    </button>
                </form>
                
                <!-- Logout Option -->
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-2 font-mono text-[10px] uppercase tracking-widest text-[#787774] hover:text-[#111111] transition-colors underline">
                        Sign Out
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- GSAP Staggered Reveal Animation ---
        if (typeof gsap !== 'undefined') {
            const tl = gsap.timeline();
            
            // Text reveal
            tl.to('.gsap-reveal', {
                y: 0,
                opacity: 1,
                duration: 1.2,
                stagger: 0.15,
                ease: "power4.out",
                delay: 0.1
            });

            // Card cascade
            tl.to('.gsap-card', {
                y: 0,
                opacity: 1,
                rotation: window.innerWidth > 768 ? -2 : 0, 
                duration: 1.4,
                ease: "power3.out"
            }, "-=0.8");
        }

        // --- Auto-Refresh Polling ---
        // Poll the server every 3 seconds to check if the user verified on another device.
        setInterval(() => {
            fetch('{{ route('verification.check') }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.verified) {
                    // Refresh the page so Laravel redirects to dashboard
                    window.location.reload();
                }
            })
            .catch(error => console.error('Polling error:', error));
        }, 3000);
    });
</script>
@endsection
