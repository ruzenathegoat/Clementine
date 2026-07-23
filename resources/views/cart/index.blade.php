@extends('layouts.app')

@section('title', 'Your Collection - Clementine')

@section('content')
<div id="cart-environment" class="relative w-full min-h-screen bg-white text-black font-body-md overflow-hidden" 
     x-data="cartState({ 
        subtotal: {{ $subtotal }}, 
        totalDiscount: {{ $totalDiscount }}, 
        shipping: {{ $shipping }}, 
        total: {{ $total }},
        itemsCount: {{ $cartItems->count() }} 
     })">

    <div class="px-8 md:px-16 py-24 max-w-7xl mx-auto w-full flex flex-col items-center">
        <!-- PHASE 1: Heading -->
        <header class="w-full pb-8 mb-16 relative overflow-visible flex flex-col">
            <h1 id="cart-heading" class="font-h1 text-[60px] md:text-[100px] leading-[0.8] tracking-tighter uppercase w-full flex flex-wrap items-baseline gap-4 opacity-0 translate-y-8">
                <span>YOUR CART</span>
                <!-- SVG "cart" italic text for stroke reveal -->
                <svg id="cart-italic" class="h-[50px] md:h-[80px] text-black/20" viewBox="0 0 300 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="text-path" d="M30 70 C40 40, 60 20, 80 40 C100 60, 90 80, 110 70 M130 50 C140 30, 160 30, 170 50 C180 70, 190 70, 200 50 M220 40 C230 30, 240 30, 250 40 M240 20 L240 80" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </h1>
            <div id="cart-divider" class="absolute bottom-0 left-0 w-full h-[1px] bg-black/10 scale-x-0 origin-left"></div>
        </header>

        <div class="w-full flex flex-col lg:flex-row gap-16 items-start" x-show="itemsCount > 0" x-cloak>
            <!-- ========================================== -->
            <!-- PHASE 2: COLLECTION LIST (Left Column)     -->
            <!-- ========================================== -->
            <div class="w-full lg:w-[60%] flex flex-col" id="collection-list">
                @foreach($cartItems as $item)
                <div class="cart-item-row flex flex-col sm:flex-row py-10 border-b border-black/10 gap-8 relative group opacity-0 translate-y-8" 
                     id="item-{{ $item->id }}" data-id="{{ $item->id }}">
                     
                     <!-- PHASE 3: Product Image (Breathing target) -->
                     <div class="w-32 h-32 md:w-40 md:h-40 flex-shrink-0 bg-white p-4 relative overflow-hidden flex items-center justify-center">
                        @if($item->product->primaryImage)
                        <img alt="{{ $item->product->name }}" class="w-full h-full object-contain mix-blend-multiply cart-product-image transition-transform duration-500 group-hover:rotate-1" src="{{ $item->product->primaryImage->url }}">
                        @endif
                     </div>
                     
                     <div class="flex flex-col justify-between flex-grow">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-h1 text-3xl uppercase tracking-tighter cart-item-title transition-all duration-300 group-hover:tracking-normal">{{ $item->product->name }}</h3>
                                <div class="font-mono text-black/50 mt-2 flex flex-col gap-1 text-[10px] uppercase tracking-widest">
                                    <span>REF: {{ $item->product->slug }}</span>
                                    @if($item->strapOption)
                                    <span>STRAP: {{ $item->strapOption->strap_name }}</span>
                                    @endif
                                </div>
                            </div>
                            <!-- PHASE 6: Remove Interaction -->
                            <button @click="removeItem({{ $item->id }}, $event)" class="cart-remove-btn relative w-[60px] h-[30px] flex items-center justify-end text-black/40 hover:text-black transition-colors overflow-hidden">
                                <span class="material-symbols-outlined text-[16px] absolute right-0 transition-all duration-300 transform remove-icon">close</span>
                                <span class="font-mono text-[9px] uppercase tracking-widest absolute right-0 opacity-0 transition-all duration-300 transform translate-x-4 remove-text">REMOVE</span>
                            </button>
                        </div>
                        
                        <div class="flex flex-col md:flex-row justify-between md:items-end mt-8 gap-6">
                            <!-- PHASE 5: Quantity Interaction -->
                            <div class="flex flex-col gap-2">
                                <span class="font-mono text-[9px] uppercase tracking-widest text-black/40">QUANTITY</span>
                                <div class="flex items-center w-[120px] h-[40px] border border-black/10 group-hover:border-black/30 transition-colors qty-container">
                                    <button @click="updateQty({{ $item->id }}, {{ $item->quantity - 1 }}, $event)" class="h-full w-10 flex items-center justify-center text-black/50 hover:text-black hover:bg-[#F9F9F9] transition-colors font-mono text-lg qty-btn active:scale-[0.98]" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                    <div class="h-full flex-grow flex items-center justify-center font-mono text-xs text-black overflow-hidden relative">
                                        <span class="qty-value" data-current="{{ $item->quantity }}">{{ $item->quantity }}</span>
                                    </div>
                                    <button @click="updateQty({{ $item->id }}, {{ $item->quantity + 1 }}, $event)" class="h-full w-10 flex items-center justify-center text-black/50 hover:text-black hover:bg-[#F9F9F9] transition-colors font-mono text-lg qty-btn active:scale-[0.98]">+</button>
                                </div>
                            </div>
                            
                            <div class="text-left md:text-right flex flex-col gap-1">
                                <div class="font-mono text-[9px] uppercase tracking-widest text-black/40">PRICE (UNIT: ${{ number_format($item->product->price, 2) }})</div>
                                <div class="font-h1 text-2xl uppercase tracking-widest text-black line-total group-hover:opacity-80 transition-opacity" data-price="{{ $item->product->price }}">${{ number_format($item->product->price * $item->quantity, 2) }}</div>
                            </div>
                        </div>
                     </div>
                </div>
                @endforeach
            </div>
            
            <!-- ========================================== -->
            <!-- PHASE 4: ORDER SUMMARY (Right Column)      -->
            <!-- ========================================== -->
            <!-- PHASE 9: Softly sticky scroll experience -->
            <div id="order-summary" class="w-full lg:w-[40%] bg-[#F9F9F9] p-8 md:p-12 sticky top-[100px] flex flex-col relative overflow-hidden transition-transform duration-700 ease-[cubic-bezier(0.23,1,0.32,1)] origin-top">
                <!-- Border draws itself -->
                <div class="absolute inset-0 border border-black/10 scale-y-0 origin-top summary-border pointer-events-none"></div>
                
                <h2 class="font-mono text-[10px] uppercase tracking-widest text-black/40 border-b border-black/10 pb-6 mb-8 summary-item opacity-0">01 / ORDER SUMMARY</h2>
                
                <div class="flex flex-col gap-4 font-mono text-xs uppercase tracking-widest text-black/60 mb-12">
                    <div class="flex justify-between summary-item opacity-0">
                        <span>SUBTOTAL (EXCL. TAX)</span>
                        <span class="text-black font-bold flex items-center">$<span x-ref="subtotalVal" class="summary-number">{{ number_format($subtotal, 2) }}</span></span>
                    </div>
                    
                    <div class="flex justify-between text-black summary-item opacity-0" x-show="totalDiscount > 0">
                        <span>{{ auth()->user()?->is_vip ? 'VIP REDUCTION' : 'REDUCTION' }}</span>
                        <span class="font-bold flex items-center">-$<span x-ref="discountVal" class="summary-number">{{ number_format($totalDiscount, 2) }}</span></span>
                    </div>
                    
                    <div class="flex justify-between summary-item opacity-0">
                        <span>SHIPPING PROTOCOL</span>
                        <span class="text-black font-bold flex items-center">$<span x-ref="shippingVal" class="summary-number">{{ number_format($shipping, 2) }}</span></span>
                    </div>
                </div>
                
                <div class="flex justify-between items-end border-t border-black/10 pt-8 mb-12 summary-item opacity-0">
                    <span class="font-mono text-[10px] uppercase tracking-widest text-black/40">ESTIMATED TOTAL</span>
                    <span class="font-h1 text-3xl tracking-widest text-black flex items-center">$<span x-ref="totalVal" class="summary-number text-4xl">{{ number_format($total, 2) }}</span></span>
                </div>
                
                <!-- PHASE 8: CTA Activation -->
                <div class="summary-item opacity-0">
                    <a href="{{ route('checkout.index') }}" class="cta-button group relative w-full border border-black bg-white text-black py-6 overflow-hidden flex items-center justify-between px-8 hover:bg-black hover:text-white transition-all duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] active:scale-[0.99]">
                        <span class="font-h1 text-xl tracking-widest uppercase relative z-10">PROCEED TO SECURE</span>
                        <span class="material-symbols-outlined text-[18px] transform transition-transform duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:translate-x-2 relative z-10">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
        
        <div x-show="itemsCount === 0" x-cloak class="w-full py-32 text-center flex flex-col items-center justify-center opacity-0 animate-fade-in">
            <span class="font-h1 text-4xl uppercase tracking-tighter text-black/30 mb-4">COLLECTION EMPTY</span>
            <p class="font-mono text-[10px] uppercase tracking-widest text-black/50 mb-8">Your private gallery awaits selection.</p>
            <a href="{{ route('products.index') }}" class="font-mono text-xs uppercase tracking-widest border-b border-black pb-1 hover:text-black/50 hover:border-black/50 transition-colors">RETURN TO ARCHIVE</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cartState', (initialState) => ({
        subtotal: initialState.subtotal,
        totalDiscount: initialState.totalDiscount,
        shipping: initialState.shipping,
        total: initialState.total,
        itemsCount: initialState.itemsCount,
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

        init() {
            // Wait a tick for DOM
            setTimeout(() => {
                this.initGSAP();
            }, 100);
        },

        initGSAP() {
            // PHASE 1: Page Initialization
            const tl = gsap.timeline();
            
            // Heading slides up
            tl.to('#cart-heading', {
                opacity: 1,
                y: 0,
                duration: 0.9,
                ease: 'expo.out'
            })
            // Italic SVG stroke reveal
            .fromTo('#cart-italic .text-path', {
                strokeDasharray: 800,
                strokeDashoffset: 800
            }, {
                strokeDashoffset: 0,
                duration: 1.5,
                ease: 'power2.out'
            }, "-=0.6")
            // Divider expands
            .to('#cart-divider', {
                scaleX: 1,
                duration: 0.9,
                ease: 'expo.out'
            }, "-=1.0");

            // PHASE 2: Collection Reveal
            if (this.itemsCount > 0) {
                const rows = document.querySelectorAll('.cart-item-row');
                tl.to(rows, {
                    opacity: 1,
                    y: 0,
                    duration: 0.65,
                    stagger: 0.12,
                    ease: 'power3.out'
                }, "-=0.5");

                // PHASE 4: Order Summary
                tl.to('.summary-border', {
                    scaleY: 1,
                    duration: 0.8,
                    ease: 'expo.out'
                }, "-=0.4")
                .to('.summary-item', {
                    opacity: 1,
                    y: 0,
                    duration: 0.7,
                    stagger: 0.08,
                    ease: 'power2.out'
                }, "-=0.6");
            }

            // PHASE 3: Product Presence (8s Infinite Linear Breath)
            const images = document.querySelectorAll('.cart-product-image');
            if (images.length > 0) {
                gsap.to(images, {
                    y: 3,
                    duration: 4,
                    ease: 'none',
                    yoyo: true,
                    repeat: -1
                });
            }

            // PHASE 6 Hover Events Setup
            document.querySelectorAll('.cart-remove-btn').forEach(btn => {
                btn.addEventListener('mouseenter', () => {
                    gsap.to(btn.querySelector('.remove-icon'), { rotation: 15, opacity: 0, x: -10, duration: 0.3 });
                    gsap.to(btn.querySelector('.remove-text'), { opacity: 1, x: 0, duration: 0.3 });
                });
                btn.addEventListener('mouseleave', () => {
                    gsap.to(btn.querySelector('.remove-icon'), { rotation: 0, opacity: 1, x: 0, duration: 0.3 });
                    gsap.to(btn.querySelector('.remove-text'), { opacity: 0, x: 10, duration: 0.3 });
                });
            });
        },

        async updateQty(id, newQty, event) {
            if (newQty < 1) return;
            
            const btn = event.currentTarget;
            const container = btn.closest('.qty-container');
            const row = btn.closest('.cart-item-row');
            const qtySpan = container.querySelector('.qty-value');
            const lineTotalEl = row.querySelector('.line-total');
            const unitPrice = parseFloat(lineTotalEl.getAttribute('data-price'));
            const oldQty = parseInt(qtySpan.getAttribute('data-current'));

            if (newQty === oldQty) return;

            // Phase 5: Button compression visual feedback
            gsap.to(btn, { scale: 0.98, duration: 0.1, yoyo: true, repeat: 1 });

            // Visual Rolling Quantity
            gsap.to(qtySpan, {
                y: newQty > oldQty ? -20 : 20,
                opacity: 0,
                duration: 0.15,
                onComplete: () => {
                    qtySpan.innerText = newQty;
                    qtySpan.setAttribute('data-current', newQty);
                    gsap.fromTo(qtySpan, {
                        y: newQty > oldQty ? 20 : -20,
                        opacity: 0
                    }, {
                        y: 0,
                        opacity: 1,
                        duration: 0.2,
                        ease: 'power2.out'
                    });
                }
            });

            // Optimistic Line Total Update
            const newLineTotal = (unitPrice * newQty).toFixed(2);
            lineTotalEl.innerText = '$' + newLineTotal.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            try {
                const response = await fetch(`/cart/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ quantity: newQty })
                });

                if (response.ok) {
                    const data = await response.json();
                    this.updateTotalsUI(data);
                } else {
                    // Revert on error
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: 'Update failed.' } }));
                }
            } catch (err) {
                console.error(err);
            }
        },

        async removeItem(id, event) {
            const btn = event.currentTarget;
            const row = document.getElementById(`item-${id}`);
            
            try {
                const response = await fetch(`/cart/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    // PHASE 6: Smooth Remove Interaction (500ms)
                    gsap.to(row, {
                        opacity: 0,
                        duration: 0.2,
                        ease: 'power1.out',
                        onComplete: () => {
                            gsap.to(row, {
                                height: 0,
                                paddingTop: 0,
                                paddingBottom: 0,
                                marginTop: 0,
                                marginBottom: 0,
                                duration: 0.3,
                                ease: 'power2.out',
                                onComplete: () => {
                                    row.remove();
                                    this.itemsCount--;
                                    if(data && !data.isEmpty) {
                                        this.updateTotalsUI(data);
                                    }
                                }
                            });
                        }
                    });

                }
            } catch (err) {
                console.error(err);
            }
        },
        
        // PHASE 7: Rolling Numbers for Summary Update
        updateTotalsUI(data) {
            setTimeout(() => {
                this.animateNumber('subtotalVal', this.subtotal, data.subtotal);
                this.subtotal = data.subtotal;
                
                if (data.totalDiscount !== undefined) {
                    this.animateNumber('discountVal', this.totalDiscount, data.totalDiscount);
                    this.totalDiscount = data.totalDiscount;
                }
                
                this.animateNumber('shippingVal', this.shipping, data.shipping);
                this.shipping = data.shipping;
                
                // Estimated total updates last
                setTimeout(() => {
                    this.animateNumber('totalVal', this.total, data.total);
                    this.total = data.total;
                }, 200);
                
            }, 150);
        },

        animateNumber(refName, oldVal, newVal) {
            if (!this.$refs[refName]) return;
            if (oldVal === newVal) return;
            
            const obj = { val: oldVal };
            gsap.to(obj, {
                val: newVal,
                duration: 0.8,
                ease: 'power2.out',
                onUpdate: () => {
                    this.$refs[refName].innerText = obj.val.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
            });
        }
    }));
});
</script>

<style>
.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
    animation-delay: 0.2s;
}
@keyframes fadeIn {
    to { opacity: 1; }
}
/* Ensure clean numeric inputs */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
</style>
@endsection
