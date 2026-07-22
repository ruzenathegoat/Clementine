@extends('layouts.app')

@section('title', 'Collections - Clementine')

@section('content')

<!-- Header Section -->
<header class="w-full px-lg py-3xl border-b border-primary bg-background flex flex-col md:flex-row md:items-end justify-between gap-md">
    <div>
        <h1 class="font-h1 text-[60px] md:text-[80px] text-primary m-0 p-0 leading-none tracking-tighter uppercase">COLLECTIONS</h1>
        <p class="font-body-md text-sm text-[#787774] uppercase tracking-widest mt-2 md:mt-4">
            Curated assortments of uncompromising horology
        </p>
    </div>
    <div class="font-body-md text-sm text-primary border border-primary px-4 py-2 bg-background uppercase shrink-0">
        {{ $collections->count() }} ARCHIVE{{ $collections->count() === 1 ? '' : 'S' }}
    </div>
</header>

<!-- Collections Grid -->
<div class="w-full bg-surface-container-lowest flex-grow">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 border-l border-primary">
        @forelse($collections as $collection)
            <a href="{{ route('collections.show', $collection->slug) }}" class="group flex flex-col bg-background border-r border-b border-primary relative overflow-hidden h-[450px]">
                
                <!-- Background Image & Overlay -->
                <div class="absolute inset-0 z-0">
                    @if($collection->image_url)
                        <div class="w-full h-full bg-cover bg-center transition-transform duration-700 ease-mechanical group-hover:scale-105" style="background-image: url('{{ $collection->image_url }}');"></div>
                    @else
                        <div class="w-full h-full bg-[#f4f4f4] flex items-center justify-center text-primary/10">
                            <i class="ph-light ph-aperture text-[100px]"></i>
                        </div>
                    @endif
                    <!-- Stark contrast overlay on hover -->
                    <div class="absolute inset-0 bg-background/80 md:bg-background/90 transition-colors duration-300 group-hover:bg-primary/90"></div>
                </div>

                <!-- Content -->
                <div class="relative z-10 p-xl flex flex-col h-full justify-between transition-colors duration-300 group-hover:text-on-primary">
                    <div class="flex justify-between items-start">
                        <span class="font-label-caps text-xs tracking-widest border border-primary px-3 py-1 bg-background group-hover:border-on-primary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                            SERIES {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <div class="w-10 h-10 rounded-full border border-primary flex items-center justify-center bg-background group-hover:border-on-primary group-hover:bg-primary group-hover:-rotate-45 transition-all duration-300 ease-mechanical">
                            <span class="material-symbols-outlined text-[20px] group-hover:text-on-primary">arrow_forward</span>
                        </div>
                    </div>
                    
                    <div>
                        <h2 class="font-h1 text-4xl uppercase tracking-tight mb-2 group-hover:text-on-primary">{{ $collection->name }}</h2>
                        <p class="font-body-md text-sm text-secondary group-hover:text-on-primary/70 line-clamp-2">
                            {{ $collection->description ?? 'Explore the signature timepieces within this collection.' }}
                        </p>
                    </div>
                </div>
                
            </a>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 p-3xl text-center font-body-md text-secondary border-r border-b border-primary bg-background">
                No collections available at this moment.
            </div>
        @endforelse
    </div>
</div>

@endsection
