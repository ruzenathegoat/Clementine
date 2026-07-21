@extends('layouts.app')

@section('title', 'Your Recommended Watches - Clementine')

@section('content')

<div class="w-full bg-background border-b border-primary">
    <div class="w-full max-w-7xl mx-auto border-l border-r border-primary bg-surface-container-lowest min-h-screen flex flex-col">
        
        <!-- Header Section -->
        <header class="w-full px-6 md:px-12 py-24 md:py-32 border-b border-primary text-center">
            <h1 class="font-h1 text-[50px] md:text-[80px] text-primary m-0 p-0 leading-none tracking-tighter uppercase mb-6">
                YOUR MATCHES
            </h1>
            <p class="font-body-md text-lg text-primary uppercase tracking-widest max-w-3xl mx-auto mb-12">
                Based on your budget (Under ${{ number_format($budget) }}) and preferences, our Smart Advisor has calculated the perfect matches for you.
            </p>
            <a href="{{ route('advisor.index') }}" class="inline-flex items-center gap-2 border border-primary px-8 py-4 font-body-sm text-sm uppercase tracking-widest hover:bg-primary hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined text-[18px]">refresh</span>
                RETAKE ADVISOR
            </a>
        </header>

        <!-- Main Content Area -->
        <div class="w-full p-6 md:p-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-12">
                @forelse($recommendations as $index => $product)
                    <div class="group relative flex flex-col h-full border border-primary bg-surface-container-lowest hover:bg-surface-container-highest transition-colors">
                        
                        <!-- Header Bar -->
                        <div class="flex items-center justify-between border-b border-primary p-4 bg-background">
                            <div class="flex items-center gap-3">
                                <span class="bg-primary text-on-primary font-body-sm px-2 py-1 text-xs uppercase tracking-widest">
                                    #{{ $index + 1 }}
                                </span>
                                <span class="font-label-caps text-xs text-[#787774] uppercase tracking-widest">
                                    {{ $product->collection ? $product->collection->name : 'General' }}
                                </span>
                            </div>
                            <div class="font-body-md font-bold text-xs uppercase tracking-wider text-primary border border-primary px-2 py-1 bg-surface-container-highest">
                                {{ $product->match_percentage }}% MATCH
                            </div>
                        </div>

                        <!-- Image -->
                        <a href="{{ route('products.show', $product->slug) }}" class="block relative aspect-square overflow-hidden border-b border-primary">
                            @if($product->primaryImage)
                                <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-primary/30 font-body-md">NO IMAGE</div>
                            @endif
                        </a>
                        
                        <!-- Details -->
                        <div class="p-6 flex flex-col flex-1 justify-between">
                            <div>
                                <h3 class="font-headline-md text-3xl uppercase text-primary mb-4 leading-none">
                                    <a href="{{ route('products.show', $product->slug) }}" class="hover:underline">{{ $product->name }}</a>
                                </h3>
                                
                                <div class="grid grid-cols-2 gap-y-2 font-body-sm text-sm text-primary uppercase tracking-wide mb-8">
                                    <div class="text-[#787774]">GENDER</div>
                                    <div class="text-right">{{ $product->gender }}</div>
                                    
                                    <div class="text-[#787774]">MOVEMENT</div>
                                    <div class="text-right">{{ $product->movement }}</div>
                                    
                                    <div class="text-[#787774]">MATERIAL</div>
                                    <div class="text-right">{{ $product->material }}</div>
                                </div>
                            </div>
                            
                            <!-- Action / Price -->
                            <div class="flex items-center justify-between border-t border-primary pt-6 mt-auto">
                                <span class="font-headline-md text-2xl">${{ number_format($product->price, 2) }}</span>
                                
                                <form action="{{ route('cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="bg-primary text-on-primary border border-primary hover:bg-background hover:text-primary px-6 py-3 font-body-sm text-sm uppercase tracking-widest transition-colors">
                                        ADD TO CART
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-24 border border-primary bg-surface-container-highest">
                        <p class="font-headline-md text-2xl uppercase">No matches found. Try broadening your criteria.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
    </div>
</div>

@endsection
