@extends('layouts.app')

@section('title', 'CLEMENTINE | ' . strtoupper($product->name))

@section('content')
<div class="w-full flex flex-col md:flex-row min-h-[calc(100vh-80px)] border-b border-primary">
    <!-- Left: Product Image -->
    <div class="w-full md:w-1/2 border-r-0 md:border-r border-b md:border-b-0 border-primary bg-white p-lg md:p-3xl flex items-center justify-center relative">
        <!-- Back Button -->
        <a href="{{ route('products.index') }}" class="absolute top-lg left-lg border border-primary bg-background text-primary px-md py-sm font-label-caps uppercase hover:bg-primary hover:text-on-primary transition-colors z-10 flex items-center gap-xs">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            BACK
        </a>
        
        @if($product->media->isNotEmpty())
            <img src="{{ $product->media->first()->url }}" alt="{{ $product->name }}" class="w-full h-auto object-cover border border-primary bg-background">
        @else
            <div class="w-full aspect-square border border-primary bg-background flex items-center justify-center text-outline">
                <span class="material-symbols-outlined text-4xl">image_not_supported</span>
            </div>
        @endif
    </div>

    <!-- Right: Product Info -->
    <div class="w-full md:w-1/2 bg-background flex flex-col">
        <!-- Header Section -->
        <div class="p-lg md:p-2xl border-b border-primary">
            @if($product->collection)
                <p class="font-label-caps text-outline uppercase mb-sm">{{ $product->collection->name }}</p>
            @endif
            <h1 class="font-h2 text-4xl md:text-6xl text-primary uppercase leading-none mb-xs">{{ $product->name }}</h1>
            <h2 class="font-body-md text-outline text-lg uppercase mb-md">{{ $product->tagline }}</h2>
            <p class="font-body-md text-primary text-2xl">${{ number_format($product->price, 2) }}</p>

            {{-- Real-time Stock Availability Indicator --}}
            <div id="stock-availability" class="mt-md flex flex-wrap items-center gap-sm" data-product-id="{{ $product->id }}">
                @php
                    if ($product->status === 'sold_out' || $product->stock <= 0) {
                        $stockLevel = 'out';
                        $stockLabel = '[ OUT OF STOCK ]';
                        $textColor = 'text-background';
                        $bgColor = 'bg-primary border-primary';
                    } elseif ($product->stock <= 10) {
                        $stockLevel = 'low';
                        $stockLabel = '[ LOW STOCK — ' . $product->stock . ' LEFT ]';
                        $textColor = 'text-primary';
                        $bgColor = 'bg-background border-primary';
                    } else {
                        $stockLevel = 'in';
                        $stockLabel = '[ IN STOCK — ' . $product->stock . ' UNITS ]';
                        $textColor = 'text-primary';
                        $bgColor = 'bg-background border-primary';
                    }
                @endphp
                <div id="stock-badge" class="inline-flex items-center gap-xs px-md py-xs border {{ $bgColor }} transition-all duration-300">
                    <span id="stock-label" class="font-label-caps text-xs uppercase tracking-wider font-bold {{ $textColor }}">{{ $stockLabel }}</span>
                </div>
                @if($product->status === 'limited_edition')
                    <div class="inline-flex items-center gap-xs px-md py-xs border border-primary bg-primary text-on-primary">
                        <span class="font-label-caps text-xs uppercase tracking-wider">LIMITED EDITION</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Details Section -->
        <div class="p-lg md:p-2xl border-b border-primary flex-grow">
            <h3 class="font-h2 text-xl text-primary uppercase mb-md">OVERVIEW</h3>
            <p class="font-body-md text-primary mb-xl leading-relaxed whitespace-pre-line">{{ $product->description }}</p>

            <h3 class="font-h2 text-xl text-primary uppercase mb-md mt-xl">SPECIFICATIONS</h3>
            <div class="grid grid-cols-2 gap-0 border-t border-l border-primary">
                @php
                    $specs = [
                        'MATERIAL' => $product->material,
                        'MOVEMENT' => $product->movement,
                        'DIAMETER' => $product->diameter_mm ? $product->diameter_mm . ' MM' : null,
                        'GENDER' => strtoupper($product->gender),
                    ];
                @endphp
                @foreach($specs as $key => $value)
                    @if($value)
                        <div class="border-b border-r border-primary p-md flex flex-col">
                            <span class="font-label-caps text-outline text-xs mb-xs">{{ $key }}</span>
                            <span class="font-body-md text-primary uppercase">{{ $value }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Drop Info & Restrictions -->
        @php
            $maxQty = $product->stock;
            $isDropActive = false;
            $pastPurchases = 0;
            $limitReached = false;

            if($product->status === 'new' && $product->scheduled_publish_at) {
                $t40 = (clone $product->scheduled_publish_at)->addMinutes(40);
                if(now()->lt($t40)) {
                    $isDropActive = true;
                    $maxAllowed = auth()->user()?->is_vip ? 3 : 1;
                    
                    if (auth()->check()) {
                        $pastPurchases = \App\Models\OrderItem::whereHas('order', function($q) {
                            $q->where('user_id', auth()->id())->where('status', '!=', 'cancelled');
                        })->where('product_id', $product->id)->sum('quantity');
                    }
                    
                    $remainingAllowed = max(0, $maxAllowed - $pastPurchases);
                    $maxQty = min($product->stock, $remainingAllowed);
                    
                    if ($maxQty <= 0 && $product->stock > 0) {
                        $limitReached = true;
                    }
                }
            }
        @endphp

        @if($isDropActive)
        <div class="px-lg md:px-2xl py-md bg-primary text-on-primary border-b border-primary">
            <p class="font-label-caps font-bold uppercase tracking-widest text-sm mb-2">EXCLUSIVE DROP ACTIVE</p>
            <div class="flex flex-col gap-1 font-mono text-xs uppercase tracking-wider text-on-primary/70">
                <p>DROP STOCK: <span class="text-on-primary font-bold">{{ $product->stock }} LEFT</span></p>
                <p>MAX PURCHASE: <span class="text-on-primary font-bold">{{ auth()->user()?->is_vip ? '3' : '1' }} ITEM(S)</span> <span class="text-[10px] text-on-primary/50 ml-2">({{ auth()->user()?->is_vip ? 'VIP TIER' : 'REGULAR TIER' }})</span></p>
                @if($pastPurchases > 0)
                <p class="mt-1 text-on-primary border border-on-primary inline-block px-2 py-1 w-max">ALLOCATION USED: <span class="font-bold">{{ $pastPurchases }}</span></p>
                @endif
            </div>
        </div>
        @endif

        <!-- Add to Cart Form -->
        <div class="p-lg md:p-2xl bg-surface-container-low" x-data="{ qty: 1, maxQty: {{ $maxQty }} }">
            <form action="{{ route('cart.store') }}" method="POST" class="flex flex-col gap-md">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" x-model="qty">
                
                @if($product->straps->isNotEmpty())
                <div class="flex flex-col gap-sm">
                    <label class="font-label-caps text-primary uppercase">SELECT STRAP</label>
                    <div class="grid grid-cols-2 gap-sm">
                        @foreach($product->straps as $strap)
                            <label class="cursor-pointer border border-primary bg-background p-sm flex items-center justify-between hover:bg-surface transition-colors relative">
                                <input type="radio" name="strap_option_id" value="{{ $strap->id }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }} required>
                                <div class="font-body-md text-primary text-sm uppercase peer-checked:font-bold">{{ $strap->strap_name }}</div>
                                <!-- Pseudo-border for selection -->
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-primary pointer-events-none"></div>
                            </label>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @if($limitReached)
                <div class="flex flex-col gap-sm py-lg">
                    <div class="font-h2 text-2xl uppercase text-background text-center border-2 border-primary py-md bg-primary">PURCHASE LIMIT REACHED</div>
                    <p class="font-body-md text-sm text-primary text-center uppercase">You have reached the maximum allowed allocation for this drop.</p>
                </div>
                @else
                <div class="flex flex-col gap-sm">
                    <label class="font-label-caps text-primary uppercase">QUANTITY</label>
                    <div class="flex items-center border border-primary bg-background w-full h-[60px]">
                        <button type="button" @click="qty = qty > 1 ? qty - 1 : 1" class="h-full px-xl border-r border-primary hover:bg-primary hover:text-on-primary transition-colors text-2xl font-bold flex justify-center items-center">-</button>
                        <input type="number" x-model="qty" name="quantity" min="1" :max="maxQty" class="h-full flex-grow text-center font-h2 text-xl text-primary bg-transparent focus:outline-none appearance-none m-0" style="-moz-appearance: textfield;" readonly>
                        <button type="button" @click="qty = qty < maxQty ? qty + 1 : qty" class="h-full px-xl border-l border-primary hover:bg-primary hover:text-on-primary transition-colors text-2xl font-bold flex justify-center items-center">+</button>
                    </div>
                </div>
                
                <button type="submit" class="w-full border border-primary bg-primary text-on-primary py-lg font-h2 text-2xl uppercase hover:bg-surface hover:text-primary transition-colors flex justify-between px-xl items-center group mt-sm">
                    <span>ADD TO CART</span>
                    <span class="material-symbols-outlined transform group-hover:translate-x-2 transition-transform">arrow_forward</span>
                </button>
                @endif
            </form>
        </div>
    </div>
</div>

{{-- Real-time Stock Polling Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('stock-availability');
    if (!container) return;

    const productId = container.dataset.productId;
    const badge = document.getElementById('stock-badge');
    const dotWrapper = document.getElementById('stock-dot');
    const label = document.getElementById('stock-label');
    const addToCartBtn = document.querySelector('button[type="submit"]');
    const qtyWrapper = document.querySelector('[x-data]');

    function updateStockUI(stock, status) {
        let textColor, bgColor, stockLabel;

        if (status === 'sold_out' || stock <= 0) {
            textColor = 'text-background';
            bgColor = 'bg-primary border-primary';
            stockLabel = '[ OUT OF STOCK ]';
        } else if (stock <= 10) {
            textColor = 'text-primary';
            bgColor = 'bg-background border-primary';
            stockLabel = '[ LOW STOCK \u2014 ' + stock + ' LEFT ]';
        } else {
            textColor = 'text-primary';
            bgColor = 'bg-background border-primary';
            stockLabel = '[ IN STOCK \u2014 ' + stock + ' UNITS ]';
        }

        // Update badge classes
        badge.className = 'inline-flex items-center gap-xs px-md py-xs border ' + bgColor + ' transition-all duration-300';

        // Update label
        label.className = 'font-label-caps text-xs uppercase tracking-wider font-bold ' + textColor;
        label.textContent = stockLabel;
        
        if (dotWrapper) {
            dotWrapper.style.display = 'none';
        }

        // Update Alpine maxQty if available
        if (qtyWrapper && qtyWrapper.__x) {
            qtyWrapper.__x.$data.maxQty = Math.max(0, stock);
            if (qtyWrapper.__x.$data.qty > stock) {
                qtyWrapper.__x.$data.qty = Math.max(1, stock);
            }
        }

        // Disable/enable Add to Cart button
        if (addToCartBtn) {
            if (stock <= 0 || status === 'sold_out') {
                addToCartBtn.disabled = true;
                addToCartBtn.classList.add('opacity-50', 'cursor-not-allowed');
                addToCartBtn.classList.remove('hover:bg-surface', 'hover:text-primary');
            } else {
                addToCartBtn.disabled = false;
                addToCartBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                addToCartBtn.classList.add('hover:bg-surface', 'hover:text-primary');
            }
        }
    }

    function pollStock() {
        fetch('/api/products/stock?ids[]=' + encodeURIComponent(productId))
            .then(r => r.json())
            .then(data => {
                if (Array.isArray(data) && data.length > 0) {
                    const product = data[0];
                    updateStockUI(product.stock, product.status);
                }
            })
            .catch(() => { /* silently ignore network errors */ });
    }

    // Poll every 15 seconds
    setInterval(pollStock, 15000);
});
</script>
@endsection

