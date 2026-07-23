@extends('layouts.app')

@section('title', 'Smart Advisor Results - Clementine')

@section('content')

<div class="w-full bg-background min-h-screen flex flex-col pt-[80px]">
    
    <!-- Header Section -->
    <header class="w-full px-lg md:px-2xl py-2xl md:py-4xl border-b border-primary bg-background flex flex-col md:flex-row md:items-end justify-between gap-xl relative overflow-hidden" id="advisor-results-header">
        <div class="relative z-10 w-full md:w-3/4">
            <h1 class="advisor-headline font-h1 text-[clamp(4rem,8vw,6rem)] text-primary m-0 p-0 leading-[0.85] tracking-tight uppercase" style="font-weight: 400; text-wrap: balance;">
                <span class="advisor-word inline-block" style="opacity: 0; letter-spacing: 0.15em;">MATCH</span>
                <span class="advisor-word inline-block" style="opacity: 0; letter-spacing: 0.15em;">PROTOCOL</span>
            </h1>
            <div class="advisor-subhead mt-lg overflow-hidden flex flex-col md:flex-row gap-lg md:gap-2xl md:items-center">
                <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-primary/60 m-0" style="opacity: 0; transform: translateY(100%);">
                    Computed against maximum budget constraint: $<span class="text-primary">${{ number_format($budget) }}</span>
                </p>
                <a href="{{ route('advisor.index') }}" class="inline-flex items-center gap-2 border-b border-primary/30 hover:border-primary pb-1 font-mono text-[9px] uppercase tracking-[0.2em] text-primary/60 hover:text-primary transition-colors" style="opacity: 0; transform: translateY(100%);">
                    [ RECALIBRATE PARAMETERS ]
                </a>
            </div>
        </div>
    </header>

    <div class="w-full p-lg md:p-2xl bg-background">
        @if($recommendations->isEmpty())
            <!-- Empty State -->
            <div class="w-full border border-primary p-2xl md:p-4xl flex flex-col items-center justify-center text-center advisor-fade-up" style="opacity: 0; transform: translateY(20px);">
                <div class="font-mono text-[10px] tracking-[0.2em] text-primary/40 uppercase mb-lg">[ 0 MATCHES FOUND ]</div>
                <h2 class="font-h2 text-[clamp(2rem,4vw,3rem)] uppercase text-primary leading-none mb-xl max-w-2xl">
                    NO TIMEPIECE SATISFIES THE CURRENT PARAMETERS
                </h2>
                <a href="{{ route('advisor.index') }}" class="bg-primary text-background hover:bg-background hover:text-primary border border-primary px-xl py-lg font-mono text-[10px] uppercase tracking-[0.2em] transition-colors active:scale-[0.98] duration-150">
                    ADJUST CONSTRAINTS
                </a>
            </div>
        @else
            <!-- Results Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-0 border-t border-l border-primary">
                @foreach($recommendations as $index => $product)
                    <div class="group relative flex flex-col h-full border-b border-r border-primary bg-background hover:bg-primary/5 transition-colors duration-300 advisor-fade-up" style="opacity: 0; transform: translateY(20px);">
                        
                        <!-- Header Bar -->
                        <div class="flex items-center justify-between border-b border-primary/20 p-md bg-background">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-[9px] text-primary/40 uppercase tracking-[0.2em]">
                                    #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                            <div class="font-mono text-[10px] uppercase tracking-[0.2em] text-primary border border-primary px-3 py-1 bg-background">
                                [ {{ $product->match_percentage }}% MATCH ]
                            </div>
                        </div>

                        <!-- Image -->
                        <a href="{{ route('products.show', $product->slug) }}" class="block relative aspect-square overflow-hidden border-b border-primary/20 bg-surface">
                            @if($product->primaryImage)
                                <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply transition-transform duration-700 ease-[cubic-bezier(0.83,0,0.17,1)] group-hover:scale-[1.03]">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-primary/30 font-mono text-[10px] tracking-[0.2em] uppercase">NO SIGNAL</div>
                            @endif
                        </a>
                        
                        <!-- Details -->
                        <div class="p-lg flex flex-col flex-1 justify-between">
                            <div>
                                <span class="font-mono text-[9px] text-primary/50 uppercase tracking-[0.2em] block mb-2">
                                    {{ $product->collection ? $product->collection->name : 'ARCHIVE' }}
                                </span>
                                <h3 class="font-h2 text-2xl uppercase text-primary mb-6 leading-none">
                                    <a href="{{ route('products.show', $product->slug) }}" class="hover:opacity-60 transition-opacity">{{ $product->name }}</a>
                                </h3>
                                
                                <div class="grid grid-cols-2 gap-y-4 border-t border-primary/20 pt-6 mb-8">
                                    <div class="font-mono text-[9px] text-primary/40 uppercase tracking-[0.2em]">GENDER</div>
                                    <div class="font-mono text-[10px] text-primary text-right uppercase tracking-[0.1em]">{{ $product->gender }}</div>
                                    
                                    <div class="font-mono text-[9px] text-primary/40 uppercase tracking-[0.2em]">MOVEMENT</div>
                                    <div class="font-mono text-[10px] text-primary text-right uppercase tracking-[0.1em]">{{ $product->movement }}</div>
                                    
                                    <div class="font-mono text-[9px] text-primary/40 uppercase tracking-[0.2em]">MATERIAL</div>
                                    <div class="font-mono text-[10px] text-primary text-right uppercase tracking-[0.1em]">{{ $product->material }}</div>
                                </div>
                            </div>
                            
                            <!-- Action / Price -->
                            <div class="flex items-center justify-between mt-auto">
                                <span class="font-mono text-[14px] text-primary tracking-widest">${{ number_format($product->price, 2) }}</span>
                                
                                <form action="{{ route('cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="border-b border-primary pb-1 font-mono text-[9px] uppercase tracking-[0.2em] text-primary hover:text-primary/60 transition-colors">
                                        ACQUIRE
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Check if GSAP is available
    if (typeof gsap !== 'undefined') {
        const tl = gsap.timeline();
        
        tl.fromTo('.advisor-word', 
            { opacity: 0, y: 40, scale: 0.95 },
            { opacity: 1, y: 0, scale: 1, duration: 0.8, stagger: 0.05, ease: "power3.out" }
        )
        .to('.advisor-word', 
            { letterSpacing: "0em", duration: 1.2, ease: "power2.inOut" }, 
            "-=0.6"
        )
        .fromTo('.advisor-subhead p, .advisor-subhead a',
            { opacity: 0, y: '100%' },
            { opacity: 1, y: '0%', duration: 0.6, stagger: 0.1, ease: "power2.out" },
            "-=0.8"
        )
        .fromTo('.advisor-fade-up',
            { opacity: 0, y: 30 },
            { opacity: 1, y: 0, duration: 0.8, stagger: 0.05, ease: "power2.out" },
            "-=0.4"
        );
    }
});
</script>

@endsection
