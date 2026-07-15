@extends('layouts.app')

@section('title', 'CLEMENTINE | Mechanical Perfection')

@section('content')
<div class="w-full">
    
    <!-- 1. Hero Section (Ref: Image 1) -->
    <div class="w-full bg-background pt-[120px] md:pt-[160px] pb-[80px] px-lg border-b border-primary">
        <h1 class="font-h1 text-[60px] sm:text-[90px] md:text-[130px] lg:text-[180px] leading-[0.85] tracking-tighter uppercase text-primary w-full text-left hero-reveal opacity-0 translate-y-10">
            MECHANICAL <br>
            PERFECTION <br>
            WITHOUT <br>
            COMPROMISE
        </h1>
    </div>

    <!-- 2. Brand Story Section (Ref: Image 2) -->
    <div class="w-full bg-primary text-on-primary py-[120px] md:py-[200px] px-lg flex items-center justify-center border-b border-primary">
        <p class="font-h1 text-[24px] sm:text-[28px] md:text-[32px] lg:text-[40px] leading-snug md:leading-tight text-secondary w-full max-w-[65ch] opacity-0 translate-y-10 story-reveal font-italic uppercase">
            Horology culture is a subgenre of the mechanical lifestyle—an appreciation that emerged from raw engineering and precision craftsmanship. Clementine generally refers to a person who is devoted to acquiring mechanical art, especially premium timepieces. To satisfy your aesthetic need, CLEMENTINE is here. The one and only place with curated, high-end, uncompromising mechanical brands are here waiting for you to acquire 'em down! From classic calibers to modern complications, We got you all covered.
        </p>
    </div>

    @if(isset($theDrop) && $theDrop->isNotEmpty())
    <!-- 2.5 THE DROP Section -->
    <div class="w-full bg-[#111111] text-white section-reveal border-b border-primary">
        <div class="p-lg md:p-xl border-b border-[#333]">
            <h2 class="font-h1 text-[60px] sm:text-[90px] md:text-[130px] lg:text-[180px] leading-[0.8] tracking-tighter uppercase break-words w-full text-white">THE DROP</h2>
        </div>
        
        <script>
            window.isVip = {{ auth()->user()?->is_vip ? 'true' : 'false' }};
            window.serverTimeOffset = Date.now() - {{ time() * 1000 }};
        </script>
        
        @foreach($theDrop as $drop)
        <div class="flex flex-col xl:flex-row border-b border-[#333] last:border-0 relative drop-container" data-id="{{ $drop->id }}">
            <!-- Left: Massive Image -->
            <div class="w-full xl:w-1/2 aspect-square xl:aspect-auto xl:min-h-[800px] bg-[#0A0A0A] flex items-center justify-center p-xl relative overflow-hidden border-b xl:border-b-0 xl:border-r border-[#333]">
                @if ($drop->primaryImage)
                    <div class="w-full h-full bg-contain bg-center bg-no-repeat transition-transform duration-700 hover:scale-110 drop-shadow-[0_0_50px_rgba(255,255,255,0.1)]"
                         style="background-image: url('{{ $drop->primaryImage->url }}')"></div>
                @else
                    <div class="font-mono text-[#333] uppercase tracking-widest">No Visual Data</div>
                @endif
                
                <!-- Stock Status Overlay -->
                <div class="drop-out-of-stock absolute inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center hidden opacity-0 transition-opacity duration-500 z-20" id="oos-{{ $drop->id }}">
                    <span class="font-h1 text-[60px] md:text-[80px] text-red-600 rotate-[-10deg]">SOLD OUT</span>
                </div>
            </div>
            
            <!-- Right: Details & Timer -->
            <div class="w-full xl:w-1/2 p-lg md:p-3xl flex flex-col justify-center">
                <span class="font-mono text-sm tracking-widest text-[#888] mb-4 uppercase">
                    {{ $drop->collection->name ?? 'EXCLUSIVE RELEASE' }}
                </span>
                
                <h3 class="font-h1 text-[50px] md:text-[80px] leading-[0.9] uppercase mb-6">{{ $drop->name }}</h3>
                
                <p class="font-body-md text-lg text-[#AAA] max-w-2xl mb-12">
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
            <h2 class="font-h1 text-[60px] sm:text-[90px] md:text-[130px] lg:text-[180px] leading-[0.8] tracking-tighter uppercase break-words w-full">NEW ARTICLE</h2>
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
                <div class="group flex flex-col bg-background hover:bg-surface-container-lowest transition-colors product-card relative h-full border-r border-b border-primary">
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
                        
                        <!-- Image Area with Grey Background -->
                        <div class="w-full aspect-square bg-[#EAEAEA] border-b border-primary flex items-center justify-center p-xl relative overflow-hidden">
                            @if ($product->primaryImage)
                            <div class="w-full h-full bg-contain bg-center bg-no-repeat transition-transform duration-700 group-hover:scale-110 drop-shadow-2xl"
                                 style="background-image: url('{{ $product->primaryImage->url }}')"></div>
                            @else
                            <div class="w-full h-full bg-[#EAEAEA] flex items-center justify-center text-secondary text-xs uppercase">No Image</div>
                            @endif
                            
                            @if($product->stock <= 0)
                            <div class="absolute inset-0 bg-black/60 flex items-center justify-center z-10">
                                <span class="font-h1 text-3xl md:text-4xl text-red-600 rotate-[-10deg] tracking-widest border-2 border-red-600 px-4 py-2 bg-black/50">OUT OF STOCK</span>
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
    
    <!-- 4. Graphic Section: 100% Legit (Ref: Image 4) -->
    <div class="w-full bg-primary text-on-primary relative overflow-hidden h-[600px] md:h-[800px] border-b border-primary section-reveal flex items-center justify-center">
        <!-- CSS Perspective Grid Background -->
        <div class="absolute inset-0 pointer-events-none opacity-20" style="background-image: 
            linear-gradient(to right, #ffffff 1px, transparent 1px),
            linear-gradient(to bottom, #ffffff 1px, transparent 1px);
            background-size: 50px 50px;
            transform: perspective(500px) rotateX(60deg) scale(2);
            transform-origin: center top;">
        </div>
        
        <div class="w-full max-w-7xl mx-auto px-lg relative z-10 flex items-center h-full">
            <h2 class="font-h1 text-[100px] md:text-[200px] leading-[0.8] tracking-tighter uppercase opacity-90">
                100% <br> LEGIT
            </h2>
            
            <!-- Centered Watch Image (Dynamic Product Image) -->
            @php
                $baseProduct = $newArrivals->first() ?? $theDrop->first();
                $legitProduct = $baseProduct ? clone $baseProduct : null;
                $legitImageUrl = $legitProduct && $legitProduct->primaryImage ? $legitProduct->primaryImage->url : 'https://picsum.photos/seed/watch/800/600';
            @endphp
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] md:w-[600px] aspect-video drop-shadow-2xl z-20 graphic-item opacity-0 rotate-12 scale-90 bg-contain bg-center bg-no-repeat" style="background-image: url('{{ $legitImageUrl }}')">
                
                <!-- Floating Tags -->
                <div class="absolute -top-10 left-10 bg-background text-primary px-3 py-1 font-body-md text-xs border border-transparent shadow-lg font-bold flex items-center gap-2">
                    <span>🔥</span> 892 SOLD
                </div>
                
                <div class="absolute -top-4 -right-10 bg-background text-primary px-3 py-1 font-body-md text-xs border border-transparent shadow-lg font-bold flex items-center gap-2">
                    <span>😎</span> LIMITED EDITION
                </div>
                
                <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 bg-background text-primary px-3 py-1 font-body-md text-xs border border-transparent shadow-lg font-bold flex items-center gap-2">
                    AUTOMATIC ⚙️
                </div>
            </div>
            
            <div class="absolute bottom-12 right-12 text-right font-body-md text-xs uppercase opacity-75 max-w-[250px]">
                Our timepieces are 100% LEGIT. <br> FAKE IS BULLSH*T
            </div>
        </div>
    </div>
</div>

<script>
    // Page-specific GSAP animations
    window.addEventListener('preloaderFinished', () => {
        
        // 1. Hero Reveal
        gsap.to('.hero-reveal', {
            y: 0,
            opacity: 1,
            duration: 1,
            ease: 'expo.out'
        });

        // 2. Story Text Reveal
        gsap.to('.story-reveal', {
            scrollTrigger: {
                trigger: '.story-reveal',
                start: 'top 80%',
            },
            y: 0,
            opacity: 1,
            duration: 1.5,
            ease: 'power3.out'
        });

        // 3. Staggered reveal for grid sections
        gsap.utils.toArray('.section-reveal').forEach(section => {
            gsap.from(section, {
                y: 40,
                opacity: 0,
                duration: 1,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 85%',
                }
            });
        });
        
        // 4. Graphic Item pop-in
        gsap.to('.graphic-item', {
            scrollTrigger: {
                trigger: '.graphic-item',
                start: 'top 70%',
            },
            scale: 1,
            rotate: 0,
            opacity: 1,
            duration: 1.2,
            ease: 'elastic.out(1, 0.5)'
        });
    });

    // CRM Drop Countdown Logic & Stock Polling
    function updateCountdowns() {
        const now = Date.now() - window.serverTimeOffset;
        const dropBtns = document.querySelectorAll('.drop-btn');
        const activeIds = [];

        dropBtns.forEach(btn => {
            const dropTimeBase = parseInt(btn.getAttribute('data-drop-time'));
            const targetTime = window.isVip ? dropTimeBase : dropTimeBase + (10 * 60 * 1000); // +10 mins for regular
            const diff = targetTime - now;

            if (diff > 0) {
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
                        btn.innerHTML = '<span class="text-red-600 font-bold">OUT OF STOCK</span>';
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
