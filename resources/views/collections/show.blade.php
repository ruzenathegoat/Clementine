@extends('layouts.app')

@section('title', strtoupper($collection->name) . ' - Clementine')

@section('content')

<!-- Collection Header -->
<header class="w-full min-h-[40vh] md:min-h-[50vh] flex flex-col justify-end p-lg md:p-3xl border-b border-primary relative overflow-hidden bg-background">
    <!-- Optional background image -->
    @if($collection->image_url)
        <div class="absolute inset-0 z-0 opacity-20 bg-cover bg-center grayscale mix-blend-multiply" style="background-image: url('{{ $collection->image_url }}');"></div>
    @endif
    
    <div class="relative z-10 w-full max-w-5xl">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('collections.index') }}" class="w-10 h-10 rounded-full border border-primary flex items-center justify-center hover:bg-primary hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <span class="font-label-caps text-xs tracking-widest uppercase text-secondary">ARCHIVE SERIES</span>
        </div>
        
        <h1 class="font-h1 text-[60px] md:text-[120px] leading-[0.85] tracking-tighter text-primary uppercase break-words">
            {{ $collection->name }}
        </h1>
        
        @if($collection->description)
        <p class="font-body-md text-lg md:text-xl text-secondary mt-8 max-w-2xl leading-relaxed">
            {{ $collection->description }}
        </p>
        @endif
    </div>
</header>

<!-- Stats Bar -->
<div class="w-full px-lg py-md border-b border-primary bg-surface-container-low flex flex-wrap justify-between items-center gap-4">
    <div class="font-label-caps text-xs tracking-widest uppercase">
        <span class="text-secondary mr-2">TOTAL ASSETS:</span>
        <strong class="text-primary">{{ $collection->products->count() }}</strong>
    </div>
    <div class="font-label-caps text-xs tracking-widest uppercase">
        <span class="text-secondary mr-2">STATUS:</span>
        <strong class="text-primary flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span> ACTIVE COLLECTION
        </strong>
    </div>
</div>

<!-- Products Grid -->
<div class="w-full bg-surface-container-lowest flex-grow">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 w-full border-l border-primary">
        @forelse ($collection->products as $product)
            @if($product->stock <= 0)
            <div class="group flex flex-col bg-background border-r border-b border-primary opacity-60 cursor-not-allowed">
            @else
            <a href="{{ route('products.show', $product->slug) }}" class="group flex flex-col bg-background transition-colors border-r border-b border-primary hover:bg-surface-container-highest">
            @endif
            
                <div class="flex justify-between items-center p-md border-b border-primary bg-transparent">
                    <span class="font-h2 text-sm uppercase tracking-tight bg-primary text-on-primary px-2 py-1 border border-primary">
                        {{ $collection->name }}
                    </span>
                    <span class="font-h2 text-sm text-primary">${{ number_format($product->price, 2) }}</span>
                </div>
                
                <div class="w-full aspect-square bg-transparent border-b border-primary flex items-center justify-center p-xl relative overflow-hidden">
                    @if ($product->primaryImage)
                    <div class="w-full h-full bg-contain bg-center bg-no-repeat transition-transform duration-500 ease-mechanical group-hover:scale-105"
                         style="background-image: url('{{ $product->primaryImage->url }}')"></div>
                    @else
                    <div class="w-full h-full bg-transparent flex items-center justify-center text-secondary text-xs uppercase">No Image</div>
                    @endif
                    
                    @if($product->stock <= 0)
                    <div class="absolute inset-0 bg-primary/20 backdrop-blur-[2px] flex items-center justify-center z-10">
                        <span class="font-label-caps text-xs text-background tracking-widest px-4 py-2 bg-primary">[ OUT OF STOCK ]</span>
                    </div>
                    @endif
                </div>
                
                <div class="p-lg flex flex-col flex-1">
                    <h3 class="font-h2 text-lg uppercase leading-tight mb-1">{{ $product->name }}</h3>
                    <p class="font-body-md text-[10px] text-secondary uppercase pt-2">
                        {{ $product->tagline ?? 'TIMEPIECE' }}
                    </p>
                    <div class="mt-sm flex items-center gap-xs">
                        @if($product->status === 'sold_out' || $product->stock <= 0)
                            <span class="font-label-caps text-[10px] uppercase tracking-wider font-bold text-primary opacity-50">[ OUT OF STOCK ]</span>
                        @elseif($product->stock <= 10)
                            <span class="font-label-caps text-[10px] uppercase tracking-wider font-bold text-primary">[ LOW STOCK — {{ $product->stock }} LEFT ]</span>
                        @else
                            <span class="font-label-caps text-[10px] uppercase tracking-wider font-bold text-primary">[ IN STOCK ]</span>
                        @endif
                    </div>
                </div>
                
            @if($product->stock <= 0)
            </div>
            @else
            </a>
            @endif
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-4 p-3xl text-center font-body-md text-secondary border-r border-b border-primary bg-background flex flex-col items-center justify-center min-h-[300px]">
                <i class="ph-light ph-empty text-4xl mb-4 text-primary"></i>
                <p>NO ASSETS FOUND IN THIS ARCHIVE.</p>
                <a href="{{ route('collections.index') }}" class="underline text-primary mt-4 uppercase text-sm tracking-widest">Return to Collections</a>
            </div>
        @endforelse
    </div>
</div>

@endsection
