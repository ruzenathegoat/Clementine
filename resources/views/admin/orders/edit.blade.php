@extends('admin.layout')

@section('title', 'Order Details')

@section('content')
<div class="space-y-10 pb-12 max-w-6xl mx-auto">
    <!-- Header Area -->
    <div class="scroll-reveal flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[#787774] hover:text-[#111111] transition-colors mb-2">
                <i class="ph-light ph-arrow-left"></i> Back to Orders
            </a>
            <h1 class="font-serif text-3xl md:text-4xl tracking-tight leading-none text-[#111111]">
                Order <span class="font-mono text-xl text-[#787774] ml-2">#{{ substr($order->id, 0, 12) }}...</span>
            </h1>
            <p class="text-sm text-[#787774]">{{ $order->created_at->format('l, F j, Y \a\t h:i A') }}</p>
        </div>
        
        <div class="flex items-center gap-4">
            <button type="button" id="open-invoice" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-white border border-[#EAEAEA] rounded-lg text-sm font-medium text-[#111111] hover:bg-[#F9F9F8] transition-colors shadow-sm">
                <i class="ph-light ph-receipt text-lg"></i>
                Invoice
            </button>
            
            @if($order->status === 'pending')
                <span class="admin-badge bg-[#FBF3DB] text-[#956400]">Pending</span>
            @elseif($order->status === 'processing')
                <span class="admin-badge bg-[#E1F3FE] text-[#1F6C9F]">Processing</span>
            @elseif($order->status === 'shipped')
                <span class="admin-badge bg-[#EDF3EC] text-[#346538]">Shipped</span>
            @elseif($order->status === 'verified')
                <span class="admin-badge bg-[#E1F3FE] text-[#1F6C9F]">Verified</span>
            @elseif($order->status === 'completed')
                <span class="admin-badge bg-[#F9F9F8] border border-[#EAEAEA] text-[#111111]">Completed</span>
            @else
                <span class="admin-badge bg-[#FDEBEC] text-[#9F2F2D]">Cancelled</span>
            @endif
        </div>
    </div>

    @if ($errors->any())
        <div class="scroll-reveal p-4 bg-[#FDEBEC] text-[#9F2F2D] text-sm rounded-xl border border-[#FDEBEC]/50">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Left: Order Details & Customer (col-span-8) -->
        <div class="w-full lg:w-2/3 space-y-8">
            
            <!-- Items -->
            <div class="scroll-reveal admin-outer-shell">
                <div class="admin-inner-core p-6 md:p-8">
                    <h2 class="text-sm font-mono uppercase tracking-widest text-[#787774] mb-6">Order Items</h2>
                    
                    <div class="space-y-6">
                        @foreach($order->items as $item)
                        <div class="flex gap-4 items-center">
                            <div class="w-16 h-16 rounded-lg bg-[#F9F9F8] border border-[#EAEAEA] overflow-hidden flex-shrink-0 flex items-center justify-center">
                                @if($item->product && $item->product->primaryImage)
                                    <img src="{{ $item->product->primaryImage->url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="ph-light ph-image text-gray-400 text-xl"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-[#111111] truncate">{{ $item->product->name ?? 'Unknown Product' }}</p>
                                <p class="text-xs text-[#787774]">{{ $item->quantity }}x @ {{ \App\Services\CurrencyService::format($item->price_at_purchase) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-[#111111]">{{ \App\Services\CurrencyService::format($item->quantity * $item->price_at_purchase) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-[#EAEAEA] space-y-3">
                        <div class="flex justify-between text-sm text-[#787774]">
                            <span>Subtotal (Excl. Tax)</span>
                            <span>{{ \App\Services\CurrencyService::format($order->subtotal) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm text-[#D97757]">
                            <div class="flex items-center gap-1">
                                <span>{{ $order->user && $order->user->is_vip ? 'VIP Discount' : 'Discount' }}</span>
                                <span class="text-[10px]">*{{ $order->subtotal > 0 ? round(($order->discount_amount / $order->subtotal) * 100) : 0 }}%</span>
                            </div>
                            <span>-{{ \App\Services\CurrencyService::format($order->discount_amount) }}</span>
                        </div>
                        @endif
                        @if($order->tax > 0)
                        <div class="flex justify-between text-sm text-[#787774]">
                            <div class="flex items-center gap-1">
                                <span>Product Tax</span>
                                <span class="text-[10px]">*{{ $order->subtotal > 0 ? round(($order->tax / $order->subtotal) * 100) : 0 }}%</span>
                            </div>
                            <span>{{ \App\Services\CurrencyService::format($order->tax) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-sm text-[#787774]">
                            <span>Shipping Fee</span>
                            <span>{{ \App\Services\CurrencyService::format($order->shipping_fee) }}</span>
                        </div>
                        @if($order->shipping_tax > 0)
                        <div class="flex justify-between text-sm text-[#787774]">
                            <div class="flex items-center gap-1">
                                <span>Shipping Tax</span>
                                <span class="text-[10px]">*{{ $order->shipping_fee > 0 ? round(($order->shipping_tax / $order->shipping_fee) * 100) : 0 }}%</span>
                            </div>
                            <span>{{ \App\Services\CurrencyService::format($order->shipping_tax) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-lg font-medium text-[#111111] pt-3 border-t border-[#EAEAEA]">
                            <span>Total</span>
                            <span>{{ \App\Services\CurrencyService::format($order->total) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer, Payment & Shipping Info -->
            <div class="scroll-reveal grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="admin-outer-shell">
                    <div class="admin-inner-core h-full p-6">
                        <h2 class="text-sm font-mono uppercase tracking-widest text-[#787774] mb-4">Customer Details</h2>
                        <div class="space-y-3 text-sm">
                            <p><span class="text-[#787774] inline-block w-16">Name:</span> <span class="font-medium text-[#111111]">{{ $order->user->name ?? 'Unknown' }}</span></p>
                            <p><span class="text-[#787774] inline-block w-16">Email:</span> <span class="text-[#111111]">{{ $order->contact_email ?? $order->user->email ?? 'Unknown' }}</span></p>
                            @if($order->user && $order->user->is_vip)
                                <span class="inline-block mt-2 admin-badge bg-[#111111] text-white">VIP Customer</span>
                            @endif
                        </div>
                        
                        <h2 class="text-sm font-mono uppercase tracking-widest text-[#787774] mt-6 mb-4 pt-6 border-t border-[#EAEAEA]">Payment Info</h2>
                        <div class="space-y-3 text-sm">
                            <p><span class="text-[#787774] inline-block w-16">Method:</span> <span class="font-medium text-[#111111] uppercase">{{ $order->payment_method ?? 'N/A' }}</span></p>
                            <p><span class="text-[#787774] inline-block w-16">Status:</span> 
                                @if($order->payment_status === 'paid')
                                    <span class="font-medium text-[#346538] uppercase">Paid</span>
                                @elseif($order->payment_status === 'failed')
                                    <span class="font-medium text-[#9F2F2D] uppercase">Failed</span>
                                @elseif($order->payment_status === 'refunded')
                                    <span class="font-medium text-[#346538] uppercase">Refunded</span>
                                @else
                                    <span class="font-medium text-[#956400] uppercase">{{ $order->payment_status ?? 'Pending' }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="admin-outer-shell">
                    <div class="admin-inner-core h-full p-6">
                        <h2 class="text-sm font-mono uppercase tracking-widest text-[#787774] mb-4">Shipping Address</h2>
                        <div class="text-sm text-[#111111] leading-relaxed">
                            <p class="font-medium">{{ $order->shipping_full_name ?? '-' }}</p>
                            <p>{{ $order->shipping_address1 ?? 'No address provided.' }}</p>
                            @if($order->shipping_address2)
                                <p>{{ $order->shipping_address2 }}</p>
                            @endif
                            <p>{{ $order->shipping_city ?? '' }}{{ $order->shipping_postal_code ? ', ' . $order->shipping_postal_code : '' }}</p>
                            <p>{{ $order->shipping_country ?? '' }}</p>
                        </div>
                        
                        @if($order->billing_same_as_shipping === false)
                        <h2 class="text-sm font-mono uppercase tracking-widest text-[#787774] mt-6 mb-4 pt-6 border-t border-[#EAEAEA]">Billing Address</h2>
                        <div class="text-sm text-[#111111] leading-relaxed">
                            <p class="italic text-[#787774]">Separate billing address provided during checkout.</p>
                        </div>
                        @endif

                        @if($order->status === 'cancelled' && $order->cancel_reason)
                        <h2 class="text-sm font-mono uppercase tracking-widest text-[#9F2F2D] mt-6 mb-4 pt-6 border-t border-[#EAEAEA]">Cancellation Details</h2>
                        <div class="text-sm text-[#111111] leading-relaxed bg-[#FDEBEC] p-4 rounded-lg border border-[#9F2F2D]/20">
                            <p><span class="text-[#9F2F2D] font-medium inline-block w-16">Reason:</span> <span class="font-medium text-[#111111]">{{ $order->cancel_reason }}</span></p>
                            <div class="mt-2 text-[#787774] italic bg-white p-3 border border-[#EAEAEA] rounded text-xs">
                                "{{ $order->cancel_description }}"
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Right: Action Form (col-span-4) -->
        <div class="w-full lg:w-1/3">
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="sticky top-24">
                @csrf
                @method('PUT')

                <div class="scroll-reveal admin-outer-shell">
                    <div class="admin-inner-core p-6 md:p-8 relative">
                        <h2 class="text-sm font-mono uppercase tracking-widest text-[#787774] mb-6">Manage Order</h2>
                        
                        <div class="space-y-6 relative z-10">
                            <div>
                                <label for="status" class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Order Status</label>
                                <div class="relative">
                                    <select id="status" name="status" class="w-full pl-4 pr-10 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow appearance-none">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending Payment</option>
                                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="verified" {{ $order->status === 'verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <i class="ph-light ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#787774] pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- Tracking Section -->
                            <div id="tracking-section" style="display: {{ $order->status === 'shipped' ? 'block' : 'none' }};">
                                <div class="space-y-2">
                                    <label for="tracking_number" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Tracking Number (Resi)</label>
                                    <input type="text" id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="e.g. JNE123456789" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm font-mono text-[#111111] placeholder-[#787774]/50 focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow">
                                </div>

                                @if($order->shipped_at)
                                <div class="pt-4 mt-4 border-t border-[#EAEAEA]">
                                    <p class="text-xs text-[#787774]">Shipped on: <span class="text-[#111111]">{{ $order->shipped_at->format('M d, Y H:i') }}</span></p>
                                </div>
                                @endif
                            </div>

                            <div class="pt-6">
                                <button type="submit" class="w-full admin-button-island group bg-[#111111] text-white hover:bg-[#333333] transition-haptic active:scale-95 justify-center">
                                    <span>Update Order</span>
                                    <div class="admin-button-island-icon bg-white/10 group-hover:bg-white/20 transition-colors">
                                        <i class="ph-light ph-check text-white"></i>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            @if($order->status === 'cancelled' && $order->payment_status === 'paid')
            <div class="mt-6 scroll-reveal admin-outer-shell" style="border-color: #9F2F2D;">
                <div class="admin-inner-core p-6 md:p-8 bg-[#FDEBEC]">
                    <h2 class="text-sm font-mono uppercase tracking-widest text-[#9F2F2D] mb-4">Refund to Clementpay</h2>
                    <p class="text-xs text-[#9F2F2D] mb-6 leading-relaxed">This order is cancelled but payment was collected. Issue a manual refund to the customer's Clementpay balance.</p>
                    <form action="{{ route('admin.orders.refund', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to refund this order to Clementpay?');">
                        @csrf
                        <button type="submit" class="w-full admin-button-island group bg-[#9F2F2D] text-white hover:bg-[#7a2422] transition-haptic active:scale-95 justify-center">
                            <span>Process Refund</span>
                        </button>
                    </form>
                </div>
            </div>
            @elseif($order->payment_status === 'refunded')
            <div class="mt-6 scroll-reveal admin-outer-shell">
                <div class="admin-inner-core p-6 text-center text-sm font-mono text-[#346538] uppercase tracking-widest bg-[#EDF3EC]">
                    Refunded to Clementpay
                </div>
            </div>
            @endif
        </div>

    </div>
</div>

<!-- Invoice Modal -->
<div id="invoice-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#111111]/40 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden m-4">
        <!-- Header -->
        <div class="p-6 border-b border-[#EAEAEA] flex justify-between items-center bg-[#F9F9F8]">
            <h2 class="font-serif text-2xl text-[#111111]">Invoice #{{ substr($order->id, 0, 8) }}</h2>
            <button type="button" id="close-invoice" class="text-[#787774] hover:text-[#111111]">
                <i class="ph-light ph-x text-2xl"></i>
            </button>
        </div>
        
        <!-- Body -->
        <div class="p-8 overflow-y-auto" id="printable-invoice">
            <div class="flex justify-between items-start mb-12">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <x-logo class="w-8 h-8" />
                        <h1 class="text-2xl font-serif font-bold tracking-tight text-[#111111]">CLEMENTINE</h1>
                    </div>
                    <p class="text-sm text-[#787774] mt-1">Premium Watch Collection</p>
                </div>
                <div class="text-right text-sm text-[#787774]">
                    <p class="font-medium text-[#111111] mb-1 tracking-widest uppercase">Invoice</p>
                    <p>Date: {{ $order->created_at->format('M d, Y') }}</p>
                    <p>Order ID: {{ substr($order->id, 0, 12) }}</p>
                </div>
            </div>
            
            <div class="flex justify-between gap-8 mb-10 text-sm">
                <div>
                    <p class="font-mono uppercase tracking-widest text-[#787774] mb-2 text-xs">Billed To</p>
                    <p class="font-medium text-[#111111]">{{ $order->user->name ?? 'Customer' }}</p>
                    <p class="text-[#787774]">{{ $order->contact_email }}</p>
                </div>
                <div class="text-right">
                    <p class="font-mono uppercase tracking-widest text-[#787774] mb-2 text-xs">Shipped To</p>
                    <p class="font-medium text-[#111111]">{{ $order->shipping_full_name }}</p>
                    <p class="text-[#787774]">{{ $order->shipping_address1 }}</p>
                    <p class="text-[#787774]">{{ $order->shipping_city }}, {{ $order->shipping_country }}</p>
                    @if($order->tracking_number)
                        <p class="font-mono uppercase tracking-widest text-[#787774] mt-4 mb-1 text-xs">Tracking Number</p>
                        <p class="font-medium text-[#111111]">{{ $order->tracking_number }}</p>
                    @endif
                </div>
            </div>
            
            <table class="w-full text-left mb-8 text-sm">
                <thead>
                    <tr class="border-b border-[#EAEAEA] text-[#787774] font-mono uppercase tracking-widest text-xs">
                        <th class="pb-3 font-normal">Item</th>
                        <th class="pb-3 font-normal text-center">Qty</th>
                        <th class="pb-3 font-normal text-right">Price</th>
                        <th class="pb-3 font-normal text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAEAEA]">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="py-4 text-[#111111]">{{ $item->product->name ?? 'Product' }}</td>
                        <td class="py-4 text-[#787774] text-center">{{ $item->quantity }}</td>
                        <td class="py-4 text-[#787774] text-right">{{ \App\Services\CurrencyService::format($item->price_at_purchase) }}</td>
                        <td class="py-4 text-[#111111] font-medium text-right">{{ \App\Services\CurrencyService::format($item->quantity * $item->price_at_purchase) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="w-full md:w-1/2 ml-auto space-y-2 text-sm text-[#787774]">
                <div class="flex justify-between">
                    <span>Subtotal (Excl. Tax)</span>
                    <span>{{ \App\Services\CurrencyService::format($order->subtotal) }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-[#D97757]">
                    <div class="flex items-center gap-1">
                        <span>{{ $order->user && $order->user->is_vip ? 'VIP Discount' : 'Discount' }}</span>
                        <span class="text-[10px]">*{{ $order->subtotal > 0 ? round(($order->discount_amount / $order->subtotal) * 100) : 0 }}%</span>
                    </div>
                    <span>-{{ \App\Services\CurrencyService::format($order->discount_amount) }}</span>
                </div>
                @endif
                @if($order->tax > 0)
                <div class="flex justify-between">
                    <div class="flex items-center gap-1">
                        <span>Product Tax</span>
                        <span class="text-[10px]">*{{ $order->subtotal > 0 ? round(($order->tax / $order->subtotal) * 100) : 0 }}%</span>
                    </div>
                    <span>{{ \App\Services\CurrencyService::format($order->tax) }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span>Shipping Fee</span>
                    <span>{{ \App\Services\CurrencyService::format($order->shipping_fee) }}</span>
                </div>
                @if($order->shipping_tax > 0)
                <div class="flex justify-between">
                    <div class="flex items-center gap-1">
                        <span>Shipping Tax</span>
                        <span class="text-[10px]">*{{ $order->shipping_fee > 0 ? round(($order->shipping_tax / $order->shipping_fee) * 100) : 0 }}%</span>
                    </div>
                    <span>{{ \App\Services\CurrencyService::format($order->shipping_tax) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-lg font-medium text-[#111111] pt-3 border-t border-[#EAEAEA]">
                    <span>Total</span>
                    <span>{{ \App\Services\CurrencyService::format($order->total) }}</span>
                </div>
            </div>
            
            <div class="mt-16 text-center text-xs text-[#787774]">
                <p>Thank you for shopping with Clementine.</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="p-6 border-t border-[#EAEAEA] bg-[#F9F9F8] flex justify-end gap-4">
            <button type="button" id="close-invoice-btn" class="px-6 py-2.5 rounded-lg text-sm font-medium text-[#787774] hover:text-[#111111] transition-colors">
                Cancel
            </button>
            <button type="button" onclick="window.print()" class="admin-button-island group bg-[#111111] text-white hover:bg-[#333333] transition-haptic active:scale-95">
                <span>Print Document</span>
                <div class="admin-button-island-icon bg-white/10 group-hover:bg-white/20 transition-colors">
                    <i class="ph-light ph-printer text-white"></i>
                </div>
            </button>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printable-invoice, #printable-invoice * {
            visibility: visible;
        }
        #printable-invoice {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
        }
        #invoice-modal {
            background: white !important;
            backdrop-filter: none !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const trackingSection = document.getElementById('tracking-section');
        
        statusSelect.addEventListener('change', function() {
            if (this.value === 'shipped') {
                trackingSection.style.display = 'block';
            } else {
                trackingSection.style.display = 'none';
            }
        });
        
        // Modal Logic
        const modal = document.getElementById('invoice-modal');
        const openBtn = document.getElementById('open-invoice');
        const closeBtns = [document.getElementById('close-invoice'), document.getElementById('close-invoice-btn')];
        
        if(openBtn) {
            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        }
        
        closeBtns.forEach(btn => {
            if(btn) {
                btn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            }
        });
    });
</script>
@endsection
