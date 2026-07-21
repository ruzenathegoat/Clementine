@extends('layouts.app')

@section('title', 'Your Recommended Watches - Clementine')

@section('content')

<!-- Header Section -->
<header class="w-full px-lg py-3xl border-b border-primary bg-background flex flex-col items-center justify-center text-center">
    <h1 class="font-h1 text-[50px] md:text-[70px] text-primary m-0 p-0 leading-none tracking-tighter uppercase mb-4">
        YOUR MATCHES
    </h1>
    <p class="font-body-md text-sm text-primary uppercase tracking-widest max-w-2xl">
        Based on your budget (Under ${{ number_format($budget) }}) and preferences, our Smart Advisor has calculated the perfect matches for you.
    </p>
    <a href="{{ route('advisor.index') }}" class="mt-8 border border-primary px-6 py-2 font-body-sm text-body-sm uppercase tracking-widest hover:bg-primary hover:text-on-primary transition-colors">
        RETAKE ADVISOR
    </a>
</header>

<!-- Main Content Area -->
<div class="w-full bg-background min-h-[50vh] p-lg md:p-3xl">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg max-w-7xl mx-auto">
        @forelse($recommendations as $index => $product)
            <div class="group relative flex flex-col h-full border-2 border-primary bg-surface-container-lowest overflow-hidden">
                
                <!-- Rank Badge -->
                <div class="absolute top-4 left-4 z-20 bg-primary text-on-primary font-h2 text-2xl w-12 h-12 flex items-center justify-center rounded-full border-2 border-primary">
                    #{{ $index + 1 }}
                </div>

                <!-- Match Percentage Badge -->
                <div class="absolute top-4 right-4 z-20 bg-background text-primary border-2 border-primary font-body-md font-bold px-3 py-1 text-sm uppercase tracking-wider">
                    {{ $product->match_percentage }}% MATCH
                </div>

                <a href="{{ route('products.show', $product->slug) }}" class="block relative aspect-square overflow-hidden border-b-2 border-primary bg-surface-container-highest">
                    @if($product->primaryImage)
                        <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-primary/30 font-body-md">NO IMAGE</div>
                    @endif
                </a>
                
                <div class="p-6 flex flex-col flex-1 justify-between">
                    <div>
                        <div class="font-label-caps text-xs text-[#787774] mb-2 uppercase tracking-widest">
                            {{ $product->collection ? $product->collection->name : 'General' }}
                        </div>
                        <h3 class="font-headline-md text-2xl uppercase text-primary mb-2 leading-none">
                            <a href="{{ route('products.show', $product->slug) }}" class="hover:underline">{{ $product->name }}</a>
                        </h3>
                        <div class="font-body-sm text-sm text-primary/80 uppercase tracking-wide mb-6">
                            {{ $product->gender }} • {{ $product->movement }} • {{ $product->material }}
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between border-t border-primary/20 pt-4 mt-auto">
                        <span class="font-body-md font-bold text-xl">${{ number_format($product->price, 2) }}</span>
                        
                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="bg-primary text-on-primary border-2 border-primary hover:bg-background hover:text-primary px-6 py-2 font-body-sm uppercase tracking-widest transition-colors">
                                ADD TO CART
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20">
                <p class="font-headline-md text-xl uppercase">No matches found. Try broadening your criteria.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
