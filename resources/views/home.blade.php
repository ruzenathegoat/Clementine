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
                            <span class="font-mono text-[9px] uppercase tracking-[0.3em] text-white/40 block mb-4">DROP ENDS IN</span>
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

    <!-- 3. New Arrivals Section (Ref: Image 3) -->
    <div class="w-full section-reveal">
        <div class="p-lg md:p-xl border-b border-primary bg-background flex flex-col xl:flex-row justify-between xl:items-end gap-6 overflow-hidden">
            <h2 class="font-h1 text-hero-lg leading-none tracking-tighter uppercase break-words w-full">NEW ARTICLE</h2>
            <a href="{{ route('products.index') }}" class="bg-primary text-on-primary border border-primary px-xl py-md md:py-sm font-label-caps uppercase text-sm md:text-xs tracking-widest hover:bg-background hover:text-primary transition-colors flex items-center justify-center h-min whitespace-nowrap shrink-0">
                VIEW MORE
            </a>
        </div>
        <!-- Editorial Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 w-full border-l border-primary/20" id="editorial-products-grid">
            @forelse($newArrivals as $product)
                @if($product->stock <= 0)
                <div class="flex flex-col bg-background border-r border-b border-primary/20 opacity-60 cursor-not-allowed product-card relative h-full">
                @else
                <div class="flex flex-col bg-background product-card relative h-full border-r border-b border-primary/20 cursor-pointer">
                @endif
                
                    <!-- Drawn Borders (Mechanical Inspection Mode) -->
                    <div class="border-top absolute top-0 left-0 w-full h-[1px] bg-primary scale-x-0 origin-left z-20 pointer-events-none"></div>
                    <div class="border-right absolute top-0 right-0 w-[1px] h-full bg-primary scale-y-0 origin-top z-20 pointer-events-none"></div>
                    <div class="border-bottom absolute bottom-0 right-0 w-full h-[1px] bg-primary scale-x-0 origin-right z-20 pointer-events-none"></div>
                    <div class="border-left absolute bottom-0 left-0 w-[1px] h-full bg-primary scale-y-0 origin-bottom z-20 pointer-events-none"></div>

                    @if($product->stock <= 0)
                    <div class="flex-grow flex flex-col relative z-10">
                    @else
                    <a href="{{ route('products.show', $product->slug) }}" class="flex-grow flex flex-col relative z-10 block">
                    @endif
                    
                        <!-- Top Bar: Logo/Name & Price -->
                        <div class="flex justify-between items-center px-md py-sm border-b border-primary/20">
                            <span class="font-mono text-[10px] uppercase tracking-widest text-[#666666] product-meta">
                                {{ $product->collection->name ?? 'CLE' }}
                            </span>
                            <span class="font-mono text-[10px] text-[#666666] product-meta">${{ number_format($product->price, 2) }}</span>
                        </div>
                        
                        <!-- Image Area -->
                        <div class="w-full aspect-square bg-background border-b border-primary/20 flex items-center justify-center p-xl relative overflow-hidden">
                            @if ($product->primaryImage)
                                <img src="{{ $product->primaryImage->url }}" 
                                     alt="{{ $product->name }}"
                                     class="product-image w-full h-full object-contain"
                                     style="filter: brightness(0.88) contrast(1);" />
                            @else
                                <div class="w-full h-full bg-background flex items-center justify-center text-secondary text-xs uppercase">No Image</div>
                            @endif
                            
                            @if($product->stock <= 0)
                            <div class="absolute inset-0 bg-primary/10 backdrop-blur-[2px] flex items-center justify-center z-10">
                                <span class="font-label-caps text-xs text-background tracking-widest px-4 py-2 bg-primary">[ OUT OF STOCK ]</span>
                            </div>
                            @endif
                        </div>
                        
                    @if($product->stock <= 0)
                    </div>
                    @else
                    </a>
                    @endif
                        
                    <!-- Details -->
                    <div class="p-md flex flex-col relative z-10">
                        <h3 class="product-title font-h1 text-lg uppercase leading-tight mb-1 truncate" style="font-weight: 500; letter-spacing: normal;">
                            @if($product->stock <= 0)
                                {{ $product->name }}
                            @else
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            @endif
                        </h3>
                        <p class="font-body-md text-[10px] text-primary/60">
                            {{ $product->tagline ?? 'Premium mechanical timepiece' }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-4 p-3xl text-center font-body-md text-primary/60 uppercase bg-background border-r border-b border-primary/20">
                    No articles at the moment.
                </div>
            @endforelse
        </div>
    </div>
    <!-- 4. Graphic Section: Verification Protocol (Editorial) -->
    <div class="w-full bg-background relative border-b border-primary/20" id="verification-section">
        <!-- Grid container -->
        <div class="grid grid-cols-1 md:grid-cols-2 bg-background min-h-[500px] md:min-h-[700px]">
            
            <!-- Left Side: Typography -->
            <div class="flex flex-col justify-center p-xl md:p-4xl relative border-b md:border-b-0 md:border-r border-primary/20 overflow-hidden">
                <div class="flex flex-col h-full justify-center max-w-xl mx-auto md:mx-0">
                    <!-- The Core Statement -->
                    <h2 class="veri-headline font-h1 text-[clamp(3.5rem,8vw,7rem)] leading-[0.85] tracking-tight uppercase text-primary mb-12" style="filter: blur(18px) brightness(0.96) contrast(0.95);">
                        FAKE<br>IS<br>BULLSH*T.
                    </h2>
                    
                    <!-- Bottom Description -->
                    <div class="veri-paragraph font-body-md text-sm md:text-base text-primary/80 uppercase tracking-widest leading-relaxed max-w-sm">
                        <div class="veri-line" style="filter: blur(8px); opacity: 0.2;">We don't deal in replicas.</div>
                        <div class="veri-line" style="filter: blur(8px); opacity: 0.2;">Every piece is verified.</div>
                        <div class="veri-line" style="filter: blur(8px); opacity: 0.2;">If it's not real,</div>
                        <div class="veri-line" style="filter: blur(8px); opacity: 0.2;">it doesn't exist here.</div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side: Technical Inspection -->
            @php
                $baseProduct = $newArrivals->first() ?? $theDrop->first();
                $legitProduct = $baseProduct ? clone $baseProduct : null;
                $legitImageUrl = $legitProduct && $legitProduct->primaryImage ? $legitProduct->primaryImage->url : 'https://picsum.photos/seed/watch/800/600';
            @endphp
            <div class="flex flex-col relative overflow-hidden group cursor-default bg-background" id="verification-lab">
                
                <!-- Top Telemetry Bar -->
                <div class="flex justify-between items-center px-lg py-md border-b border-primary/20 font-mono text-[10px] uppercase tracking-widest text-primary/70 z-30 bg-background/80 backdrop-blur-sm relative">
                    <span class="veri-top-label" style="filter: blur(4px); opacity: 0;">[ VERIFICATION PROTOCOL ]</span>
                    <span class="veri-top-label hidden sm:inline-block" style="filter: blur(4px); opacity: 0;">SYS.AUTH.1.0</span>
                </div>

                <!-- Inspection Stage -->
                <div class="flex-1 relative flex items-center justify-center p-2xl min-h-[400px]">
                    <!-- GSAP Drawn Grid -->
                    <div class="absolute inset-0 pointer-events-none veri-grid overflow-hidden" style="opacity: 0.03;">
                        <!-- Vertical lines -->
                        @for ($i = 1; $i < 10; $i++)
                            <div class="absolute top-0 bottom-0 bg-primary w-[1px] veri-grid-v" style="left: {{ $i * 10 }}%; transform-origin: top; transform: scaleY(0);"></div>
                        @endfor
                        <!-- Horizontal lines -->
                        @for ($i = 1; $i < 10; $i++)
                            <div class="absolute left-0 right-0 bg-primary h-[1px] veri-grid-h" style="top: {{ $i * 10 }}%; transform-origin: left; transform: scaleX(0);"></div>
                        @endfor
                    </div>

                    <!-- Watch Image -->
                    <div class="relative z-20 w-full h-full max-w-[400px] max-h-[400px] flex items-center justify-center">
                        <!-- Inspection Frame (drawn on hover) -->
                        <div class="hover-border-top absolute top-0 left-0 w-full h-[1px] bg-primary scale-x-0 origin-left z-10 pointer-events-none"></div>
                        <div class="hover-border-right absolute top-0 right-0 w-[1px] h-full bg-primary scale-y-0 origin-top z-10 pointer-events-none"></div>
                        <div class="hover-border-bottom absolute bottom-0 right-0 w-full h-[1px] bg-primary scale-x-0 origin-right z-10 pointer-events-none"></div>
                        <div class="hover-border-left absolute bottom-0 left-0 w-[1px] h-full bg-primary scale-y-0 origin-bottom z-10 pointer-events-none"></div>

                        <img src="{{ $legitImageUrl }}" class="veri-watch object-contain w-full h-full" style="filter: blur(12px) brightness(0.88) contrast(1); transform: translateY(3px);" alt="Authentication Subject">
                    </div>
                </div>

                <!-- Certification Panel -->
                <div class="bg-background font-mono text-[10px] uppercase tracking-widest text-primary/80 z-30 relative veri-panel-container">
                    <div class="veri-panel-border absolute top-0 left-0 w-full h-[1px] bg-primary/20 scale-x-0 origin-left"></div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-0">
                        <div class="p-lg flex flex-col justify-center border-r border-primary/20 border-opacity-0 veri-panel-item" style="opacity: 0;">
                            <span class="text-primary/50 mb-2">REFERENCE</span>
                            <span class="text-primary">REF: Z-99</span>
                        </div>
                        <div class="p-lg flex flex-col justify-center border-r border-primary/20 border-opacity-0 veri-panel-item hidden md:flex" style="opacity: 0;">
                            <span class="text-primary/50 mb-2">INSPECTION</span>
                            <span class="text-primary">PASSED</span>
                        </div>
                        <div class="p-lg flex flex-col justify-center border-r border-primary/20 border-opacity-0 veri-panel-item hidden md:flex" style="opacity: 0;">
                            <span class="text-primary/50 mb-2">SCORE</span>
                            <span class="text-primary">100%</span>
                        </div>
                        <!-- Status Indicator -->
                        <div class="p-lg flex flex-col justify-center veri-panel-item" style="opacity: 0;">
                            <span class="text-primary/50 mb-2">AUTHENTICITY</span>
                            <span class="text-primary flex items-center">
                                VERIFIED <span class="veri-dot ml-3 text-primary text-[8px]">●</span> LIVE
                            </span>
                        </div>
                    </div>
                </div>

            </div>
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
        // 3. Staggered reveal for grid sections (The Drop & New Arrivals)
        gsap.utils.toArray('.section-reveal').forEach(section => {
            const items = section.querySelectorAll('.drop-container, .product-card');
            if (items.length > 0) {
                gsap.fromTo(items, 
                    { y: 60, opacity: 0, scale: 0.98 },
                    {
                        y: 0,
                        opacity: 1,
                        scale: 1,
                        stagger: 0.1,
                        duration: 0.8,
                        ease: 'power4.out',
                        scrollTrigger: {
                            trigger: section,
                            start: 'top 85%',
                            toggleActions: 'play none none reverse'
                        }
                    }
                );
            } else {
                gsap.fromTo(section, 
                    { y: 60, opacity: 0 },
                    {
                        y: 0,
                        opacity: 1,
                        duration: 0.8,
                        ease: 'power4.out',
                        scrollTrigger: {
                            trigger: section,
                            start: 'top 85%',
                            toggleActions: 'play none none reverse'
                        }
                    }
                );
            }
        });
        
        // Mechanical Inspection Mode for Editorial Products
        const inspectCards = document.querySelectorAll('#editorial-products-grid .product-card');
        
        inspectCards.forEach(card => {
            const img = card.querySelector('.product-image');
            const title = card.querySelector('.product-title');
            const meta = card.querySelectorAll('.product-meta');
            
            const bTop = card.querySelector('.border-top');
            const bRight = card.querySelector('.border-right');
            const bBottom = card.querySelector('.border-bottom');
            const bLeft = card.querySelector('.border-left');

            card.animation = gsap.timeline({ paused: true, defaults: { ease: 'power2.out' } });
            
            // 1. Text fades out slightly
            card.animation.to([title, ...meta], { opacity: 0.4, duration: 0.3 }, 0);
            
            // 2. Watch moves closer, light hits it
            if (img) {
                card.animation.to(img, { 
                    scale: 1.08, 
                    y: -10,
                    filter: 'brightness(1.05) contrast(1.1)',
                    duration: 0.5,
                    ease: 'power3.out'
                }, 0);
            }
            
            // 3. Draw borders (like a targeting reticle)
            const d = 0.4 / 4;
            card.animation.to(bTop, { scaleX: 1, duration: d }, 0)
                          .to(bRight, { scaleY: 1, duration: d }, d)
                          .to(bBottom, { scaleX: 1, duration: d }, d * 2)
                          .to(bLeft, { scaleY: 1, duration: d }, d * 3);

            card.addEventListener('mouseenter', () => {
                // Dim other cards
                gsap.to(inspectCards, { 
                    filter: (i, t) => t === card ? 'brightness(1)' : 'brightness(0.3)',
                    duration: 0.4,
                    ease: 'power2.out'
                });
                
                // Play timeline forward
                card.animation.timeScale(1).play();
            });

            card.addEventListener('mouseleave', () => {
                // Restore all cards brightness
                gsap.to(inspectCards, { 
                    filter: 'brightness(1)',
                    duration: 0.3,
                    ease: 'power2.out'
                });

                // Reverse timeline
                card.animation.timeScale(1).reverse();
            });
        });

        // 4. Verification Protocol (Editorial Graphic Section)
        const veriSection = document.getElementById('verification-section');
        if (veriSection) {
            const tlVeri = gsap.timeline({
                scrollTrigger: {
                    trigger: veriSection,
                    start: 'top 65%',
                    once: true // Never reverse
                }
            });

            // Step 02: Protocol Initialization
            tlVeri.to('.veri-top-label', {
                filter: 'blur(0px)',
                opacity: 1,
                duration: 0.4,
                ease: 'power2.out',
                stagger: 0.1
            }, 0);

            // Step 03: Technical Grid (Delay 100ms from start)
            tlVeri.to('.veri-grid-h', {
                scaleX: 1,
                duration: 0.3, // Part of 600ms total
                ease: 'power2.inOut',
                stagger: 0.02
            }, 0.1)
            .to('.veri-grid-v', {
                scaleY: 1,
                duration: 0.3,
                ease: 'power2.inOut',
                stagger: 0.02
            }, "-=0.1"); // overlaps

            // Step 04: Headline Verification
            tlVeri.to('.veri-headline', {
                filter: 'blur(0px) brightness(1) contrast(1)',
                duration: 0.9,
                ease: 'power2.out'
            }, 0.5);

            // Step 08: Micro Typography
            tlVeri.to('.veri-line', {
                filter: 'blur(0px)',
                opacity: 1,
                duration: 0.6,
                ease: 'power2.out',
                stagger: 0.15
            }, 0.7);

            // Step 05: Watch Authentication
            tlVeri.to('.veri-watch', {
                filter: 'blur(0px) brightness(1) contrast(1.08)',
                y: 0,
                duration: 0.7,
                ease: 'power2.out'
            }, 0.9);

            // Step 06: Certification Panel
            tlVeri.to('.veri-panel-border', {
                scaleX: 1,
                duration: 0.5,
                ease: 'power2.out'
            }, 1.2)
            .to('.veri-panel-item', {
                opacity: 1,
                duration: 0.3,
                ease: 'none',
                stagger: 0.08
            }, 1.4);

            // Step 07: Status Indicator pulsing
            tlVeri.add(() => {
                gsap.fromTo('.veri-dot', 
                    { opacity: 0.4 },
                    { 
                        opacity: 1, 
                        duration: 1.1, 
                        ease: 'power1.inOut', 
                        yoyo: true, 
                        repeat: -1, 
                        repeatDelay: 2.8 
                    }
                );
            }, 1.7);
            
            // Hover Interaction for the lab section
            const lab = document.getElementById('verification-lab');
            const watch = lab.querySelector('.veri-watch');
            const grid = lab.querySelector('.veri-grid');
            
            const bTop = lab.querySelector('.hover-border-top');
            const bRight = lab.querySelector('.hover-border-right');
            const bBottom = lab.querySelector('.hover-border-bottom');
            const bLeft = lab.querySelector('.hover-border-left');

            const tlHover = gsap.timeline({ paused: true, defaults: { ease: 'power2.out' } });
            
            // Stage 1: Clarity
            tlHover.to(watch, { filter: 'blur(0px) brightness(1.04) contrast(1.12)', duration: 0.3 }, 0);
            
            // Stage 2: Move grid
            tlHover.to(grid, { x: 2, y: 2, duration: 0.4 }, 0);
            
            // Stage 3: Draw thin inspection frame (350ms total)
            const d = 0.35 / 4;
            tlHover.to(bTop, { scaleX: 1, duration: d }, 0)
                   .to(bRight, { scaleY: 1, duration: d }, d)
                   .to(bBottom, { scaleX: 1, duration: d }, d * 2)
                   .to(bLeft, { scaleY: 1, duration: d }, d * 3);

            lab.addEventListener('mouseenter', () => tlHover.timeScale(1).play());
            lab.addEventListener('mouseleave', () => tlHover.timeScale(1).reverse());
        }
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
@endsection
