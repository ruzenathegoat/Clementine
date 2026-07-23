@extends('layouts.app')

@section('title', strtoupper($collection->name) . ' - Archive')

@push('styles')
<style>
    /* Mechanical/Archive Custom CSS */
    :root {
        --ease-out: cubic-bezier(0.23, 1, 0.32, 1);
        --bg-color: #FAFAFA;
        --ink-color: #0A0A0A;
    }

    body {
        background-color: var(--bg-color);
        color: var(--ink-color);
        scrollbar-width: none;
    }
    body::-webkit-scrollbar { display: none; }

    .title-display {
        letter-spacing: -0.04em;
        text-wrap: balance;
    }

    .archive-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        border-top: 1px solid rgba(10, 10, 10, 0.1);
        border-left: 1px solid rgba(10, 10, 10, 0.1);
    }
    
    .archive-item {
        border-right: 1px solid rgba(10, 10, 10, 0.1);
        border-bottom: 1px solid rgba(10, 10, 10, 0.1);
        cursor: crosshair;
        background-color: var(--bg-color);
    }

    /* Product Hover Masking */
    .clip-reveal {
        clip-path: inset(100% 0 0 0);
        transition: clip-path 250ms var(--ease-out), transform 250ms var(--ease-out);
        will-change: clip-path, transform;
    }
    .archive-item:hover .clip-reveal {
        clip-path: inset(0 0 0 0);
        transform: translateY(-6px);
    }
    .base-img {
        transition: opacity 250ms var(--ease-out);
    }
    .archive-item:hover .base-img {
        opacity: 0; 
    }
</style>
@endpush

@section('content')

<!-- COLLECTION HERO -->
<header class="w-full flex flex-col md:flex-row min-h-[60vh] border-b border-[#e5e5e5]">
    
    <!-- Left: Typography & Info -->
    <div class="w-full md:w-1/2 p-6 md:p-12 flex flex-col justify-between border-b md:border-b-0 md:border-r border-[#e5e5e5]">
        <div>
            <a href="{{ route('collections.index') }}" class="inline-flex items-center gap-2 font-mono text-[10px] tracking-widest text-[#555] hover:text-[#1A1A1A] transition-colors mb-12 md:mb-24 uppercase">
                <span class="material-symbols-outlined text-[14px]">arrow_back</span>
                BACK TO ARCHIVES
            </a>
            
            <h1 class="font-h1 text-[12vw] md:text-[80px] leading-[0.9] text-ink-color uppercase title-display m-0" style="view-transition-name: title-{{ $collection->id }};">
                {{ $collection->name }}
            </h1>
            
            @if($collection->description)
            <p class="font-body-md text-sm text-[#555] mt-8 max-w-[50ch] text-pretty leading-relaxed">
                {{ $collection->description }}
            </p>
            @endif
        </div>
        
        <div class="mt-12 flex items-center gap-12 font-mono text-[10px] tracking-[0.2em] uppercase text-[#1A1A1A]">
            <div>
                <span class="text-[#909090] block mb-2">ASSETS</span>
                <span class="text-base">{{ str_pad($collection->products->count(), 2, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div>
                <span class="text-[#909090] block mb-2">STATUS</span>
                <span class="flex items-center gap-2 text-base">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#1A1A1A] animate-pulse"></span> ACTIVE
                </span>
            </div>
        </div>
    </div>
    
    <!-- Right: Featured Image -->
    <div class="w-full md:w-1/2 relative bg-[#F4F4F4] min-h-[40vh] md:min-h-0 flex items-center justify-center p-12 overflow-hidden">
        @if($collection->image_url)
            <img src="{{ $collection->image_url }}" 
                 alt="{{ $collection->name }}" 
                 class="w-full h-full object-contain max-w-[80%] max-h-[80%]"
                 style="view-transition-name: img-{{ $collection->id }};">
        @endif
    </div>
</header>

<!-- PRODUCTS GRID -->
<section class="w-full max-w-[1800px] mx-auto px-6 md:px-12 py-24">
    <div class="mb-12 font-mono text-[10px] tracking-[0.2em] text-[#909090] uppercase border-b border-[#e5e5e5] pb-4">
        [ CATALOGUED ASSETS ]
    </div>
    
    <div class="archive-grid">
        @forelse ($collection->products as $product)
            @if($product->stock <= 0)
            <div class="archive-item flex flex-col justify-between h-[420px] relative overflow-hidden opacity-40 cursor-not-allowed">
            @else
            <a href="{{ route('products.show', $product->slug) }}" class="archive-item group flex flex-col justify-between h-[420px] relative overflow-hidden transition-transform duration-150 active:scale-[0.98]">
            @endif
            
                <!-- Price / Name top -->
                <div class="relative z-10 flex justify-between items-start p-6">
                    <h3 class="font-h1 text-xl md:text-2xl uppercase tracking-tighter text-[#1A1A1A] leading-none m-0 max-w-[70%] text-balance">
                        {{ $product->name }}
                    </h3>
                    <span class="font-mono text-[10px] tracking-widest text-[#1A1A1A]">
                        ${{ number_format($product->price, 0) }}
                    </span>
                </div>
                
                <!-- Image -->
                <div class="absolute inset-0 z-0 flex items-center justify-center p-12 pointer-events-none mt-8">
                    <div class="w-full h-full relative">
                        @if ($product->primaryImage)
                            <img src="{{ $product->primaryImage->url }}" class="absolute inset-0 w-full h-full object-contain grayscale-[40%] brightness-[0.8] contrast-[1.1] base-img" alt="{{ $product->name }}">
                            <img src="{{ $product->primaryImage->url }}" class="absolute inset-0 w-full h-full object-contain clip-reveal" alt="{{ $product->name }} Detail">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[#909090] text-xs uppercase font-mono tracking-widest">No Image</div>
                        @endif
                    </div>
                </div>
                
                <!-- Stock Status Bottom -->
                <div class="relative z-10 p-6 flex flex-col justify-end mt-auto">
                    @if($product->stock <= 0)
                        <span class="font-mono text-[9px] tracking-[0.2em] text-[#1A1A1A]">[ OUT OF STOCK ]</span>
                    @elseif($product->stock <= 10)
                        <span class="font-mono text-[9px] tracking-[0.2em] text-[#1A1A1A]">[ LOW STOCK ]</span>
                    @else
                        <span class="font-mono text-[9px] tracking-[0.2em] text-[#909090]">[ IN STOCK ]</span>
                    @endif
                </div>
                
            @if($product->stock <= 0)
            </div>
            @else
            </a>
            @endif
        @empty
            <div class="col-span-full py-24 text-center font-mono text-xs tracking-[0.2em] text-[#909090] uppercase">
                NO ASSETS FOUND IN THIS ARCHIVE.
            </div>
        @endforelse
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        if (!prefersReducedMotion && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);

            // Stagger entrance of grid items
            const items = gsap.utils.toArray('.archive-item');
            gsap.set(items, { opacity: 0, y: 20 });

            ScrollTrigger.batch(items, {
                start: "top 85%",
                onEnter: batch => {
                    gsap.to(batch, {
                        opacity: 1,
                        y: 0,
                        duration: 0.5,
                        stagger: 0.05,
                        ease: 'power2.out',
                        overwrite: true
                    });
                },
                once: true
            });
        } else if (prefersReducedMotion && typeof gsap !== 'undefined') {
            gsap.set('.archive-item', { opacity: 1, y: 0 });
        }
    });
</script>
@endpush
