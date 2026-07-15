@extends('layouts.app')

@section('title', 'CLEMENTINE | Timeless Horology')

@section('content')

    {{-- Hero --}}
    <section class="w-full bg-background pt-3xl pb-2xl px-lg border-b border-border">
        <h1 class="font-display text-[10vw] leading-[0.9] text-text-primary uppercase tracking-tighter text-center max-w-[1400px] mx-auto">
            TIMELESS DESIGN WITHOUT COMPROMISE
        </h1>
    </section>

    {{-- Collection Strip --}}
    <section class="grid grid-cols-1 md:grid-cols-4 w-full border-b border-border">
        @forelse ($collections as $collection)
            <div class="relative aspect-square {{ !$loop->last ? 'border-r border-border' : '' }} group overflow-hidden">
                @if ($collection->image_url)
                    <img src="{{ $collection->image_url }}" alt="{{ $collection->name }}"
                         class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-[filter] duration-300" />
                @else
                    <div class="w-full h-full bg-surface"></div>
                @endif
                <div class="absolute inset-0 flex items-end p-lg bg-gradient-to-t from-black/40 to-transparent pointer-events-none">
                    <span class="font-display text-2xl text-text-inverse uppercase">{{ $collection->name }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-4 p-3xl text-center font-mono text-text-secondary">No collections yet.</div>
        @endforelse
    </section>

    {{-- Brand Story --}}
    <section class="bg-primary text-text-inverse py-3xl px-lg border-b border-border">
        <div class="max-w-4xl mx-auto text-center">
            <p class="font-mono text-xl leading-relaxed opacity-90">
                Clementine was born from a simple belief — that a watch should outlast trends, not chase them.
                Every case is machined, every movement regulated, every strap hand-finished by people who'd wear
                it themselves. We build the one watch you reach for every single day, for the next twenty years.
            </p>
        </div>
    </section>

    {{-- Featured Showcase --}}
    <section class="bg-primary relative min-h-[600px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none"
             style="background-image: linear-gradient(to right, #ffffff 1px, transparent 1px), linear-gradient(to bottom, #ffffff 1px, transparent 1px); background-size: 40px 40px;"></div>
        <div class="absolute top-lg left-lg">
            <h2 class="font-display text-3xl text-text-inverse border-l-4 border-text-inverse pl-md">100% SWISS MADE</h2>
        </div>
        <div class="absolute bottom-xl right-xl">
            <a href="{{ route('products.index') }}"
               class="inline-block bg-text-inverse text-primary px-3xl py-lg font-mono text-lg uppercase hover:opacity-80 transition-opacity">
                SHOP THE SERIES
            </a>
        </div>
    </section>

    {{-- Feature List --}}
    <section class="w-full bg-background">
        <div class="grid grid-cols-1 divide-y divide-border">
            <div class="flex justify-between items-center px-lg py-xl hover:bg-surface transition-colors">
                <span class="font-display text-2xl text-text-primary">SWISS MOVEMENT</span>
                <span class="material-symbols-outlined scale-150">precision_manufacturing</span>
            </div>
            <div class="flex justify-between items-center px-lg py-xl hover:bg-surface transition-colors">
                <span class="font-display text-2xl text-text-primary">SAPPHIRE CRYSTAL</span>
                <span class="material-symbols-outlined scale-150">diamond</span>
            </div>
            <div class="flex justify-between items-center px-lg py-xl hover:bg-surface transition-colors">
                <span class="font-display text-2xl text-text-primary">100M WATER RESISTANT</span>
                <span class="material-symbols-outlined scale-150">water_drop</span>
            </div>
            <div class="flex justify-between items-center px-lg py-xl hover:bg-surface transition-colors">
                <span class="font-display text-2xl text-text-primary">GENUINE LEATHER</span>
                <span class="material-symbols-outlined scale-150">branding_watermark</span>
            </div>
        </div>
    </section>

    {{-- New Arrivals (real DB data) --}}
    <section class="border-t border-border">
        <div class="flex justify-between items-end p-lg border-b border-border">
            <div>
                <span class="font-mono text-xs uppercase text-text-secondary block mb-xs">CURATED DROPS</span>
                <h2 class="font-display text-3xl text-text-primary uppercase">NEW ARRIVALS</h2>
            </div>
            <a href="{{ route('products.index') }}" class="font-mono text-xs uppercase text-text-primary border-b border-border pb-1 hover:opacity-60 transition-opacity">
                View More
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 w-full">
            @forelse ($newArrivals as $product)
                <a href="{{ route('products.show', $product->slug) }}"
                   class="border-r border-b lg:border-b-0 border-border p-lg flex flex-col group last:border-r-0">
                    <div class="bg-surface aspect-square mb-lg relative overflow-hidden">
                        @if ($product->primaryImage)
                            <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        @endif
                        @if ($product->status === 'new')
                            <div class="absolute top-md left-md bg-primary text-text-inverse px-sm py-1 font-mono text-[10px] uppercase">NEW</div>
                        @elseif ($product->status === 'limited_edition')
                            <div class="absolute top-md left-md bg-primary text-text-inverse px-sm py-1 font-mono text-[10px] uppercase">LIMITED EDITION</div>
                        @endif
                    </div>
                    <h3 class="font-mono font-bold text-lg mb-xs uppercase">{{ $product->name }}</h3>
                    <div class="flex justify-between items-center">
                        <span class="font-mono text-text-primary">${{ number_format($product->price, 2) }}</span>
                        <span class="material-symbols-outlined p-xs group-hover:bg-primary group-hover:text-text-inverse transition-colors">add_shopping_cart</span>
                    </div>
                </a>
            @empty
                <div class="col-span-4 p-3xl text-center font-mono text-text-secondary">No products yet.</div>
            @endforelse
        </div>
    </section>

@endsection