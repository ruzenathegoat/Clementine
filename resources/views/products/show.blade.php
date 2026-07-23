@extends('layouts.app')

@section('title', 'CLEMENTINE | ' . strtoupper($product->name))

@section('content')

@php
    $maxQty = $product->stock;
    $isDropActive = false;
    $pastPurchases = 0;
    $limitReached = false;
    $denominator = max(20, $product->stock + 15); // Fallback generic denominator for exclusive drop look

    if($product->status === 'new' && $product->scheduled_publish_at) {
        $t40 = (clone $product->scheduled_publish_at)->addMinutes(40);
        if(now()->lt($t40)) {
            $isDropActive = true;
            $maxAllowed = auth()->user()?->is_vip ? 3 : 1;
            
            if (auth()->check()) {
                $pastPurchases = \App\Models\OrderItem::whereHas('order', function($q) {
                    $q->where('user_id', auth()->id())->where('status', '!=', 'cancelled');
                })->where('product_id', $product->id)->sum('quantity');
            }
            
            $remainingAllowed = max(0, $maxAllowed - $pastPurchases);
            $maxQty = min($product->stock, $remainingAllowed);
            
            if ($maxQty <= 0 && $product->stock > 0) {
                $limitReached = true;
            }
        }
    }
@endphp

<!-- PDP Master Container: Black wash to white transition will occur on an absolute overlay -->
<div id="pdp-environment" class="relative w-full min-h-screen bg-white text-black font-body-md overflow-hidden">
    
    <!-- Environment Wash (Starts Black, fades out to reveal white gallery) -->
    <div id="pdp-wash" class="fixed inset-0 bg-black z-40 pointer-events-none"></div>

    <div class="w-full max-w-[1920px] mx-auto flex flex-col md:flex-row relative z-10 pt-[80px]">
        
        <!-- ========================================== -->
        <!-- LEFT COLUMN: PRODUCT INSPECTION (55%)      -->
        <!-- ========================================== -->
        <div id="pdp-left-col" class="w-full md:w-[55%] md:h-[calc(100vh-80px)] md:sticky top-[80px] bg-white flex items-center justify-center relative p-8 md:p-16 border-r border-black/10 editorial-grid-line origin-top" style="border-right-width: 0px;">
            <!-- Grid Lines -->
            <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black/10 scale-x-0 origin-left editorial-grid-line"></div>
            
            <!-- Back Action -->
            <a href="{{ route('products.index') }}" class="group absolute top-8 left-8 flex items-center gap-2 font-mono text-[10px] uppercase tracking-widest text-black/50 hover:text-black transition-colors z-20 overflow-hidden pdp-reveal-item opacity-0 translate-y-4">
                <span class="material-symbols-outlined text-[14px] transform transition-transform duration-300 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:-translate-x-2">arrow_back</span>
                <span>RETURN TO ARCHIVE</span>
            </a>

            <!-- Product Container (Mouse Parallax Target) -->
            <div id="product-parallax-container" class="relative w-full max-w-[600px] aspect-square flex items-center justify-center">
                @if($product->media->isNotEmpty())
                    <img id="product-hero-image" src="{{ $product->media->first()->url }}" alt="{{ $product->name }}" class="w-full h-auto object-contain z-10 drop-shadow-[0_20px_40px_rgba(0,0,0,0.05)] transform scale-[0.97] translate-y-10 opacity-0 pointer-events-none">
                    
                    <!-- Museum Spotlight / Shadow -->
                    <div id="product-shadow" class="absolute bottom-[10%] left-1/2 -translate-x-1/2 w-[60%] h-[20px] bg-black/10 blur-xl rounded-[100%] opacity-0"></div>
                @else
                    <div id="product-hero-image" class="w-full aspect-square flex items-center justify-center text-black/30 font-mono text-xs uppercase tracking-widest transform scale-[0.97] translate-y-10 opacity-0">
                        [ VISUAL DATA UNAVAILABLE ]
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- RIGHT COLUMN: EDITORIAL CONTENT (45%)      -->
        <!-- ========================================== -->
        <div id="pdp-right-col" class="w-full md:w-[45%] bg-white flex flex-col pt-8 md:pt-16 pb-32">
            
            <!-- 1. Header Block -->
            <div class="px-8 md:px-16 pb-16 flex flex-col gap-6 relative">
                <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black/10 scale-x-0 origin-left editorial-grid-line"></div>
                
                <div class="flex flex-col gap-1 pdp-reveal-item opacity-0 translate-y-6">
                    @if($product->collection)
                        <span class="font-mono text-[10px] uppercase tracking-widest text-black/40">{{ $product->collection->name }}</span>
                    @endif
                    <h1 class="font-h1 text-5xl md:text-6xl tracking-tight leading-[0.9] uppercase text-black">{{ $product->name }}</h1>
                    <h2 class="font-mono text-xs uppercase tracking-widest text-black/60 mt-2">{{ $product->tagline }}</h2>
                </div>

                <div class="pdp-reveal-item opacity-0 translate-y-6 mt-4">
                    <span class="font-h1 text-3xl uppercase tracking-widest text-black">${{ number_format($product->price, 2) }}</span>
                </div>
                
                <!-- Allocation Block -->
                <div class="pdp-reveal-item opacity-0 translate-y-6 mt-6 flex flex-col gap-3 w-full max-w-[300px]" id="stock-availability" data-product-id="{{ $product->id }}">
                    <div class="flex justify-between items-end font-mono text-[10px] uppercase tracking-widest text-black/60">
                        <span id="stock-label">ALLOCATION</span>
                        <span><span id="stock-current" class="text-black font-bold">{{ $product->stock }}</span> / {{ $denominator }}</span>
                    </div>
                    <!-- Allocation Progress Bar -->
                    <div class="w-full h-[1px] bg-black/10 relative overflow-hidden">
                        @php
                            $progressPct = ($product->stock / $denominator) * 100;
                        @endphp
                        <div id="stock-progress-line" class="absolute top-0 left-0 h-full bg-black transform scale-x-0 origin-left transition-transform duration-1000 ease-[cubic-bezier(0.165,0.84,0.44,1)]" data-target-scale="{{ $progressPct / 100 }}"></div>
                    </div>
                    @if($isDropActive && $pastPurchases > 0)
                        <span class="font-mono text-[9px] uppercase tracking-widest text-black/40 mt-1">ALLOCATION USED: {{ $pastPurchases }}</span>
                    @endif
                </div>
            </div>

            <!-- 2. Overview Block -->
            <div class="px-8 md:px-16 py-16 relative">
                <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black/10 scale-x-0 origin-left editorial-grid-line"></div>
                <h3 class="font-mono text-[10px] uppercase tracking-widest text-black/40 mb-8 pdp-reveal-item opacity-0 translate-y-6">01 / OVERVIEW</h3>
                <p class="font-body-md text-base md:text-lg leading-relaxed text-black/80 whitespace-pre-line max-w-[45ch] pdp-reveal-item opacity-0 translate-y-6">{{ $product->description }}</p>
            </div>

            <!-- 3. Specifications Block (Scroll Reveal) -->
            <div class="px-8 md:px-16 py-16 relative">
                <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black/10 scale-x-0 origin-left editorial-grid-line"></div>
                <h3 class="font-mono text-[10px] uppercase tracking-widest text-black/40 mb-8 pdp-reveal-item opacity-0 translate-y-6">02 / SPECIFICATIONS</h3>
                
                <div class="flex flex-col border-t border-black/10">
                    @php
                        $specs = [
                            'REFERENCE' => $product->slug,
                            'MOVEMENT' => $product->movement,
                            'CASE MATERIAL' => $product->case_material,
                            'DIAMETER' => $product->diameter_mm ? $product->diameter_mm . ' MM' : null,
                            'WATER RESISTANCE' => $product->water_resistance,
                            'CRYSTAL' => $product->crystal,
                            'WARRANTY' => $product->warranty_years ? $product->warranty_years . ' YEARS' : null,
                        ];
                    @endphp
                    @foreach($specs as $key => $value)
                        @if($value)
                            <div class="spec-row flex flex-col md:flex-row md:items-center justify-between py-6 border-b border-black/10 hover:bg-[#F9F9F9] transition-colors duration-300 opacity-0 translate-y-6">
                                <span class="font-mono text-[10px] uppercase tracking-widest text-black/50 transition-colors duration-300">{{ $key }}</span>
                                <span class="font-mono text-xs uppercase tracking-widest text-black mt-2 md:mt-0">{{ $value }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- 4. Acquisition Block -->
            <div class="px-8 md:px-16 py-16" x-data="{ qty: 1, maxQty: {{ $maxQty }} }">
                <h3 class="font-mono text-[10px] uppercase tracking-widest text-black/40 mb-8 pdp-reveal-item opacity-0 translate-y-6">03 / SECURE ALLOCATION</h3>
                
                <form action="{{ route('cart.store') }}" method="POST" class="flex flex-col gap-12">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" x-model="qty">
                    
                    @if($product->straps->isNotEmpty())
                    <div class="flex flex-col gap-6 pdp-reveal-item opacity-0 translate-y-6">
                        <label class="font-mono text-[10px] uppercase tracking-widest text-black/50">STRAP CONFIGURATION</label>
                        <div class="flex flex-col gap-0 border border-black/10">
                            @foreach($product->straps as $strap)
                                <label class="cursor-pointer bg-white p-6 flex items-center justify-between hover:bg-[#F9F9F9] transition-colors relative border-b border-black/10 last:border-b-0 group">
                                    <input type="radio" name="strap_option_id" value="{{ $strap->id }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }} required>
                                    <div class="font-mono text-xs uppercase tracking-widest text-black/60 peer-checked:text-black peer-checked:font-bold transition-colors">{{ $strap->strap_name }}</div>
                                    <div class="w-3 h-3 border border-black/30 rounded-full flex items-center justify-center peer-checked:border-black transition-colors">
                                        <div class="w-1.5 h-1.5 bg-black rounded-full opacity-0 peer-checked:opacity-100 transition-opacity transform scale-50 peer-checked:scale-100"></div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($limitReached)
                    <div class="pdp-reveal-item opacity-0 translate-y-6">
                        <div class="flex flex-col gap-2 p-8 border border-black/10 bg-[#F9F9F9] items-center text-center">
                            <span class="font-h1 text-2xl uppercase tracking-widest text-black">ALLOCATION FULL</span>
                            <p class="font-mono text-[10px] uppercase tracking-widest text-black/50">You have reached the maximum allowed limit for this drop.</p>
                        </div>
                    </div>
                    @else
                    <div class="flex flex-col gap-6 pdp-reveal-item opacity-0 translate-y-6">
                        <label class="font-mono text-[10px] uppercase tracking-widest text-black/50">QUANTITY</label>
                        <div class="flex items-center w-[120px] h-[40px] border border-black/10 group-hover:border-black/30 transition-colors">
                            <button type="button" @click="qty = qty > 1 ? qty - 1 : 1" class="h-full w-10 flex items-center justify-center text-black/50 hover:text-black hover:bg-[#F9F9F9] transition-colors font-mono text-lg">-</button>
                            <input type="number" x-model="qty" name="quantity" min="1" :max="maxQty" class="h-full flex-grow text-center font-mono text-xs uppercase tracking-widest text-black bg-transparent focus:outline-none appearance-none m-0 pointer-events-none" style="-moz-appearance: textfield;" readonly>
                            <button type="button" @click="qty = qty < maxQty ? qty + 1 : qty" class="h-full w-10 flex items-center justify-center text-black/50 hover:text-black hover:bg-[#F9F9F9] transition-colors font-mono text-lg">+</button>
                        </div>
                    </div>
                    
                    <div class="pdp-reveal-item opacity-0 translate-y-6" id="add-to-cart-wrapper">
                        <button type="submit" class="cta-button group relative w-full border border-black bg-white text-black py-6 overflow-hidden flex items-center justify-between px-8 hover:bg-black hover:text-white transition-all duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] active:scale-[0.98]">
                            <span class="font-h1 text-xl tracking-widest uppercase relative z-10">ACQUIRE</span>
                            <span class="material-symbols-outlined text-[18px] transform transition-transform duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:translate-x-2 relative z-10">arrow_forward</span>
                        </button>
                    </div>
                    @endif
                </form>
            </div>
            
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ---------------------------------------------------------
    // GSAP MASTER TIMELINE: Private Inspection Room
    // ---------------------------------------------------------
    
    // 1. Initial State Hiding
    gsap.set('#main-nav', { opacity: 0, y: 24 });
    
    // Master Timeline
    const masterTl = gsap.timeline();

    // PHASE 1: Page Initialization (Wash & Nav)
    masterTl.to('#pdp-wash', {
        opacity: 0,
        duration: 1.4,
        ease: 'cubic-bezier(0.65, 0, 0.35, 1)', // easeInOutCubic equivalent
    }, 0)
    .to('#main-nav', {
        opacity: 1,
        y: 0,
        duration: 0.6,
        ease: 'power3.out',
    }, "-=0.6")
    // Grid Lines Reveal (ScaleX horizontally, borderRight down)
    .to('.editorial-grid-line', {
        scaleX: 1,
        borderRightWidth: '1px',
        duration: 0.8,
        stagger: 0.1,
        ease: 'power2.out',
    }, "-=0.4");

    // PHASE 2: Product Reveal
    masterTl.to('#product-hero-image', {
        opacity: 1,
        y: 0,
        scale: 1,
        duration: 1.2,
        ease: 'expo.out',
    }, "-=0.6")
    .to('#product-shadow', {
        opacity: 1,
        duration: 1.2,
        ease: 'power2.out',
    }, "-=1.0");

    // PHASE 3: Editorial Content (Initial Viewport)
    masterTl.to('.pdp-reveal-item', {
        opacity: 1,
        y: 0,
        duration: 0.6,
        stagger: 0.08,
        ease: 'power3.out',
    }, "-=1.0");

    // ---------------------------------------------------------
    // PHASE 4: Product Inspection (Breathing Animation)
    // ---------------------------------------------------------
    masterTl.add(() => {
        gsap.to('#product-hero-image', {
            y: -5, // Move up 5px
            duration: 3.5,
            ease: 'sine.inOut',
            yoyo: true,
            repeat: -1
        });
        gsap.to('#product-shadow', {
            scale: 0.95,
            opacity: 0.7,
            duration: 3.5,
            ease: 'sine.inOut',
            yoyo: true,
            repeat: -1
        });
    });

    // ---------------------------------------------------------
    // PHASE 5-6: Scroll Experience & Specification Reveal
    // ---------------------------------------------------------
    const specRows = document.querySelectorAll('.spec-row');
    if (specRows.length > 0) {
        ScrollTrigger.batch('.spec-row', {
            start: 'top 90%',
            once: true,
            onEnter: batch => gsap.to(batch, {
                opacity: 1,
                y: 0,
                duration: 0.5,
                stagger: 0.07,
                ease: 'power3.out',
            }),
        });
    }

    // ---------------------------------------------------------
    // PHASE 7: Allocation Status Reveal
    // ---------------------------------------------------------
    const stockLine = document.getElementById('stock-progress-line');
    if (stockLine) {
        const targetScale = stockLine.getAttribute('data-target-scale');
        ScrollTrigger.create({
            trigger: '#stock-availability',
            start: 'top 95%',
            once: true,
            onEnter: () => {
                gsap.to(stockLine, {
                    scaleX: targetScale,
                    duration: 1.0,
                    ease: 'power3.out',
                    delay: 0.2 // Wait for the text to appear from Phase 3
                });
            }
        });
    }

    // ---------------------------------------------------------
    // PHASE 10: Image Interaction (Parallax)
    // ---------------------------------------------------------
    const parallaxContainer = document.getElementById('pdp-left-col');
    const heroImage = document.getElementById('product-hero-image');
    
    if (parallaxContainer && heroImage && window.matchMedia("(hover: hover) and (pointer: fine)").matches) {
        // Use Framer Motion style spring simulation or simple lerp for smooth parallax
        let targetX = 0;
        let targetY = 0;
        let targetRotate = 0;
        
        parallaxContainer.addEventListener('mousemove', (e) => {
            const rect = parallaxContainer.getBoundingClientRect();
            // Calculate mouse position relative to center (-1 to 1)
            const x = ((e.clientX - rect.left) / rect.width) * 2 - 1;
            const y = ((e.clientY - rect.top) / rect.height) * 2 - 1;
            
            // Max bounds: 8px translation, 1deg rotation
            targetX = x * 8;
            targetY = y * 8;
            targetRotate = x * 1;
            
            gsap.to(heroImage, {
                x: targetX,
                // Do not override the Y completely to preserve breathing, just add slight rotation/x
                rotation: targetRotate,
                duration: 0.5,
                ease: 'power2.out',
                overwrite: 'auto'
            });
        });

        parallaxContainer.addEventListener('mouseleave', () => {
            gsap.to(heroImage, {
                x: 0,
                rotation: 0,
                duration: 0.8,
                ease: 'power2.out',
                overwrite: 'auto'
            });
        });
    }

    // ---------------------------------------------------------
    // BACKGROUND AJAX POLLING (Re-integrated)
    // ---------------------------------------------------------
    const container = document.getElementById('stock-availability');
    if (!container) return;
    const productId = container.dataset.productId;
    const currentStockLabel = document.getElementById('stock-current');
    const qtyWrapper = document.querySelector('[x-data]');
    const addToCartBtn = document.querySelector('.cta-button');
    const btnText = addToCartBtn ? addToCartBtn.querySelector('span:first-child') : null;

    function pollStock() {
        fetch('/api/products/stock?ids[]=' + encodeURIComponent(productId), {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                const product = data[0];
                const stock = product.stock;
                
                // Update Numeric UI
                if (currentStockLabel) {
                    currentStockLabel.textContent = stock;
                }
                
                // Update Progress Line dynamically
                if (stockLine) {
                    const denominator = {{ $denominator }};
                    const targetScale = Math.min(1, Math.max(0, stock / denominator));
                    gsap.to(stockLine, {
                        scaleX: targetScale,
                        duration: 0.6,
                        ease: 'power2.out'
                    });
                }

                // Alpine UI constraints
                if (qtyWrapper && qtyWrapper.__x) {
                    qtyWrapper.__x.$data.maxQty = Math.max(0, stock);
                    if (qtyWrapper.__x.$data.qty > stock) {
                        qtyWrapper.__x.$data.qty = Math.max(1, stock);
                    }
                }

                // Button State
                if (addToCartBtn) {
                    if (stock <= 0 || product.status === 'sold_out') {
                        addToCartBtn.disabled = true;
                        addToCartBtn.classList.add('opacity-50', 'pointer-events-none');
                        if (btnText) btnText.textContent = 'SOLD OUT';
                    } else {
                        addToCartBtn.disabled = false;
                        addToCartBtn.classList.remove('opacity-50', 'pointer-events-none');
                        if (btnText) btnText.textContent = 'ACQUIRE';
                    }
                }
            }
        })
        .catch(() => { /* silently ignore network errors */ });
    }

    setInterval(pollStock, 15000);
});
</script>

<style>
/* Clean inputs and scrollbars for PDP */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
#pdp-environment ::-webkit-scrollbar {
    width: 0px;
    background: transparent;
}
</style>
@endsection
