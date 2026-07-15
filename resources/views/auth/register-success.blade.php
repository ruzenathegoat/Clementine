@extends('layouts.app')

@section('title', 'Welcome to the Club - Clementine')

@section('content')
<div class="w-full min-h-[100dvh] flex flex-col md:flex-row bg-[#FBFBFA] relative overflow-hidden">
    
    <!-- Confetti Canvas (Fixed to window) -->
    <canvas id="confetti-canvas" class="fixed inset-0 w-full h-full pointer-events-none z-[100]"></canvas>

    <!-- Left Side: Editorial Typography -->
    <div class="w-full md:w-1/2 flex flex-col justify-center px-6 md:px-16 py-24 md:py-40 z-10">
        <div class="gsap-reveal opacity-0 translate-y-12">
            <!-- Micro Eyebrow Tag -->
            <div class="inline-flex items-center px-3 py-1 mb-8 rounded-full border border-[#EAEAEA] bg-white text-[10px] font-bold uppercase tracking-[0.2em] text-[#787774]">
                Status: Verified
            </div>
        </div>

        <h1 class="gsap-reveal opacity-0 translate-y-12 font-h1 text-[60px] md:text-[80px] lg:text-[110px] leading-[0.85] tracking-tighter uppercase text-[#111111] mb-8">
            WELCOME<br>TO THE<br>CLUB.
        </h1>
        
        <p class="gsap-reveal opacity-0 translate-y-12 font-body-md text-base md:text-lg text-[#2F3437] max-w-md leading-[1.6]">
            Your identity has been secured. You now have privileged access to our curated collections, exclusive drops, and your personal horology hub.
        </p>
    </div>

    <!-- Right Side: The Z-Axis Cascade & Double Bezel Card -->
    <div class="w-full md:w-1/2 flex items-center justify-center p-6 md:p-16 z-10 bg-white md:bg-transparent relative">
        
        <!-- Outer Shell (Double-Bezel) -->
        <div class="gsap-card opacity-0 translate-y-16 rotate-0 md:-rotate-2 w-full max-w-[420px] rounded-[2rem] p-2 border border-[#EAEAEA] bg-[#F9F9F8] shadow-[0_4px_24px_rgba(0,0,0,0.02)]">
            <!-- Inner Core -->
            <div class="rounded-[calc(2rem-8px)] bg-white border border-[#EAEAEA] p-8 md:p-10 flex flex-col relative overflow-hidden shadow-[inset_0_1px_1px_rgba(255,255,255,0.8)]">
                
                <!-- Watermark -->
                <div class="absolute -right-8 -top-8 text-[#F9F9F8] text-[120px] font-h1 opacity-50 pointer-events-none select-none">
                    C
                </div>

                <div class="font-mono text-xs text-[#787774] mb-10 uppercase tracking-wider">
                    Access Pass // {{ now()->format('y.m.d') }}
                </div>

                <div class="flex flex-col gap-6 mb-12">
                    <div class="flex flex-col border-b border-[#EAEAEA] pb-4">
                        <span class="font-mono text-[10px] text-[#787774] uppercase tracking-widest mb-1">User</span>
                        <span class="font-body-md text-sm font-bold text-[#111111] truncate">{{ auth()->user()->name ?? 'NEW MEMBER' }}</span>
                    </div>
                    <div class="flex flex-col border-b border-[#EAEAEA] pb-4">
                        <span class="font-mono text-[10px] text-[#787774] uppercase tracking-widest mb-1">Clearance</span>
                        <span class="font-body-md text-sm font-bold text-[#111111]">LEVEL 1 / MEMBER</span>
                    </div>
                </div>

                <!-- Magnetic Button-in-Button -->
                <a href="{{ route('home') }}" class="group relative w-full flex items-center justify-between rounded-full bg-[#111111] text-white pl-6 pr-2 py-2 transition-all duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] active:scale-[0.98] hover:bg-[#222222]">
                    <span class="font-body-md text-xs font-bold uppercase tracking-widest">Enter Hub</span>
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-1 group-hover:-translate-y-[1px] group-hover:scale-105">
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </div>
                </a>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- 1. GSAP Staggered Reveal Animation ---
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

            // Card cascade (Flat on mobile, rotated on desktop)
            tl.to('.gsap-card', {
                y: 0,
                opacity: 1,
                rotation: window.innerWidth > 768 ? -2 : 0, 
                duration: 1.4,
                ease: "power3.out"
            }, "-=0.8");
        }

        // --- 2. Premium Confetti Trigger ---
        const myCanvas = document.getElementById('confetti-canvas');
        if (myCanvas && typeof confetti !== 'undefined') {
            const myConfetti = confetti.create(myCanvas, {
                resize: true,
                useWorker: true
            });

            // Brand Colors: Ink, Copper, Warm Bone, White
            const clementineColors = ['#111111', '#D97757', '#FBFBFA', '#EAEAEA'];
            
            // Fire a dramatic bottom-up burst after GSAP delay
            setTimeout(() => {
                myConfetti({
                    particleCount: 160,
                    spread: 90,
                    origin: { y: 1 }, // Fire from the very bottom edge
                    startVelocity: 70,
                    colors: clementineColors,
                    zIndex: 100,
                    disableForReducedMotion: true
                });
                
                // Second smaller burst for depth layering
                setTimeout(() => {
                    myConfetti({
                        particleCount: 80,
                        spread: 130,
                        origin: { y: 1 },
                        startVelocity: 50,
                        colors: clementineColors,
                        zIndex: 100
                    });
                }, 300);

            }, 800); // Matches the GSAP card entrance timeline
        }
    });
</script>
@endsection
