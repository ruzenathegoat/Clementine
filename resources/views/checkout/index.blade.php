@extends('layouts.app')

@section('title', 'Checkout - Clementine')

@section('content')
<div class="w-full max-w-[1280px] mx-auto px-6 py-12 lg:py-16 flex flex-col lg:flex-row gap-16 lg:gap-24 relative" 
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
    
    <!-- Left Column: Forms -->
    <div class="w-full lg:w-[55%] flex flex-col gap-12">
        <div class="flex flex-col gap-8">
            <a href="{{ route('cart.index') }}" aria-label="Go back" class="flex items-center gap-2 text-primary hover:opacity-60 transition-opacity w-max">
                <span class="material-symbols-outlined" style="font-size: 20px;">arrow_back</span>
                <span class="text-sm font-bold tracking-wide uppercase font-label-caps">Back to Cart</span>
            </a>
            
            <div>
                <h1 class="font-h1 text-4xl md:text-5xl mb-6 uppercase">Checkout</h1>
                <div class="flex items-center gap-4 text-xs font-bold uppercase tracking-wider text-primary border-y border-primary py-4 px-2 font-label-caps">
                    <span class="border-b border-primary pb-0.5">Shipping</span>
                    <span>/</span>
                    <span>Payment</span>
                    <span>/</span>
                    <span>Review</span>
                </div>
            </div>
        </div>
        
        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="flex flex-col gap-12">
            @csrf

            <!-- Contact -->
            <section class="flex flex-col gap-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-primary font-label-caps">Contact</h2>
                <div class="flex flex-col gap-2">
                    <label class="sr-only" for="contact_email">Email address</label>
                    <input class="w-full p-4 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white transition-colors font-body-md" id="contact_email" name="contact_email" placeholder="Email address" type="email" required>
                </div>
            </section>

            <!-- Delivery -->
            <section class="flex flex-col gap-4 border-t border-outline-variant pt-12">
                <h2 class="text-sm font-bold uppercase tracking-wider text-primary font-label-caps">Delivery</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="sr-only" for="shipping_country">Country/Region</label>
                        <select class="w-full p-4 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white transition-colors font-body-md" id="shipping_country" name="shipping_country" x-model="country" required>
                            <option value="US">United States</option>
                            <option value="ID">Indonesia</option>
                            <option value="GB">United Kingdom</option>
                            <option value="SG">Singapore</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="sr-only" for="shipping_full_name">Full name</label>
                        <input class="w-full p-4 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white transition-colors font-body-md" id="shipping_full_name" name="shipping_full_name" placeholder="Full name" type="text" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="sr-only" for="shipping_address1">Address Line 1</label>
                        <input class="w-full p-4 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white transition-colors font-body-md" id="shipping_address1" name="shipping_address1" placeholder="Address Line 1" type="text" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="sr-only" for="shipping_address2">Address Line 2 (Optional)</label>
                        <input class="w-full p-4 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white transition-colors font-body-md" id="shipping_address2" name="shipping_address2" placeholder="Apartment, suite, etc. (optional)" type="text">
                    </div>
                    <div>
                        <label class="sr-only" for="shipping_city">City</label>
                        <input class="w-full p-4 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white transition-colors font-body-md" id="shipping_city" name="shipping_city" placeholder="City" type="text" required>
                    </div>
                    <div>
                        <label class="sr-only" for="shipping_postal_code">Postal code</label>
                        <input class="w-full p-4 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white transition-colors font-body-md" id="shipping_postal_code" name="shipping_postal_code" placeholder="Postal code" type="text" required>
                    </div>
                </div>
            </section>

            <!-- Payment -->
            <section class="flex flex-col gap-4 border-t border-outline-variant pt-12">
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-primary font-label-caps">Payment</h2>
                    <p class="text-xs text-on-surface-variant mt-1 font-body-md">All transactions are secure and encrypted.</p>
                </div>
                <div class="border border-outline-variant rounded-none bg-white divide-y divide-outline-variant font-body-md">
                    <!-- Credit Card Option -->
                    <div class="p-4 flex flex-col gap-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input class="w-4 h-4 text-primary border-outline-variant focus:ring-primary focus:ring-offset-0" type="radio" name="payment_method" value="card" x-model="paymentMethod" required>
                            <span class="text-sm font-medium">Credit Card</span>
                        </label>
                        <div class="flex flex-col gap-4 pl-7" x-show="paymentMethod === 'card'" x-collapse>
                            <div>
                                <label class="sr-only" for="card_number">Card number</label>
                                <input class="w-full p-3 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white" id="card_number" placeholder="Card number" type="text">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <input class="w-full p-3 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white" placeholder="MM / YY" type="text">
                                <input class="w-full p-3 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white" placeholder="Security code" type="text">
                            </div>
                            <input class="w-full p-3 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white" placeholder="Name on card" type="text">
                        </div>
                    </div>
                    <!-- Virtual Account Option -->
                    <div class="p-4 flex flex-col gap-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input class="w-4 h-4 text-primary border-outline-variant focus:ring-primary focus:ring-offset-0" type="radio" name="payment_method" value="virtual_account" x-model="paymentMethod">
                            <span class="text-sm font-medium">Virtual Account</span>
                        </label>
                        <div class="flex flex-col gap-4 pl-7" x-show="paymentMethod === 'virtual_account'" x-collapse>
                            <div>
                                <label class="sr-only" for="bank">Select Bank</label>
                                <select class="w-full p-3 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white transition-colors" id="bank" name="bank">
                                    <option value="" disabled selected>Select Bank</option>
                                    <option value="BCA">BCA Virtual Account</option>
                                    <option value="Mandiri">Mandiri Virtual Account</option>
                                    <option value="BNI">BNI Virtual Account</option>
                                    <option value="BRI">BRI Virtual Account</option>
                                    <option value="Permata">Permata Virtual Account</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Clementpay Option -->
                    <div class="p-4 flex flex-col gap-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input class="w-4 h-4 text-primary border-outline-variant focus:ring-primary focus:ring-offset-0" type="radio" name="payment_method" value="clementpay" x-model="paymentMethod" :disabled="grandTotal > clementpayBalance">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium" :class="grandTotal > clementpayBalance ? 'text-gray-400' : ''">Clementpay</span>
                                <span class="text-xs text-on-surface-variant">Available Balance: $<span x-text="clementpayBalance.toFixed(2)"></span></span>
                            </div>
                        </label>
                        <div class="flex flex-col gap-2 pl-7 text-sm" x-show="grandTotal > clementpayBalance" x-collapse>
                            <span class="text-red-500 font-medium text-xs uppercase tracking-wider font-label-caps">Insufficient balance</span>
                            <a href="{{ route('clementpay.index') }}" target="_blank" class="text-primary underline text-xs font-bold uppercase tracking-wider font-label-caps">Top Up Now</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Billing -->
            <section class="flex flex-col gap-4 border-t border-outline-variant pt-12">
                <h2 class="text-sm font-bold uppercase tracking-wider text-primary font-label-caps">Billing Address</h2>
                <div class="border border-outline-variant rounded-none bg-white divide-y divide-outline-variant font-body-md">
                    <div class="p-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="billing_same_as_shipping" value="0">
                            <input class="w-4 h-4 text-primary border-outline-variant focus:ring-primary focus:ring-offset-0" type="checkbox" name="billing_same_as_shipping" value="1" x-model="billingSame" checked>
                            <span class="text-sm font-medium">Same as shipping address</span>
                        </label>
                    </div>
                </div>
            </section>

            <button type="submit" class="w-full py-4 bg-primary text-white text-sm font-bold uppercase tracking-widest hover:opacity-80 transition-opacity rounded-none border border-primary mt-8">
                Pay now
            </button>
        </form>
    </div>

    <!-- Right Column: Order Summary -->
    <div class="w-full lg:w-[45%] bg-surface-container-lowest lg:bg-transparent lg:border-l lg:border-outline-variant lg:pl-16 flex flex-col gap-8 relative">
        <div class="lg:sticky lg:top-24">
            <h2 class="sr-only">Order summary</h2>
            <div class="flex flex-col gap-6">
                <!-- Cart Items -->
                <div class="flex flex-col gap-6 max-h-[50vh] overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($cartItems as $item)
                    <div class="flex gap-4">
                        <div class="relative w-20 h-20 bg-surface border border-outline-variant flex-shrink-0 flex items-center justify-center p-2">
                            @if($item->product->primaryImage)
                            <img alt="{{ $item->product->name }}" class="w-full h-full object-cover mix-blend-multiply" src="{{ $item->product->primaryImage->url }}">
                            @endif
                            <span class="absolute -top-2 -right-2 bg-surface-tint text-white text-[11px] font-medium w-5 h-5 flex items-center justify-center rounded-full">{{ $item->quantity }}</span>
                        </div>
                        <div class="flex flex-col justify-center flex-1">
                            <span class="text-sm font-medium uppercase font-headline-md tracking-wide">{{ $item->product->name }}</span>
                            <span class="text-xs text-on-surface-variant font-body-md mt-1">{{ $item->product->collection->name ?? '' }}</span>
                            
                            @if($item->product->status === 'new' && $item->product->scheduled_publish_at)
                                @php
                                    $t40 = (clone $item->product->scheduled_publish_at)->addMinutes(40);
                                @endphp
                                @if(now()->lt($t40))
                                    <div class="mt-2 text-[10px] text-primary font-bold uppercase font-label-caps tracking-wider border border-primary w-max px-2 py-1 bg-surface-variant">
                                        DROP STOCK: {{ $item->product->stock }} LEFT<br>
                                        MAX PURCHASE: {{ auth()->user()?->is_vip ? '3' : '1' }} ITEM
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm font-medium font-body-md">${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Discount -->
                <div class="flex gap-2 border-y border-outline-variant py-6">
                    <input class="w-full p-3 text-sm focus:ring-0 border border-outline-variant focus:border-primary rounded-none bg-white uppercase font-body-md" placeholder="Discount code" type="text">
                    <button class="px-6 bg-surface-variant text-on-surface text-sm font-bold uppercase tracking-wider border border-outline-variant hover:border-primary transition-colors font-label-caps">Apply</button>
                </div>

                <!-- Totals -->
                <div class="flex flex-col gap-3 text-sm font-body-md">
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant">Subtotal (Excl. Tax)</span>
                        <span class="font-medium" x-text="'$' + subtotal.toFixed(2)"></span>
                    </div>
                    @if($totalDiscount > 0)
                    <div class="flex justify-between items-center text-[#D97757]">
                        <div class="flex items-center gap-1">
                            <span>{{ auth()->user()?->is_vip ? 'VIP Discount' : 'Discount' }}</span>
                            <span class="text-[10px]">*{{ $subtotal > 0 ? round(($totalDiscount / $subtotal) * 100) : 0 }}%</span>
                        </div>
                        <span class="font-medium">-${{ number_format($totalDiscount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-1">
                            <span class="text-on-surface-variant">Product Tax</span>
                            <span class="text-[10px] text-primary" x-show="subtotal > 0" x-text="'*' + Math.round((totalTax / subtotal) * 100) + '%'"></span>
                        </div>
                        <span class="font-medium" x-text="'$' + totalTax.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant">Shipping Fee</span>
                        <span class="font-medium text-surface-tint" x-text="'$' + shippingFee.toFixed(2)"></span>
                    </div>
                    <div x-show="shippingTax > 0" x-cloak class="flex justify-between items-center">
                        <div class="flex items-center gap-1">
                            <span class="text-on-surface-variant">Shipping Tax</span>
                            <span class="text-[10px] text-primary" x-text="'*' + Math.round(defaultShippingTaxPct * 100) + '%'"></span>
                        </div>
                        <span class="font-medium" x-text="'$' + shippingTax.toFixed(2)"></span>
                    </div>
                </div>

                <div class="flex justify-between items-end border-t border-outline-variant pt-6">
                    <span class="text-base font-medium uppercase tracking-widest font-label-caps">Total (Incl. Tax)</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-xs text-on-surface-variant font-body-md">USD</span>
                        <span class="text-2xl font-medium font-headline-md" x-text="'$' + grandTotal.toFixed(2)"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
