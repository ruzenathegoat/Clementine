<div class="border border-outline-variant p-6 bg-surface-container-lowest flex flex-col md:flex-row justify-between gap-6">
    <div class="flex flex-col gap-4">
        <div class="flex items-center gap-3">
            <span class="font-label-caps text-sm uppercase tracking-widest font-bold">Order #{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}</span>
            <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : ($order->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-surface-variant text-on-surface') }}">
                {{ $order->status }}
            </span>
        </div>
        
        <p class="text-xs text-on-surface-variant font-body-md">{{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y') }}</p>
        
        <div class="flex flex-wrap gap-4 mt-2">
            @foreach($order->items as $item)
                <div class="flex items-center gap-3 bg-white border border-outline-variant p-2 pr-4">
                    <div class="w-10 h-10 bg-surface flex items-center justify-center p-1 border border-outline-variant">
                        @if($item->product->primaryImage)
                            <img src="{{ $item->product->primaryImage->url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover mix-blend-multiply">
                        @endif
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold uppercase">{{ $item->product->name }}</span>
                        <span class="text-[10px] text-gray-500">Qty: {{ $item->quantity }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="flex flex-col md:items-end justify-between border-t md:border-t-0 md:border-l border-outline-variant pt-4 md:pt-0 md:pl-6 gap-4">
        <div class="flex flex-col md:items-end gap-1">
            <span class="font-label-caps text-[10px] uppercase tracking-widest text-on-surface-variant">Total Amount</span>
            <span class="font-headline-md text-xl font-bold">${{ number_format($order->total, 2) }}</span>
        </div>
        
        <div class="flex flex-col gap-2 w-full">
            @if($order->payment_status === 'pending' && $order->payment_method === 'virtual_account')
                <a href="{{ route('orders.show', $order) }}" class="text-center px-6 py-2 bg-copper text-white text-xs font-bold uppercase tracking-wider hover:opacity-80 transition-opacity">
                    Pay Now
                </a>
            @endif
            @if(in_array($order->status, ['pending', 'processing']) && now()->diffInMinutes($order->created_at) <= 15)
                <a href="{{ route('orders.cancel_form', $order) }}" class="text-center px-6 py-2 border border-red-600 text-red-600 text-xs font-bold uppercase tracking-wider hover:bg-red-600 hover:text-white transition-colors">
                    Cancel Order
                </a>
            @endif
            <button type="button" onclick="openInvoice('{{ $order->id }}')" class="px-6 py-2 border border-primary text-primary text-xs font-bold uppercase tracking-wider hover:bg-primary hover:text-white transition-colors">
                View Invoice
            </button>
        </div>
    </div>
</div>
