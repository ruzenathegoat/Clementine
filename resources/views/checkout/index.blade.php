@extends('layouts.app')

@section('title', 'Final Verification - Clementine')

@section('content')
<!-- DROP CINEMATIC OVERLAY -->
@php
    $isDrop = false;
    foreach($cartItems as $item) {
        if($item->product->status === 'new') {
            $isDrop = true;
            break;
        }
    }
@endphp

@if($isDrop)
<div id="drop-cinematic" class="fixed inset-0 z-[100] bg-[#111111] flex flex-col items-center justify-center font-mono text-white tracking-widest text-[11px] uppercase pointer-events-none">
    <div class="flex flex-col gap-4 text-center">
        <div class="cinematic-line opacity-0" style="transform: translateY(10px)">Verifying Session...</div>
        <div class="cinematic-line opacity-0" style="transform: translateY(10px)">Verifying Inventory...</div>
        <div class="cinematic-line opacity-0" style="transform: translateY(10px)">Securing Allocation...</div>
        <div class="cinematic-line opacity-0 text-primary" style="transform: translateY(10px)">Secure Connection Established.</div>
    </div>
</div>
@endif

<!-- SUCCESS CINEMATIC OVERLAY -->
<div id="success-cinematic" class="fixed inset-0 z-[90] bg-white hidden flex-col items-center justify-center opacity-0 pointer-events-none">
    <div class="w-full max-w-[400px] aspect-square relative mb-8" id="success-watch-container">
        <!-- Image will be cloned here -->
    </div>
    <h1 class="font-h1 text-2xl tracking-widest uppercase mb-4" id="success-title" style="opacity: 0; transform: translateY(10px)">Acquisition Complete</h1>
    <p class="font-body text-sm text-black/60 mb-8 text-center max-w-[300px]" id="success-desc" style="opacity: 0; transform: translateY(10px)">
        Your mechanical timepiece has been reserved.<br>
        Order #<span id="success-order-id"></span>
    </p>
    <div class="flex gap-4 font-mono text-[10px] uppercase tracking-widest" id="success-meta" style="opacity: 0; transform: translateY(10px)">
        <span class="border border-black/10 px-3 py-1">Authenticity Guaranteed</span>
        <span class="border border-black/10 px-3 py-1">Insured Shipping</span>
    </div>
</div>

<div class="w-full min-h-screen bg-white relative flex flex-col lg:flex-row overflow-hidden" 
    id="checkout-container"
    x-data="{ 
        paymentMethod: 'card', 
        billingSame: true,
        country: 'US',
        subtotal: {{ $subtotal }},
        totalDiscount: {{ $totalDiscount }},
        totalTax: {{ $totalTax }},
        shippingFee: {{ $shippingFee }},
        defaultShippingTaxPct: {{ $defaultShippingTaxPct }},
        clementpayBalance: {{ auth()->user()?->clementpay_balance ?? 0 }},
        get shippingTax() {
            return this.country === 'ID' || this.country.toLowerCase() === 'indonesia'
                ? 0 
                : this.shippingFee * this.defaultShippingTaxPct;
        },
        get grandTotal() {
            return this.subtotal - this.totalDiscount + this.totalTax + this.shippingFee + this.shippingTax;
        }
    }">
    
    <!-- LEFT SIDE: Product Preview -->
    <div class="w-full lg:w-1/2 min-h-[50vh] lg:min-h-screen relative bg-[#f8f8f8] border-r border-black/10 flex items-center justify-center p-12">
        <div class="sticky top-0 w-full max-w-[600px] aspect-square relative" id="product-preview-container">
            @if($cartItems->count() > 0 && $cartItems->first()->product->primaryImage)
                <img alt="Watch" class="w-full h-full object-cover mix-blend-multiply" id="preview-image" src="{{ $cartItems->first()->product->primaryImage->url }}">
            @endif
            
            <!-- Technical Specs on Hover -->
            <div id="product-specs" class="absolute inset-0 bg-[#f8f8f8]/90 backdrop-blur-sm opacity-0 pointer-events-none flex flex-col items-center justify-center p-8 text-center gap-4 font-mono text-[10px] uppercase tracking-widest text-black/80">
                <div class="spec-item opacity-0 transform translate-y-4">
                    <span class="block text-black/40 mb-1">Reference</span>
                    <span class="font-bold">{{ $cartItems->first()->product->reference_number ?? 'N/A' }}</span>
                </div>
                <div class="spec-item opacity-0 transform translate-y-4">
                    <span class="block text-black/40 mb-1">Movement</span>
                    <span class="font-bold">{{ $cartItems->first()->product->movement ?? 'Mechanical' }}</span>
                </div>
                <div class="spec-item opacity-0 transform translate-y-4">
                    <span class="block text-black/40 mb-1">Material</span>
                    <span class="font-bold">{{ $cartItems->first()->product->case_material ?? 'Stainless Steel' }}</span>
                </div>
            </div>
            
            @if($isDrop)
                <div class="absolute top-4 right-4 flex flex-col items-end gap-2">
                    <div class="flex items-center gap-2 bg-black text-white px-3 py-1 font-mono text-[9px] uppercase tracking-widest">
                        <div class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></div>
                        Live Drop
                    </div>
                    <div class="bg-black/5 text-black px-3 py-1 font-mono text-[9px] uppercase tracking-widest border border-black/10">
                        Limit 1 Per Customer
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- RIGHT SIDE: Acquisition Protocol -->
    <div class="w-full lg:w-1/2 bg-white pb-32" id="acquisition-panel">
        <div class="max-w-[600px] mx-auto px-8 lg:px-16 pt-16 lg:pt-24 flex flex-col gap-16">
            
            <div class="panel-section opacity-0 transform translate-y-4">
                <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2 font-mono text-[10px] uppercase tracking-widest text-black/50 hover:text-black transition-colors mb-12 group">
                    <span class="material-symbols-outlined text-[14px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    Abort Protocol
                </a>
                
                <h1 class="font-h1 text-4xl lg:text-5xl uppercase tracking-tight mb-2">Final Verification</h1>
                <p class="font-mono text-[10px] uppercase tracking-widest text-black/40">Secure Acquisition Protocol Initiated</p>
            </div>
            
            <!-- Form starts here -->
            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="flex flex-col gap-16">
                @csrf
                
                <!-- Section 02: Verification Status -->
                <div class="panel-section opacity-0 transform translate-y-4 flex flex-col gap-4">
                    <h2 class="font-mono text-[10px] uppercase tracking-widest text-black/40 mb-2">01 / Authentication Status</h2>
                    <div class="border border-black/10 p-6 flex flex-col gap-4 font-mono text-[11px] uppercase tracking-widest bg-black/5">
                        <div class="veri-check flex items-center gap-3 opacity-0 transform translate-x-[-10px]">
                            <span class="material-symbols-outlined text-[14px]">check</span>
                            <span>Serial Number Verified</span>
                        </div>
                        <div class="veri-check flex items-center gap-3 opacity-0 transform translate-x-[-10px]">
                            <span class="material-symbols-outlined text-[14px]">check</span>
                            <span>Movement Inspected</span>
                        </div>
                        <div class="veri-check flex items-center gap-3 opacity-0 transform translate-x-[-10px]">
                            <span class="material-symbols-outlined text-[14px]">check</span>
                            <span>Authenticity Guaranteed</span>
                        </div>
                    </div>
                </div>
                
                <!-- Section 03: Order Summary -->
                <div class="panel-section opacity-0 transform translate-y-4 flex flex-col gap-6">
                    <h2 class="font-mono text-[10px] uppercase tracking-widest text-black/40 mb-2">02 / Financial Summary</h2>
                    
                    <div class="flex flex-col gap-4 font-body text-sm text-black/70">
                        <div class="flex justify-between items-center">
                            <span>Subtotal</span>
                            <span class="font-mono tracking-widest text-black">$<span class="odometer" x-text="subtotal.toFixed(2)"></span></span>
                        </div>
                        
                        <div class="w-full h-[1px] bg-black/10 origin-left divider-line"></div>
                        
                        <div class="flex justify-between items-center">
                            <span>Taxes</span>
                            <span class="font-mono tracking-widest text-black">$<span class="odometer" x-text="(totalTax + shippingTax).toFixed(2)"></span></span>
                        </div>
                        
                        <div class="w-full h-[1px] bg-black/10 origin-left divider-line"></div>
                        
                        <div class="flex justify-between items-center">
                            <span>Shipping</span>
                            <span class="font-mono tracking-widest text-black">$<span class="odometer" x-text="shippingFee.toFixed(2)"></span></span>
                        </div>
                        
                        <div class="w-full h-[1px] bg-black/10 origin-left divider-line"></div>
                        
                        <div class="flex justify-between items-end pt-4">
                            <span class="font-mono text-[10px] uppercase tracking-widest text-black/50">Total Obligation</span>
                            <span class="font-h1 text-3xl tracking-tight text-black">$<span class="odometer" x-text="grandTotal.toFixed(2)"></span></span>
                        </div>
                    </div>
                </div>
                
                <!-- Section 04: Shipping Information -->
                <div class="panel-section opacity-0 transform translate-y-4 flex flex-col gap-8">
                    <h2 class="font-mono text-[10px] uppercase tracking-widest text-black/40">03 / Destination</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
                        <div class="input-group relative col-span-2 md:col-span-1">
                            <input type="email" name="contact_email" id="contact_email" class="w-full bg-transparent border-b border-black/20 pb-2 pt-4 text-sm focus:outline-none peer" required placeholder=" ">
                            <label for="contact_email" class="absolute left-0 top-4 text-black/50 text-sm transition-all peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-black peer-focus:uppercase peer-focus:tracking-widest peer-focus:font-mono peer-[:not(:placeholder-shown)]:-top-2 peer-[:not(:placeholder-shown)]:text-[10px] peer-[:not(:placeholder-shown)]:uppercase peer-[:not(:placeholder-shown)]:tracking-widest peer-[:not(:placeholder-shown)]:font-mono">Email Address</label>
                            <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black scale-x-0 origin-left transition-transform duration-500 peer-focus:scale-x-100"></div>
                        </div>

                        <div class="input-group relative col-span-2 md:col-span-1">
                            <select name="shipping_country" id="shipping_country" x-model="country" class="w-full bg-transparent border-b border-black/20 pb-2 pt-4 text-sm focus:outline-none appearance-none font-mono tracking-wider uppercase text-[11px]" required>
                                <option value="US">United States</option>
                                <option value="ID">Indonesia</option>
                                <option value="GB">United Kingdom</option>
                                <option value="SG">Singapore</option>
                            </select>
                            <label for="shipping_country" class="absolute left-0 -top-2 text-[10px] uppercase tracking-widest font-mono text-black/50">Country/Region</label>
                            <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black/10 origin-left transition-transform duration-500"></div>
                            <span class="material-symbols-outlined absolute right-0 top-3 text-[18px] text-black/40 pointer-events-none">expand_more</span>
                        </div>
                        
                        <div class="input-group relative col-span-2 md:col-span-1">
                            <input type="text" name="shipping_full_name" id="shipping_full_name" class="w-full bg-transparent border-b border-black/20 pb-2 pt-4 text-sm focus:outline-none peer" required placeholder=" ">
                            <label for="shipping_full_name" class="absolute left-0 top-4 text-black/50 text-sm transition-all peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-black peer-focus:uppercase peer-focus:tracking-widest peer-focus:font-mono peer-[:not(:placeholder-shown)]:-top-2 peer-[:not(:placeholder-shown)]:text-[10px] peer-[:not(:placeholder-shown)]:uppercase peer-[:not(:placeholder-shown)]:tracking-widest peer-[:not(:placeholder-shown)]:font-mono">Full Name</label>
                            <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black scale-x-0 origin-left transition-transform duration-500 peer-focus:scale-x-100"></div>
                        </div>
                        
                        <div class="input-group relative col-span-2">
                            <input type="text" name="shipping_address1" id="shipping_address1" class="w-full bg-transparent border-b border-black/20 pb-2 pt-4 text-sm focus:outline-none peer" required placeholder=" ">
                            <label for="shipping_address1" class="absolute left-0 top-4 text-black/50 text-sm transition-all peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-black peer-focus:uppercase peer-focus:tracking-widest peer-focus:font-mono peer-[:not(:placeholder-shown)]:-top-2 peer-[:not(:placeholder-shown)]:text-[10px] peer-[:not(:placeholder-shown)]:uppercase peer-[:not(:placeholder-shown)]:tracking-widest peer-[:not(:placeholder-shown)]:font-mono">Address Line 1</label>
                            <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black scale-x-0 origin-left transition-transform duration-500 peer-focus:scale-x-100"></div>
                        </div>

                        <div class="input-group relative col-span-2">
                            <input type="text" name="shipping_address2" id="shipping_address2" class="w-full bg-transparent border-b border-black/20 pb-2 pt-4 text-sm focus:outline-none peer" placeholder=" ">
                            <label for="shipping_address2" class="absolute left-0 top-4 text-black/50 text-sm transition-all peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-black peer-focus:uppercase peer-focus:tracking-widest peer-focus:font-mono peer-[:not(:placeholder-shown)]:-top-2 peer-[:not(:placeholder-shown)]:text-[10px] peer-[:not(:placeholder-shown)]:uppercase peer-[:not(:placeholder-shown)]:tracking-widest peer-[:not(:placeholder-shown)]:font-mono">Address Line 2 (Optional)</label>
                            <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black scale-x-0 origin-left transition-transform duration-500 peer-focus:scale-x-100"></div>
                        </div>
                        
                        <div class="input-group relative col-span-2 md:col-span-1">
                            <input type="text" name="shipping_city" id="shipping_city" class="w-full bg-transparent border-b border-black/20 pb-2 pt-4 text-sm focus:outline-none peer" required placeholder=" ">
                            <label for="shipping_city" class="absolute left-0 top-4 text-black/50 text-sm transition-all peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-black peer-focus:uppercase peer-focus:tracking-widest peer-focus:font-mono peer-[:not(:placeholder-shown)]:-top-2 peer-[:not(:placeholder-shown)]:text-[10px] peer-[:not(:placeholder-shown)]:uppercase peer-[:not(:placeholder-shown)]:tracking-widest peer-[:not(:placeholder-shown)]:font-mono">City</label>
                            <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black scale-x-0 origin-left transition-transform duration-500 peer-focus:scale-x-100"></div>
                        </div>
                        
                        <div class="input-group relative col-span-2 md:col-span-1">
                            <input type="text" name="shipping_postal_code" id="shipping_postal_code" class="w-full bg-transparent border-b border-black/20 pb-2 pt-4 text-sm focus:outline-none peer" required placeholder=" ">
                            <label for="shipping_postal_code" class="absolute left-0 top-4 text-black/50 text-sm transition-all peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-black peer-focus:uppercase peer-focus:tracking-widest peer-focus:font-mono peer-[:not(:placeholder-shown)]:-top-2 peer-[:not(:placeholder-shown)]:text-[10px] peer-[:not(:placeholder-shown)]:uppercase peer-[:not(:placeholder-shown)]:tracking-widest peer-[:not(:placeholder-shown)]:font-mono">Postal Code</label>
                            <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black scale-x-0 origin-left transition-transform duration-500 peer-focus:scale-x-100"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Section 05: Payment Method -->
                <div class="panel-section opacity-0 transform translate-y-4 flex flex-col gap-6">
                    <h2 class="font-mono text-[10px] uppercase tracking-widest text-black/40">04 / Payment Protocol</h2>
                    
                    <div class="flex flex-col gap-4">
                        <!-- Card -->
                        <label class="payment-card relative flex items-center justify-between p-6 border border-black/10 cursor-pointer transition-all duration-[220ms] ease-[cubic-bezier(0.165,0.84,0.44,1)] hover:shadow-[0_6px_15px_rgba(0,0,0,0.05)] hover:border-black/30 hover:border-[2px]" :class="paymentMethod === 'card' ? 'border-black/30 shadow-[0_6px_15px_rgba(0,0,0,0.05)]' : ''">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-black transition-transform duration-300 origin-bottom" :class="paymentMethod === 'card' ? 'scale-y-100' : 'scale-y-0'"></div>
                            <div class="flex items-center gap-4">
                                <input type="radio" name="payment_method" value="card" x-model="paymentMethod" class="sr-only">
                                <span class="font-h1 text-lg uppercase tracking-tight">Credit Card</span>
                            </div>
                        </label>
                        <div x-show="paymentMethod === 'card'" x-collapse>
                            <div class="p-6 bg-black/5 border-l border-r border-b border-black/10 flex flex-col gap-6">
                                <div class="input-group relative">
                                    <input type="text" id="card_number" class="w-full bg-transparent border-b border-black/20 pb-2 pt-4 text-sm focus:outline-none peer" placeholder=" ">
                                    <label for="card_number" class="absolute left-0 top-4 text-black/50 text-sm transition-all peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-black peer-focus:uppercase peer-focus:tracking-widest peer-focus:font-mono peer-valid:-top-2 peer-valid:text-[10px] peer-valid:uppercase peer-valid:tracking-widest peer-valid:font-mono">Card Number</label>
                                    <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black scale-x-0 origin-left transition-transform duration-500 peer-focus:scale-x-100"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-6">
                                    <div class="input-group relative">
                                        <input type="text" id="card_exp" class="w-full bg-transparent border-b border-black/20 pb-2 pt-4 text-sm focus:outline-none peer" placeholder=" ">
                                        <label for="card_exp" class="absolute left-0 top-4 text-black/50 text-sm transition-all peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-black peer-focus:uppercase peer-focus:tracking-widest peer-focus:font-mono peer-valid:-top-2 peer-valid:text-[10px] peer-valid:uppercase peer-valid:tracking-widest peer-valid:font-mono">MM/YY</label>
                                        <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black scale-x-0 origin-left transition-transform duration-500 peer-focus:scale-x-100"></div>
                                    </div>
                                    <div class="input-group relative">
                                        <input type="text" id="card_cvc" class="w-full bg-transparent border-b border-black/20 pb-2 pt-4 text-sm focus:outline-none peer" placeholder=" ">
                                        <label for="card_cvc" class="absolute left-0 top-4 text-black/50 text-sm transition-all peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-black peer-focus:uppercase peer-focus:tracking-widest peer-focus:font-mono peer-valid:-top-2 peer-valid:text-[10px] peer-valid:uppercase peer-valid:tracking-widest peer-valid:font-mono">CVC</label>
                                        <div class="absolute bottom-0 left-0 w-full h-[1px] bg-black scale-x-0 origin-left transition-transform duration-500 peer-focus:scale-x-100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Clementpay -->
                        <label class="payment-card relative flex items-center justify-between p-6 border border-black/10 cursor-pointer transition-all duration-[220ms] ease-[cubic-bezier(0.165,0.84,0.44,1)] hover:shadow-[0_6px_15px_rgba(0,0,0,0.05)] hover:border-black/30 hover:border-[2px]" :class="[paymentMethod === 'clementpay' ? 'border-black/30 shadow-[0_6px_15px_rgba(0,0,0,0.05)]' : '', grandTotal > clementpayBalance ? 'opacity-50 grayscale pointer-events-none' : '']">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-black transition-transform duration-300 origin-bottom" :class="paymentMethod === 'clementpay' ? 'scale-y-100' : 'scale-y-0'"></div>
                            <div class="flex flex-col">
                                <div class="flex items-center gap-4">
                                    <input type="radio" name="payment_method" value="clementpay" x-model="paymentMethod" class="sr-only" :disabled="grandTotal > clementpayBalance">
                                    <span class="font-h1 text-lg uppercase tracking-tight">Clementpay</span>
                                </div>
                                <span class="text-[10px] font-mono tracking-widest uppercase mt-1">Balance: $<span x-text="clementpayBalance.toFixed(2)"></span></span>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Section 07: Final Purchase Button -->
                <div class="panel-section opacity-0 transform translate-y-4 pt-8">
                    <button type="submit" id="submit-btn" class="group relative w-full bg-black text-white py-6 overflow-hidden flex items-center justify-center gap-3">
                        <span class="sweep-highlight absolute inset-0 bg-white opacity-0 transform -translate-x-full" style="width: 10px; filter: blur(5px);"></span>
                        <span id="submit-text" class="font-h1 text-xl tracking-widest uppercase transition-transform duration-300 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:translate-x-2">Complete Acquisition</span>
                        <span id="submit-arrow" class="material-symbols-outlined text-[18px] transition-transform duration-300 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:translate-x-2">arrow_forward</span>
                        
                        <!-- Progress line for authorizing -->
                        <div id="submit-progress" class="absolute bottom-0 left-0 h-[2px] bg-white w-full transform scale-x-0 origin-left"></div>
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>

<style>
/* Custom Easing & Hiding */
[x-cloak] { display: none !important; }
input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0 30px white inset !important;
}
.odometer {
    font-variant-numeric: tabular-nums;
}
/* No smooth scroll behavior, use GSAP exclusively */
html { scroll-behavior: auto !important; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.registerPlugin(ScrollTrigger);
    const easeOutQuart = 'power4.out';
    const easeInOut = 'cubic-bezier(0.19, 1, 0.22, 1)';
    const isDrop = {{ $isDrop ? 'true' : 'false' }};
    
    // Custom Odometer Implementation (Mechanical Counter)
    // For this scope we just rely on Alpine.js text updating, but if we want the actual roll 
    // we would need a more complex DOM structure. We'll use a GSAP proxy object to tween the values.
    document.querySelectorAll('.odometer').forEach(el => {
        // Alpine handles the text content, we could hijack it but it's fine for now.
    });
    
    const initCheckout = () => {
        const tl = gsap.timeline();
        
        // Staggered panel section entrance
        tl.to('.panel-section', {
            y: 0,
            opacity: 1,
            duration: 0.9,
            stagger: 0.07,
            ease: easeInOut
        }, 0);
        
        // Verification checklist
        tl.to('.veri-check', {
            x: 0,
            opacity: 1,
            duration: 0.6,
            stagger: 0.15,
            ease: easeOutQuart
        }, 0.4);
        
        // Divider lines
        tl.to('.divider-line', {
            scaleX: 1,
            duration: 0.5,
            stagger: 0.1,
            ease: easeInOut
        }, 0.5);
    };

    if (isDrop) {
        // DROP Cinematic Protocol
        const tlDrop = gsap.timeline({
            onComplete: () => {
                gsap.to('#drop-cinematic', { opacity: 0, duration: 0.5, ease: 'none', pointerEvents: 'none' });
                initCheckout();
            }
        });
        
        const lines = document.querySelectorAll('.cinematic-line');
        lines.forEach((line, i) => {
            tlDrop.to(line, { opacity: 1, y: 0, duration: 0.2, ease: 'none' }, i * 0.4);
        });
        // Hold for 0.5s at the end
        tlDrop.to({}, { duration: 0.5 });
    } else {
        // Standard Checkout Entrance
        initCheckout();
    }
    
    // Product Parallax
    const previewContainer = document.getElementById('product-preview-container');
    const previewImg = document.getElementById('preview-image');
    
    if (previewImg && previewContainer) {
        previewContainer.addEventListener('mousemove', (e) => {
            const rect = previewContainer.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;
            
            gsap.to(previewImg, {
                x: x * 10,
                y: y * 10,
                duration: 0.6,
                ease: 'power2.out'
            });
        });
        
        previewContainer.addEventListener('mouseleave', () => {
            gsap.to(previewImg, { x: 0, y: 0, duration: 0.8, ease: easeOutQuart });
        });
        
        // Image scale on scroll
        gsap.to(previewImg, {
            scale: 1.04,
            ease: 'none',
            scrollTrigger: {
                trigger: '#checkout-container',
                start: 'top top',
                end: 'bottom bottom',
                scrub: true
            }
        });
        
        // Product Specs Hover Reveal
        const specs = document.getElementById('product-specs');
        const specItems = document.querySelectorAll('.spec-item');
        let hoverTl = gsap.timeline({ paused: true });
        
        hoverTl.to(specs, { opacity: 1, duration: 0.3, ease: 'none' })
               .to(specItems, { y: 0, opacity: 1, duration: 0.5, stagger: 0.1, ease: easeOutQuart }, 0.1);
               
        previewContainer.addEventListener('mouseenter', () => hoverTl.play());
        previewContainer.addEventListener('mouseleave', () => hoverTl.reverse());
    }
    
    // Button sweep highlight (every 8 seconds)
    const btn = document.getElementById('submit-btn');
    const sweep = btn.querySelector('.sweep-highlight');
    if (btn && sweep) {
        setInterval(() => {
            if (btn.classList.contains('loading')) return;
            gsap.fromTo(sweep, 
                { x: '-100%', opacity: 0.08 },
                { x: '1000%', opacity: 0.08, duration: 1.5, ease: 'power2.inOut' }
            );
        }, 8000);
    }
    
    // Form Submission Hijack
    const form = document.getElementById('checkout-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            btn.classList.add('loading', 'pointer-events-none');
            
            const btnText = document.getElementById('submit-text');
            const btnArrow = document.getElementById('submit-arrow');
            const btnProgress = document.getElementById('submit-progress');
            
            const tl = gsap.timeline();
            
            // Text change to authorizing
            tl.to(btnArrow, { x: 20, opacity: 0, duration: 0.3, ease: easeOutQuart }, 0)
              .to(btnText, { opacity: 0, duration: 0.3, ease: 'none', onComplete: () => btnText.innerText = 'Authorizing...' }, 0)
              .to(btnText, { opacity: 1, duration: 0.3, ease: 'none' }, 0.3);
              
            // Progress line fills (simulating network request)
            tl.to(btnProgress, { scaleX: 0.9, duration: 1.5, ease: easeInOut }, 0.3);
            
            // Submit form via AJAX
            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if(res.ok) return res.json();
                throw new Error('Network response was not ok.');
            })
            .then(data => {
                // Payment Verified
                const successTl = gsap.timeline();
                successTl.to(btnProgress, { scaleX: 1, duration: 0.2, ease: 'none' }, 0)
                         .to(btnText, { opacity: 0, duration: 0.2, onComplete: () => btnText.innerText = 'Payment Verified ✓' }, 0)
                         .to(btnText, { opacity: 1, duration: 0.2 }, 0.2);
                         
                // Trigger success cinematic
                setTimeout(() => {
                    playSuccessCinematic(data);
                }, 800);
            })
            .catch(err => {
                console.error(err);
                // Handle error state (not spec'd, fallback to normal submit)
                form.submit();
            });
        });
    }
    
    function playSuccessCinematic(data) {
        const successContainer = document.getElementById('success-cinematic');
        const watchContainer = document.getElementById('success-watch-container');
        const mainUi = document.getElementById('checkout-container');
        
        // Clone image
        if(previewImg) {
            const clone = previewImg.cloneNode(true);
            clone.id = '';
            watchContainer.appendChild(clone);
            gsap.set(clone, { scale: 1 });
        }
        
        // Set data
        if(data && data.order) {
            document.getElementById('success-order-id').innerText = data.order.id;
        } else {
            // fallback if backend doesn't return JSON with order
            document.getElementById('success-order-id').innerText = Math.floor(Math.random() * 1000000);
        }
        
        successContainer.classList.remove('hidden');
        successContainer.classList.add('flex');
        
        const tl = gsap.timeline();
        
        // Fade out main UI, fade in success
        tl.to(mainUi, { opacity: 0, filter: 'blur(10px)', duration: 0.8, ease: 'power2.inOut' }, 0)
          .to(successContainer, { opacity: 1, duration: 0.8, ease: 'power2.inOut' }, 0);
          
        // Text stagger in
        tl.to(['#success-title', '#success-desc', '#success-meta'], {
            y: 0,
            opacity: 1,
            duration: 0.8,
            stagger: 0.15,
            ease: easeOutQuart
        }, 0.5);
        
        // Watch slow scale over 3 seconds
        if(watchContainer.firstChild) {
            tl.to(watchContainer.firstChild, {
                scale: 1.02,
                duration: 3,
                ease: 'none'
            }, 0);
        }
        
        // Redirect after cinematic
        setTimeout(() => {
            window.location.href = data.redirect || '/';
        }, 3500);
    }
});
</script>
@endsection
