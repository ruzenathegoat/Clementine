@extends('layouts.app')

@section('title', 'Order Status - Clementine')

@section('content')
<div class="px-lg py-xl max-w-4xl mx-auto w-full min-h-[80vh] flex flex-col items-center justify-center">
    
    @if($order->payment_status === 'paid')
        <!-- Success State - Premium Editorial -->
        <div class="w-full max-w-5xl mx-auto flex flex-col border border-primary bg-background" 
             x-data="{ show: false }" 
             x-init="setTimeout(() => show = true, 100)">
            
            <div class="p-8 md:p-16 flex flex-col items-start border-b border-primary"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 class="transition-all duration-1000 ease-out">
                
                <div class="flex items-center gap-6 mb-12">
                    <div class="w-16 h-16 flex items-center justify-center border border-primary bg-surface">
                        <span class="material-symbols-outlined text-[32px] text-primary">check</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-body-md text-xs font-bold uppercase tracking-widest text-primary/60">Status</span>
                        <span class="font-h2 text-xl md:text-2xl uppercase tracking-widest">Transaction Settled</span>
                    </div>
                </div>
                
                <h1 class="font-h1 text-6xl md:text-8xl uppercase tracking-tighter mb-8 leading-none">ACQUISITION<br>CONFIRMED.</h1>
                
                <p class="font-body-md text-sm text-primary/70 max-w-2xl leading-relaxed">
                    Your allocation has been secured. The official folio and provenance documents have been dispatched to 
                    <span class="text-primary font-bold">{{ $order->contact_email }}</span>.
                </p>
            </div>

            <!-- Bento Data Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 style="transition: all 1000ms cubic-bezier(0.16, 1, 0.3, 1) 150ms;">
                
                <div class="p-8 md:p-12 border-b md:border-b-0 md:border-r border-primary flex flex-col justify-between gap-6">
                    <span class="font-body-md text-xs font-bold uppercase tracking-widest text-primary/60">Reference</span>
                    <span class="font-mono text-sm tracking-wider uppercase bg-surface border border-primary px-3 py-2 w-max text-primary">
                        #{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}
                    </span>
                </div>
                
                <div class="p-8 md:p-12 border-b md:border-b-0 md:border-r border-primary flex flex-col justify-between gap-6">
                    <span class="font-body-md text-xs font-bold uppercase tracking-widest text-primary/60">Settled Amount</span>
                    <span class="font-h2 text-3xl md:text-4xl text-primary">${{ number_format($order->total, 2) }}</span>
                </div>
                
                <a href="{{ route('profile.index') }}" class="p-8 md:p-12 flex flex-col justify-between gap-6 bg-surface hover:bg-primary hover:text-secondary transition-colors group">
                    <span class="font-body-md text-xs font-bold uppercase tracking-widest group-hover:text-secondary/70 text-primary/60 transition-colors">Next Steps</span>
                    <div class="font-h2 text-2xl md:text-3xl flex items-center justify-between">
                        Access Collection
                        <span class="material-symbols-outlined text-[32px] group-hover:translate-x-2 transition-transform">arrow_forward</span>
                    </div>
                </a>
            </div>
        </div>
    @else
        <!-- Pending State -->
        <div class="w-full flex flex-col md:flex-row border border-primary bg-background">
            <!-- Left Side: Instructions -->
            <div class="w-full md:w-3/5 p-xl md:p-[60px] flex flex-col justify-center border-b md:border-b-0 md:border-r border-primary">
                <div class="flex items-center gap-3 mb-8">
                    <span class="w-2 h-2 bg-copper rounded-full animate-pulse"></span>
                    <span class="font-label-caps text-xs uppercase tracking-widest font-bold text-copper">Waiting for Payment</span>
                </div>
                <h1 class="font-h1 text-4xl mb-2 uppercase tracking-tight">Complete your order</h1>
                <p class="font-body-md text-sm text-secondary mb-12">Please transfer the exact amount to the virtual account number below.</p>
                
                @if($order->payment_method === 'virtual_account' && $order->payment_details)
                    <div class="flex flex-col gap-8 mb-12">
                        <div>
                            <p class="font-label-caps text-[10px] uppercase tracking-widest text-on-surface-variant mb-2">Bank</p>
                            <p class="font-headline-md text-xl font-bold">{{ $order->payment_details['bank'] ?? 'Bank' }} Virtual Account</p>
                        </div>
                        <div>
                            <p class="font-label-caps text-[10px] uppercase tracking-widest text-on-surface-variant mb-2">Virtual Account Number</p>
                            <div class="flex items-center gap-4">
                                <p class="font-headline-md text-3xl font-bold tracking-wider">{{ $order->payment_details['va_number'] ?? '0000000000' }}</p>
                                <button type="button" class="text-primary hover:text-copper transition-colors" onclick="navigator.clipboard.writeText('{{ $order->payment_details['va_number'] ?? '' }}'); toasts.push({type: 'success', message: 'VA Number copied to clipboard'}); setTimeout(() => toasts.shift(), 3000);" title="Copy">
                                    <span class="material-symbols-outlined text-[20px]">content_copy</span>
                                </button>
                            </div>
                        </div>
                        <div>
                            <p class="font-label-caps text-[10px] uppercase tracking-widest text-on-surface-variant mb-2">Total Amount</p>
                            <p class="font-headline-md text-3xl font-bold tracking-wider">${{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>

                    <!-- Developer Simulation Box -->
                    <div class="mt-auto border border-primary p-6 bg-surface-container-lowest">
                        <div class="flex items-start gap-3 mb-4">
                            <span class="material-symbols-outlined text-copper">developer_mode</span>
                            <div>
                                <h3 class="font-label-caps text-xs font-bold uppercase tracking-widest text-primary">Developer Sandbox</h3>
                                <p class="text-[11px] font-body-md text-on-surface-variant mt-1">Because this is a local environment, you can bypass the actual bank transfer.</p>
                            </div>
                        </div>
                        <form action="{{ route('orders.simulate_payment', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full border border-primary bg-background text-primary font-h2 text-lg py-3 px-6 hover:bg-primary hover:text-on-primary transition-colors uppercase">
                                Simulate VA Payment
                            </button>
                        </form>
                    </div>
                @elseif($order->payment_method === 'qris')
                    <div class="flex flex-col gap-8 mb-12">
                        <div class="border border-outline-variant p-6 bg-surface-container-lowest">
                            <p class="font-body-md text-sm text-center">To complete your payment, please open the QRIS Gateway.</p>
                        </div>
                    </div>
                    
                    <!-- Developer Simulation Box -->
                    <div class="mt-auto border border-primary p-6 bg-surface-container-lowest">
                        <div class="flex items-start gap-3 mb-4">
                            <span class="material-symbols-outlined text-copper">developer_mode</span>
                            <div>
                                <h3 class="font-label-caps text-xs font-bold uppercase tracking-widest text-primary">Developer Sandbox</h3>
                                <p class="text-[11px] font-body-md text-on-surface-variant mt-1">Because this is a local environment, you can bypass the actual bank transfer.</p>
                            </div>
                        </div>
                        <a href="{{ route('dummy.qris', ['type' => 'order', 'reference_id' => $order->id, 'amount' => $order->total]) }}" class="block text-center w-full border border-primary bg-background text-primary font-h2 text-lg py-3 px-6 hover:bg-primary hover:text-on-primary transition-colors uppercase">
                            Open QRIS Gateway
                        </a>
                    </div>
                @else
                    <div class="border border-outline-variant p-6 bg-surface-container-lowest mb-8">
                        <p class="font-body-md text-sm text-center">Payment instructions are not available.</p>
                    </div>
                @endif
            </div>

            <!-- Right Side: Order Summary -->
            <div class="w-full md:w-2/5 p-xl md:p-[40px] flex flex-col bg-surface-container-lowest">
                <h2 class="font-label-caps text-sm uppercase tracking-widest font-bold mb-8">Order #{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}</h2>
                
                <div class="flex flex-col gap-6 flex-grow max-h-[40vh] overflow-y-auto pr-2 custom-scrollbar border-b border-outline-variant pb-8 mb-8">
                    @foreach($order->items as $item)
                        <div class="flex gap-4">
                            <div class="relative w-16 h-16 bg-surface border border-outline-variant flex-shrink-0 flex items-center justify-center p-2">
                                @if($item->product->primaryImage)
                                <img alt="{{ $item->product->name }}" class="w-full h-full object-cover mix-blend-multiply" src="{{ $item->product->primaryImage->url }}">
                                @endif
                                <span class="absolute -top-2 -right-2 bg-surface-tint text-white text-[10px] font-medium w-4 h-4 flex items-center justify-center rounded-full">{{ $item->quantity }}</span>
                            </div>
                            <div class="flex flex-col justify-center flex-1">
                                <span class="text-xs font-bold uppercase font-headline-md tracking-wide">{{ $item->product->name }}</span>
                                <span class="text-[10px] text-on-surface-variant font-body-md mt-1">{{ $item->product->collection->name ?? '' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-xs font-medium font-body-md">${{ number_format($item->price_at_purchase * $item->quantity, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-col gap-3 text-sm font-body-md mt-auto">
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant">Subtotal (Excl. Tax)</span>
                        <span class="font-medium">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between items-center text-[#D97757]">
                        <div class="flex items-center gap-1">
                            <span>{{ $order->user && $order->user->is_vip ? 'VIP Discount' : 'Discount' }}</span>
                            <span class="text-[10px]">*{{ $order->subtotal > 0 ? round(($order->discount_amount / $order->subtotal) * 100) : 0 }}%</span>
                        </div>
                        <span class="font-medium">-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($order->tax > 0)
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-1">
                            <span class="text-on-surface-variant">Product Tax</span>
                            <span class="text-[10px] text-primary">*{{ $order->subtotal > 0 ? round(($order->tax / $order->subtotal) * 100) : 0 }}%</span>
                        </div>
                        <span class="font-medium">${{ number_format($order->tax, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant">Shipping Fee</span>
                        <span class="font-medium text-surface-tint">${{ number_format($order->shipping_fee, 2) }}</span>
                    </div>
                    @if($order->shipping_tax > 0)
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-1">
                            <span class="text-on-surface-variant">Shipping Tax</span>
                            <span class="text-[10px] text-primary">*{{ $order->shipping_fee > 0 ? round(($order->shipping_tax / $order->shipping_fee) * 100) : 0 }}%</span>
                        </div>
                        <span class="font-medium">${{ number_format($order->shipping_tax, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-end border-t border-outline-variant pt-4 mt-2">
                        <span class="text-sm font-bold uppercase tracking-widest font-label-caps">Total (Incl. Tax)</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-[10px] text-on-surface-variant font-body-md">USD</span>
                            <span class="text-xl font-medium font-headline-md">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>

                    @if(in_array($order->status, ['pending', 'processing']) && now()->diffInMinutes($order->created_at) <= 15)
                    <div class="mt-8 border-t border-outline-variant pt-8">
                        <div class="bg-red-50 p-6 border border-red-200">
                            <h3 class="font-headline-md text-red-600 mb-2 uppercase tracking-wide">Cancel Order</h3>
                            <p class="text-sm text-red-600/80 mb-6 font-body-md">You can cancel your order within 15 minutes of placement. If paid, your Clementpay balance will be refunded.</p>
                            
                            <a href="{{ route('orders.cancel_form', $order->id) }}" class="inline-block px-8 py-3 bg-red-600 text-white font-bold uppercase tracking-widest text-xs hover:bg-red-700 transition-colors">
                                Cancel My Order
                            </a>
                        </div>
                    </div>
                    @elseif($order->status === 'cancelled')
                        <div class="mt-8 border-t border-outline-variant pt-6">
                            <div class="w-full border border-outline-variant text-on-surface-variant font-label-caps text-xs font-bold uppercase tracking-widest py-3 text-center bg-surface-variant">
                                Order Cancelled
                            </div>
                        </div>
                    @elseif($order->status === 'pending_cancel')
                        <div class="mt-8 border-t border-outline-variant pt-6">
                            <div class="w-full border border-outline-variant text-on-surface-variant font-label-caps text-xs font-bold uppercase tracking-widest py-3 text-center bg-orange-50 text-orange-700">
                                Cancellation Requested (Pending Admin Approval)
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
