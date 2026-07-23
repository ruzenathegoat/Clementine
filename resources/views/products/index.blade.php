@extends('layouts.app')

@section('title', 'Shop All Watches - Clementine')

@section('content')

<!-- Header Section -->
<header class="w-full px-lg md:px-2xl py-2xl md:py-4xl border-b border-primary bg-background flex flex-col md:flex-row md:items-end justify-between gap-xl relative overflow-hidden" id="catalog-header">
    <div class="relative z-10 w-full md:w-3/4">
        <h1 class="catalog-headline font-h1 text-[clamp(4rem,8vw,6rem)] text-primary m-0 p-0 leading-[0.85] tracking-tight uppercase" style="font-weight: 400; text-wrap: balance;">
            <span class="catalog-word inline-block" style="opacity: 0; letter-spacing: 0.15em;">SHOP</span>
            <span class="catalog-word inline-block" style="opacity: 0; letter-spacing: 0.15em;">ALL</span>
            <span class="catalog-word inline-block" style="opacity: 0; letter-spacing: 0.15em;">WATCHES</span>
        </h1>
    </div>
    <div class="catalog-counter font-mono text-[10px] text-primary/70 uppercase tracking-[0.2em] relative z-10 shrink-0 mb-2 md:mb-4" style="opacity: 0; transform: translateY(16px);">
        <span class="counter-number">{{ $products->count() }}</span> WATCH{{ $products->count() === 1 ? '' : 'ES' }}
    </div>
</header>

<!-- Main Content Area -->
<div class="flex-1 w-full flex flex-col md:flex-row items-stretch bg-background">
    
    <!-- Filter Sidebar (Sticky) -->
    <aside class="catalog-sidebar relative w-full md:w-[320px] shrink-0 border-r border-primary bg-background flex flex-col border-b md:border-b-0 md:sticky md:top-0 md:h-screen overflow-y-auto" style="opacity: 0; transform: translateY(20px);">
        
        <div class="p-lg flex justify-between items-center border-b border-primary bg-background sticky top-0 z-20">
            <h2 class="font-mono text-[10px] tracking-[0.2em] uppercase text-primary/50">FILTERS</h2>
            <button type="button" class="clear-filters-btn font-mono text-[9px] tracking-[0.1em] uppercase text-primary hover:text-primary/50 transition-colors">CLEAR ALL</button>
        </div>

        <form id="filter-form" method="GET" action="{{ route('products.index') }}" class="flex flex-col pb-24">
            
            <!-- Collection -->
            <div class="p-lg border-b border-primary filter-section">
                <h3 class="font-mono text-[11px] tracking-[0.15em] mb-md uppercase text-primary">COLLECTION</h3>
                <div class="flex flex-col gap-0 border border-primary">
                    @foreach ($collectionOptions as $opt)
                        <label class="filter-chip relative group cursor-pointer border-b border-primary last:border-b-0 flex items-center justify-between px-3 py-2 bg-background transition-colors duration-200 ease-out {{ $opt['active'] ? 'bg-primary is-active' : 'hover:bg-primary' }}">
                            <input type="radio" name="collection" value="{{ $opt['slug'] ?? '' }}"
                                   {{ $opt['active'] ? 'checked' : '' }}
                                   class="sr-only filter-input" />
                            <span class="font-mono text-[11px] tracking-[0.05em] uppercase z-10 transition-colors duration-200 ease-out {{ $opt['active'] ? 'text-background' : 'text-primary group-hover:text-background' }}">{{ $opt['label'] }}</span>
                            <!-- Sweep indicator -->
                            <div class="sweep-indicator absolute left-0 top-0 bottom-0 bg-primary w-0 z-0"></div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Price -->
            <div class="p-lg border-b border-primary filter-section">
                <h3 class="font-mono text-[11px] tracking-[0.15em] mb-md uppercase text-primary">PRICE</h3>
                <div class="flex items-center gap-sm">
                    <input type="number" name="price_min" value="{{ request('price_min', '') }}" placeholder="MIN"
                           class="filter-input w-full border border-primary px-sm py-xs font-mono text-[11px] bg-background focus:ring-0 focus:outline-none focus:border-primary placeholder:text-primary/30" />
                    <span class="font-mono text-[11px] text-primary/50">—</span>
                    <input type="number" name="price_max" value="{{ request('price_max', '') }}" placeholder="MAX"
                           class="filter-input w-full border border-primary px-sm py-xs font-mono text-[11px] bg-background focus:ring-0 focus:outline-none focus:border-primary placeholder:text-primary/30" />
                </div>
            </div>

            <!-- Material -->
            @if ($materials->isNotEmpty())
            <div class="p-lg border-b border-primary filter-section">
                <h3 class="font-mono text-[11px] tracking-[0.15em] mb-md uppercase text-primary">MATERIAL</h3>
                <div class="flex flex-col gap-0 border border-primary">
                    @foreach ($materials as $material)
                        @php $isActive = in_array($material, (array) request('material', [])); @endphp
                        <label class="filter-chip relative group cursor-pointer border-b border-primary last:border-b-0 flex items-center px-3 py-2 bg-background transition-colors duration-200 ease-out {{ $isActive ? 'bg-primary is-active' : 'hover:bg-primary' }}">
                            <input type="checkbox" name="material[]" value="{{ $material }}"
                                   {{ $isActive ? 'checked' : '' }}
                                   class="sr-only filter-input" />
                            <span class="font-mono text-[11px] tracking-[0.05em] uppercase z-10 transition-colors duration-200 ease-out {{ $isActive ? 'text-background' : 'text-primary group-hover:text-background' }}">{{ $material }}</span>
                            <div class="sweep-indicator absolute left-0 top-0 bottom-0 bg-primary w-0 z-0"></div>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Diameter -->
            @if ($diameters->isNotEmpty())
            <div class="p-lg border-b border-primary filter-section">
                <h3 class="font-mono text-[11px] tracking-[0.15em] mb-md uppercase text-primary">DIAMETER</h3>
                <div class="flex flex-wrap border-t border-l border-primary">
                    @foreach ($diameters as $diameter)
                        @php $isActive = in_array($diameter, (array) request('diameter', [])); @endphp
                        <label style="margin-top: -1px; margin-left: -1px;" class="filter-chip relative group cursor-pointer border border-primary flex items-center justify-center px-3 py-2 bg-background transition-colors duration-200 ease-out flex-1 min-w-[33%] {{ $isActive ? 'bg-primary is-active' : 'hover:bg-primary' }}">
                            <input type="checkbox" name="diameter[]" value="{{ $diameter }}"
                                   {{ $isActive ? 'checked' : '' }}
                                   class="sr-only filter-input" />
                            <span class="font-mono text-[11px] tracking-[0.05em] uppercase z-10 transition-colors duration-200 ease-out {{ $isActive ? 'text-background' : 'text-primary group-hover:text-background' }}">{{ $diameter }}MM</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Movement -->
            @if ($movements->isNotEmpty())
            <div class="p-lg border-b border-primary filter-section">
                <h3 class="font-mono text-[11px] tracking-[0.15em] mb-md uppercase text-primary">MOVEMENT</h3>
                <div class="flex flex-col gap-0 border border-primary">
                    @foreach ($movements as $movement)
                        @php $isActive = in_array($movement, (array) request('movement', [])); @endphp
                        <label class="filter-chip relative group cursor-pointer border-b border-primary last:border-b-0 flex items-center px-3 py-2 bg-background transition-colors duration-200 ease-out {{ $isActive ? 'bg-primary is-active' : 'hover:bg-primary' }}">
                            <input type="checkbox" name="movement[]" value="{{ $movement }}"
                                   {{ $isActive ? 'checked' : '' }}
                                   class="sr-only filter-input" />
                            <span class="font-mono text-[11px] tracking-[0.05em] uppercase z-10 transition-colors duration-200 ease-out {{ $isActive ? 'text-background' : 'text-primary group-hover:text-background' }}">{{ $movement }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif
            
            <button type="submit" class="hidden">Submit</button>
        </form>
    </aside>

    <!-- Product Grid Container -->
    <div class="flex-1 relative bg-background min-h-[50vh]">
        
        <div class="grid grid-cols-[repeat(auto-fill,minmax(280px,1fr))] w-full content-start border-l-0 md:border-l border-primary" id="catalog-grid">
            @forelse ($products as $index => $product)
                @php 
                    $isOutOfStock = $product->stock <= 0;
                    $colIndex = $index % 3; // For stagger
                @endphp
                <div class="catalog-product-card group relative flex flex-col bg-background border-r border-b border-primary cursor-pointer active:scale-[0.98] transition-transform duration-150" 
                     data-id="{{ $product->id }}"
                     data-flip-id="prod-{{ $product->id }}"
                     data-col="{{ $colIndex }}"
                     onclick="window.location.href='{{ route('products.show', $product->slug) }}'">
                    
                    <!-- Metadata Top Bar -->
                    <div class="card-meta flex justify-between items-center p-md border-b border-primary bg-background transition-colors duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:bg-[#f4f4f4]">
                        <span class="font-mono text-[10px] tracking-[0.1em] uppercase text-primary">
                            {{ $product->collection->name ?? 'ARCHIVE' }}
                        </span>
                        <span class="card-price font-mono text-[10px] text-primary transition-transform duration-300 ease-out group-hover:-translate-y-[1px]">
                            ${{ number_format($product->price, 2) }}
                        </span>
                    </div>
                    
                    <!-- Image Area -->
                    <div class="w-full aspect-[4/5] bg-background border-b border-primary flex items-center justify-center p-xl relative overflow-hidden">
                        @if ($product->primaryImage)
                            <div class="card-image-wrapper relative w-full h-full flex items-center justify-center transition-transform duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:scale-[1.03] group-hover:-translate-y-2">
                                <div class="watch-breathe w-full h-full bg-contain bg-center bg-no-repeat relative z-10"
                                     style="background-image: url('{{ $product->primaryImage->url }}')"></div>
                                
                                <!-- Shadow (only under watch, appears on hover) -->
                                <div class="card-shadow absolute bottom-10 left-1/2 -translate-x-1/2 w-[60%] h-[12px] bg-black/10 blur-[8px] rounded-full opacity-0 z-0 transition-opacity duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:opacity-100"></div>
                            </div>
                        @else
                            <div class="w-full h-full bg-background flex items-center justify-center font-mono text-[10px] text-primary/30 uppercase tracking-[0.1em]">NO IMAGE DATA</div>
                        @endif
                        
                        @if($isOutOfStock)
                            <div class="absolute inset-0 bg-background/40 backdrop-blur-[1px] flex items-center justify-center z-20">
                                <span class="font-mono text-[10px] text-background tracking-[0.2em] px-3 py-1 bg-primary">ARCHIVED</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Bottom Information -->
                    <div class="p-md flex flex-col flex-1 bg-background z-10 transition-colors duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:bg-[#fafafa]">
                        <h3 class="card-title font-h2 text-lg uppercase leading-tight tracking-normal transition-all duration-500 ease-out group-hover:tracking-[0.02em]">{{ $product->name }}</h3>
                        <p class="card-subtitle font-body-md text-[11px] text-primary/75 uppercase pt-1 opacity-75 transition-opacity duration-500 ease-out group-hover:opacity-100">
                            {{ $product->tagline ?? 'MECHANICAL TIMEPIECE' }}
                        </p>
                        
                        <!-- Stock Monitor -->
                        <div class="mt-lg flex items-center">
                            @if($isOutOfStock)
                                <span class="card-stock font-mono text-[9px] uppercase tracking-[0.15em] text-primary/40 font-normal">UNAVAILABLE</span>
                            @elseif($product->stock <= 10)
                                <span class="card-stock low-stock font-mono text-[9px] uppercase tracking-[0.15em] text-primary font-normal transition-all duration-300 group-hover:font-medium group-hover:text-red-700">LOW STOCK &mdash; {{ $product->stock }} LEFT</span>
                            @else
                                <span class="card-stock font-mono text-[9px] uppercase tracking-[0.15em] text-primary/70 font-normal transition-colors duration-300 group-hover:text-primary">INVENTORY &mdash; {{ $product->stock }} UNITS</span>
                            @endif
                        </div>
                    </div>
                    
                </div>
            @empty
                <div class="col-span-full p-4xl flex flex-col items-center justify-center min-h-[400px] border-b border-primary catalog-empty-state">
                    <span class="font-mono text-[10px] tracking-[0.2em] uppercase text-primary/50 mb-4">NO MATCHING TIMEPIECES</span>
                    <button type="button" class="clear-filters-btn font-mono text-[11px] tracking-[0.1em] uppercase text-primary border-b border-primary pb-1 hover:text-primary/50 transition-colors">RESET PROTOCOL</button>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/Flip.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.registerPlugin(ScrollTrigger, Flip);

    const isDesktop = window.innerWidth >= 768;

    // --- PHASE 1-4: ENTRANCE SEQUENCE ---
    const entranceTl = gsap.timeline({ defaults: { ease: "power3.out" } });

    // Phase 1: Headline
    entranceTl.to(".catalog-word", {
        opacity: 1,
        letterSpacing: "0em", // compresses from 0.15em
        duration: 0.7,
        stagger: 0.1,
        ease: "expo.out"
    }, 0.1);

    // Phase 2: Counter
    entranceTl.to(".catalog-counter", {
        opacity: 1,
        y: 0,
        duration: 0.3
    }, 0.3);

    // Phase 3: Sidebar
    entranceTl.to(".catalog-sidebar", {
        opacity: 1,
        y: 0,
        duration: 0.45
    }, 0.4);

    // Phase 4: Product Grid Stagger (by column)
    const cards = gsap.utils.toArray('.catalog-product-card');
    gsap.set(cards, { opacity: 0, y: 24 }); // initial state
    
    // Create ScrollTrigger batch for staggered reveal
    ScrollTrigger.batch(".catalog-product-card", {
        onEnter: batch => {
            const sorted = batch.sort((a, b) => a.dataset.col - b.dataset.col);
            gsap.to(sorted, {
                opacity: 1,
                y: 0,
                duration: 0.6,
                stagger: 0.09,
                ease: "power3.out",
                overwrite: true
            });
        },
        once: true,
        start: "top 95%"
    });

    // --- IDLE: WATCH BREATHING ---
    function initBreathing() {
        const watches = document.querySelectorAll('.watch-breathe');
        watches.forEach((watch, i) => {
            // Kill existing tweens on this element if any
            gsap.killTweensOf(watch);
            gsap.to(watch, {
                y: 5,
                duration: 3.5,
                ease: "none",
                yoyo: true,
                repeat: -1,
                delay: i * 0.2
            });
        });
    }
    initBreathing();

    // --- LOW STOCK MONITOR ---
    setInterval(() => {
        gsap.to(".low-stock", {
            opacity: 0.4,
            duration: 0.4,
            yoyo: true,
            repeat: 1,
            ease: "power1.inOut"
        });
    }, 12000);

    // --- EDITORIAL FOCUS SYSTEM (HOVER) ---
    // Note: Hover animations have been moved to CSS (group-hover utilities) for better performance and alignment with Emil's design engineering principles.

    // --- AJAX FILTERING & GSAP FLIP ---
    const filterForm = document.getElementById('filter-form');
    const clearBtns = document.querySelectorAll('.clear-filters-btn');
    
    // Need a wrapping function so we don't duplicate code
    async function performFilterUpdate() {
        const formData = new FormData(filterForm);
        const searchParams = new URLSearchParams();
        
        for (const [key, value] of formData.entries()) {
            if (value) searchParams.append(key, value);
        }
        
        const url = `${filterForm.action}?${searchParams.toString()}`;
        
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const htmlString = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlString, 'text/html');
            
            const newGridHTML = doc.getElementById('catalog-grid').innerHTML;
            const newCounterText = doc.querySelector('.counter-number').textContent;
            
            updateGrid(newGridHTML, newCounterText, url);
        } catch (e) {
            console.error("Filter fetch failed", e);
            window.location.href = url; // Fallback
        }
    }

    function updateGrid(newGridHTML, newCounterText, newUrl) {
        const gridContainer = document.getElementById('catalog-grid').parentElement;
        const currentCards = gsap.utils.toArray('.catalog-product-card, .catalog-empty-state');
        
        // 1. Fade out current items
        gsap.to(currentCards, {
            opacity: 0,
            y: 10,
            duration: 0.3,
            stagger: 0.02,
            ease: "power2.in",
            onComplete: () => {
                // 2. Replace HTML
                gridContainer.innerHTML = newGridHTML;
                
                // 3. Restart breathing animations
                initBreathing();
                
                const newCards = gsap.utils.toArray('.catalog-product-card, .catalog-empty-state');
                
                // Reset ScrollTrigger batches for new elements
                ScrollTrigger.getAll().forEach(st => {
                    if (st.vars.trigger === ".catalog-product-card") st.kill();
                });
                
                // Set initial state for new items
                gsap.set(newCards, { opacity: 0, y: 20 });
                
                // Re-init ScrollTrigger batch for new elements
                ScrollTrigger.batch(".catalog-product-card", {
                    onEnter: batch => {
                        gsap.to(batch, { 
                            opacity: 1, 
                            y: 0, 
                            duration: 0.45, 
                            stagger: 0.05, 
                            ease: "power2.out", 
                            overwrite: true 
                        });
                    },
                    once: true,
                    start: "top 95%"
                });
                
                // If there are empty state elements, fade them in
                gsap.to('.catalog-empty-state', { opacity: 1, y: 0, duration: 0.45, ease: "power2.out" });
            }
        });

        // Update counter
        const counterEl = document.querySelector('.counter-number');
        if (counterEl.textContent !== newCounterText) {
            gsap.to(counterEl, {
                y: -10,
                opacity: 0,
                duration: 0.2,
                onComplete: () => {
                    counterEl.textContent = newCounterText;
                    gsap.fromTo(counterEl, 
                        { y: 10, opacity: 0 }, 
                        { y: 0, opacity: 1, duration: 0.3, ease: "power2.out" }
                    );
                }
            });
        }
        
        // Update URL quietly
        window.history.pushState({}, '', newUrl);
    }

    // Input changes (Checkboxes, Radios)
    filterForm.addEventListener('change', (e) => {
        if (!e.target.classList.contains('filter-input')) return;
        
        // For radio buttons, we need to clear the active state of other radios in the same group
        if (e.target.type === 'radio') {
            document.querySelectorAll(`input[name="${e.target.name}"]`).forEach(radio => {
                const rLabel = radio.closest('label');
                if (rLabel && radio !== e.target) {
                    rLabel.classList.remove('bg-primary', 'is-active');
                    rLabel.classList.add('hover:bg-primary');
                    const span = rLabel.querySelector('span');
                    if (span) {
                        span.classList.remove('text-background');
                        span.classList.add('text-primary', 'group-hover:text-background');
                    }
                }
            });
        }
        
        const label = e.target.closest('label');
        if (label) {
            if (e.target.type === 'radio' || e.target.type === 'checkbox') {
                if (e.target.checked) {
                    label.classList.add('bg-primary', 'is-active');
                    label.classList.remove('hover:bg-primary');
                    label.querySelector('span').classList.add('text-background');
                    label.querySelector('span').classList.remove('text-primary', 'group-hover:text-background');
                    
                    const sweep = label.querySelector('.sweep-indicator');
                    if (sweep) {
                        gsap.fromTo(sweep, { width: 0 }, { width: '100%', duration: 0.18, ease: "power2.out" });
                    }
                } else {
                    label.classList.remove('bg-primary', 'is-active');
                    label.classList.add('hover:bg-primary');
                    label.querySelector('span').classList.remove('text-background');
                    label.querySelector('span').classList.add('text-primary', 'group-hover:text-background');
                }
            }
        }
        
        performFilterUpdate();
    });

    // Debounced Text Input (Price)
    let priceTimeout;
    const priceInputs = document.querySelectorAll('input[type="number"]');
    priceInputs.forEach(input => {
        input.addEventListener('input', () => {
            clearTimeout(priceTimeout);
            priceTimeout = setTimeout(() => {
                performFilterUpdate();
            }, 600);
        });
    });

    // Clear All
    document.addEventListener('click', (e) => {
        if (e.target.closest('.clear-filters-btn')) {
            e.preventDefault();
            
            const activeLabels = document.querySelectorAll('.filter-chip.is-active');
            
            if (activeLabels.length > 0) {
                gsap.to(activeLabels, {
                    backgroundColor: "transparent",
                    duration: 0.1,
                    stagger: 0.12,
                    onComplete: () => {
                        filterForm.reset();
                        activeLabels.forEach(l => {
                            l.classList.remove('bg-primary', 'is-active');
                            l.classList.add('hover:bg-primary');
                            const span = l.querySelector('span');
                            if(span) {
                                span.classList.remove('text-background');
                                span.classList.add('text-primary', 'group-hover:text-background');
                            }
                        });
                        performFilterUpdate();
                    }
                });
            } else {
                filterForm.reset();
                performFilterUpdate();
            }
        }
    });

});
</script>

@endsection