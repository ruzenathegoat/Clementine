@extends('layouts.app')

@section('title', 'Collections - Clementine')

@push('styles')
<style>
    /* 
     * Emil Design Engineering & Impeccable CSS Variables
     */
    :root {
        --ease-out: cubic-bezier(0.23, 1, 0.32, 1);
        --ease-in-out: cubic-bezier(0.77, 0, 0.175, 1);
        --bg-color: #FAFAFA;
        --ink-color: #0A0A0A;
    }

    body {
        background-color: var(--bg-color);
        color: var(--ink-color);
        /* Hide scrollbar for a seamless, app-like archive feel */
        scrollbar-width: none;
    }
    body::-webkit-scrollbar {
        display: none;
    }

    /* Swiss Grid Borders */
    .archive-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        border-top: 1px solid rgba(10, 10, 10, 0.1);
        border-left: 1px solid rgba(10, 10, 10, 0.1);
    }
    
    .archive-panel {
        border-right: 1px solid rgba(10, 10, 10, 0.1);
        border-bottom: 1px solid rgba(10, 10, 10, 0.1);
        cursor: crosshair;
        background-color: var(--bg-color);
    }

    /* Typography fixes per Impeccable (no tighter than -0.04em, balance/pretty) */
    .title-display {
        letter-spacing: -0.04em;
        text-wrap: balance;
    }
    .text-pretty {
        text-wrap: pretty;
    }

    /* Motion & Reveal */
    .clip-reveal {
        clip-path: inset(100% 0 0 0);
        transition: clip-path 250ms var(--ease-out), transform 250ms var(--ease-out);
        will-change: clip-path, transform;
    }
    .archive-panel:hover .clip-reveal {
        clip-path: inset(0 0 0 0);
        transform: translateY(-8px);
    }

    .base-img {
        transition: opacity 250ms var(--ease-out);
    }
    .archive-panel:hover .base-img {
        opacity: 0; /* Crossfade to sharp via opacity to avoid dual-image overlap */
    }

    /* Button/Interactive element press state (Emil) */
    .pressable {
        transition: transform 160ms var(--ease-out);
    }
    .pressable:active {
        transform: scale(0.97);
    }

    /* Hover arrow slide */
    .hover-arrow {
        transition: transform 250ms var(--ease-out);
    }
    .archive-panel:hover .hover-arrow {
        transform: translateX(6px);
    }
    
    /* Background giant text */
    .bg-giant-text {
        transition: opacity 250ms var(--ease-out), transform 250ms var(--ease-out);
    }
    .archive-panel:hover .bg-giant-text {
        opacity: 0.04;
        transform: translateY(-8px);
    }
    
    /* View Transition */
    @media (prefers-reduced-motion: no-preference) {
        .archive-panel {
            view-transition-name: panel-container;
        }
    }
</style>
@endpush

@section('content')

<!-- HERO SECTION: Stark, pure typography, no AI scaffolding metadata -->
<header class="w-full min-h-[70vh] flex flex-col justify-end px-6 md:px-12 py-16 md:py-24 relative overflow-hidden">
    <div class="max-w-[1400px] w-full mx-auto relative z-10 flex flex-col gap-6">
        <h1 class="font-h1 text-[clamp(60px,12vw,160px)] leading-[0.85] text-ink-color uppercase title-display m-0 overflow-hidden" id="hero-title">
            Collections
        </h1>
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-8 mt-4 overflow-hidden">
            <p class="font-body-md text-base md:text-lg text-[#555] max-w-[65ch] text-pretty m-0" id="hero-desc">
                The complete archive of our mechanical artifacts. Each collection represents a distinct engineering philosophy, preserved in uncompromising detail.
            </p>
            
            <span class="font-mono text-xs tracking-wider text-[#1A1A1A] px-4 py-2 border border-[#1A1A1A]" id="hero-count">
                {{ str_pad($collections->count(), 2, '0', STR_PAD_LEFT) }} ARCHIVES
            </span>
        </div>
    </div>
</header>

<!-- ARCHIVE GRID -->
<section class="w-full max-w-[1800px] mx-auto px-6 md:px-12 pb-24">
    <div class="archive-grid">
        @forelse($collections as $index => $collection)
            <a href="{{ route('collections.show', $collection->slug) }}" 
               class="archive-panel group relative flex flex-col justify-between h-[480px] md:h-[600px] overflow-hidden pressable">
                
                <!-- Background Giant Text -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0 overflow-hidden">
                    <span class="font-h1 text-[12vw] md:text-[140px] uppercase text-[#1A1A1A] opacity-[0.015] whitespace-nowrap title-display bg-giant-text">
                        {{ $collection->name }}
                    </span>
                </div>

                <!-- Product Preview Image -->
                <div class="absolute inset-0 z-0 pointer-events-none flex items-center justify-center p-8 md:p-16">
                    <div class="w-full h-full relative">
                        <!-- Idle State: Muted -->
                        <img src="{{ $collection->image_url ?? 'https://picsum.photos/seed/watch/800/600' }}" 
                             class="absolute inset-0 w-full h-full object-contain grayscale-[60%] brightness-[0.8] contrast-[0.9] base-img" 
                             alt="{{ $collection->name }}">
                        
                        <!-- Hover State: Sharp Reveal via Clip-Path -->
                        <img src="{{ $collection->image_url ?? 'https://picsum.photos/seed/watch/800/600' }}" 
                             class="absolute inset-0 w-full h-full object-contain clip-reveal" 
                             style="view-transition-name: img-{{ $collection->id }};"
                             alt="{{ $collection->name }} Detail">
                    </div>
                </div>

                <!-- Top Row -->
                <div class="relative z-10 flex justify-between items-start p-6">
                    <span class="font-mono text-[10px] tracking-widest text-[#1A1A1A]">
                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <div class="w-8 h-8 flex items-center justify-center border border-transparent group-hover:border-[#1A1A1A] rounded-full transition-colors duration-250 hover-arrow">
                        <span class="material-symbols-outlined text-[16px] text-[#1A1A1A]">arrow_forward</span>
                    </div>
                </div>

                <!-- Bottom Row -->
                <div class="relative z-10 p-6 flex flex-col justify-end">
                    <h2 class="font-h1 text-3xl md:text-4xl uppercase title-display text-[#1A1A1A] m-0" style="view-transition-name: title-{{ $collection->id }};">
                        {{ $collection->name }}
                    </h2>
                    
                    <!-- Description fades and slides up on hover -->
                    <div class="grid grid-rows-[0fr] group-hover:grid-rows-[1fr] transition-[grid-template-rows] duration-250 var(--ease-out)">
                        <div class="overflow-hidden">
                            <p class="font-body-md text-sm text-[#555] text-pretty mt-3 mb-0">
                                {{ Str::limit($collection->description ?? 'Explore the signature timepieces within this exclusive mechanical collection.', 90) }}
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full h-[40vh] flex items-center justify-center font-mono text-sm tracking-widest text-[#555]">
                No collections available in the archive.
            </div>
        @endforelse
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        if (!prefersReducedMotion) {
            // Register ScrollTrigger if available (assuming GSAP is loaded globally via app layout)
            if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);

                // Hero Entrance Animation
                // Split title text manually to avoid SplitText premium requirement
                const heroTitle = document.getElementById('hero-title');
                if (heroTitle) {
                    const text = heroTitle.innerText.trim();
                    heroTitle.innerHTML = '';
                    const chars = text.split('').map(char => {
                        const span = document.createElement('span');
                        span.innerText = char;
                        span.style.display = 'inline-block';
                        span.style.transform = 'translateY(100%)';
                        span.style.opacity = '0';
                        heroTitle.appendChild(span);
                        return span;
                    });

                    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
                    
                    // Stagger character reveal
                    tl.to(chars, {
                        y: '0%',
                        opacity: 1,
                        duration: 0.8,
                        stagger: 0.03,
                        delay: 0.1
                    });

                    // Fade in description and count
                    tl.fromTo(['#hero-desc', '#hero-count'], 
                        { y: 20, opacity: 0 },
                        { y: 0, opacity: 1, duration: 0.8, stagger: 0.1 },
                        "-=0.4"
                    );
                }

                // Grid Stagger Animation on Scroll
                const panels = gsap.utils.toArray('.archive-panel');
                
                // Set initial state
                gsap.set(panels, { opacity: 0, y: 30 });

                ScrollTrigger.batch(panels, {
                    start: "top 85%",
                    onEnter: batch => {
                        gsap.to(batch, {
                            opacity: 1,
                            y: 0,
                            duration: 0.6,
                            stagger: 0.1,
                            ease: 'power2.out',
                            overwrite: true
                        });
                    },
                    once: true
                });
            }
        } else {
            // Reduced motion fallbacks
            gsap.set('.archive-panel', { opacity: 1, y: 0 });
        }
        
        // Mouse Parallax for Archive Panels (Subtle, QuickTo for performance)
        if (window.matchMedia('(pointer: fine)').matches && !prefersReducedMotion && typeof gsap !== 'undefined') {
            const panels = document.querySelectorAll('.archive-panel');
            
            panels.forEach(panel => {
                // We use quickTo for high-performance follow without artificial tween durations.
                const xTo = gsap.quickTo(panel, "rotateY", { duration: 0.4, ease: "power2.out" });
                const yTo = gsap.quickTo(panel, "rotateX", { duration: 0.4, ease: "power2.out" });
                
                const imgToX = gsap.quickTo(panel.querySelector('.clip-reveal'), "x", { duration: 0.4, ease: "power2.out" });
                const imgToY = gsap.quickTo(panel.querySelector('.clip-reveal'), "y", { duration: 0.4, ease: "power2.out" });

                panel.addEventListener('mousemove', (e) => {
                    const rect = panel.getBoundingClientRect();
                    const relX = (e.clientX - rect.left) / rect.width; // 0 to 1
                    const relY = (e.clientY - rect.top) / rect.height; // 0 to 1
                    
                    const rotateY = (relX - 0.5) * 4; // -2 to 2 deg
                    const rotateX = (relY - 0.5) * -2; // -1 to 1 deg
                    
                    gsap.set(panel, { transformPerspective: 1000 });
                    xTo(rotateY);
                    yTo(rotateX);
                    
                    if (panel.querySelector('.clip-reveal')) {
                        imgToX((relX - 0.5) * 12);
                        imgToY(((relY - 0.5) * 12) - 8); // -8 matches the base translateY hover state
                    }
                });
                
                panel.addEventListener('mouseleave', () => {
                    xTo(0);
                    yTo(0);
                    if (panel.querySelector('.clip-reveal')) {
                        imgToX(0);
                        // Reset to the hover-removed state (GSAP will clear this when hover is active via CSS)
                        imgToY(0); 
                    }
                });
            });
        }
    });
</script>
@endpush
