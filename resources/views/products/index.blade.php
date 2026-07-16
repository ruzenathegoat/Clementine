@extends('layouts.app')

@section('title', 'Shop All Watches - Clementine')

@section('content')

<!-- Header Section -->
<header class="w-full px-lg py-3xl border-b border-primary bg-background flex flex-col md:flex-row md:items-end justify-between gap-md">
    <div>
        <h1 class="font-h1 text-[60px] md:text-[80px] text-primary m-0 p-0 leading-none tracking-tighter uppercase">SHOP ALL WATCHES</h1>
    </div>
    <div class="font-body-md text-sm text-primary border border-primary px-4 py-2 bg-background uppercase shrink-0">
        {{ $products->count() }} WATCH{{ $products->count() === 1 ? '' : 'ES' }}
    </div>
</header>

<!-- Filter Summary -->
@php
    $activeChips = collect();
    if (request()->filled('gender')) $activeChips->push(['label' => strtoupper(request('gender')), 'remove' => request()->except('gender')]);
    if (request()->filled('collection')) $activeChips->push(['label' => strtoupper(request('collection')), 'remove' => request()->except('collection')]);
    if (request()->filled('material')) foreach ((array) request('material') as $m) $activeChips->push(['label' => strtoupper($m), 'remove' => array_merge(request()->except('material'), ['material' => array_values(array_diff((array) request('material'), [$m]))])]);
    if (request()->filled('movement')) foreach ((array) request('movement') as $m) $activeChips->push(['label' => strtoupper($m), 'remove' => array_merge(request()->except('movement'), ['movement' => array_values(array_diff((array) request('movement'), [$m]))])]);
    if (request()->filled('diameter')) foreach ((array) request('diameter') as $d) $activeChips->push(['label' => "{$d}MM", 'remove' => array_merge(request()->except('diameter'), ['diameter' => array_values(array_diff((array) request('diameter'), [$d]))])]);
    if (request()->filled('price_min') || request()->filled('price_max')) $activeChips->push(['label' => '$' . request('price_min', $priceBounds->min_price) . '-' . request('price_max', $priceBounds->max_price), 'remove' => request()->except(['price_min', 'price_max'])]);
@endphp

@if ($activeChips->isNotEmpty())
<div class="w-full px-lg py-md border-b border-primary bg-background flex flex-wrap gap-sm items-center">
    <span class="font-body-sm text-body-sm text-[#787774] uppercase mr-md">Active Filters:</span>
    @foreach ($activeChips as $chip)
        <a href="{{ route('products.index') }}?{{ http_build_query($chip['remove']) }}"
           class="flex items-center gap-2 border border-primary px-3 py-1 font-body-sm text-body-sm bg-surface-container-highest hover:bg-primary hover:text-on-primary transition-colors">
            {{ $chip['label'] }} <span class="material-symbols-outlined text-[14px]">close</span>
        </a>
    @endforeach
    <a href="{{ route('products.index') }}" class="font-body-sm text-body-sm underline text-[#787774] hover:text-primary ml-sm">CLEAR ALL</a>
</div>
@endif

<!-- Main Content Area -->
<div class="flex-1 w-full flex flex-col md:flex-row items-stretch">
    <!-- Filter Sidebar -->
    <aside x-data="{ 
        width: 320, 
        isDragging: false,
        startDrag(e) {
            if(window.innerWidth < 768) return;
            this.isDragging = true;
            document.body.style.cursor = 'col-resize';
            document.body.style.userSelect = 'none';
            const startX = e.pageX;
            const startWidth = this.width;
            
            const onMouseMove = (e) => {
                if (!this.isDragging) return;
                let newWidth = startWidth + (e.pageX - startX);
                this.width = Math.max(250, Math.min(newWidth, 600));
            };
            
            const onMouseUp = () => {
                this.isDragging = false;
                document.body.style.cursor = '';
                document.body.style.userSelect = '';
                document.removeEventListener('mousemove', onMouseMove);
                document.removeEventListener('mouseup', onMouseUp);
            };
            
            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
        }
    }" 
    :style="window.innerWidth >= 768 ? `width: ${width}px; flex-basis: ${width}px; flex-shrink: 0;` : ''"
    class="relative w-full md:w-[320px] shrink-0 border-r border-primary bg-background flex flex-col border-b md:border-b-0 group/sidebar">
        
        <!-- Resizer Handle -->
        <div @mousedown="startDrag" 
             class="absolute top-0 right-0 h-full w-[10px] cursor-col-resize z-50 hover:bg-primary/10 active:bg-primary/20 transition-colors hidden md:block" 
             style="transform: translateX(5px);">
             <div class="absolute inset-y-0 right-[4px] w-[2px] bg-primary opacity-0 group-hover/sidebar:opacity-20 transition-opacity"></div>
        </div>
        <div class="p-lg flex justify-between items-center border-b border-primary bg-surface-container-lowest">
            <h2 class="font-headline-md text-[24px] uppercase">FILTERS</h2>
            <a href="{{ route('products.index') }}" class="font-body-sm text-body-sm underline hover:text-[#787774]">CLEAR ALL</a>
        </div>

        <!-- Collection -->
        <div class="p-lg border-b border-primary">
            <h3 class="font-body-md text-body-md font-bold mb-md uppercase">COLLECTION</h3>
            <div class="flex flex-wrap gap-xs">
                @foreach ($collectionOptions as $opt)
                    <a href="{{ $opt['href'] }}"
                       class="border border-primary px-4 py-2 font-body-sm text-body-sm rounded-pill {{ $opt['active'] ? 'bg-primary text-on-primary' : 'hover:bg-surface-container-highest' }}">
                        {{ $opt['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <form method="GET" action="{{ route('products.index') }}">
            @if (request()->filled('gender'))<input type="hidden" name="gender" value="{{ request('gender') }}">@endif
            @if (request()->filled('collection'))<input type="hidden" name="collection" value="{{ request('collection') }}">@endif

            <!-- Price -->
            <div class="p-lg border-b border-primary">
                <h3 class="font-body-md text-body-md font-bold mb-md uppercase">HARGA</h3>
                <div class="flex items-center gap-sm">
                    <input type="number" name="price_min" value="{{ request('price_min', $priceBounds->min_price ?? 0) }}"
                           min="{{ $priceBounds->min_price ?? 0 }}" max="{{ $priceBounds->max_price ?? 0 }}"
                           class="w-full border border-primary px-sm py-xs font-body-sm text-body-sm bg-background focus:ring-primary focus:border-primary" />
                    <span class="font-body-sm text-body-sm">—</span>
                    <input type="number" name="price_max" value="{{ request('price_max', $priceBounds->max_price ?? 0) }}"
                           min="{{ $priceBounds->min_price ?? 0 }}" max="{{ $priceBounds->max_price ?? 0 }}"
                           class="w-full border border-primary px-sm py-xs font-body-sm text-body-sm bg-background focus:ring-primary focus:border-primary" />
                </div>
                <div class="flex justify-between font-body-sm text-body-sm mt-2 text-[#787774]">
                    <span>MIN ${{ $priceBounds->min_price ?? 0 }}</span>
                    <span>MAX ${{ $priceBounds->max_price ?? 0 }}</span>
                </div>
            </div>

            <!-- Material -->
            @if ($materials->isNotEmpty())
            <div class="p-lg border-b border-primary">
                <h3 class="font-body-md text-body-md font-bold mb-md uppercase">MATERIAL</h3>
                <div class="flex flex-col gap-sm">
                    @foreach ($materials as $material)
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="material[]" value="{{ $material }}"
                                   {{ in_array($material, (array) request('material', [])) ? 'checked' : '' }}
                                   class="w-4 h-4 border-primary rounded-none text-primary focus:ring-primary" />
                            <span class="font-body-sm text-body-sm uppercase">{{ $material }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Diameter -->
            @if ($diameters->isNotEmpty())
            <div class="p-lg border-b border-primary">
                <h3 class="font-body-md text-body-md font-bold mb-md uppercase">DIAMETER</h3>
                <div class="flex flex-wrap gap-xs">
                    @foreach ($diameters as $diameter)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="diameter[]" value="{{ $diameter }}"
                                   {{ in_array($diameter, (array) request('diameter', [])) ? 'checked' : '' }}
                                   class="peer sr-only" />
                            <span class="block border border-primary px-3 py-1 font-body-sm text-body-sm rounded-pill peer-checked:bg-primary peer-checked:text-on-primary hover:bg-surface-container-highest">
                                {{ $diameter }}mm
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Movement -->
            @if ($movements->isNotEmpty())
            <div class="p-lg border-b border-primary">
                <h3 class="font-body-md text-body-md font-bold mb-md uppercase">MOVEMENT</h3>
                <div class="flex flex-col gap-sm">
                    @foreach ($movements as $movement)
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="movement[]" value="{{ $movement }}"
                                   {{ in_array($movement, (array) request('movement', [])) ? 'checked' : '' }}
                                   class="w-4 h-4 border-primary rounded-none text-primary focus:ring-primary" />
                            <span class="font-body-sm text-body-sm uppercase">{{ $movement }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="p-lg">
                <button type="submit" class="w-full border border-primary bg-primary text-on-primary py-3 font-label-caps text-label-caps uppercase hover:bg-surface hover:text-primary transition-colors">
                    APPLY FILTERS
                </button>
            </div>
        </form>
    </aside>

    <!-- Product Grid -->
    <div class="flex-1 bg-surface-container-lowest relative">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 w-full content-start">
            @forelse ($products as $product)
                @if($product->stock <= 0)
                <div class="group flex flex-col bg-background border-r border-b border-primary opacity-60 cursor-not-allowed">
                @else
                <a href="{{ route('products.show', $product->slug) }}" class="group flex flex-col bg-background transition-colors border-r border-b border-primary">
                @endif
                
                    <div class="flex justify-between items-center p-md border-b border-primary bg-background">
                        <span class="font-h2 text-sm uppercase tracking-tight bg-primary text-on-primary px-2 py-1 border border-primary">
                            {{ $product->collection->name ?? '—' }}
                        </span>
                        <span class="font-h2 text-sm text-primary">${{ number_format($product->price, 2) }}</span>
                    </div>
                    <div class="w-full aspect-square bg-background border-b border-primary flex items-center justify-center p-xl relative overflow-hidden">
                        @if ($product->primaryImage)
                        <div class="w-full h-full bg-contain bg-center bg-no-repeat transition-transform duration-500 ease-mechanical group-hover:scale-105"
                             style="background-image: url('{{ $product->primaryImage->url }}')"></div>
                        @else
                        <div class="w-full h-full bg-background flex items-center justify-center text-[#787774] text-xs uppercase">No Image</div>
                        @endif
                        
                        @if($product->stock <= 0)
                        <div class="absolute inset-0 bg-primary/20 backdrop-blur-[2px] flex items-center justify-center z-10">
                            <span class="font-label-caps text-xs text-background tracking-widest px-4 py-2 bg-primary">[ OUT OF STOCK ]</span>
                        </div>
                        @endif
                    </div>
                    <div class="p-lg flex flex-col flex-1">
                        <h3 class="font-h2 text-lg uppercase leading-tight mb-1">{{ $product->name }}</h3>
                        <p class="font-body-md text-[10px] text-[#787774] uppercase pt-2">
                            {{ $product->tagline ?? 'TIMEPIECE' }}
                        </p>
                        {{-- Stock Availability Badge --}}
                        <div class="mt-sm flex items-center gap-xs">
                            @if($product->status === 'sold_out' || $product->stock <= 0)
                                <span class="font-label-caps text-[10px] uppercase tracking-wider font-bold text-primary opacity-50">[ OUT OF STOCK ]</span>
                            @elseif($product->stock <= 10)
                                <span class="font-label-caps text-[10px] uppercase tracking-wider font-bold text-primary">[ LOW STOCK — {{ $product->stock }} LEFT ]</span>
                            @else
                                <span class="font-label-caps text-[10px] uppercase tracking-wider font-bold text-primary">[ IN STOCK — {{ $product->stock }} UNITS ]</span>
                            @endif
                        </div>
                    </div>
                    
                @if($product->stock <= 0)
                </div>
                @else
                </a>
                @endif
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 p-3xl text-center font-body-md text-[#787774] border-b border-r border-primary">
                    No watches match these filters.
                    <a href="{{ route('products.index') }}" class="underline text-primary ml-2">Clear filters</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection