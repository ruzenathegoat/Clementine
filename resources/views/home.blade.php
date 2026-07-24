@extends('layouts.app')

@section('title', 'Clementine')

@section('content')
<div class="w-full">
    
    <!-- 1. Hero Section (Canvas Image Sequence) -->
    <div class="relative w-full h-[300vh] border-b border-primary" id="hero-sequence-container">
        <div class="sticky top-0 w-full h-screen overflow-hidden bg-primary flex flex-col justify-center items-center" id="hero-sequence-pinned">
            
            <!-- Canvas for Image Sequence -->
            <canvas id="hero-canvas" class="absolute inset-0 w-full h-full object-cover"></canvas>
            
            <!-- Dark Overlay for Contrast -->
            <div class="absolute inset-0 bg-primary/60 z-10"></div>

            <!-- Typography overlay -->
            <div class="absolute inset-0 z-20 flex flex-col justify-end pb-24 mix-blend-normal pointer-events-none">
                <div class="w-full max-w-7xl mx-auto px-6 md:px-12">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12 items-end">
                        
                        <!-- Headline -->
                        <div class="md:col-span-8 lg:col-span-9">
                            <h1 class="font-h1 text-[clamp(2.5rem,5vw,5.5rem)] leading-[0.95] tracking-tight text-secondary uppercase hero-reveal-text opacity-0" style="text-wrap: balance;">
                                The World's Premier Mechanical Horology Network
                            </h1>
                        </div>

                        <!-- Subheadline & Buttons -->
                        <div class="md:col-span-4 lg:col-span-3 flex flex-col gap-6 md:pb-3">
                            <p class="font-body-md text-secondary/80 text-sm md:text-base leading-relaxed hero-reveal-text opacity-0">
                                Hundreds of curated timepieces now have a tamper-proof provenance trail.
                            </p>
                            
                            <!-- Buttons -->
                            <div class="flex flex-col sm:flex-row md:flex-col gap-3 hero-reveal-btn opacity-0 pointer-events-auto">
                                <a href="{{ route('collections.index') }}" class="bg-secondary text-primary font-label-caps text-xs tracking-widest uppercase px-6 py-4 hover:bg-white transition-colors duration-300 text-center w-full">
                                    EXPLORE COLLECTION
                                </a>
                                <a href="{{ route('profile.index') }}" class="bg-transparent border border-secondary/50 text-secondary font-label-caps text-xs tracking-widest uppercase px-6 py-4 hover:bg-secondary/10 transition-colors duration-300 text-center w-full">
                                    JOIN THE CLUB
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- 2. Brand Story Section -->
    <div id="brand-story-section" class="w-full bg-primary text-on-primary py-[150px] md:py-[250px] px-6 md:px-12 flex flex-col items-center justify-center border-b border-primary relative overflow-hidden">
        <div class="w-full max-w-[850px] flex flex-col items-start mx-auto">
            
            <!-- Section Header -->
            <div class="w-full mb-16 md:mb-24">
                <div class="w-full h-[1px] bg-secondary/30 mb-6 story-divider origin-left" style="transform: scaleX(0);"></div>
                <h2 class="font-mono text-[10px] md:text-[12px] uppercase text-secondary/80 story-heading" style="opacity: 0; letter-spacing: 0.12em;">
                    HOROLOGY CULTURE
                </h2>
            </div>
            
            <!-- Paragraph 1 -->
            <div class="story-p1 font-h1 text-[22px] sm:text-[26px] md:text-[32px] lg:text-[40px] leading-[1.4] text-left mb-24 md:mb-32">
                Horology comes down to this: gears, springs, and jewels arranged with enough precision to track something as slippery as time, no battery required. People have been obsessing over how to do that better for about four hundred years. It's a strange thing to love, and we love it anyway.
            </div>

            <!-- Paragraph 2 -->
            <div class="story-p2 font-h1 text-[22px] sm:text-[26px] md:text-[32px] lg:text-[40px] leading-[1.4] text-left">
                Clementine is for the people who'd rather own one watch they actually understand than five they don't. Some of what we carry is old heritage calibers built the way they always were. Some of it is newer, stranger complications from makers still pushing the mechanics forward. What we won't do is stock a watch just because the name on the dial sells itself. If a piece doesn't earn its place on a wrist, it doesn't earn a place with us.
            </div>

        </div>
    </div>

    @if(isset($theDrop) && $theDrop->isNotEmpty())

    <!-- 2.5 THE DROP — Private Allocation Room -->
    <script>
        window.isVip = {{ auth()->user()?->is_vip ? 'true' : 'false' }};
        window.serverTimeOffset = Date.now() - {{ time() * 1000 }};
    </script>

    <section class="w-full bg-black text-white relative" id="drop-section">
        
        @foreach($theDrop as $index => $drop)
        <div class="drop-container relative" data-id="{{ $drop->id }}">
            
            <!-- Pin Wrapper: this gets pinned for ~800px of scroll -->
            <div class="drop-pin-wrapper relative min-h-screen overflow-hidden">
                
                <!-- BG fade target -->
                <div class="absolute inset-0 bg-black z-0 drop-bg" style="opacity: 0;"></div>

                <!-- Title Block -->
                <div class="relative z-10 w-full pt-24 md:pt-32 pb-16 md:pb-24 px-6 md:px-12 lg:px-24">
                    <div class="flex items-baseline gap-3 md:gap-5 overflow-hidden">
                        <span class="drop-title-the font-h1 text-[clamp(3rem,10vw,5.5rem)] leading-none uppercase" style="font-weight: 200; letter-spacing: 0.4em; opacity: 0;">THE</span>
                        <span class="drop-title-drop font-serif italic text-[clamp(3rem,10vw,5.5rem)] leading-none lowercase" style="opacity: 0; clip-path: inset(0 100% 0 0);">drop</span>
                    </div>
                </div>
                
                <!-- Asymmetric Grid: 60/40 -->
                <div class="relative z-10 flex flex-col lg:flex-row w-full" style="min-height: 70vh;">
                    
                    <!-- Left 60%: Product Image -->
                    <div class="w-full lg:w-[60%] relative flex items-center justify-center p-8 md:p-16 lg:p-24">
                        
                        <!-- Museum Spotlight -->
                        <div class="absolute inset-0 z-0 drop-spotlight" style="opacity: 0; background: radial-gradient(ellipse 50% 60% at 50% 20%, rgba(255,255,255,0.06) 0%, transparent 70%);"></div>
                        
                        <!-- Product Image -->
                        <div class="relative z-10 w-full max-w-[560px] mx-auto drop-product-wrapper">
                            @if ($drop->primaryImage)
                            <img src="{{ $drop->primaryImage->url }}" 
                                 alt="{{ $drop->name }}" 
                                 class="drop-product-img w-full h-auto object-contain"
                                 style="opacity: 0; transform: translateY(60px); filter: brightness(0.92) contrast(1.05);" />
                            @else
                            <div class="w-full aspect-square flex items-center justify-center">
                                <span class="font-mono text-white/30 text-xs uppercase tracking-[0.2em]">NO VISUAL DATA</span>
                            </div>
                            @endif

                            <!-- Authentication Scan Beam -->
                            <div class="absolute inset-0 pointer-events-none z-20">
                                <div class="drop-scan-beam absolute left-0 right-0 h-[1px] bg-white/60" style="top: -10%; opacity: 0;"></div>
                            </div>
                            
                            <!-- HUD Labels (Authentication) -->
                            <div class="absolute inset-0 pointer-events-none z-30">
                                <span class="drop-hud-label absolute top-[8%] right-[5%] font-mono text-[9px] uppercase tracking-[0.25em] text-white/0">VERIFIED</span>
                                <span class="drop-hud-label absolute top-[35%] left-[3%] font-mono text-[9px] uppercase tracking-[0.25em] text-white/0">MOVEMENT VERIFIED</span>
                                <span class="drop-hud-label absolute bottom-[30%] right-[8%] font-mono text-[9px] uppercase tracking-[0.25em] text-white/0">AUTHENTIC</span>
                                <span class="drop-hud-label absolute bottom-[8%] left-[5%] font-mono text-[9px] uppercase tracking-[0.25em] text-white/0">DROP READY</span>
                            </div>

                            <!-- Technical Callout Labels -->
                            <div class="absolute inset-0 pointer-events-none z-30">
                                <div class="drop-callout absolute top-[18%] left-[-15%] flex items-center gap-3" style="opacity: 0;">
                                    <span class="font-mono text-[9px] uppercase tracking-[0.2em] text-white/70 whitespace-nowrap">Carbon TPT Case</span>
                                    <div class="w-12 h-[1px] bg-white/30"></div>
                                </div>
                                <div class="drop-callout absolute top-[42%] right-[-12%] flex items-center gap-3 flex-row-reverse" style="opacity: 0;">
                                    <span class="font-mono text-[9px] uppercase tracking-[0.2em] text-white/70 whitespace-nowrap">Tourbillon</span>
                                    <div class="w-12 h-[1px] bg-white/30"></div>
                                </div>
                                <div class="drop-callout absolute bottom-[35%] left-[-10%] flex items-center gap-3" style="opacity: 0;">
                                    <span class="font-mono text-[9px] uppercase tracking-[0.2em] text-white/70 whitespace-nowrap">Titanium Bridge</span>
                                    <div class="w-12 h-[1px] bg-white/30"></div>
                                </div>
                                <div class="drop-callout absolute bottom-[15%] right-[-8%] flex items-center gap-3 flex-row-reverse" style="opacity: 0;">
                                    <span class="font-mono text-[9px] uppercase tracking-[0.2em] text-white/70 whitespace-nowrap">Power Reserve</span>
                                    <div class="w-12 h-[1px] bg-white/30"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Sold Out Overlay -->
                        <div class="drop-out-of-stock absolute inset-0 bg-black/70 flex items-center justify-center hidden opacity-0 transition-opacity duration-500 z-40" id="oos-{{ $drop->id }}">
                            <span class="font-mono text-xs text-white/80 tracking-[0.3em] uppercase border border-white/20 px-8 py-4">SOLD OUT</span>
                        </div>
                    </div>
                    
                    <!-- Right 40%: Details -->
                    <div class="w-full lg:w-[40%] flex flex-col justify-center px-6 md:px-12 lg:px-16 py-16 lg:py-24 border-t lg:border-t-0 lg:border-l border-white/10">
                        
                        <!-- Brand Label -->
                        <span class="drop-detail font-mono text-[10px] uppercase tracking-[0.3em] text-white/40 mb-6" style="opacity: 0;">
                            {{ $drop->collection->name ?? 'EXCLUSIVE RELEASE' }}
                        </span>
                        
                        <!-- Product Name -->
                        <h3 class="drop-detail drop-detail-title font-h1 text-[clamp(1.8rem,4vw,3.2rem)] leading-[1.05] uppercase mb-8 tracking-tight" style="opacity: 0;">{{ $drop->name }}</h3>
                        
                        <!-- Editorial Paragraph -->
                        <p class="drop-detail font-body-md text-[14px] md:text-[15px] text-white/60 leading-[1.7] max-w-[420px] mb-12" style="opacity: 0;">
                            {{ $drop->description ?? $drop->tagline ?? 'Extremely limited allocation. This timepiece has been verified through our authentication protocol. Secure yours before the window closes.' }}
                        </p>
                        
                        <!-- Price -->
                        <div class="drop-detail drop-detail-price font-mono text-[clamp(1.4rem,3vw,2rem)] tracking-wide mb-12" style="opacity: 0;">
                            ${{ number_format($drop->price, 2) }}
                        </div>

                        <!-- Thin Divider -->
                        <div class="w-full h-[1px] bg-white/10 mb-10 drop-divider" style="transform: scaleX(0); transform-origin: left;"></div>
                        
                        <!-- Countdown Section -->
                        <div class="mb-8 drop-countdown-block" style="opacity: 0;">
                            <span class="font-mono text-[9px] uppercase tracking-[0.3em] text-white/40 block mb-4">DROP STARTS IN</span>
                            <div class="flex items-center gap-1 font-mono text-[clamp(1.6rem,4vw,2.4rem)] tracking-wide drop-flip-clock" data-drop-id="{{ $drop->id }}">
                                <div class="flip-digit-pair" data-unit="h">
                                    <div class="flip-digit relative overflow-hidden inline-block w-[1.2em] h-[1.5em]">
                                        <span class="flip-current block text-center leading-[1.5em]">0</span>
                                        <span class="flip-next absolute inset-0 block text-center leading-[1.5em]" style="transform: translateY(100%);">0</span>
                                    </div>
                                    <div class="flip-digit relative overflow-hidden inline-block w-[1.2em] h-[1.5em]">
                                        <span class="flip-current block text-center leading-[1.5em]">0</span>
                                        <span class="flip-next absolute inset-0 block text-center leading-[1.5em]" style="transform: translateY(100%);">0</span>
                                    </div>
                                </div>
                                <span class="text-white/30 mx-1">:</span>
                                <div class="flip-digit-pair" data-unit="m">
                                    <div class="flip-digit relative overflow-hidden inline-block w-[1.2em] h-[1.5em]">
                                        <span class="flip-current block text-center leading-[1.5em]">0</span>
                                        <span class="flip-next absolute inset-0 block text-center leading-[1.5em]" style="transform: translateY(100%);">0</span>
                                    </div>
                                    <div class="flip-digit relative overflow-hidden inline-block w-[1.2em] h-[1.5em]">
                                        <span class="flip-current block text-center leading-[1.5em]">0</span>
                                        <span class="flip-next absolute inset-0 block text-center leading-[1.5em]" style="transform: translateY(100%);">0</span>
                                    </div>
                                </div>
                                <span class="text-white/30 mx-1">:</span>
                                <div class="flip-digit-pair" data-unit="s">
                                    <div class="flip-digit relative overflow-hidden inline-block w-[1.2em] h-[1.5em]">
                                        <span class="flip-current block text-center leading-[1.5em]">0</span>
                                        <span class="flip-next absolute inset-0 block text-center leading-[1.5em]" style="transform: translateY(100%);">0</span>
                                    </div>
                                    <div class="flip-digit relative overflow-hidden inline-block w-[1.2em] h-[1.5em]">
                                        <span class="flip-current block text-center leading-[1.5em]">0</span>
                                        <span class="flip-next absolute inset-0 block text-center leading-[1.5em]" style="transform: translateY(100%);">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Allocation Status -->
                        <div class="mb-12 drop-allocation-block" style="opacity: 0;">
                            <div class="flex justify-between items-center mb-3">
                                <span class="font-mono text-[9px] uppercase tracking-[0.3em] text-white/40">ALLOCATION</span>
                                <span class="font-mono text-[13px] text-white/80 drop-alloc-count">{{ $drop->stock }} / 20</span>
                            </div>
                            <div class="w-full h-[1px] bg-white/10 relative">
                                <div class="drop-alloc-bar absolute left-0 top-0 h-full bg-white/60" style="width: {{ min(($drop->stock / 20) * 100, 100) }}%; transform: scaleX(0); transform-origin: left;"></div>
                            </div>
                        </div>

                        <!-- CTA -->
                        @if($drop->scheduled_publish_at)
                        <div class="drop-cta-wrapper relative overflow-hidden" style="opacity: 0;">
                            <!-- CTA sweep line -->
                            <div class="drop-cta-sweep absolute top-0 left-0 h-full w-full bg-white z-10" style="transform: translateX(-101%);"></div>
                            
                            <a href="{{ route('products.show', $drop->slug) }}" 
                               class="drop-btn relative z-20 w-full py-5 border border-white/30 font-mono text-[11px] uppercase tracking-[0.25em] text-center block text-white/50 pointer-events-none"
                               data-id="{{ $drop->id }}" 
                               data-drop-time="{{ $drop->scheduled_publish_at->getTimestamp() * 1000 }}">
                                <span class="drop-btn-text inline-flex items-center gap-3">
                                    <span>WAIT...</span>
                                </span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </section>
    @endif

    
    <!-- 4. HOROLOGY TIMELINE (Pinned Scroll) -->
    <section id="horology-timeline-section" class="w-full bg-[#111111] text-white overflow-hidden relative">
        <!-- Entry Sequence Header -->
        <div class="absolute top-0 left-0 w-full px-6 md:px-12 lg:px-24 pt-16 md:pt-24 z-20 pointer-events-none timeline-header">
            <div class="flex items-center gap-4 mb-12">
                <div class="h-[1px] w-12 bg-white/30 timeline-header-line origin-left scale-x-0"></div>
                <span class="font-mono text-xs tracking-[0.2em] uppercase text-white/50">ARCHIVE 01</span>
            </div>
            <div class="overflow-hidden">
                <h2 class="font-h1 text-[clamp(2rem,6vw,4rem)] leading-none uppercase tracking-widest text-white timeline-header-title translate-y-full">HOROLOGY<br>TIMELINE</h2>
            </div>
        </div>

        <!-- Sticky Wrapper (Pinned by GSAP) -->
        <div class="timeline-sticky-container h-auto md:h-screen w-full relative flex items-center">
            
            <!-- Global Blueprint Grid (Fades in) -->
            <div class="absolute inset-0 z-0 opacity-0 timeline-blueprint-grid" style="background-image: linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 50px 50px;">
                <!-- Crosshairs -->
                <div class="absolute top-1/4 left-1/4 w-4 h-4 border-l border-t border-white/10"></div>
                <div class="absolute bottom-1/4 right-1/4 w-4 h-4 border-r border-b border-white/10"></div>
            </div>

            <!-- Horizontal Track -->
            <div class="timeline-track flex h-full w-full relative z-10 flex-col md:flex-row md:w-[800vw]">

                <!-- Scene 1: 1675 -->
                <div class="timeline-scene w-full min-h-[80vh] md:w-[100vw] md:h-full flex-shrink-0 relative flex items-center justify-center px-6 md:px-24 py-24 md:py-0 border-b md:border-b-0 md:border-r border-white/10">
                    <div class="w-full max-w-[1400px] grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-16 items-center relative">
                        
                        <!-- Huge Year Background -->
                        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none z-0 overflow-hidden">
                            <div class="font-h1 text-[clamp(6rem,20vw,20rem)] text-white/[0.05] leading-none tracking-tighter timeline-year-bg translate-y-20 opacity-0">1675</div>
                        </div>

                        <!-- Left: Editorial Text -->
                        <div class="col-span-1 md:col-span-5 relative z-10">
                            <div class="flex items-center gap-3 mb-6 timeline-scene-ref opacity-0">
                                <div class="w-2 h-2 rounded-full bg-white/20"></div>
                                <span class="font-mono text-[10px] tracking-[0.2em] text-white/40">REF: ARCHIVE-1675-BS</span>
                            </div>
                            <h3 class="font-h1 text-3xl md:text-5xl uppercase tracking-wider mb-8 timeline-scene-title leading-[1.1] opacity-0">The Balance Spring</h3>
                            <p class="font-body text-base md:text-lg text-white/60 leading-[1.6] max-w-md timeline-scene-desc opacity-0">Mechanical watchmaking did not appear overnight. Every movement, every complication, and every innovation exists because generations of engineers refused to stop improving the impossible.</p>
                        </div>

                        <!-- Right: Macro Photography & Blueprint -->
                        <div class="col-span-1 md:col-span-7 relative z-10 h-[50vh] md:h-[70vh] flex items-center justify-center">
                            <!-- Macro Image Wrapper with Clipping Mask -->
                            <div class="relative w-full h-full max-w-[600px] max-h-[800px] overflow-hidden timeline-scene-img-wrap" style="clip-path: inset(100% 0 0 0);">
                                <!-- Image with subtle parallax -->
                                <img src="{{ asset('images/products/The balance spring watch.png') }}" alt="The Balance Spring" class="w-full h-full object-cover grayscale contrast-125 opacity-90 timeline-scene-img scale-105 " loading="lazy">
                                
                                <!-- Blueprint SVG Overlay -->
                                <svg class="absolute inset-0 w-full h-full pointer-events-none timeline-blueprint-svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                                    <line x1="10" y1="10" x2="10" y2="90" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <line x1="10" y1="10" x2="90" y2="10" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <circle cx="50" cy="50" r="30" stroke="rgba(255,255,255,0.2)" stroke-width="0.1" fill="none" class="blueprint-circle" stroke-dasharray="200" stroke-dashoffset="200"/>
                                    <!-- Measurement Marks -->
                                    <path d="M 45 20 L 55 20 M 50 18 L 50 22" stroke="rgba(255,255,255,0.4)" stroke-width="0.2" class="blueprint-mark opacity-0"/>
                                </svg>
                                
                                <!-- Technical Labels -->
                                <div class="absolute bottom-4 right-4 font-mono text-[9px] tracking-[0.2em] text-white/60 bg-[#111111]/80 px-2 py-1 backdrop-blur-sm opacity-0 timeline-museum-label">FIG. 1 // 1675</div>
                            </div>
                        </div>

                    </div>
                </div>
    
                <!-- Scene 2: 1759 -->
                <div class="timeline-scene w-full min-h-[80vh] md:w-[100vw] md:h-full flex-shrink-0 relative flex items-center justify-center px-6 md:px-24 py-24 md:py-0 border-b md:border-b-0 md:border-r border-white/10">
                    <div class="w-full max-w-[1400px] grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-16 items-center relative">
                        
                        <!-- Huge Year Background -->
                        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none z-0 overflow-hidden">
                            <div class="font-h1 text-[clamp(6rem,20vw,20rem)] text-white/[0.05] leading-none tracking-tighter timeline-year-bg translate-y-20 opacity-0">1759</div>
                        </div>

                        <!-- Left: Editorial Text -->
                        <div class="col-span-1 md:col-span-5 relative z-10">
                            <div class="flex items-center gap-3 mb-6 timeline-scene-ref opacity-0">
                                <div class="w-2 h-2 rounded-full bg-white/20"></div>
                                <span class="font-mono text-[10px] tracking-[0.2em] text-white/40">REF: ARCHIVE-1759-MC</span>
                            </div>
                            <h3 class="font-h1 text-3xl md:text-5xl uppercase tracking-wider mb-8 timeline-scene-title leading-[1.1] opacity-0">Marine Chronometers</h3>
                            <p class="font-body text-base md:text-lg text-white/60 leading-[1.6] max-w-md timeline-scene-desc opacity-0">Precision became a matter of life and death. The ability to calculate longitude at sea required mechanical oscillators that could resist salt, temperature, and the violent motion of the ocean.</p>
                        </div>

                        <!-- Right: Macro Photography & Blueprint -->
                        <div class="col-span-1 md:col-span-7 relative z-10 h-[50vh] md:h-[70vh] flex items-center justify-center">
                            <!-- Macro Image Wrapper with Clipping Mask -->
                            <div class="relative w-full h-full max-w-[600px] max-h-[800px] overflow-hidden timeline-scene-img-wrap" style="clip-path: inset(100% 0 0 0);">
                                <!-- Image with subtle parallax -->
                                <img src="{{ asset('images/products/marine-chronometer.jpg') }}" alt="Marine Chronometers" class="w-full h-full object-cover grayscale contrast-125 opacity-90 timeline-scene-img scale-105 " loading="lazy">
                                
                                <!-- Blueprint SVG Overlay -->
                                <svg class="absolute inset-0 w-full h-full pointer-events-none timeline-blueprint-svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                                    <line x1="10" y1="10" x2="10" y2="90" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <line x1="10" y1="10" x2="90" y2="10" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <circle cx="50" cy="50" r="30" stroke="rgba(255,255,255,0.2)" stroke-width="0.1" fill="none" class="blueprint-circle" stroke-dasharray="200" stroke-dashoffset="200"/>
                                    <!-- Measurement Marks -->
                                    <path d="M 45 20 L 55 20 M 50 18 L 50 22" stroke="rgba(255,255,255,0.4)" stroke-width="0.2" class="blueprint-mark opacity-0"/>
                                </svg>
                                
                                <!-- Technical Labels -->
                                <div class="absolute bottom-4 right-4 font-mono text-[9px] tracking-[0.2em] text-white/60 bg-[#111111]/80 px-2 py-1 backdrop-blur-sm opacity-0 timeline-museum-label">FIG. 2 // 1759</div>
                            </div>
                        </div>

                    </div>
                </div>
    
                <!-- Scene 3: 1810 -->
                <div class="timeline-scene w-full min-h-[80vh] md:w-[100vw] md:h-full flex-shrink-0 relative flex items-center justify-center px-6 md:px-24 py-24 md:py-0 border-b md:border-b-0 md:border-r border-white/10">
                    <div class="w-full max-w-[1400px] grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-16 items-center relative">
                        
                        <!-- Huge Year Background -->
                        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none z-0 overflow-hidden">
                            <div class="font-h1 text-[clamp(6rem,20vw,20rem)] text-white/[0.05] leading-none tracking-tighter timeline-year-bg translate-y-20 opacity-0">1810</div>
                        </div>

                        <!-- Left: Editorial Text -->
                        <div class="col-span-1 md:col-span-5 relative z-10">
                            <div class="flex items-center gap-3 mb-6 timeline-scene-ref opacity-0">
                                <div class="w-2 h-2 rounded-full bg-white/20"></div>
                                <span class="font-mono text-[10px] tracking-[0.2em] text-white/40">REF: ARCHIVE-1810-CP</span>
                            </div>
                            <h3 class="font-h1 text-3xl md:text-5xl uppercase tracking-wider mb-8 timeline-scene-title leading-[1.1] opacity-0">Complicated Pocket Watches</h3>
                            <p class="font-body text-base md:text-lg text-white/60 leading-[1.6] max-w-md timeline-scene-desc opacity-0">Miniaturization of grand complications. Perpetual calendars, minute repeaters, and tourbillons were engineered into spaces no larger than a gold coin.</p>
                        </div>

                        <!-- Right: Macro Photography & Blueprint -->
                        <div class="col-span-1 md:col-span-7 relative z-10 h-[50vh] md:h-[70vh] flex items-center justify-center">
                            <!-- Macro Image Wrapper with Clipping Mask -->
                            <div class="relative w-full h-full max-w-[600px] max-h-[800px] overflow-hidden timeline-scene-img-wrap" style="clip-path: inset(100% 0 0 0);">
                                <!-- Image with subtle parallax -->
                                <img src="{{ asset('images/products/Complicated Pocket Watch.png') }}" alt="Complicated Pocket Watches" class="w-full h-full object-cover grayscale contrast-125 opacity-90 timeline-scene-img scale-105 " loading="lazy">
                                
                                <!-- Blueprint SVG Overlay -->
                                <svg class="absolute inset-0 w-full h-full pointer-events-none timeline-blueprint-svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                                    <line x1="10" y1="10" x2="10" y2="90" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <line x1="10" y1="10" x2="90" y2="10" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <circle cx="50" cy="50" r="30" stroke="rgba(255,255,255,0.2)" stroke-width="0.1" fill="none" class="blueprint-circle" stroke-dasharray="200" stroke-dashoffset="200"/>
                                    <!-- Measurement Marks -->
                                    <path d="M 45 20 L 55 20 M 50 18 L 50 22" stroke="rgba(255,255,255,0.4)" stroke-width="0.2" class="blueprint-mark opacity-0"/>
                                </svg>
                                
                                <!-- Technical Labels -->
                                <div class="absolute bottom-4 right-4 font-mono text-[9px] tracking-[0.2em] text-white/60 bg-[#111111]/80 px-2 py-1 backdrop-blur-sm opacity-0 timeline-museum-label">FIG. 3 // 1810</div>
                            </div>
                        </div>

                    </div>
                </div>
    
                <!-- Scene 4: 1868 -->
                <div class="timeline-scene w-full min-h-[80vh] md:w-[100vw] md:h-full flex-shrink-0 relative flex items-center justify-center px-6 md:px-24 py-24 md:py-0 border-b md:border-b-0 md:border-r border-white/10">
                    <div class="w-full max-w-[1400px] grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-16 items-center relative">
                        
                        <!-- Huge Year Background -->
                        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none z-0 overflow-hidden">
                            <div class="font-h1 text-[clamp(6rem,20vw,20rem)] text-white/[0.05] leading-none tracking-tighter timeline-year-bg translate-y-20 opacity-0">1868</div>
                        </div>

                        <!-- Left: Editorial Text -->
                        <div class="col-span-1 md:col-span-5 relative z-10">
                            <div class="flex items-center gap-3 mb-6 timeline-scene-ref opacity-0">
                                <div class="w-2 h-2 rounded-full bg-white/20"></div>
                                <span class="font-mono text-[10px] tracking-[0.2em] text-white/40">REF: ARCHIVE-1868-FW</span>
                            </div>
                            <h3 class="font-h1 text-3xl md:text-5xl uppercase tracking-wider mb-8 timeline-scene-title leading-[1.1] opacity-0">The First Wristwatch</h3>
                            <p class="font-body text-base md:text-lg text-white/60 leading-[1.6] max-w-md timeline-scene-desc opacity-0">A transition from the pocket to the wrist. Originally conceived as a piece of jewelry, it soon became an indispensable tool for aviation and military coordination.</p>
                        </div>

                        <!-- Right: Macro Photography & Blueprint -->
                        <div class="col-span-1 md:col-span-7 relative z-10 h-[50vh] md:h-[70vh] flex items-center justify-center">
                            <!-- Macro Image Wrapper with Clipping Mask -->
                            <div class="relative w-full h-full max-w-[600px] max-h-[800px] overflow-hidden timeline-scene-img-wrap" style="clip-path: inset(100% 0 0 0);">
                                <!-- Image with subtle parallax -->
                                <img src="{{ asset('images/products/The first wrist watch.png') }}" alt="The First Wristwatch" class="w-full h-full object-cover grayscale contrast-125 opacity-90 timeline-scene-img scale-105 " loading="lazy">
                                
                                <!-- Blueprint SVG Overlay -->
                                <svg class="absolute inset-0 w-full h-full pointer-events-none timeline-blueprint-svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                                    <line x1="10" y1="10" x2="10" y2="90" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <line x1="10" y1="10" x2="90" y2="10" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <circle cx="50" cy="50" r="30" stroke="rgba(255,255,255,0.2)" stroke-width="0.1" fill="none" class="blueprint-circle" stroke-dasharray="200" stroke-dashoffset="200"/>
                                    <!-- Measurement Marks -->
                                    <path d="M 45 20 L 55 20 M 50 18 L 50 22" stroke="rgba(255,255,255,0.4)" stroke-width="0.2" class="blueprint-mark opacity-0"/>
                                </svg>
                                
                                <!-- Technical Labels -->
                                <div class="absolute bottom-4 right-4 font-mono text-[9px] tracking-[0.2em] text-white/60 bg-[#111111]/80 px-2 py-1 backdrop-blur-sm opacity-0 timeline-museum-label">FIG. 4 // 1868</div>
                            </div>
                        </div>

                    </div>
                </div>
    
                <!-- Scene 5: 1926 -->
                <div class="timeline-scene w-full min-h-[80vh] md:w-[100vw] md:h-full flex-shrink-0 relative flex items-center justify-center px-6 md:px-24 py-24 md:py-0 border-b md:border-b-0 md:border-r border-white/10">
                    <div class="w-full max-w-[1400px] grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-16 items-center relative">
                        
                        <!-- Huge Year Background -->
                        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none z-0 overflow-hidden">
                            <div class="font-h1 text-[clamp(6rem,20vw,20rem)] text-white/[0.05] leading-none tracking-tighter timeline-year-bg translate-y-20 opacity-0">1926</div>
                        </div>

                        <!-- Left: Editorial Text -->
                        <div class="col-span-1 md:col-span-5 relative z-10">
                            <div class="flex items-center gap-3 mb-6 timeline-scene-ref opacity-0">
                                <div class="w-2 h-2 rounded-full bg-white/20"></div>
                                <span class="font-mono text-[10px] tracking-[0.2em] text-white/40">REF: ARCHIVE-1926-RO</span>
                            </div>
                            <h3 class="font-h1 text-3xl md:text-5xl uppercase tracking-wider mb-8 timeline-scene-title leading-[1.1] opacity-0">Rolex Oyster</h3>
                            <p class="font-body text-base md:text-lg text-white/60 leading-[1.6] max-w-md timeline-scene-desc opacity-0">The birth of the waterproof case. Hermetically sealed construction protected delicate calibers from dust and moisture, changing the trajectory of tool watches forever.</p>
                        </div>

                        <!-- Right: Macro Photography & Blueprint -->
                        <div class="col-span-1 md:col-span-7 relative z-10 h-[50vh] md:h-[70vh] flex items-center justify-center">
                            <!-- Macro Image Wrapper with Clipping Mask -->
                            <div class="relative w-full h-full max-w-[600px] max-h-[800px] overflow-hidden timeline-scene-img-wrap" style="clip-path: inset(100% 0 0 0);">
                                <!-- Image with subtle parallax -->
                                <img src="{{ asset('images/products/Rolex Oyster.png') }}" alt="Rolex Oyster" class="w-full h-full object-cover grayscale contrast-125 opacity-90 timeline-scene-img scale-105 " loading="lazy">
                                
                                <!-- Blueprint SVG Overlay -->
                                <svg class="absolute inset-0 w-full h-full pointer-events-none timeline-blueprint-svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                                    <line x1="10" y1="10" x2="10" y2="90" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <line x1="10" y1="10" x2="90" y2="10" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <circle cx="50" cy="50" r="30" stroke="rgba(255,255,255,0.2)" stroke-width="0.1" fill="none" class="blueprint-circle" stroke-dasharray="200" stroke-dashoffset="200"/>
                                    <!-- Measurement Marks -->
                                    <path d="M 45 20 L 55 20 M 50 18 L 50 22" stroke="rgba(255,255,255,0.4)" stroke-width="0.2" class="blueprint-mark opacity-0"/>
                                </svg>
                                
                                <!-- Technical Labels -->
                                <div class="absolute bottom-4 right-4 font-mono text-[9px] tracking-[0.2em] text-white/60 bg-[#111111]/80 px-2 py-1 backdrop-blur-sm opacity-0 timeline-museum-label">FIG. 5 // 1926</div>
                            </div>
                        </div>

                    </div>
                </div>
    
                <!-- Scene 6: 1969 -->
                <div class="timeline-scene w-full min-h-[80vh] md:w-[100vw] md:h-full flex-shrink-0 relative flex items-center justify-center px-6 md:px-24 py-24 md:py-0 border-b md:border-b-0 md:border-r border-white/10">
                    <div class="w-full max-w-[1400px] grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-16 items-center relative">
                        
                        <!-- Huge Year Background -->
                        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none z-0 overflow-hidden">
                            <div class="font-h1 text-[clamp(6rem,20vw,20rem)] text-white/[0.05] leading-none tracking-tighter timeline-year-bg translate-y-20 opacity-0">1969</div>
                        </div>

                        <!-- Left: Editorial Text -->
                        <div class="col-span-1 md:col-span-5 relative z-10">
                            <div class="flex items-center gap-3 mb-6 timeline-scene-ref opacity-0">
                                <div class="w-2 h-2 rounded-full bg-white/20"></div>
                                <span class="font-mono text-[10px] tracking-[0.2em] text-white/40">REF: ARCHIVE-1969-AC</span>
                            </div>
                            <h3 class="font-h1 text-3xl md:text-5xl uppercase tracking-wider mb-8 timeline-scene-title leading-[1.1] opacity-0">The Automatic Chronograph</h3>
                            <p class="font-body text-base md:text-lg text-white/60 leading-[1.6] max-w-md timeline-scene-desc opacity-0">The race to build the first self-winding stopwatch. A monumental year that saw rival consortiums achieve the holy grail of mechanical sports timing simultaneously.</p>
                        </div>

                        <!-- Right: Macro Photography & Blueprint -->
                        <div class="col-span-1 md:col-span-7 relative z-10 h-[50vh] md:h-[70vh] flex items-center justify-center">
                            <!-- Macro Image Wrapper with Clipping Mask -->
                            <div class="relative w-full h-full max-w-[600px] max-h-[800px] overflow-hidden timeline-scene-img-wrap" style="clip-path: inset(100% 0 0 0);">
                                <!-- Image with subtle parallax -->
                                <img src="{{ asset('images/products/The Automatic Chronogaph.jpg') }}" alt="The Automatic Chronograph" class="w-full h-full object-cover grayscale contrast-125 opacity-90 timeline-scene-img scale-105 " loading="lazy">
                                
                                <!-- Blueprint SVG Overlay -->
                                <svg class="absolute inset-0 w-full h-full pointer-events-none timeline-blueprint-svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                                    <line x1="10" y1="10" x2="10" y2="90" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <line x1="10" y1="10" x2="90" y2="10" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <circle cx="50" cy="50" r="30" stroke="rgba(255,255,255,0.2)" stroke-width="0.1" fill="none" class="blueprint-circle" stroke-dasharray="200" stroke-dashoffset="200"/>
                                    <!-- Measurement Marks -->
                                    <path d="M 45 20 L 55 20 M 50 18 L 50 22" stroke="rgba(255,255,255,0.4)" stroke-width="0.2" class="blueprint-mark opacity-0"/>
                                </svg>
                                
                                <!-- Technical Labels -->
                                <div class="absolute bottom-4 right-4 font-mono text-[9px] tracking-[0.2em] text-white/60 bg-[#111111]/80 px-2 py-1 backdrop-blur-sm opacity-0 timeline-museum-label">FIG. 6 // 1969</div>
                            </div>
                        </div>

                    </div>
                </div>
    
                <!-- Scene 7: 1999 -->
                <div class="timeline-scene w-full min-h-[80vh] md:w-[100vw] md:h-full flex-shrink-0 relative flex items-center justify-center px-6 md:px-24 py-24 md:py-0 border-b md:border-b-0 md:border-r border-white/10">
                    <div class="w-full max-w-[1400px] grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-16 items-center relative">
                        
                        <!-- Huge Year Background -->
                        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none z-0 overflow-hidden">
                            <div class="font-h1 text-[clamp(6rem,20vw,20rem)] text-white/[0.05] leading-none tracking-tighter timeline-year-bg translate-y-20 opacity-0">1999</div>
                        </div>

                        <!-- Left: Editorial Text -->
                        <div class="col-span-1 md:col-span-5 relative z-10">
                            <div class="flex items-center gap-3 mb-6 timeline-scene-ref opacity-0">
                                <div class="w-2 h-2 rounded-full bg-white/20"></div>
                                <span class="font-mono text-[10px] tracking-[0.2em] text-white/40">REF: ARCHIVE-1999-CE</span>
                            </div>
                            <h3 class="font-h1 text-3xl md:text-5xl uppercase tracking-wider mb-8 timeline-scene-title leading-[1.1] opacity-0">Co-Axial Escapement</h3>
                            <p class="font-body text-base md:text-lg text-white/60 leading-[1.6] max-w-md timeline-scene-desc opacity-0">The first practical new watch escapement in 250 years. Radically reducing sliding friction and redefining the theoretical limits of mechanical chronometry.</p>
                        </div>

                        <!-- Right: Macro Photography & Blueprint -->
                        <div class="col-span-1 md:col-span-7 relative z-10 h-[50vh] md:h-[70vh] flex items-center justify-center">
                            <!-- Macro Image Wrapper with Clipping Mask -->
                            <div class="relative w-full h-full max-w-[600px] max-h-[800px] overflow-hidden timeline-scene-img-wrap" style="clip-path: inset(100% 0 0 0);">
                                <!-- Image with subtle parallax -->
                                <img src="{{ asset('images/products/Co Axial Escapement.png') }}" alt="Co-Axial Escapement" class="w-full h-full object-cover grayscale contrast-125 opacity-90 timeline-scene-img scale-105 " loading="lazy">
                                
                                <!-- Blueprint SVG Overlay -->
                                <svg class="absolute inset-0 w-full h-full pointer-events-none timeline-blueprint-svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                                    <line x1="10" y1="10" x2="10" y2="90" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <line x1="10" y1="10" x2="90" y2="10" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <circle cx="50" cy="50" r="30" stroke="rgba(255,255,255,0.2)" stroke-width="0.1" fill="none" class="blueprint-circle" stroke-dasharray="200" stroke-dashoffset="200"/>
                                    <!-- Measurement Marks -->
                                    <path d="M 45 20 L 55 20 M 50 18 L 50 22" stroke="rgba(255,255,255,0.4)" stroke-width="0.2" class="blueprint-mark opacity-0"/>
                                </svg>
                                
                                <!-- Technical Labels -->
                                <div class="absolute bottom-4 right-4 font-mono text-[9px] tracking-[0.2em] text-white/60 bg-[#111111]/80 px-2 py-1 backdrop-blur-sm opacity-0 timeline-museum-label">FIG. 7 // 1999</div>
                            </div>
                        </div>

                    </div>
                </div>
    
                <!-- Scene 8: Today -->
                <div class="timeline-scene w-full min-h-[80vh] md:w-[100vw] md:h-full flex-shrink-0 relative flex items-center justify-center px-6 md:px-24 py-24 md:py-0 border-b md:border-b-0 md:border-r border-white/10">
                    <div class="w-full max-w-[1400px] grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-16 items-center relative">
                        
                        <!-- Huge Year Background -->
                        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none z-0 overflow-hidden">
                            <div class="font-h1 text-[clamp(6rem,20vw,20rem)] text-white/[0.05] leading-none tracking-tighter timeline-year-bg translate-y-20 opacity-0">Today</div>
                        </div>

                        <!-- Left: Editorial Text -->
                        <div class="col-span-1 md:col-span-5 relative z-10">
                            <div class="flex items-center gap-3 mb-6 timeline-scene-ref opacity-0">
                                <div class="w-2 h-2 rounded-full bg-white/20"></div>
                                <span class="font-mono text-[10px] tracking-[0.2em] text-white/40">REF: ARCHIVE-TODAY-RM</span>
                            </div>
                            <h3 class="font-h1 text-3xl md:text-5xl uppercase tracking-wider mb-8 timeline-scene-title leading-[1.1] opacity-0">The Renaissance</h3>
                            <p class="font-body text-base md:text-lg text-white/60 leading-[1.6] max-w-md timeline-scene-desc opacity-0">Every innovation led to one extraordinary machine.</p>
                        </div>

                        <!-- Right: Macro Photography & Blueprint -->
                        <div class="col-span-1 md:col-span-7 relative z-10 h-[50vh] md:h-[70vh] flex items-center justify-center">
                            <!-- Macro Image Wrapper with Clipping Mask -->
                            <div class="relative w-full h-full max-w-[600px] max-h-[800px] overflow-hidden timeline-scene-img-wrap" style="clip-path: inset(100% 0 0 0);">
                                <!-- Image with subtle parallax -->
                                <img src="{{ asset('images/products/Richard Mille Automatic Chronograph.png') }}" alt="The Renaissance" class="w-full h-full object-cover grayscale contrast-125 opacity-90 timeline-scene-img scale-105 " loading="lazy">
                                
                                <!-- Blueprint SVG Overlay -->
                                <svg class="absolute inset-0 w-full h-full pointer-events-none timeline-blueprint-svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                                    <line x1="10" y1="10" x2="10" y2="90" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <line x1="10" y1="10" x2="90" y2="10" stroke="rgba(255,255,255,0.3)" stroke-width="0.2" class="blueprint-line" stroke-dasharray="100" stroke-dashoffset="100"/>
                                    <circle cx="50" cy="50" r="30" stroke="rgba(255,255,255,0.2)" stroke-width="0.1" fill="none" class="blueprint-circle" stroke-dasharray="200" stroke-dashoffset="200"/>
                                    <!-- Measurement Marks -->
                                    <path d="M 45 20 L 55 20 M 50 18 L 50 22" stroke="rgba(255,255,255,0.4)" stroke-width="0.2" class="blueprint-mark opacity-0"/>
                                </svg>
                                
                                <!-- Technical Labels -->
                                <div class="absolute bottom-4 right-4 font-mono text-[9px] tracking-[0.2em] text-white/60 bg-[#111111]/80 px-2 py-1 backdrop-blur-sm opacity-0 timeline-museum-label">FIG. 8 // Today</div>
                            </div>
                        </div>

                    </div>
                </div>
    
            </div>
            
            <!-- Global Timeline Progress Indicator (Engraved Line) -->
            <div class="absolute bottom-12 left-12 right-12 h-[1px] bg-white/10 z-20 hidden md:block timeline-progress-track">
                <div class="h-full bg-white/60 origin-left scale-x-0 timeline-progress-fill"></div>
            </div>

        </div>
    </section>


    <!-- NEW: Movement Lab Section -->
    <section id="movement-lab-section" class="w-full bg-[#111111] text-white relative z-20">
        <!-- Add empty divs to create the scroll distance for ScrollTrigger (e.g. 600vh total) -->
        <div class="ml-scroll-space h-[600vh] w-full absolute top-0 left-0 pointer-events-none"></div>

        <div class="ml-sticky-container h-screen w-full sticky top-0 overflow-hidden flex items-center justify-center">
            
            <!-- Grid Background (Fades in) -->
            <div class="absolute inset-0 z-0 opacity-0 ml-blueprint-grid" style="background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 40px 40px;"></div>
            
            <!-- Editorial Info Panel (Left) -->
            <div class="absolute left-6 md:left-24 top-1/2 -translate-y-1/2 w-[280px] md:w-80 z-20 opacity-0 ml-editorial-panel pointer-events-none">
                <div class="font-mono text-[10px] tracking-[0.2em] text-white/50 mb-4 ml-panel-ref">ML-000</div>
                <h3 class="font-h1 text-2xl md:text-3xl uppercase tracking-wider mb-6 ml-panel-title">COMPONENT</h3>
                <p class="font-body text-sm md:text-base text-white/70 leading-[1.6] mb-8 ml-panel-desc">Description goes here.</p>
                <div class="grid grid-cols-2 gap-y-4 gap-x-8 border-t border-white/10 pt-4 font-mono text-[9px] md:text-[10px] uppercase tracking-wider text-white/60 ml-panel-specs">
                    <div>
                        <div class="text-white/40 mb-1">CATEGORY</div>
                        <div class="text-white ml-spec-cat">Regulation</div>
                    </div>
                    <div>
                        <div class="text-white/40 mb-1">FREQ</div>
                        <div class="text-white ml-spec-freq">28800 BPH</div>
                    </div>
                    <div>
                        <div class="text-white/40 mb-1">DIFFICULTY</div>
                        <div class="text-white ml-spec-diff">Advanced</div>
                    </div>
                </div>
            </div>

            <!-- SVG Connecting Line -->
            <svg class="absolute inset-0 w-full h-full pointer-events-none z-10" viewBox="0 0 100 100" preserveAspectRatio="none">
                <!-- Using fixed coordinates that will be updated dynamically via JS -->
                <path d="M 30 50 L 50 50" stroke="rgba(255,255,255,0.2)" stroke-width="0.1" fill="none" class="ml-connect-line opacity-0" stroke-dasharray="100" stroke-dashoffset="100"/>
            </svg>

            <!-- SVG Movement Graphic (Center) -->
            <div class="relative w-full max-w-[800px] aspect-square z-10 flex items-center justify-center ml-svg-container" style="transform: scale(0.4);">
                
                <!-- Tiny Specs Markers -->
                <div class="absolute top-1/4 left-1/4 font-mono text-[9px] tracking-widest text-white/50 ml-spec-marker opacity-0 -translate-x-1/2 -translate-y-1/2">26 JEWELS</div>
                <div class="absolute bottom-1/4 right-1/4 font-mono text-[9px] tracking-widest text-white/50 ml-spec-marker opacity-0 translate-x-1/2 translate-y-1/2">72H RESERVE</div>
                <div class="absolute top-1/4 right-1/4 font-mono text-[9px] tracking-widest text-white/50 ml-spec-marker opacity-0 translate-x-1/2 -translate-y-1/2">SWISS LEVER</div>
                
                <!-- The SVG -->
                <svg class="w-full h-full overflow-visible ml-movement-svg" viewBox="-100 -100 200 200">
                    <defs>
                        <linearGradient id="metal" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#eeeeee" />
                            <stop offset="50%" stop-color="#dddddd" />
                            <stop offset="100%" stop-color="#cccccc" />
                        </linearGradient>
                        <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="2" dy="2" stdDeviation="3" flood-opacity="0.1"/>
                        </filter>
                    </defs>

                    <!-- Main Barrel (Upper Left) -->
                    <g id="ml-barrel" class="ml-component cursor-crosshair transition-opacity duration-300" data-id="barrel">
                        <circle cx="-30" cy="-30" r="35" fill="none" stroke="#888" stroke-width="0.5" stroke-dasharray="2 1"/>
                        <circle cx="-30" cy="-30" r="33" fill="#f8f8f8" stroke="#333" stroke-width="0.5" filter="url(#shadow)"/>
                        <path d="M-30,-30 m0,-2 a2,2 0 1,1 0,4 a4,4 0 1,1 0,-8 a6,6 0 1,1 0,12 a8,8 0 1,1 0,-16 a10,10 0 1,1 0,20 a12,12 0 1,1 0,-24 a14,14 0 1,1 0,28 a16,16 0 1,1 0,-32 a18,18 0 1,1 0,36 a20,20 0 1,1 0,-40 a22,22 0 1,1 0,44 a24,24 0 1,1 0,-48 a26,26 0 1,1 0,52" fill="none" stroke="#aaa" stroke-width="0.3"/>
                        <circle cx="-30" cy="-30" r="2" fill="#222"/>
                        <path d="M -30 -65 A 35 35 0 0 1 5 -30" fill="none" stroke="#888" stroke-width="2" stroke-dasharray="1 1.5"/>
                    </g>

                    <!-- Gear Train (Center Left) -->
                    <g id="ml-gear-train" class="ml-component cursor-crosshair transition-opacity duration-300" data-id="gears">
                        <circle cx="0" cy="0" r="20" fill="none" stroke="#888" stroke-width="0.5"/>
                        <circle cx="0" cy="0" r="18" fill="#f4f4f4" stroke="#444" stroke-width="0.5" filter="url(#shadow)"/>
                        <circle cx="0" cy="0" r="1.5" fill="#c0392b"/> 
                        <line x1="0" y1="-18" x2="0" y2="18" stroke="#555" stroke-width="1.5"/>
                        <line x1="-18" y1="0" x2="18" y2="0" stroke="#555" stroke-width="1.5"/>
                        <line x1="-12.7" y1="-12.7" x2="12.7" y2="12.7" stroke="#555" stroke-width="1.5"/>
                        <line x1="-12.7" y1="12.7" x2="12.7" y2="-12.7" stroke="#555" stroke-width="1.5"/>
                        
                        <circle cx="-15" cy="20" r="14" fill="none" stroke="#888" stroke-width="0.5"/>
                        <circle cx="-15" cy="20" r="12" fill="#eaeaea" stroke="#555" stroke-width="0.3" filter="url(#shadow)"/>
                        <circle cx="-15" cy="20" r="1" fill="#c0392b"/>
                        <circle cx="-15" cy="20" r="8" fill="none" stroke="#777" stroke-width="0.2"/>
                        
                        <circle cx="10" cy="25" r="10" fill="none" stroke="#888" stroke-width="0.5"/>
                        <circle cx="10" cy="25" r="8" fill="#f0f0f0" stroke="#666" stroke-width="0.3" filter="url(#shadow)"/>
                        <circle cx="10" cy="25" r="1" fill="#c0392b"/>
                        <path d="M 10 17 L 10 33 M 2 25 L 18 25" stroke="#777" stroke-width="0.5"/>
                    </g>

                    <!-- Escapement (Bottom) -->
                    <g id="ml-escapement" class="ml-component cursor-crosshair transition-opacity duration-300" data-id="escapement">
                        <circle cx="25" cy="35" r="8" fill="none" stroke="#888" stroke-width="0.3"/>
                        <path d="M 25 27 L 27 30 L 29 27 L 29 29 L 32 30 L 30 33 L 33 35 L 30 37 L 32 40 L 29 39 L 29 42 L 27 39 L 25 43 L 23 39 L 21 42 L 21 39 L 18 40 L 20 37 L 17 35 L 20 33 L 18 30 L 21 29 L 21 27 L 23 30 Z" fill="#e8e8e8" stroke="#333" stroke-width="0.3" filter="url(#shadow)"/>
                        <circle cx="25" cy="35" r="1" fill="#c0392b"/>
                        
                        <path d="M 35 45 L 33 40 L 29 39 L 31 37 L 34 38 L 37 34 L 39 36 L 36 39 Z" fill="#f5f5f5" stroke="#222" stroke-width="0.4" filter="url(#shadow)"/>
                        <circle cx="35" cy="45" r="1" fill="#c0392b"/>
                        <rect x="30.5" y="36.5" width="2" height="1" fill="#c0392b" transform="rotate(-30, 31.5, 37)"/>
                        <rect x="37" y="34.5" width="2" height="1" fill="#c0392b" transform="rotate(20, 38, 35)"/>
                    </g>

                    <!-- Balance Wheel (Bottom Right) -->
                    <g id="ml-balance" class="ml-component cursor-crosshair transition-opacity duration-300" data-id="balance">
                        <path d="M 60 70 C 60 70, 70 50, 85 45 C 90 43, 95 48, 92 52 C 85 60, 75 75, 65 75 Z" fill="url(#metal)" stroke="#333" stroke-width="0.5" filter="url(#shadow)"/>
                        <path d="M 68 55 A 15 15 0 0 1 78 50" fill="none" stroke="#222" stroke-width="1.5"/>
                        <circle cx="73" cy="52" r="1" fill="#c0392b"/>
                        <circle cx="45" cy="55" r="22" fill="none" stroke="#888" stroke-width="0.5"/>
                        <circle cx="45" cy="55" r="20" fill="none" stroke="#cda434" stroke-width="2.5" filter="url(#shadow)"/> 
                        <circle cx="45" cy="55" r="1.5" fill="#c0392b"/>
                        <line x1="25" y1="55" x2="65" y2="55" stroke="#cda434" stroke-width="1.5"/>
                        <path d="M 45 55 m 0 -2 a 2 2 0 1 1 0 4 a 4 4 0 1 1 0 -8 a 6 6 0 1 1 0 12 a 8 8 0 1 1 0 -16 a 10 10 0 1 1 0 20 a 12 12 0 1 1 0 -24 a 14 14 0 1 1 0 28 a 16 16 0 1 1 0 -32" fill="none" stroke="#aaa" stroke-width="0.2"/>
                        <circle cx="25" cy="55" r="1" fill="#444"/>
                        <circle cx="65" cy="55" r="1" fill="#444"/>
                        <circle cx="45" cy="35" r="1" fill="#444"/>
                        <circle cx="45" cy="75" r="1" fill="#444"/>
                    </g>

                    <!-- Main Bridge (Center Right) -->
                    <g id="ml-bridge" class="ml-component cursor-crosshair transition-opacity duration-300" data-id="bridge">
                        <path d="M -15 15 C -25 -10, -5 -35, 15 -25 C 35 -15, 30 10, 40 20 C 50 30, 25 45, 10 35 C -5 25, -5 40, -15 15 Z" fill="url(#metal)" stroke="#444" stroke-width="0.5" filter="url(#shadow)"/>
                        <circle cx="0" cy="0" r="1.2" fill="#c0392b"/>
                        <circle cx="10" cy="25" r="1.2" fill="#c0392b"/>
                        <circle cx="25" cy="10" r="1.2" fill="#c0392b"/>
                        <circle cx="-5" cy="-20" r="2.5" fill="#ccc" stroke="#333" stroke-width="0.3"/>
                        <line x1="-7" y1="-20" x2="-3" y2="-20" stroke="#333" stroke-width="0.5"/>
                        <circle cx="25" cy="-5" r="2.5" fill="#ccc" stroke="#333" stroke-width="0.3"/>
                        <line x1="23" y1="-7" x2="27" y2="-3" stroke="#333" stroke-width="0.5"/>
                        <circle cx="15" cy="30" r="2.5" fill="#ccc" stroke="#333" stroke-width="0.3"/>
                        <line x1="13" y1="28" x2="17" y2="32" stroke="#333" stroke-width="0.5"/>
                        <text x="0" y="-10" font-family="monospace" font-size="4" fill="#666" transform="rotate(-15, 0, -10)">CLEMENTINE</text>
                        <text x="5" y="10" font-family="monospace" font-size="3" fill="#666" transform="rotate(-15, 5, 10)">TWENTY-SIX 26 JEWELS</text>
                    </g>

                    <!-- Rotor (Top) -->
                    <g id="ml-rotor" class="ml-component cursor-crosshair transition-opacity duration-300" data-id="rotor">
                        <circle cx="0" cy="0" r="8" fill="#e0e0e0" stroke="#333" stroke-width="0.5"/>
                        <circle cx="0" cy="0" r="6" fill="#f0f0f0" stroke="#444" stroke-width="0.2"/>
                        <circle cx="0" cy="0" r="2" fill="#999" stroke="#333" stroke-width="0.3"/>
                        <path d="M 0 0 L -5 5 M 0 0 L 5 -5 M 0 0 L -5 -5 M 0 0 L 5 5" stroke="#ccc" stroke-width="0.5"/>
                        <path d="M -50 0 A 50 50 0 0 1 50 0 L 40 0 A 40 40 0 0 0 -40 0 Z" fill="url(#metal)" stroke="#444" stroke-width="0.5" filter="url(#shadow)"/>
                        <path d="M -52 0 A 52 52 0 0 1 52 0 L 48 0 A 48 48 0 0 0 -48 0 Z" fill="#d4af37" opacity="0.3"/> 
                        <path d="M -40 0 A 40 40 0 0 1 40 0 L 10 0 A 10 10 0 0 0 -10 0 Z" fill="#e8e8e8" stroke="#555" stroke-width="0.2"/>
                        <circle cx="0" cy="0" r="10" fill="none" stroke="#bbb" stroke-width="0.5" stroke-dasharray="1 2"/>
                        <text x="0" y="-30" font-family="sans-serif" font-weight="bold" font-size="6" fill="#555" text-anchor="middle" transform="rotate(0, 0, -30)">CLEMENTINE</text>
                        <text x="0" y="-23" font-family="monospace" font-size="3" fill="#666" text-anchor="middle">MANUFACTURE CAL. 400</text>
                    </g>
                    
                    <!-- Hover Indicator (Moved via JS) -->
                    <circle id="ml-hover-indicator" cx="0" cy="0" r="15" fill="none" stroke="white" stroke-width="0.5" stroke-dasharray="2 2" class="opacity-0 pointer-events-none transition-opacity duration-300"/>
                </svg>
            </div>
            
            <!-- Technical Labels (Phase 01) -->
            <div class="absolute inset-0 pointer-events-none ml-tech-labels opacity-0 z-20 font-mono text-[9px] md:text-[10px] uppercase tracking-[0.2em] text-white/60 hidden md:block">
                <div class="absolute top-1/4 left-1/3 ml-label" style="transform: translate(-100px, -50px);">
                    <div class="mb-1">CALIBER 400</div>
                    <div class="w-12 h-[1px] bg-white/30"></div>
                </div>
                <div class="absolute top-1/3 right-1/4 ml-label" style="transform: translate(50px, -20px);">
                    <div class="mb-1">AUTOMATIC</div>
                    <div class="w-12 h-[1px] bg-white/30"></div>
                </div>
                <div class="absolute bottom-1/3 left-1/4 ml-label" style="transform: translate(-30px, 30px);">
                    <div class="mb-1">28,800 VPH</div>
                    <div class="w-12 h-[1px] bg-white/30"></div>
                </div>
                <div class="absolute bottom-1/4 right-1/3 ml-label" style="transform: translate(80px, 50px);">
                    <div class="mb-1">CHRONOMETER</div>
                    <div class="w-12 h-[1px] bg-white/30"></div>
                </div>
            </div>

            <!-- Final Message -->
            <div class="absolute inset-0 z-30 pointer-events-none flex items-center justify-center bg-[#111111]/95 backdrop-blur-sm opacity-0 ml-final-msg">
                <h2 class="font-h1 text-3xl md:text-6xl uppercase tracking-widest text-center leading-[1.2]">
                    <span class="block ml-final-text-1 translate-y-8 opacity-0">True Precision</span>
                    <span class="block text-white/50 ml-final-text-2 translate-y-8 opacity-0 mt-4 md:mt-6">Is Never An Accident.</span>
                </h2>
            </div>
            
        </div>
    </section>

    <!-- 3.5 Watchmaker's Notes -->
    <div id="notes-section" class="w-full bg-background relative border-b border-primary/20 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 min-h-screen">
            
            <!-- Left Sticky Column -->
            <div class="lg:col-span-4 border-b lg:border-b-0 lg:border-r border-primary/20 relative">
                <div class="sticky top-0 p-xl md:p-3xl h-screen flex flex-col justify-center overflow-hidden">
                    <!-- Section Intro Labels -->
                    <div class="notes-labels flex items-center justify-between mb-8 opacity-0">
                        <span class="font-mono text-[9px] uppercase tracking-widest text-primary/60">FIELD MANUAL 01</span>
                        <div class="w-8 h-[1px] bg-primary/20"></div>
                    </div>
                    
                    <!-- Massive Title -->
                    <h2 class="notes-title font-h1 text-[clamp(2.5rem,3.5vw,4.5rem)] leading-[0.85] tracking-tight uppercase text-primary mb-12 break-words" style="clip-path: inset(100% 0 0 0);">
                        WATCHMAKER<br>NOTES
                    </h2>
                    
                    <!-- Intro Paragraph -->
                    <p class="notes-intro font-mono text-xs md:text-sm text-primary/80 leading-relaxed max-w-[400px] opacity-0 transform translate-y-4">
                        Every mechanical watch hides hundreds of invisible decisions.<br><br>
                        This archive explains the craftsmanship behind them.
                    </p>
                </div>
            </div>

            <!-- Right Scrollable / Masonry Grid -->
            <div class="lg:col-span-8 p-6 md:p-12 lg:p-24 relative notes-grid" style="background-size: 24px 24px; background-image: linear-gradient(to right, rgba(0,0,0,0.03) 1px, transparent 1px), linear-gradient(to bottom, rgba(0,0,0,0.03) 1px, transparent 1px);">
                <div class="notes-grid-lines absolute inset-0 pointer-events-none z-0">
                    <div class="notes-line-top absolute top-0 left-0 w-full h-[1px] bg-primary/20 origin-left scale-x-0"></div>
                    <div class="notes-line-bottom absolute bottom-0 right-0 w-full h-[1px] bg-primary/20 origin-right scale-x-0"></div>
                    <div class="notes-line-left absolute bottom-0 left-0 w-[1px] h-full bg-primary/20 origin-bottom scale-y-0"></div>
                    <div class="notes-line-right absolute top-0 right-0 w-[1px] h-full bg-primary/20 origin-top scale-y-0"></div>
                </div>

                <div class="flex flex-col gap-12 md:gap-24 relative z-10 notes-article-stack">
                    
                    <!-- Article 1: Large Feature -->
                    <article class="note-module relative bg-background border border-primary/10 transition-colors duration-300 w-full cursor-pointer overflow-hidden group">
                        <div class="p-6 md:p-10 flex flex-col note-content-wrapper transition-all duration-500 relative z-10">
                            <div class="flex justify-between items-center mb-8">
                                <span class="font-mono text-[9px] uppercase tracking-widest text-primary/50">FIELD NOTE 04</span>
                                <span class="font-mono text-[9px] uppercase tracking-widest text-primary/40 group-hover:text-primary transition-colors duration-300 note-ref">REF: WM-04</span>
                            </div>
                            
                            <!-- Image with clipping mask reveal -->
                            <div class="note-img-wrapper w-full aspect-video bg-[#FAFAFA] overflow-hidden mb-10 relative">
                                <img src="{{ asset('images/products/aster-minimalist-01.jpg') }}" class="note-img w-full h-full object-cover transform transition-transform duration-700 ease-out grayscale contrast-125 opacity-80" alt="Balance Wheel" style="clip-path: inset(0 0 100% 0);">
                            </div>

                            <h3 class="note-title font-h1 text-3xl md:text-5xl uppercase mb-6 transition-transform duration-500">Balance Wheel</h3>
                            
                            <p class="font-mono text-xs md:text-sm text-primary/70 leading-relaxed max-w-[650px]">
                                The heartbeat of every mechanical watch. While electronic watches rely on a quartz crystal, mechanical watches use a delicate balance wheel oscillating at extremely precise frequencies to regulate the release of energy from the mainspring.
                            </p>
                            
                            <!-- Expanded Specifications (Hidden initially) -->
                            <div class="note-specs hidden mt-12 border-t border-primary/10 pt-8 flex-col gap-4">
                                <div class="spec-row flex justify-between font-mono text-[10px] uppercase tracking-widest border-b border-primary/5 pb-4 opacity-0 transform -translate-x-4">
                                    <span class="text-primary/50">ARCHIVE</span>
                                    <span>WM-014</span>
                                </div>
                                <div class="spec-row flex justify-between font-mono text-[10px] uppercase tracking-widest border-b border-primary/5 pb-4 opacity-0 transform -translate-x-4">
                                    <span class="text-primary/50">PUBLICATION YEAR</span>
                                    <span>2026</span>
                                </div>
                                <div class="spec-row flex justify-between font-mono text-[10px] uppercase tracking-widest pb-4 opacity-0 transform -translate-x-4">
                                    <span class="text-primary/50">DIFFICULTY</span>
                                    <span>LEVEL II</span>
                                </div>
                            </div>
                            
                            <!-- Reading Progress Line -->
                            <div class="note-progress absolute top-0 left-0 w-[2px] h-0 bg-primary z-20"></div>
                        </div>
                    </article>

                    <!-- Article 2: Narrow Vertical -->
                    <article class="note-module relative bg-background border border-primary/10 transition-colors duration-300 w-full md:w-[60%] ml-auto cursor-pointer overflow-hidden group">
                        <div class="p-6 md:p-10 flex flex-col note-content-wrapper transition-all duration-500 relative z-10">
                            <div class="flex justify-between items-center mb-8">
                                <span class="font-mono text-[9px] uppercase tracking-widest text-primary/50">FIELD NOTE 07</span>
                                <span class="font-mono text-[9px] uppercase tracking-widest text-primary/40 group-hover:text-primary transition-colors duration-300 note-ref">REF: WM-07</span>
                            </div>
                            
                            <h3 class="note-title font-h1 text-2xl md:text-4xl uppercase mb-6 transition-transform duration-500">Sapphire Crystal</h3>
                            
                            <div class="note-img-wrapper w-full aspect-square bg-[#FAFAFA] overflow-hidden mb-8 relative">
                                <img src="{{ asset('images/products/meridian-chrono-01.jpg') }}" class="note-img w-full h-full object-cover transform transition-transform duration-700 ease-out grayscale contrast-125 opacity-80" alt="Sapphire Crystal" style="clip-path: inset(0 0 100% 0);">
                            </div>
                            
                            <p class="font-mono text-xs md:text-sm text-primary/70 leading-relaxed">
                                Second only to diamond in hardness, synthetic sapphire provides unparalleled scratch resistance and optical clarity, protecting the intricate dial and mechanics below.
                            </p>
                            
                            <!-- Expanded Specifications -->
                            <div class="note-specs hidden mt-10 border-t border-primary/10 pt-6 flex-col gap-3">
                                <div class="spec-row flex justify-between font-mono text-[10px] uppercase tracking-widest border-b border-primary/5 pb-3 opacity-0 transform -translate-x-4">
                                    <span class="text-primary/50">MATERIAL</span>
                                    <span>CORUNDUM</span>
                                </div>
                                <div class="spec-row flex justify-between font-mono text-[10px] uppercase tracking-widest pb-3 opacity-0 transform -translate-x-4">
                                    <span class="text-primary/50">MOHS SCALE</span>
                                    <span>9.0</span>
                                </div>
                            </div>
                            <div class="note-progress absolute top-0 left-0 w-[2px] h-0 bg-primary z-20"></div>
                        </div>
                    </article>

                    <!-- Quote Block -->
                    <div class="note-quote w-full py-12 md:py-24 flex items-center justify-center relative my-12">
                        <div class="absolute left-0 top-1/2 w-8 h-[1px] bg-primary/20"></div>
                        <h3 class="note-quote-text font-h1 text-[clamp(2rem,4vw,3.5rem)] leading-[1.1] uppercase text-center max-w-[800px]" style="text-wrap: balance;">
                            "A mechanical watch isn't<br>powered by batteries.<br>It's powered by engineering."
                        </h3>
                        <div class="absolute right-0 top-1/2 w-8 h-[1px] bg-primary/20"></div>
                    </div>

                    <!-- Article 3: Typography Dominant -->
                    <article class="note-module relative bg-background border border-primary/10 transition-colors duration-300 w-full cursor-pointer overflow-hidden group">
                        <div class="p-6 md:p-10 flex flex-col note-content-wrapper transition-all duration-500 relative z-10">
                            <div class="flex justify-between items-center mb-8">
                                <span class="font-mono text-[9px] uppercase tracking-widest text-primary/50">FIELD NOTE 12</span>
                                <span class="font-mono text-[9px] uppercase tracking-widest text-primary/40 group-hover:text-primary transition-colors duration-300 note-ref">REF: WM-12</span>
                            </div>
                            
                            <h3 class="note-title font-h1 text-4xl md:text-6xl uppercase mb-8 transition-transform duration-500">Case Finishing</h3>
                            
                            <p class="font-mono text-sm md:text-base text-primary leading-relaxed max-w-[800px] mb-8">
                                The transition between brushed and polished surfaces defines the architectural character of a timepiece. Zaratsu polishing, satin brushing, and chamfering require hundreds of hours of manual labor.
                            </p>
                            
                            <div class="note-specs hidden border-t border-primary/10 pt-8 flex-col gap-4">
                                <div class="spec-row flex justify-between font-mono text-[10px] uppercase tracking-widest border-b border-primary/5 pb-4 opacity-0 transform -translate-x-4">
                                    <span class="text-primary/50">TECHNIQUE</span>
                                    <span>ZARATSU POLISHING</span>
                                </div>
                                <div class="spec-row flex justify-between font-mono text-[10px] uppercase tracking-widest pb-4 opacity-0 transform -translate-x-4">
                                    <span class="text-primary/50">DIFFICULTY</span>
                                    <span>LEVEL IV</span>
                                </div>
                            </div>
                            <div class="note-progress absolute top-0 left-0 w-[2px] h-0 bg-primary z-20"></div>
                        </div>
                    </article>

                </div>
            </div>
            
        </div>
    </div>
    
    <!-- 3. New Arrivals Section - Collector's Selection -->
    <div class="w-full section-reveal" id="new-arrival-section">
        <div class="p-lg md:p-2xl border-b border-primary bg-background flex flex-col xl:flex-row justify-between xl:items-end gap-16 overflow-hidden min-h-[300px]">
            <h2 class="new-arrival-heading font-h1 text-[clamp(4rem,10vw,8rem)] leading-none uppercase break-words w-full" style="font-family: 'Satoshi', sans-serif; font-weight: 200; opacity: 0; transform: translateY(40px);">NEW ARRIVAL</h2>
            <a href="{{ route('products.index') }}" class="new-arrival-cta text-primary bg-transparent border border-primary px-xl py-lg font-label-caps uppercase text-xs tracking-[0.2em] hover:bg-black hover:text-white transition-colors duration-500 flex items-center justify-center shrink-0 group" style="opacity: 0;">
                VIEW MORE
                <span class="material-symbols-outlined ml-3 text-[14px] transform transition-transform duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:translate-x-2">arrow_forward</span>
            </a>
        </div>
        
        <!-- Editorial Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 w-full bg-background relative group/grid" id="editorial-products-grid">
            
            <!-- GSAP Constructed Grid Lines -->
            <div class="grid-lines-container absolute inset-0 pointer-events-none z-20">
                <div class="grid-line-h absolute top-0 left-0 w-full h-[1px] bg-primary/20 scale-x-0 origin-left"></div>
                <div class="grid-line-h absolute bottom-0 left-0 w-full h-[1px] bg-primary/20 scale-x-0 origin-left hidden md:block lg:hidden"></div>
                
                <div class="grid-line-v absolute top-0 left-0 w-[1px] h-full bg-primary/20 scale-y-0 origin-top"></div>
                <div class="grid-line-v absolute top-0 left-[25%] w-[1px] h-full bg-primary/20 scale-y-0 origin-top hidden lg:block"></div>
                <div class="grid-line-v absolute top-0 left-[50%] w-[1px] h-full bg-primary/20 scale-y-0 origin-top hidden md:block"></div>
                <div class="grid-line-v absolute top-0 left-[75%] w-[1px] h-full bg-primary/20 scale-y-0 origin-top hidden lg:block"></div>
                <div class="grid-line-v absolute top-0 right-0 w-[1px] h-full bg-primary/20 scale-y-0 origin-top"></div>
            </div>

            @forelse($newArrivals as $product)
                @if($product->stock <= 0)
                <div class="flex flex-col bg-background product-card relative h-full p-0 m-0 border-b md:border-b-0 border-primary/20 lg:border-none cursor-not-allowed group/card transition-all duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] hover:!opacity-100 hover:!grayscale-0 group-hover/grid:opacity-90 group-hover/grid:grayscale-[20%]">
                @else
                <div class="flex flex-col bg-background product-card relative h-full p-0 m-0 border-b md:border-b-0 border-primary/20 lg:border-none cursor-pointer group/card transition-all duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] hover:!opacity-100 hover:!grayscale-0 group-hover/grid:opacity-90 group-hover/grid:grayscale-[20%]" onclick="handlePreNavigation(event, this, '{{ route('products.show', $product->slug) }}')">
                @endif
                
                    <!-- Local Card Background for click expansion -->
                    <div class="card-bg absolute inset-0 bg-background z-0 pointer-events-none"></div>

                    <!-- Card Contents -->
                    <div class="flex-grow flex flex-col relative z-10 w-full h-full p-8 md:p-12 overflow-hidden items-start justify-between">
                        
                        <!-- Top Info -->
                        <div class="w-full flex justify-between items-start mb-12">
                            <span class="product-brand font-mono text-[9px] uppercase tracking-widest text-[#666666] opacity-0 transition-all duration-500 ease-out group-hover/card:!tracking-[0.15em]">{{ $product->collection->name ?? 'CLE' }}</span>
                            <span class="product-price font-mono text-[9px] text-[#666666] opacity-0 transform transition-transform duration-500 ease-out group-hover/card:!translate-x-2">${{ number_format($product->price, 2) }}</span>
                        </div>
                        
                        <!-- Floating Image Area -->
                        <div class="w-full aspect-square flex items-center justify-center relative mb-12">
                            @if ($product->primaryImage)
                                <img src="{{ $product->primaryImage->url }}" 
                                     alt="{{ $product->name }}"
                                     class="product-image w-[80%] h-[80%] object-contain opacity-0 transform transition-all duration-500 ease-out group-hover/card:!-translate-y-3 group-hover/card:!brightness-100 group-hover/card:!contrast-105"
                                     style="transform: translateY(30px); filter: brightness(0.92) contrast(1);" />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-primary/30 text-xs uppercase opacity-0 product-image" style="transform: translateY(30px);">No Image</div>
                            @endif
                            
                            @if($product->stock <= 0)
                            <div class="absolute inset-0 bg-background/40 backdrop-blur-[1px] flex items-center justify-center z-10 product-out-stock opacity-0">
                                <span class="font-mono text-[9px] text-primary tracking-[0.2em] px-3 py-1 border border-primary/20">OUT OF STOCK</span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Details -->
                        <div class="flex flex-col relative z-10 w-full">
                            <h3 class="product-title font-h1 text-xl md:text-2xl uppercase leading-tight mb-3 opacity-0 transform transition-transform duration-500 ease-out group-hover/card:!-translate-y-2" style="font-weight: 400; letter-spacing: normal;">
                                {{ $product->name }}
                            </h3>
                            <p class="product-desc font-body-md text-[11px] text-primary/50 leading-relaxed opacity-0 transition-opacity duration-500 ease-out group-hover/card:!opacity-100">
                                {{ $product->tagline ?? 'An exceptional example of mechanical precision, curated for the serious collector.' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-4 p-3xl text-center font-mono text-[10px] text-primary/50 uppercase tracking-widest">
                    The gallery is currently empty.
                </div>
            @endforelse
        </div>
    </div>
    
<x-magazine-section />

<script>
    // Page-specific GSAP animations
    function initAnimations() {
        
        // 1. Hero Reveal & Canvas Sequence
        gsap.fromTo('.hero-reveal-line', 
            { scaleX: 0 },
            { scaleX: 1, opacity: 1, duration: 1.2, ease: 'expo.out', delay: 0.2 }
        );
        gsap.fromTo('.hero-reveal-text', 
            { y: 30, opacity: 0 },
            { y: 0, opacity: 1, duration: 1.2, stagger: 0.2, ease: 'power4.out', delay: 0.4 }
        );
        gsap.fromTo('.hero-reveal-btn',
            { y: 20, opacity: 0 },
            { y: 0, opacity: 1, duration: 1, ease: 'power3.out', delay: 0.8 }
        );

        // --- Canvas Image Sequence Logic ---
        const canvas = document.getElementById("hero-canvas");
        if (canvas) {
            const context = canvas.getContext("2d");
            
            // Set canvas size (update on resize)
            function resizeCanvas() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                render();
                if (window.ScrollTrigger) ScrollTrigger.refresh();
            }
            window.addEventListener("resize", resizeCanvas);

            const frameCount = 240;
            const basePath = "{{ asset('hero-sequence/ezgif.com-webp-maker-') }}";
            const currentFrame = index => `${basePath}${index + 1}.webp`;

            const images = [];
            const seq = { frame: 0 };

            // Preload images
            for (let i = 0; i < frameCount; i++) {
                const img = new Image();
                img.src = currentFrame(i);
                images.push(img);
            }

            // Draw first frame when it loads
            images[0].onload = resizeCanvas;
            if (images[0].complete) {
                resizeCanvas();
            }

            function render() {
                const frameIndex = Math.round(seq.frame);
                if (!images[frameIndex] || !images[frameIndex].complete || images[frameIndex].naturalWidth === 0) return;
                
                // Calculate aspect ratios to cover the canvas (object-cover equivalent)
                const img = images[frameIndex];
                const canvasRatio = canvas.width / canvas.height;
                const imgRatio = img.width / img.height;
                
                let drawWidth = canvas.width;
                let drawHeight = canvas.height;
                let offsetX = 0;
                let offsetY = 0;

                if (imgRatio > canvasRatio) {
                    drawWidth = canvas.height * imgRatio;
                    offsetX = (canvas.width - drawWidth) / 2;
                } else {
                    drawHeight = canvas.width / imgRatio;
                    offsetY = (canvas.height - drawHeight) / 2;
                }
                
                context.clearRect(0, 0, canvas.width, canvas.height);
                context.drawImage(img, offsetX, offsetY, drawWidth, drawHeight);
            }

            gsap.to(seq, {
                frame: frameCount - 1,
                snap: "frame",
                ease: "none",
                scrollTrigger: {
                    trigger: "#hero-sequence-container",
                    start: "top top",
                    end: "bottom bottom",
                    scrub: 0.5 // Emil Kowalski principle: subtle smooth interpolation instead of raw 1:1 rigid tie
                },
                onUpdate: render
            });
        }
        // -----------------------------------

        // 2. Editorial Brand Story Reveal
        const storySection = document.getElementById('brand-story-section');
        if (storySection && typeof SplitType !== 'undefined') {
            const divider = storySection.querySelector('.story-divider');
            const heading = storySection.querySelector('.story-heading');
            const p1 = storySection.querySelector('.story-p1');
            const p2 = storySection.querySelector('.story-p2');

            // Split text into lines
            const splitP1 = new SplitType(p1, { types: 'lines', lineClass: 'story-line' });
            const splitP2 = new SplitType(p2, { types: 'lines', lineClass: 'story-line' });

            // Restore keywords inside lines for emphasis
            const keywords = ['precision', 'mechanics', 'heritage', 'complications', 'mechanical'];
            storySection.querySelectorAll('.story-line').forEach(line => {
                let html = line.innerHTML;
                keywords.forEach(kw => {
                    const regex = new RegExp(`\\b(${kw})\\b`, 'gi');
                    html = html.replace(regex, `<span class="keyword-emphasis transition-all duration-300" style="font-weight: 400;">$1</span>`);
                });
                line.innerHTML = html;
            });

            // Initial line states (muted gray)
            gsap.set('.story-line', { opacity: 0.2, color: 'rgba(255, 255, 255, 0.4)' });

            // Main Scrub Timeline
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: storySection,
                    start: 'top 75%',
                    end: 'bottom 40%',
                    scrub: 1, // Smooth tactical scrubbing
                }
            });

            // Step 1: Divider expands
            tl.to(divider, { scaleX: 1, duration: 0.6, ease: 'power2.out' }, 0);
            
            // Step 2: Heading tracking reveal
            tl.to(heading, { letterSpacing: '0em', opacity: 1, duration: 0.8, ease: 'power2.out' }, 0.2);

            // P1 Reading Progress
            const p1Lines = p1.querySelectorAll('.story-line');
            p1Lines.forEach((line) => {
                tl.to(line, {
                    opacity: 1,
                    color: '#ffffff',
                    duration: 1,
                    ease: 'none',
                    onUpdate: function() {
                        const keywordsInLine = line.querySelectorAll('.keyword-emphasis');
                        if (this.progress() > 0.8) {
                            keywordsInLine.forEach(k => k.style.fontWeight = '600');
                        } else {
                            keywordsInLine.forEach(k => k.style.fontWeight = '400');
                        }
                    }
                }, '+=0'); // Sequential
            });

            // Visual breathing room
            tl.to({}, { duration: 2 }); // Add empty space in timeline

            // P2 Reading Progress
            const p2Lines = p2.querySelectorAll('.story-line');
            p2Lines.forEach((line) => {
                tl.to(line, {
                    opacity: 1,
                    color: '#ffffff',
                    duration: 1,
                    ease: 'none',
                    onUpdate: function() {
                        const keywordsInLine = line.querySelectorAll('.keyword-emphasis');
                        if (this.progress() > 0.8) {
                            keywordsInLine.forEach(k => k.style.fontWeight = '600');
                        } else {
                            keywordsInLine.forEach(k => k.style.fontWeight = '400');
                        }
                    }
                }, '+=0');
            });
        }


        // 2.5 THE DROP — Private Allocation Room
        const dropSection = document.getElementById('drop-section');
        if (dropSection) {
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const easeOut = 'power4.out';       // cubic-bezier(0.23, 1, 0.32, 1)
            const easeInOut = 'power3.inOut';   // cubic-bezier(0.77, 0, 0.175, 1)

            document.querySelectorAll('.drop-container').forEach((container) => {
                const wrapper = container.querySelector('.drop-pin-wrapper');
                const bg = container.querySelector('.drop-bg');
                const titleThe = container.querySelector('.drop-title-the');
                const titleDrop = container.querySelector('.drop-title-drop');
                const productImg = container.querySelector('.drop-product-img');
                const spotlight = container.querySelector('.drop-spotlight');
                const scanBeam = container.querySelector('.drop-scan-beam');
                const hudLabels = container.querySelectorAll('.drop-hud-label');
                const callouts = container.querySelectorAll('.drop-callout');
                const details = container.querySelectorAll('.drop-detail');
                const divider = container.querySelector('.drop-divider');
                const countdownBlock = container.querySelector('.drop-countdown-block');
                const allocationBlock = container.querySelector('.drop-allocation-block');
                const allocBar = container.querySelector('.drop-alloc-bar');
                const ctaWrapper = container.querySelector('.drop-cta-wrapper');
                const ctaSweep = container.querySelector('.drop-cta-sweep');
                const productWrapper = container.querySelector('.drop-product-wrapper');

                if (prefersReducedMotion) {
                    // Reduced motion: show everything immediately, no transforms
                    gsap.set(bg, { opacity: 1 });
                    gsap.set(titleThe, { opacity: 1, letterSpacing: '-0.02em' });
                    gsap.set(titleDrop, { opacity: 1, clipPath: 'inset(0 0 0 0)' });
                    if (productImg) gsap.set(productImg, { opacity: 1, y: 0 });
                    gsap.set(spotlight, { opacity: 1 });
                    hudLabels.forEach(l => gsap.set(l, { color: 'rgba(255,255,255,0.5)' }));
                    details.forEach(d => gsap.set(d, { opacity: 1 }));
                    gsap.set(divider, { scaleX: 1 });
                    gsap.set(countdownBlock, { opacity: 1 });
                    gsap.set(allocationBlock, { opacity: 1 });
                    if (allocBar) gsap.set(allocBar, { scaleX: 1 });
                    if (ctaWrapper) gsap.set(ctaWrapper, { opacity: 1 });
                    return;
                }

                // ========================================
                // MAIN SCROLL TIMELINE (Phases 1-4, 9)
                // ========================================
                const mainTl = gsap.timeline({
                    scrollTrigger: {
                        trigger: container,
                        start: 'top 80%',
                        end: 'bottom 20%',
                        scrub: 0.8,
                        // Soft pin feel: not an actual pin, but scrub resistance over the scroll range
                    }
                });

                // --- PHASE 1: Initialization ---
                // BG fades to full black
                mainTl.to(bg, { opacity: 1, duration: 0.5, ease: 'none' }, 0);

                // "THE" appears: letter-spacing compresses from 0.4em -> -0.02em
                mainTl.to(titleThe, {
                    opacity: 1,
                    letterSpacing: '-0.02em',
                    duration: 1.4,
                    ease: easeOut
                }, 0.2);

                // "drop" stroke reveal via clip-path
                mainTl.to(titleDrop, {
                    opacity: 1,
                    clipPath: 'inset(0 0 0 0)',
                    duration: 1.6,
                    ease: easeInOut
                }, 0.8);

                // --- PHASE 2: Product Arrival ---
                if (productImg) {
                    // Watch rises 60px upward with opacity
                    mainTl.to(productImg, {
                        y: 0,
                        opacity: 1,
                        duration: 1.8,
                        ease: easeOut
                    }, 1.5);

                    // Museum spotlight fades in
                    mainTl.to(spotlight, {
                        opacity: 1,
                        duration: 1.2,
                        ease: 'power2.out'
                    }, 1.8);
                }

                // --- PHASE 3: Authentication Scan ---
                // Scan beam travels top to bottom
                mainTl.to(scanBeam, {
                    opacity: 0.7,
                    duration: 0.3,
                    ease: 'none'
                }, 3);
                mainTl.to(scanBeam, {
                    top: '110%',
                    duration: 2.4,
                    ease: 'none'
                }, 3);
                mainTl.to(scanBeam, {
                    opacity: 0,
                    duration: 0.3,
                    ease: 'none'
                }, 5.1);

                // HUD labels appear sequentially
                hudLabels.forEach((label, i) => {
                    mainTl.to(label, {
                        color: 'rgba(255,255,255,0.5)',
                        duration: 0.6,
                        ease: 'power2.out'
                    }, 3.3 + (i * 0.5));
                });

                // --- PHASE 4: Mechanical Callouts (scroll-scrubbed) ---
                callouts.forEach((callout, i) => {
                    const startTime = 5.5 + (i * 1.2);
                    // Fade in
                    mainTl.to(callout, {
                        opacity: 1,
                        duration: 0.6,
                        ease: 'power2.out'
                    }, startTime);
                    // Fade out before next
                    if (i < callouts.length - 1) {
                        mainTl.to(callout, {
                            opacity: 0,
                            duration: 0.4,
                            ease: 'power2.in'
                        }, startTime + 0.8);
                    }
                });

                // --- Right-side details reveal (staggered, starts around phase 2-3) ---
                details.forEach((detail, i) => {
                    mainTl.to(detail, {
                        opacity: 1,
                        duration: 0.8,
                        ease: easeOut
                    }, 2.5 + (i * 0.3));
                });

                // Divider draws left to right
                mainTl.to(divider, {
                    scaleX: 1,
                    duration: 1.2,
                    ease: easeInOut
                }, 3.5);

                // Countdown block
                mainTl.to(countdownBlock, {
                    opacity: 1,
                    duration: 0.6,
                    ease: easeOut
                }, 4);

                // Allocation block
                mainTl.to(allocationBlock, {
                    opacity: 1,
                    duration: 0.6,
                    ease: easeOut
                }, 4.3);

                // Allocation bar draws
                if (allocBar) {
                    mainTl.to(allocBar, {
                        scaleX: 1,
                        duration: 1,
                        ease: easeInOut
                    }, 4.5);
                }

                // CTA wrapper appears
                if (ctaWrapper) {
                    mainTl.to(ctaWrapper, {
                        opacity: 1,
                        duration: 0.6,
                        ease: easeOut
                    }, 5);
                }

                // --- PHASE 9: Scroll Exit ---
                // Watch slightly enlarges as user leaves
                if (productImg) {
                    gsap.to(productImg, {
                        scale: 1.03,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: container,
                            start: 'bottom 60%',
                            end: 'bottom top',
                            scrub: true
                        }
                    });
                }

                // "THE" tracking re-expands on exit
                gsap.to(titleThe, {
                    letterSpacing: '0.15em',
                    ease: 'none',
                    scrollTrigger: {
                        trigger: container,
                        start: 'bottom 50%',
                        end: 'bottom top',
                        scrub: true
                    }
                });

                // "drop" fades on exit
                gsap.to(titleDrop, {
                    opacity: 0.3,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: container,
                        start: 'bottom 40%',
                        end: 'bottom top',
                        scrub: true
                    }
                });

                // Spotlight disappears on exit
                gsap.to(spotlight, {
                    opacity: 0,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: container,
                        start: 'bottom 50%',
                        end: 'bottom top',
                        scrub: true
                    }
                });

                // ========================================
                // PHASE 5: Floating Idle (always active)
                // ========================================
                if (productImg) {
                    gsap.to(productImg, {
                        y: '+=6',
                        duration: 3,
                        ease: 'sine.inOut',
                        yoyo: true,
                        repeat: -1,
                    });
                }

                // ========================================
                // PHASE 8: CTA Activation (Setup)
                // ========================================
                // We no longer trigger the sweep blindly on scroll.
                // We set up a custom event that the CRM logic can fire when the countdown reaches zero.
                if (ctaWrapper && ctaSweep) {
                    const ctaBtn = container.querySelector('.drop-btn');
                    const ctaBtnText = container.querySelector('.drop-btn-text');
                    
                    ctaBtn.addEventListener('dropTimeReached', () => {
                        if (ctaBtn.dataset.activated === 'true') return;
                        ctaBtn.dataset.activated = 'true';
                        
                        const ctaTl = gsap.timeline();

                        // Sweep line crosses
                        ctaTl.to(ctaSweep, {
                            x: '101%',
                            duration: 0.5,
                            ease: easeInOut
                        });

                        // After sweep, change text & update styles
                        ctaTl.add(() => {
                            if (ctaBtnText) {
                                ctaBtnText.innerHTML = '<span>REQUEST ACCESS</span><span class="material-symbols-outlined text-[14px]" style="transform: translateX(0); transition: transform 300ms cubic-bezier(0.23, 1, 0.32, 1);">arrow_forward</span>';
                            }
                            // Enable clicks
                            ctaBtn.classList.remove('opacity-50', 'pointer-events-none', 'text-white/50');
                            ctaBtn.classList.add('hover:bg-white', 'hover:text-black', 'text-white');
                            
                            // Sweep exits right
                            gsap.to(ctaSweep, {
                                x: '202%',
                                duration: 0.4,
                                ease: easeOut
                            });
                        }, '+=0');
                    });
                }

                // ========================================
                // MICRO INTERACTIONS
                // ========================================
                // Hover product: 2° rotation
                if (productWrapper) {
                    productWrapper.addEventListener('mouseenter', () => {
                        gsap.to(productImg, { rotate: 2, duration: 0.4, ease: easeOut });
                    });
                    productWrapper.addEventListener('mouseleave', () => {
                        gsap.to(productImg, { rotate: 0, duration: 0.4, ease: easeOut });
                    });
                }

                // Hover title: tracking increases
                const titleBlock = container.querySelector('.drop-detail-title');
                if (titleBlock) {
                    titleBlock.addEventListener('mouseenter', () => {
                        gsap.to(titleBlock, { letterSpacing: '0.02em', duration: 0.3, ease: easeOut });
                    });
                    titleBlock.addEventListener('mouseleave', () => {
                        gsap.to(titleBlock, { letterSpacing: '-0.02em', duration: 0.3, ease: easeOut });
                    });
                }

                // Hover price: tiny opacity transition
                const priceBlock = container.querySelector('.drop-detail-price');
                if (priceBlock) {
                    priceBlock.addEventListener('mouseenter', () => {
                        gsap.to(priceBlock, { opacity: 0.7, duration: 0.2, ease: 'power1.out' });
                    });
                    priceBlock.addEventListener('mouseleave', () => {
                        gsap.to(priceBlock, { opacity: 1, duration: 0.2, ease: 'power1.in' });
                    });
                }

                // Hover allocation: progress bar animates once
                if (allocBar) {
                    const allocBlock = container.querySelector('.drop-allocation-block');
                    if (allocBlock) {
                        allocBlock.addEventListener('mouseenter', () => {
                            gsap.fromTo(allocBar,
                                { scaleX: 0 },
                                { scaleX: 1, duration: 0.6, ease: easeInOut }
                            );
                        });
                    }
                }
            }); // end forEach drop-container

            // ========================================
            // PHASE 6: Airport Flip-Clock Countdown
            // ========================================
            function flipDigit(digitEl, newValue) {
                const current = digitEl.querySelector('.flip-current');
                const next = digitEl.querySelector('.flip-next');
                if (!current || !next) return;
                if (current.textContent === newValue) return;

                next.textContent = newValue;
                gsap.set(next, { y: '100%' });

                gsap.to(current, {
                    y: '-100%',
                    duration: 0.35,
                    ease: 'power2.in',
                    onComplete: () => {
                        current.textContent = newValue;
                        gsap.set(current, { y: '0%' });
                    }
                });
                gsap.to(next, {
                    y: '0%',
                    duration: 0.35,
                    ease: easeOut,
                });
            }

            function updateFlipClocks() {
                const now = Date.now() - (window.serverTimeOffset || 0);
                document.querySelectorAll('.drop-flip-clock').forEach(clock => {
                    const dropId = clock.getAttribute('data-drop-id');
                    const btn = document.querySelector(`.drop-btn[data-id="${dropId}"]`);
                    if (!btn) return;

                    const dropTimeBase = parseInt(btn.getAttribute('data-drop-time'));
                    const targetTime = window.isVip ? dropTimeBase : dropTimeBase + (10 * 60 * 1000);
                    const diff = Math.max(0, targetTime - now);

                    const h = Math.floor(diff / (1000 * 60 * 60)).toString().padStart(2, '0');
                    const m = Math.floor((diff / (1000 * 60)) % 60).toString().padStart(2, '0');
                    const s = Math.floor((diff / 1000) % 60).toString().padStart(2, '0');

                    const digits = [h[0], h[1], m[0], m[1], s[0], s[1]];
                    const allDigits = clock.querySelectorAll('.flip-digit');
                    allDigits.forEach((el, i) => {
                        if (digits[i] !== undefined) flipDigit(el, digits[i]);
                    });
                });
            }

            setInterval(updateFlipClocks, 1000);
            updateFlipClocks(); // initial call

            // ========================================
            // PHASE 7: Allocation Simulation (demo)
            // ========================================
            setInterval(() => {
                document.querySelectorAll('.drop-alloc-count').forEach(el => {
                    const match = el.textContent.match(/(\d+)\s*\/\s*(\d+)/);
                    if (match) {
                        let current = parseInt(match[1]);
                        const total = parseInt(match[2]);
                        if (current > 1) {
                            current--;
                            el.textContent = `${current} / ${total}`;
                            // Update bar width
                            const bar = el.closest('.drop-allocation-block')?.querySelector('.drop-alloc-bar');
                            if (bar) {
                                gsap.to(bar, {
                                    width: `${(current / total) * 100}%`,
                                    duration: 0.5,
                                    ease: easeOut
                                });
                            }
                        }
                    }
                });
            }, 60000); // Every 60s
        } // end dropSection
        
        // NEW: Horology Timeline Animations Placeholder
        const horologySection = document.getElementById('horology-timeline-section');
        if (horologySection && !window.matchMedia('(prefers-reduced-motion: reduce)').matches && window.innerWidth > 768) {
            
            // 1. Pinned Timeline Scroll
            const track = horologySection.querySelector('.timeline-track');
            const scenes = gsap.utils.toArray('.timeline-scene');
            const totalWidth = track.scrollWidth - window.innerWidth;
            
            const timelineScroll = gsap.to(track, {
                x: () => -totalWidth + "px",
                ease: "none",
                scrollTrigger: {
                    trigger: horologySection,
                    pin: '.timeline-sticky-container',
                    start: "top top",
                    end: () => "+=" + (totalWidth),
                    scrub: 1,
                    invalidateOnRefresh: true,
                    onUpdate: (self) => {
                        gsap.set('.timeline-progress-fill', { scaleX: self.progress });
                    }
                }
            });

            // 2. Entry Sequence (Header & Grid)
            gsap.timeline({
                scrollTrigger: {
                    trigger: horologySection,
                    start: "top 60%",
                }
            })
            .to('.timeline-header-line', { scaleX: 1, duration: 1, ease: 'power3.inOut' })
            .to('.timeline-header-title', { y: 0, duration: 1, ease: 'power3.out' }, "-=0.5")
            .to('.timeline-blueprint-grid', { opacity: 1, duration: 2 }, "-=0.5");

            // 3. Scene Animations (Triggered as they enter horizontally via Container Animation)
            scenes.forEach((scene, i) => {
                const yearBg = scene.querySelector('.timeline-year-bg');
                const title = scene.querySelector('.timeline-scene-title');
                const desc = scene.querySelector('.timeline-scene-desc');
                const ref = scene.querySelector('.timeline-scene-ref');
                const imgWrap = scene.querySelector('.timeline-scene-img-wrap');
                const img = scene.querySelector('.timeline-scene-img');
                const blueprintLines = scene.querySelectorAll('.blueprint-line, .blueprint-circle');
                const labels = scene.querySelectorAll('.blueprint-mark, .timeline-museum-label');
                
                // Split text for editorial fade
                const splitDesc = new SplitType(desc, { types: 'lines' });

                gsap.timeline({
                    scrollTrigger: {
                        trigger: scene,
                        containerAnimation: timelineScroll,
                        start: "left center",
                        toggleActions: "play none none reverse"
                    }
                })
                .to(yearBg, { y: 0, opacity: 1, duration: 1, ease: 'power3.out' })
                .to(imgWrap, { clipPath: 'inset(0% 0 0 0)', duration: 1.2, ease: 'power4.inOut' }, "-=0.8")
                .to(img, { scale: 1, duration: 1.2, ease: 'power3.out' }, "-=1.2")
                .to(title, { opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' }, "-=0.8")
                .to(ref, { opacity: 1, duration: 0.5 }, "-=0.6")
                .to(splitDesc.lines, { opacity: 1, y: 0, duration: 0.8, stagger: 0.1, ease: 'power2.out' }, "-=0.6")
                .to(blueprintLines, { strokeDashoffset: 0, duration: 1.5, ease: 'power2.inOut', stagger: 0.2 }, "-=1")
                .to(labels, { opacity: 1, duration: 0.5 }, "-=0.5");

                // Tiny Mouse Parallax
                scene.addEventListener('mousemove', (e) => {
                    if (img) {
                        const rect = scene.getBoundingClientRect();
                        const x = (e.clientX - rect.left) / rect.width - 0.5;
                        const y = (e.clientY - rect.top) / rect.height - 0.5;
                        gsap.to(img, { x: x * 10, y: y * 10, duration: 1, ease: 'power2.out' });
                        gsap.to(blueprintLines, { x: x * -5, y: y * -5, duration: 1, ease: 'power2.out' });
                    }
                });
            });

            // 4. Final Scene Dissolve to Movement Lab
            const lastScene = scenes[scenes.length - 1];
            gsap.timeline({
                scrollTrigger: {
                    trigger: lastScene,
                    containerAnimation: timelineScroll,
                    start: "left left",
                    end: "right left",
                    scrub: true
                }
            })
            .to('.timeline-blueprint-grid', { opacity: 0, duration: 1 })
            .to(lastScene.querySelector('.timeline-scene-img-wrap'), { filter: 'blur(10px) brightness(1.5)', opacity: 0, duration: 1 }, 0);
            
        } else if (horologySection && window.innerWidth <= 768) {
            // MOBILE: Vertical scroll animations (no pinned horizontal scroll)
            const stickyContainer = horologySection.querySelector('.timeline-sticky-container');
            if (stickyContainer) {
                stickyContainer.style.height = 'auto';
                stickyContainer.style.overflow = 'visible';
            }

            // Entry Sequence (Header & Grid) — same as desktop
            gsap.timeline({
                scrollTrigger: {
                    trigger: horologySection,
                    start: "top 80%",
                }
            })
            .to('.timeline-header-line', { scaleX: 1, duration: 1, ease: 'power3.inOut' })
            .to('.timeline-header-title', { y: 0, duration: 1, ease: 'power3.out' }, "-=0.5")
            .to('.timeline-blueprint-grid', { opacity: 1, duration: 2 }, "-=0.5");

            // Scene Animations — triggered vertically as each scene enters viewport
            const mobileScenes = horologySection.querySelectorAll('.timeline-scene');
            mobileScenes.forEach((scene) => {
                const yearBg = scene.querySelector('.timeline-year-bg');
                const title = scene.querySelector('.timeline-scene-title');
                const desc = scene.querySelector('.timeline-scene-desc');
                const ref = scene.querySelector('.timeline-scene-ref');
                const imgWrap = scene.querySelector('.timeline-scene-img-wrap');
                const img = scene.querySelector('.timeline-scene-img');
                const blueprintLines = scene.querySelectorAll('.blueprint-line, .blueprint-circle');
                const labels = scene.querySelectorAll('.blueprint-mark, .timeline-museum-label');

                // Split text for editorial fade
                if (typeof SplitType !== 'undefined') {
                    const splitDesc = new SplitType(desc, { types: 'lines' });

                    gsap.timeline({
                        scrollTrigger: {
                            trigger: scene,
                            start: "top 85%",
                            toggleActions: "play none none reverse"
                        }
                    })
                    .to(yearBg, { y: 0, opacity: 1, duration: 0.8, ease: 'power3.out' })
                    .to(ref, { opacity: 1, duration: 0.4 }, "-=0.4")
                    .to(title, { opacity: 1, y: 0, duration: 0.7, ease: 'power2.out' }, "-=0.3")
                    .to(splitDesc.lines, { opacity: 1, y: 0, duration: 0.6, stagger: 0.08, ease: 'power2.out' }, "-=0.4")
                    .to(imgWrap, { clipPath: 'inset(0% 0 0 0)', duration: 1, ease: 'power4.inOut' }, "-=0.6")
                    .to(img, { scale: 1, duration: 1, ease: 'power3.out' }, "-=1")
                    .to(blueprintLines, { strokeDashoffset: 0, duration: 1.2, ease: 'power2.inOut', stagger: 0.15 }, "-=0.8")
                    .to(labels, { opacity: 1, duration: 0.4 }, "-=0.4");
                }
            });
        }


        const movementSection = document.getElementById('movement-lab-section');
        if (movementSection && !window.matchMedia('(prefers-reduced-motion: reduce)').matches && window.innerWidth > 768) {
            
            // Elements
            const svgContainer = movementSection.querySelector('.ml-svg-container');
            const blueprintGrid = movementSection.querySelector('.ml-blueprint-grid');
            const techLabels = movementSection.querySelector('.ml-tech-labels');
            const editorialPanel = movementSection.querySelector('.ml-editorial-panel');
            const connectLine = movementSection.querySelector('.ml-connect-line');
            const finalMsg = movementSection.querySelector('.ml-final-msg');
            const components = movementSection.querySelectorAll('.ml-component');
            const hoverIndicator = document.getElementById('ml-hover-indicator');
            const specMarkers = movementSection.querySelectorAll('.ml-spec-marker');
            
            // Text elements in panel
            const pTitle = movementSection.querySelector('.ml-panel-title');
            const pDesc = movementSection.querySelector('.ml-panel-desc');
            const pRef = movementSection.querySelector('.ml-panel-ref');
            const pCat = movementSection.querySelector('.ml-spec-cat');
            const pFreq = movementSection.querySelector('.ml-spec-freq');
            
            // Component Data for Editorial Panel
            const compData = {
                'rotor': { title: 'Rotor', ref: 'ML-001', cat: 'Winding', freq: 'N/A', desc: 'A heavy semi-circular metal weight that rotates freely with the motion of the wearer\'s wrist, winding the mainspring to store energy.' },
                'bridge': { title: 'Main Bridge', ref: 'ML-002', cat: 'Architecture', freq: 'N/A', desc: 'The structural foundation that secures the gear train in place. Machined to tolerances of mere microns to ensure perfect gear meshing.' },
                'gears': { title: 'Gear Train', ref: 'ML-003', cat: 'Transmission', freq: 'N/A', desc: 'A series of precisely calculated wheels and pinions that transmit energy from the mainspring to the escapement, dividing time into seconds, minutes, and hours.' },
                'escapement': { title: 'Escapement', ref: 'ML-004', cat: 'Distribution', freq: '28,800 BPH', desc: 'The defining mechanism of a mechanical watch. It catches and releases the energy of the gear train in tiny, exact intervals, producing the iconic ticking sound.' },
                'balance': { title: 'Balance Wheel', ref: 'ML-005', cat: 'Regulation', freq: '4 Hz', desc: 'The heartbeat of the movement. Oscillating back and forth with incredible precision, its frequency determines the ultimate accuracy of the timepiece.' },
                'barrel': { title: 'Main Barrel', ref: 'ML-006', cat: 'Energy Storage', freq: '72 Hours', desc: 'Houses the coiled mainspring. As the spring unwinds over 72 hours, it provides the pure mechanical force required to power the entire movement.' }
            };

            // Master Timeline
            const mlTl = gsap.timeline({
                scrollTrigger: {
                    trigger: movementSection,
                    pin: '.ml-sticky-container',
                    start: "top top",
                    end: "+=5000",
                    scrub: 1,
                    onUpdate: (self) => {
                        updateActiveComponent(self.progress);
                    }
                }
            });

            // PHASE 01: Transition & Engineering Mode (Scale up & Draw labels)
            mlTl.to(svgContainer, { scale: 1, duration: 1.5, ease: 'power2.inOut' }, 0)
                .to(blueprintGrid, { opacity: 1, duration: 1 }, 0.5)
                .to(techLabels, { opacity: 1, duration: 1 }, 1);

            // PHASE 02: Exploded View (Translate SVG groups)
            // Absolute time mapping makes scrubbing predictable
            mlTl.to('#ml-rotor', { y: -80, duration: 1.5, ease: 'power1.inOut' }, 2.5)
                .to('#ml-bridge', { x: 60, duration: 1.5, ease: 'power1.inOut' }, 2.5)
                .to('#ml-gear-train', { x: -70, duration: 1.5, ease: 'power1.inOut' }, 2.5)
                .to('#ml-escapement', { y: 50, x: -20, duration: 1.5, ease: 'power1.inOut' }, 2.5)
                .to('#ml-balance', { x: 80, y: 60, duration: 1.5, ease: 'power1.inOut' }, 2.5)
                .to('#ml-barrel', { x: -60, y: -60, duration: 1.5, ease: 'power1.inOut' }, 2.5)
                .to(techLabels, { opacity: 0, duration: 0.5 }, 2.5);

            // PHASE 03: Component Analysis (Scroll linked via onUpdate)
            // Show Editorial Panel & Connecting Line
            mlTl.to(editorialPanel, { opacity: 1, duration: 0.5 }, 4)
                .to('.ml-connect-line', { opacity: 1, strokeDashoffset: 0, duration: 1, ease: 'power2.out' }, 4);

            // Phase 04: Micro Details (Tiny specs appear)
            mlTl.to(specMarkers, { opacity: 1, duration: 1, stagger: 0.2 }, 4.5);

            // Hold phase 03/04 for a long scroll duration (progress mapped in updateActiveComponent)
            mlTl.to({}, { duration: 8 });

            // PHASE 05: Precision (Reassembly)
            mlTl.to(editorialPanel, { opacity: 0, duration: 0.5 }, 13)
                .to('.ml-connect-line', { opacity: 0, duration: 0.5 }, 13)
                .to(specMarkers, { opacity: 0, duration: 0.5 }, 13)
                .to(components, { x: 0, y: 0, opacity: 1, duration: 1.5, ease: 'back.out(1.2)' }, 13.5); // Snap back together

            // Final Message
            mlTl.to(finalMsg, { opacity: 1, duration: 1 }, 15)
                .to('.ml-final-text-1', { opacity: 1, y: 0, duration: 0.8 }, 15.5)
                .to('.ml-final-text-2', { opacity: 1, y: 0, duration: 0.8 }, 15.8)
                .to({}, { duration: 1 }); // Padding before unpin

            // Helper to update active component based on scroll progress (28% to 78% of scroll space)
            let currentActive = null;
            function updateActiveComponent(progress) {
                // If before explosion or after reassembly, reset highlights
                if (progress < 0.28 || progress > 0.78) {
                    if (currentActive) {
                        gsap.to(components, { opacity: 1, duration: 0.3 });
                        currentActive = null;
                    }
                    return;
                }
                
                // Map progress to 6 segments
                const compArray = ['rotor', 'bridge', 'barrel', 'gears', 'escapement', 'balance'];
                const segmentProgress = (progress - 0.28) / 0.5; // 0 to 1
                let index = Math.floor(segmentProgress * 6);
                if (index > 5) index = 5;
                if (index < 0) index = 0;
                
                const activeId = compArray[index];
                
                if (currentActive !== activeId) {
                    currentActive = activeId;
                    
                    // Dim others, highlight active (100% vs 35% opacity as per spec)
                    components.forEach(c => {
                        gsap.to(c, { opacity: c.dataset.id === activeId ? 1 : 0.35, duration: 0.3 });
                    });
                    
                    // Update Editorial Panel Content dynamically
                    const data = compData[activeId];
                    if (data) {
                        // Fade out, update text, fade in
                        gsap.to([pTitle, pDesc, pRef, pCat, pFreq], { 
                            opacity: 0, 
                            duration: 0.2, 
                            onComplete: () => {
                                pTitle.textContent = data.title;
                                pDesc.textContent = data.desc;
                                pRef.textContent = data.ref;
                                pCat.textContent = data.cat;
                                pFreq.textContent = data.freq;
                                gsap.to([pTitle, pDesc, pRef, pCat, pFreq], { opacity: 1, duration: 0.2 });
                            }
                        });
                    }

                    // Animate connecting line target
                    const path = connectLine;
                    // Approximate SVG viewport coordinates based on component final positions
                    const targets = {
                        'rotor': '50 20',
                        'bridge': '65 40',
                        'barrel': '35 30',
                        'gears': '40 50',
                        'escapement': '55 65',
                        'balance': '70 70'
                    };
                    gsap.to(path, { attr: { d: `M 25 50 L ${targets[activeId]}` }, duration: 0.4, ease: 'power2.out' });
                }
            }

            // Phase 04: Mouse Hover & Parallax
            components.forEach(comp => {
                comp.addEventListener('mouseenter', () => {
                    // Only active during exploded view
                    if (mlTl.progress() > 0.15 && mlTl.progress() < 0.85) {
                        gsap.to(hoverIndicator, { opacity: 1, duration: 0.2 });
                        gsap.to(comp, { filter: 'brightness(1.1) drop-shadow(0 0 5px rgba(0,0,0,0.1))', duration: 0.2 });
                    }
                });
                comp.addEventListener('mouseleave', () => {
                    gsap.to(hoverIndicator, { opacity: 0, duration: 0.2 });
                    gsap.to(comp, { filter: 'none', duration: 0.2 });
                });
                comp.addEventListener('mousemove', (e) => {
                    if (mlTl.progress() > 0.15 && mlTl.progress() < 0.85) {
                        const svgRect = svgContainer.getBoundingClientRect();
                        // Map mouse position to SVG viewBox (-100 to 100)
                        const x = ((e.clientX - svgRect.left) / svgRect.width) * 200 - 100;
                        const y = ((e.clientY - svgRect.top) / svgRect.height) * 200 - 100;
                        
                        gsap.to(hoverIndicator, { 
                            attr: { cx: x, cy: y }, 
                            duration: 0.1, 
                            ease: 'none' 
                        });
                        
                        // Subtle 4px translation on the hovered component
                        gsap.to(comp, { 
                            x: `+=${(e.movementX * 0.1)}`, 
                            y: `+=${(e.movementY * 0.1)}`, 
                            duration: 0.5, 
                            ease: 'power2.out',
                            overwrite: 'auto'
                        });
                    }
                });
            });

            // Global Blueprint Layer Parallax
            movementSection.addEventListener('mousemove', (e) => {
                const rect = movementSection.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width - 0.5;
                const y = (e.clientY - rect.top) / rect.height - 0.5;
                
                // Labels move 15%
                gsap.to(techLabels, { x: x * 30, y: y * 30, duration: 1, ease: 'power2.out' });
                // Grid moves 10%
                gsap.to(blueprintGrid, { backgroundPosition: `${x * 20}px ${y * 20}px`, duration: 1, ease: 'power2.out' });
            });
        }

        // 3.5 Watchmaker's Notes
        const notesSection = document.getElementById('notes-section');
        if (notesSection && typeof gsap !== 'undefined') {
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            
            // Elements
            const labels = notesSection.querySelector('.notes-labels');
            const title = notesSection.querySelector('.notes-title');
            const intro = notesSection.querySelector('.notes-intro');
            const lines = {
                top: notesSection.querySelector('.notes-line-top'),
                bottom: notesSection.querySelector('.notes-line-bottom'),
                left: notesSection.querySelector('.notes-line-left'),
                right: notesSection.querySelector('.notes-line-right')
            };
            const articles = notesSection.querySelectorAll('.note-module');
            const gridBg = notesSection.querySelector('.notes-grid');

            if (prefersReducedMotion) {
                gsap.set([labels, intro], { opacity: 1, y: 0 });
                gsap.set(title, { clipPath: 'inset(0 0 0 0)' });
                gsap.set(Object.values(lines), { scaleX: 1, scaleY: 1 });
                gsap.set('.note-img', { clipPath: 'inset(0 0 0 0)' });
            } else {
                // Section Entry Timeline
                const tl = gsap.timeline({
                    scrollTrigger: {
                        trigger: notesSection,
                        start: 'top 75%',
                        end: 'bottom bottom'
                    }
                });

                tl.to(labels, { opacity: 1, duration: 0.9, ease: 'power3.out' }, 0)
                  .to(title, { clipPath: 'inset(0 0 0 0)', duration: 0.9, ease: 'power3.out' }, 0.2)
                  .to(lines.top, { scaleX: 1, duration: 0.6, ease: 'power2.inOut' }, 0.3)
                  .to(lines.bottom, { scaleX: 1, duration: 0.6, ease: 'power2.inOut' }, 0.5)
                  .to(lines.left, { scaleY: 1, duration: 0.6, ease: 'power2.inOut' }, 0.7)
                  .to(lines.right, { scaleY: 1, duration: 0.6, ease: 'power2.inOut' }, 0.9)
                  .to(intro, { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out' }, 0.6);

                // Article Reveal
                articles.forEach((article, i) => {
                    const img = article.querySelector('.note-img');
                    if (img) {
                        gsap.to(img, {
                            clipPath: 'inset(0 0 0 0)',
                            duration: 0.9,
                            ease: 'power3.out',
                            scrollTrigger: {
                                trigger: article,
                                start: 'top 85%'
                            }
                        });
                    }
                });

                // Scroll Parallax
                gsap.to(gridBg, {
                    backgroundPosition: '0px 100px',
                    ease: 'none',
                    scrollTrigger: {
                        trigger: notesSection,
                        start: 'top bottom',
                        end: 'bottom top',
                        scrub: true
                    }
                });

                // Quotes Progressive Reading
                const quoteElement = notesSection.querySelector('.note-quote-text');
                if (quoteElement && typeof SplitType !== 'undefined') {
                    const splitQuote = new SplitType(quoteElement, { types: 'lines', lineClass: 'quote-prog-line' });
                    
                    gsap.set('.quote-prog-line', { opacity: 0.2, color: 'rgba(0, 0, 0, 0.4)' });

                    const quoteTl = gsap.timeline({
                        scrollTrigger: {
                            trigger: '.note-quote',
                            start: 'top 80%',
                            end: 'bottom 50%',
                            scrub: 1
                        }
                    });

                    const qLines = quoteElement.querySelectorAll('.quote-prog-line');
                    qLines.forEach((line) => {
                        quoteTl.to(line, {
                            opacity: 1,
                            color: '#000000',
                            duration: 1,
                            ease: 'none'
                        }, '+=0');
                    });
                }
            }

            // Article Interactions (Hover & Click/FLIP)
            articles.forEach(article => {
                const imgWrapper = article.querySelector('.note-img-wrapper');
                const noteTitle = article.querySelector('.note-title');
                const specs = article.querySelector('.note-specs');
                const specRows = article.querySelectorAll('.spec-row');
                const noteProgress = article.querySelector('.note-progress');
                let isExpanded = false;

                // Hover
                article.addEventListener('mouseenter', () => {
                    if (isExpanded || prefersReducedMotion) return;
                    if (imgWrapper) gsap.to(imgWrapper, { y: -8, duration: 0.4, ease: 'power2.out' });
                    if (noteTitle) gsap.to(noteTitle, { y: -6, duration: 0.4, ease: 'power2.out' });
                    gsap.to(article, { backgroundColor: '#FAFAFA', borderColor: 'rgba(0,0,0,0.15)', duration: 0.3 });
                });

                article.addEventListener('mouseleave', () => {
                    if (isExpanded || prefersReducedMotion) return;
                    if (imgWrapper) gsap.to(imgWrapper, { y: 0, duration: 0.4, ease: 'power2.out' });
                    if (noteTitle) gsap.to(noteTitle, { y: 0, duration: 0.4, ease: 'power2.out' });
                    gsap.to(article, { backgroundColor: '', borderColor: 'rgba(0,0,0,0.1)', duration: 0.3 });
                });

                // Click (Inline Expand FLIP)
                article.addEventListener('click', (e) => {
                    // Prevent triggering if clicking something that should be a link later
                    if (e.target.tagName === 'A') return;
                    if (typeof Flip === 'undefined') return;

                    const state = Flip.getState(article);
                    
                    isExpanded = !isExpanded;
                    
                    if (isExpanded) {
                        // Expand inline
                        article.classList.add('expanded');
                        // Make narrow articles full width on expand
                        if (article.classList.contains('md:w-[60%]')) {
                            article.classList.remove('md:w-[60%]', 'ml-auto');
                            article.dataset.narrow = 'true';
                        }
                        specs.classList.remove('hidden');
                        specs.classList.add('flex');
                        
                        gsap.to(specRows, { opacity: 1, x: 0, duration: 0.5, stagger: 0.1, delay: 0.3, ease: 'power2.out' });
                        
                        // Fake progress fill for expanded view
                        gsap.to(noteProgress, { height: '100%', duration: 1.5, ease: 'power2.inOut' });
                    } else {
                        // Collapse
                        article.classList.remove('expanded');
                        if (article.dataset.narrow === 'true') {
                            article.classList.add('md:w-[60%]', 'ml-auto');
                        }
                        specs.classList.add('hidden');
                        specs.classList.remove('flex');
                        
                        gsap.set(specRows, { opacity: 0, x: -16 });
                        gsap.to(noteProgress, { height: '0%', duration: 0.4, ease: 'power2.out' });
                    }

                    Flip.from(state, {
                        duration: 0.7,
                        ease: 'power3.inOut',
                        nested: true
                    });
                });
            });
            
        }

        // 3. New Arrivals: Collector's Selection (GSAP Orchestration)
        const newArrivalSection = document.getElementById('new-arrival-section');
        const gridContainer = document.getElementById('editorial-products-grid');
        
        if (newArrivalSection && gridContainer) {
            
            // Phase 8: Scroll Compression
            gsap.to('.new-arrival-heading', {
                scale: 0.85,
                letterSpacing: '-0.02em',
                ease: 'none',
                transformOrigin: 'left center',
                scrollTrigger: {
                    trigger: newArrivalSection,
                    start: 'top top',
                    end: '+=500',
                    scrub: true
                }
            });

            // Phases 1-3: Arrival & Grid Construction Timeline
            const arrivalTl = gsap.timeline({
                scrollTrigger: {
                    trigger: newArrivalSection,
                    start: 'top 75%',
                    toggleActions: 'play none none none'
                }
            });

            // Phase 1: Header Reveal
            arrivalTl.to('.new-arrival-heading', {
                y: 0,
                opacity: 1,
                duration: 0.9,
                ease: 'expo.out'
            })
            .to('.new-arrival-cta', {
                opacity: 1,
                duration: 0.8,
                ease: 'power2.out'
            }, "-=0.4");

            // Phase 2: Grid Construction
            const gridLinesV = document.querySelectorAll('.grid-line-v');
            const gridLinesH = document.querySelectorAll('.grid-line-h');
            
            arrivalTl.to(gridLinesV, {
                scaleY: 1,
                duration: 1.2,
                stagger: 0.1,
                ease: 'expo.inOut'
            }, "-=0.2")
            .to(gridLinesH, {
                scaleX: 1,
                duration: 1.2,
                ease: 'expo.inOut'
            }, "-=0.8");

            // Phase 3: Curated Product Reveal
            const cards = document.querySelectorAll('#editorial-products-grid .product-card');
            
            cards.forEach((card, index) => {
                const img = card.querySelector('.product-image');
                const outStock = card.querySelector('.product-out-stock');
                const brand = card.querySelector('.product-brand');
                const price = card.querySelector('.product-price');
                const title = card.querySelector('.product-title');
                const desc = card.querySelector('.product-desc');
                
                const startTime = 1.0 + (index * 0.14); // 140ms stagger offset

                arrivalTl.to(img, {
                    y: 0,
                    opacity: 1,
                    filter: 'brightness(1) contrast(1)',
                    duration: 1.0,
                    ease: 'power3.out'
                }, startTime)
                .to(brand, { opacity: 1, duration: 0.6, ease: 'power2.out' }, startTime + 0.4)
                .to(price, { opacity: 1, duration: 0.6, ease: 'power2.out' }, startTime + 0.5)
                .to(title, { opacity: 1, duration: 0.6, ease: 'power2.out' }, startTime + 0.6)
                .to(desc, { opacity: 0.5, duration: 0.6, ease: 'power2.out' }, startTime + 0.7);
                
                if (outStock) {
                    arrivalTl.to(outStock, { opacity: 1, duration: 0.5, ease: 'power1.out' }, startTime + 0.3);
                }
            });
        }
        
        // Phase 7: Shared Element Pre-Navigation Transition
        window.handlePreNavigation = function(e, cardEl, targetUrl) {
            e.preventDefault();
            
            // Prevent double clicks
            if (cardEl.dataset.transitioning === "true") return;
            cardEl.dataset.transitioning = "true";
            
            const img = cardEl.querySelector('.product-image');
            const bg = cardEl.querySelector('.card-bg');
            const otherCards = Array.from(document.querySelectorAll('.product-card')).filter(c => c !== cardEl);
            
            // Lock scrolling
            document.body.style.overflow = 'hidden';
            
            const tl = gsap.timeline({
                onComplete: () => {
                    window.location.href = targetUrl;
                }
            });
            
            // Fade out everything else
            tl.to(otherCards, { opacity: 0, duration: 0.3, ease: 'power2.inOut' }, 0)
              .to('.grid-lines-container', { opacity: 0, duration: 0.3, ease: 'power2.inOut' }, 0)
              .to('.new-arrival-heading', { opacity: 0, duration: 0.3, ease: 'power2.inOut' }, 0)
              .to('.new-arrival-cta', { opacity: 0, duration: 0.3, ease: 'power2.inOut' }, 0);
              
            // Card transition
            tl.to(bg, { 
                scale: 1.02, 
                backgroundColor: '#FAFAFA', 
                duration: 0.6, 
                ease: 'power3.inOut' 
            }, 0)
            .to(img, {
                y: -30,
                scale: 1.1,
                filter: 'brightness(1) contrast(1.05)',
                duration: 0.7,
                ease: 'power3.inOut'
            }, 0);
        };

    } // End initAnimations

    if (sessionStorage.getItem('preloaderShown')) {
        initAnimations();
    } else {
        window.addEventListener('preloaderFinished', initAnimations);
    }

    // CRM Drop Countdown Logic & Stock Polling
    function updateCountdowns() {
        const now = Date.now() - window.serverTimeOffset;
        const dropBtns = document.querySelectorAll('.drop-btn');
        const activeIds = [];

        dropBtns.forEach(btn => {
            const dropTimeBase = parseInt(btn.getAttribute('data-drop-time'));
            const targetTime = window.isVip ? dropTimeBase : dropTimeBase + (10 * 60 * 1000); // +10 mins for regular
            const expirationTime = dropTimeBase + (40 * 60 * 1000); // 40 mins total window
            const diff = targetTime - now;

            if (now >= expirationTime) {
                // The drop window has completely expired. Hide it.
                const container = btn.closest('.drop-container');
                if (container) container.remove();
                
                if(document.querySelectorAll('.drop-container').length === 0) {
                    const section = btn.closest('#drop-section') || btn.closest('.section-reveal');
                    if (section) section.remove();
                }
            } else if (diff > 0) {
                // Not ready yet.
                // If it's the old structure, it won't have .drop-btn-text
                const textSpan = btn.querySelector('.drop-btn-text');
                if (!textSpan) {
                    const m = Math.floor((diff / 1000 / 60) % 60).toString().padStart(2, '0');
                    const s = Math.floor((diff / 1000) % 60).toString().padStart(2, '0');
                    const h = Math.floor((diff / (1000 * 60 * 60))).toString().padStart(2, '0');
                    btn.innerHTML = window.isVip ? `VIP Drop in ${h}:${m}:${s}` : `Available in ${h}:${m}:${s}`;
                    btn.classList.add('opacity-50', 'pointer-events-none');
                }
                // (The new structure uses flip-clocks for countdown and doesn't modify the button until ready)
            } else {
                // It's active!
                const textSpan = btn.querySelector('.drop-btn-text');
                if (textSpan) {
                    // Trigger the GSAP sweep animation if we haven't already
                    btn.dispatchEvent(new Event('dropTimeReached'));
                } else {
                    // Old structure
                    btn.innerHTML = 'SHOP DROP NOW';
                    btn.classList.remove('opacity-50', 'pointer-events-none', 'bg-white', 'text-black');
                    btn.classList.add('hover:bg-white', 'hover:text-black', 'bg-black', 'text-white');
                }
                
                // Once drop is active, it needs to be stock polled too
                activeIds.push(btn.getAttribute('data-id'));
            }
        });

        // Also poll normal items for stock if needed
        document.querySelectorAll('.stock-status-btn').forEach(b => activeIds.push(b.getAttribute('data-id')));

        return activeIds;
    }

    setInterval(updateCountdowns, 1000);

    // Stock Polling every 5 seconds
    setInterval(() => {
        const productIds = Array.from(document.querySelectorAll('.drop-btn, .stock-status-btn')).map(el => el.getAttribute('data-id'));
        if (productIds.length === 0) return;

        fetch('/api/products/stock?ids[]=' + productIds.join('&ids[]='))
            .then(res => res.json())
            .then(data => {
                data.forEach(prod => {
                    const btn = document.querySelector(`[data-id="${prod.id}"]`);
                    if (btn && (prod.stock <= 0 || prod.status === 'sold_out')) {
                        const textSpan = btn.querySelector('.drop-btn-text');
                        if (textSpan) {
                            textSpan.innerHTML = '<span>[ OUT OF STOCK ]</span>';
                        } else {
                            btn.innerHTML = '<span class="text-primary font-bold opacity-50">[ OUT OF STOCK ]</span>';
                        }
                        btn.classList.add('opacity-50', 'pointer-events-none');
                        // Override drop logic if out of stock
                        btn.classList.remove('drop-btn');
                        
                        // Trigger massive overlay if in THE DROP section
                        const oosOverlay = document.getElementById('oos-' + prod.id);
                        if (oosOverlay) {
                            oosOverlay.classList.remove('hidden', 'opacity-0');
                            
                            // Hidden 5 min countdown to completely remove it from THE DROP
                            if (!btn.dataset.soldOutTimerStarted) {
                                btn.dataset.soldOutTimerStarted = 'true';
                                setTimeout(() => {
                                    const container = btn.closest('.drop-container');
                                    if(container) container.remove();
                                    
                                    // If no more drops, remove the whole section
                                    if(document.querySelectorAll('.drop-container').length === 0) {
                                        const section = document.getElementById('drop-section') || document.querySelector('.bg-\\[\\#111111\\]');
                                        if(section) section.remove();
                                    }
                                }, 5 * 60 * 1000);
                            }
                        }
                    }
                });
            })
            .catch(err => console.error('Stock poll error:', err));
    }, 5000);

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/Flip.min.js"></script>
<script>
    if (typeof gsap !== 'undefined' && typeof Flip !== 'undefined') {
        gsap.registerPlugin(Flip);
    }
</script>
@endsection
