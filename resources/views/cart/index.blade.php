@extends('layouts.app')

@section('title', 'Your Cart - Clementine')

@section('content')
<div class="px-lg py-xl max-w-7xl mx-auto w-full gap-xl flex flex-col items-center">
    <header class="w-full border-b border-primary pb-sm mb-lg">
        <h1 class="font-h1 text-[60px] md:text-[100px] leading-[0.8] tracking-tighter uppercase w-full">YOUR CART<span class="font-serif italic text-copper lowercase tracking-normal">cart</span></h1>
    </header>
    
    <div class="w-full flex flex-col lg:flex-row gap-xl items-start">
        <!-- Left Column: Items -->
        <div class="w-full lg:w-2/3 flex flex-col border-t border-primary">
            @forelse($cartItems as $item)
            <div class="flex flex-col sm:flex-row py-lg border-b border-primary gap-md relative group">
                <div class="w-32 h-32 flex-shrink-0 border border-primary bg-surface p-sm">
                    @if($item->product->primaryImage)
                    <img alt="{{ $item->product->name }}" class="w-full h-full object-cover mix-blend-multiply" src="{{ $item->product->primaryImage->url }}">
                    @endif
                </div>
                <div class="flex flex-col justify-between flex-grow">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-h2 text-2xl uppercase">{{ $item->product->name }}</h3>
                            <div class="font-body-sm text-secondary mt-sm flex flex-col gap-xs">
                                <span>COLLECTION: {{ $item->product->collection->name ?? 'N/A' }}</span>
                                @if($item->strapOption)
                                <span>STRAP: {{ $item->strapOption->material }}</span>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-primary hover:text-error transition-colors p-sm border border-transparent hover:border-primary">
                                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">close</span>
                            </button>
                        </form>
                    </div>
                    <div class="flex flex-col md:flex-row justify-between md:items-end mt-md gap-md">
                        <!-- Brutalist Quantity Selector -->
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-primary bg-background h-[40px] w-max">
                            @csrf @method('PUT')
                            <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="h-full px-md border-r border-primary hover:bg-primary hover:text-on-primary transition-colors font-bold text-lg flex items-center justify-center" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                            <span class="h-full px-lg font-h2 text-sm flex items-center justify-center min-w-[3rem]">{{ $item->quantity }}</span>
                            <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="h-full px-md border-l border-primary hover:bg-primary hover:text-on-primary transition-colors font-bold text-lg flex items-center justify-center">+</button>
                        </form>
                        
                        <div class="text-left md:text-right">
                            <div class="font-label-caps text-secondary mb-xs text-xs">UNIT: <span class="text-primary">${{ number_format($item->product->price, 2) }}</span></div>
                            <div class="font-h2 text-xl uppercase text-primary">${{ number_format($item->product->price * $item->quantity, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-xl text-center font-body-md text-primary opacity-50 uppercase border-b border-primary">Your cart is empty.</div>
            @endforelse
        </div>
        
        <!-- Right Column: Summary -->
        <div class="w-full lg:w-1/3 border border-primary bg-background p-xl sticky top-[100px]">
            <h2 class="font-h2 text-2xl uppercase border-b border-primary pb-sm mb-lg">ORDER SUMMARY</h2>
            <div class="flex flex-col gap-sm font-body-md mb-xl text-sm">
                <div class="flex justify-between">
                    <span>SUBTOTAL (EXCL. TAX)</span>
                    <span class="text-primary font-bold">${{ number_format($subtotal, 2) }}</span>
                </div>
                @if($totalDiscount > 0)
                <div class="flex justify-between text-[#D97757]">
                    <span>{{ auth()->user()?->is_vip ? 'VIP DISCOUNT' : 'DISCOUNT' }}</span>
                    <span class="font-bold">-${{ number_format($totalDiscount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span>SHIPPING FEE</span>
                    <span class="text-primary font-bold">${{ number_format($shipping, 2) }}</span>
                </div>
            </div>
            <div class="flex justify-between items-end border-t border-primary pt-md mb-xl">
                <span class="font-label-caps font-bold">ESTIMATED TOTAL</span>
                <span class="font-h2 text-3xl text-primary">${{ number_format($total, 2) }}</span>
            </div>
            @if($cartItems->isNotEmpty())
            <a href="{{ route('checkout.index') }}" class="flex justify-between items-center w-full bg-primary text-on-primary font-h2 text-xl py-lg px-xl border border-primary hover:bg-background hover:text-primary transition-colors uppercase group">
                <span>CHECKOUT</span>
                <span class="material-symbols-outlined transform group-hover:translate-x-2 transition-transform">arrow_forward</span>
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
