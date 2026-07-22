@extends('layouts.app')

@section('title', 'CLEMENTINE | Mechanical Perfection')

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

    <!-- 2. Brand Story Section (Ref: Image 2) -->
    <div class="w-full bg-primary text-on-primary py-[120px] md:py-[200px] px-lg flex items-center justify-center border-b border-primary">
        <p class="font-h1 text-[24px] sm:text-[28px] md:text-[32px] lg:text-[40px] leading-snug md:leading-tight text-secondary w-full max-w-[65ch] opacity-0 story-reveal text-pretty text-left">
            Horology culture is a subgenre of the mechanical lifestyle—an appreciation that emerged from raw engineering and precision craftsmanship. Clementine generally refers to a person who is devoted to acquiring mechanical art, especially premium timepieces. To satisfy your aesthetic need, CLEMENTINE is here. The one and only place with curated, high-end, uncompromising mechanical brands are here waiting for you to acquire 'em down! From classic calibers to modern complications, We got you all covered.
        </p>
    </div>

    @if(isset($theDrop) && $theDrop->isNotEmpty())
    <!-- 2.5 THE DROP Section -->
    <div class="w-full bg-primary text-on-primary section-reveal border-b border-background">
        <div class="p-lg md:p-xl border-b border-background/20">
            <h2 class="font-h1 text-hero-lg leading-none tracking-tighter uppercase break-words w-full text-on-primary">THE <span class="font-serif italic  lowercase tracking-normal">drop</span></h2>
        </div>
        
        <script>
            window.isVip = {{ auth()->user()?->is_vip ? 'true' : 'false' }};
            window.serverTimeOffset = Date.now() - {{ time() * 1000 }};
        </script>
        
        @foreach($theDrop as $drop)
        <div class="flex flex-col xl:flex-row border-b border-background/20 last:border-0 relative drop-container" data-id="{{ $drop->id }}">
            <!-- Left: Massive Image -->
            <div class="w-full xl:w-1/2 aspect-square xl:aspect-auto xl:min-h-[800px] bg-primary flex items-center justify-center p-xl relative overflow-hidden border-b xl:border-b-0 xl:border-r border-background/20">
                @if ($drop->primaryImage)
                    <div class="w-full h-full bg-contain bg-center bg-no-repeat transition-all duration-500 ease-mechanical hover:scale-[1.03] active:scale-95"
                         style="background-image: url('{{ $drop->primaryImage->url }}')"></div>
                @else
                    <div class="font-mono text-background/50 uppercase tracking-widest">No Visual Data</div>
                @endif
                
                <!-- Stock Status Overlay -->
                <div class="drop-out-of-stock absolute inset-0 bg-primary/40 backdrop-blur-sm flex items-center justify-center hidden opacity-0 transition-opacity duration-500 z-20" id="oos-{{ $drop->id }}">
                    <span class="font-label-caps text-sm text-background tracking-widest px-6 py-3 bg-primary">[ SOLD OUT ]</span>
                </div>
            </div>
            
            <!-- Right: Details & Timer -->
            <div class="w-full xl:w-1/2 p-lg md:p-3xl flex flex-col justify-center">
                <span class="font-mono text-sm tracking-widest text-background/50 mb-4 uppercase">
                    {{ $drop->collection->name ?? 'EXCLUSIVE RELEASE' }}
                </span>
                
                <h3 class="font-h1 text-hero-md leading-none uppercase mb-6">{{ $drop->name }}</h3>
                
                <p class="font-body-md text-lg text-background/80 max-w-2xl mb-12">
                    {{ $drop->description ?? $drop->tagline ?? 'Extremely limited allocation. Secure yours before the window closes.' }}
                </p>
                
                <div class="text-3xl font-mono mb-12">${{ number_format($drop->price, 2) }}</div>
                
                <div class="mt-auto">
                    @if($drop->scheduled_publish_at)
                        <a href="{{ route('products.show', $drop->slug) }}" 
                           class="drop-btn w-full py-6 border border-white text-xl md:text-2xl font-h2 uppercase text-center block transition-colors bg-white text-black opacity-50 pointer-events-none"
                           data-id="{{ $drop->id }}" 
                           data-drop-time="{{ $drop->scheduled_publish_at->getTimestamp() * 1000 }}">
                            SYNCING SYSTEM CLOCK...
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- 3. New Arrivals Section (Ref: Image 3) -->
    <div class="w-full section-reveal">
        <div class="p-lg md:p-xl border-b border-primary bg-background flex flex-col xl:flex-row justify-between xl:items-end gap-6 overflow-hidden">
            <h2 class="font-h1 text-hero-lg leading-none tracking-tighter uppercase break-words w-full">NEW ARTICLE</h2>
            <a href="{{ route('products.index') }}" class="bg-primary text-on-primary border border-primary px-xl py-md md:py-sm font-label-caps uppercase text-sm md:text-xs tracking-widest hover:bg-background hover:text-primary transition-colors flex items-center justify-center h-min whitespace-nowrap shrink-0">
                VIEW MORE
            </a>
        </div>
        <!-- Hypebizz-style Grid (Explicit borders for perfect brutalism) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 w-full border-l border-primary">
            @forelse($newArrivals as $product)
                @if($product->stock <= 0)
                <div class="group flex flex-col bg-background border-r border-b border-primary opacity-60 cursor-not-allowed product-card relative h-full">
                @else
                <div class="group flex flex-col bg-background transition-colors product-card relative h-full border-r border-b border-primary">
                @endif
                
                    @if($product->stock <= 0)
                    <div class="flex-grow flex flex-col">
                    @else
                    <a href="{{ route('products.show', $product->slug) }}" class="flex-grow flex flex-col">
                    @endif
                    
                        <!-- Top Bar: Logo/Name & Price -->
                        <div class="flex justify-between items-center px-md py-sm border-b border-primary">
                            <span class="font-h2 text-sm uppercase tracking-tight">
                                {{ $product->collection->name ?? 'CLE' }}
                            </span>
                            <span class="font-h2 text-sm">${{ number_format($product->price, 2) }}</span>
                        </div>
                        
                        <!-- Image Area -->
                        <div class="w-full aspect-square bg-background border-b border-primary flex items-center justify-center p-xl relative overflow-hidden">
                            @if ($product->primaryImage)
                                <div class="w-full h-full bg-contain bg-center bg-no-repeat transition-all duration-500 ease-mechanical group-hover:scale-[1.03] active:scale-95"
                                     style="background-image: url('{{ $product->primaryImage->url }}')"></div>
                            @else
                            <div class="w-full h-full bg-background flex items-center justify-center text-secondary text-xs uppercase">No Image</div>
                            @endif
                            
                            @if($product->stock <= 0)
                            <div class="absolute inset-0 bg-primary/20 backdrop-blur-[2px] flex items-center justify-center z-10">
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
                    <div class="p-md flex flex-col">
                        <h3 class="font-h2 text-lg uppercase leading-tight mb-1"><a href="{{ route('products.show', $product->slug) }}" class="hover:underline">{{ $product->name }}</a></h3>
                        <p class="font-body-md text-[10px] text-secondary">
                            {{ $product->tagline ?? 'Premium mechanical timepiece' }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-4 p-3xl text-center font-body-md text-secondary uppercase bg-background border-r border-b border-primary">
                    No articles at the moment.
                </div>
            @endforelse
        </div>
    </div>
       <!-- 4. Graphic Section: Legit / Authenticity (Swiss Industrial Brutalism) -->
    <div class="w-full bg-primary relative section-reveal border-b border-primary">
        <!-- 1px gap grid to create perfect brutalist borders -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-[1px] bg-primary min-h-[400px] md:min-h-[500px]">
            
            <!-- Left Side: Massive Typography & Text -->
            <div class="md:col-span-8 bg-background flex flex-col justify-between p-lg md:p-2xl relative overflow-hidden">
                <!-- Top Telemetry Bar -->
                <div class="flex justify-between items-start font-mono text-[10px] sm:text-xs uppercase tracking-[0.1em] text-primary/70 mb-12 md:mb-20">
                    <span>[ VERIFICATION PROTOCOL ]</span>
                    <span class="hidden sm:inline-block">>>> /// SYS. AUTH. 1.0</span>
                </div>
                
                <!-- The Core Statement -->
                <h2 class="fake-statement font-h1 text-[clamp(3rem,8vw,7rem)] leading-[0.85] tracking-[-0.04em] uppercase text-primary break-words z-20 mb-8 md:mb-12">
                    FAKE<br>IS<br>BULLSH*T.
                </h2>
                
                <!-- Bottom Description & Reference -->
                <div class="flex flex-col sm:flex-row gap-6 items-start sm:items-end justify-between z-20 mt-auto">
                    <p class="font-body-md text-xs md:text-sm text-primary/80 uppercase tracking-widest leading-relaxed max-w-lg">
                        We don't deal in replicas, clones, or compromises. Mechanical integrity is absolute. Every piece is verified. If it's not real, it doesn't exist here.
                    </p>
                    <div class="hidden md:flex flex-col items-end font-mono text-[10px] text-primary/50 tracking-[0.15em] text-right">
                        <span>REF: Z-99</span>
                        <span>SEC: ALPHA</span>
                    </div>
                </div>
            </div>
            
            <!-- Right Side: Graphic/Product Image -->
            @php
                $baseProduct = $newArrivals->first() ?? $theDrop->first();
                $legitProduct = $baseProduct ? clone $baseProduct : null;
                $legitImageUrl = $legitProduct && $legitProduct->primaryImage ? $legitProduct->primaryImage->url : 'https://picsum.photos/seed/watch/800/600';
            @endphp
            <div class="md:col-span-4 bg-background flex flex-col relative group overflow-hidden">
                <!-- Red Alert Header -->
                <div class="bg-[#E61919] text-white p-sm px-md flex justify-between items-center font-mono text-[10px] tracking-[0.15em] uppercase z-30 relative">
                    <span>STATUS: SECURE</span>
                    <span class="animate-pulse">●</span>
                </div>

                <!-- Image Canvas -->
                <div class="flex-1 relative flex items-center justify-center p-xl min-h-[300px] overflow-hidden">
                    <!-- Brutalist Grid Overlay -->
                    <div class="absolute inset-0 pointer-events-none" style="background-image: linear-gradient(to right, rgba(17,17,17,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(17,17,17,0.05) 1px, transparent 1px); background-size: 40px 40px;"></div>
                    
                    <!-- Center Crosshair -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 pointer-events-none">
                        <div class="absolute top-1/2 left-0 w-full h-[1px] bg-primary/40"></div>
                        <div class="absolute top-0 left-1/2 w-[1px] h-full bg-primary/40"></div>
                    </div>

                    <div class="absolute inset-0 bg-contain bg-center bg-no-repeat graphic-item opacity-0 scale-95 z-20 m-xl mix-blend-multiply" style="background-image: url('{{ $legitImageUrl }}')"></div>
                </div>

                <!-- Bottom Telemetry Data -->
                <div class="grid grid-cols-2 gap-[1px] bg-primary border-t border-primary mt-auto z-30 relative">
                    <div class="bg-background p-md font-mono text-[10px] uppercase tracking-widest flex flex-col items-center justify-center text-center">
                        <span class="text-primary/50 mb-1">VERIFICATION</span>
                        <span class="text-primary font-bold">100% AUTHENTIC</span>
                    </div>
                    <div class="bg-background p-md font-mono text-[10px] uppercase tracking-widest flex flex-col items-center justify-center text-center">
                        <span class="text-primary/50 mb-1">POLICY</span>
                        <span class="text-primary font-bold">ZERO TOLERANCE</span>
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

        // 2. Story Text Reveal (Physical Scrubbed Motion)
        gsap.fromTo('.story-reveal', 
            {
                y: 50,
                opacity: 0,
                scale: 0.98,
                filter: 'blur(8px)'
            },
            {
                scrollTrigger: {
                    trigger: '.story-reveal',
                    start: 'top 95%',
                    end: 'top 50%',
                    scrub: 1, // Smooth tactical scrubbing, tying animation directly to scroll momentum
                },
                y: 0,
                opacity: 1,
                scale: 1,
                filter: 'blur(0px)',
                ease: 'none'
            }
        );

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
        
        // 4. Graphic Item pop-in (Refined mechanical motion)
        gsap.to('.graphic-item', {
            scrollTrigger: {
                trigger: '.graphic-item',
                start: 'top 70%',
            },
            scale: 1,
            opacity: 1,
            duration: 1.2,
            ease: 'expo.out'
        });

        // 5. Graphic Section Statement Parallax
        if (document.querySelector('.fake-statement')) {
            gsap.fromTo('.fake-statement',
                { y: 50, opacity: 0.2, filter: 'blur(10px)', scale: 0.95 },
                {
                    y: 0,
                    opacity: 1,
                    filter: 'blur(0px)',
                    scale: 1,
                    scrollTrigger: {
                        trigger: '.fake-statement',
                        start: 'top 90%',
                        end: 'top 40%',
                        scrub: 1
                    },
                    ease: 'none'
                }
            );
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
                    const section = btn.closest('.section-reveal');
                    if (section) section.remove();
                }
            } else if (diff > 0) {
                const m = Math.floor((diff / 1000 / 60) % 60).toString().padStart(2, '0');
                const s = Math.floor((diff / 1000) % 60).toString().padStart(2, '0');
                const h = Math.floor((diff / (1000 * 60 * 60))).toString().padStart(2, '0');
                
                btn.innerHTML = window.isVip ? `VIP Drop in ${h}:${m}:${s}` : `Available in ${h}:${m}:${s}`;
                btn.classList.add('opacity-50', 'pointer-events-none', 'bg-white', 'text-black');
                btn.classList.remove('hover:bg-black', 'hover:text-white');
            } else {
                btn.innerHTML = 'SHOP DROP NOW';
                btn.classList.remove('opacity-50', 'pointer-events-none', 'bg-white', 'text-black');
                btn.classList.add('hover:bg-white', 'hover:text-black', 'bg-black', 'text-white');
                
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
                        btn.innerHTML = '<span class="text-primary font-bold opacity-50">[ OUT OF STOCK ]</span>';
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
                                        const section = document.querySelector('.bg-\\[\\#111111\\]');
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
