<!-- Acquisition Record (Order Card) -->
<div class="relative group cursor-crosshair border-b border-[rgba(10,10,10,0.15)] pb-12 transition-colors duration-300 hover:bg-[#FDFDFD]" 
     x-data="{ 
         showInvoice() { 
             openInvoice({
                 id: '{{ $order->id }}',
                 ref: '{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}',
                 date: '{{ \Carbon\Carbon::parse($order->created_at)->format('Y.m.d') }}',
                 total: '{{ number_format($order->total_amount, 0) }}',
                 status: '{{ $order->status }}',
                 items: {{ json_encode($order->items->map(function($item) {
                     return [
                         'name' => $item->product->name,
                         'price' => number_format($item->price_at_purchase, 0),
                         'qty' => $item->quantity
                     ];
                 })) }}
             });
         } 
     }">
     
    <!-- Top Meta -->
    <div class="flex justify-between items-start mb-8 relative z-10">
        <div>
            <div class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase mb-1">Reference Number</div>
            <div class="font-h2 text-sm md:text-lg uppercase tracking-widest text-[#1A1A1A]">
                {{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}
            </div>
        </div>
        
        <div class="text-right">
            <div class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase mb-1">Acquired Date</div>
            <div class="font-mono text-xs uppercase tracking-widest text-[#1A1A1A]">
                {{ \Carbon\Carbon::parse($order->created_at)->format('Y.m.d') }}
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="flex flex-col md:flex-row gap-8 md:gap-16 items-start relative z-10">
        
        <!-- Image Stack -->
        <div class="relative w-full max-w-[200px] h-[160px] flex-shrink-0 flex items-center justify-center pointer-events-none">
            @if($order->items->count() > 0)
                @foreach($order->items->take(3) as $index => $item)
                    @if($item->product->primaryImage)
                        <img src="{{ $item->product->primaryImage->url }}" 
                             alt="{{ $item->product->name }}" 
                             class="absolute w-[120px] h-auto object-contain grayscale-[20%] brightness-95 contrast-110 transition-all duration-400 ease-[cubic-bezier(0.23,1,0.32,1)] origin-center"
                             style="
                                z-index: {{ 10 - $index }};
                                transform: translateX({{ $index * 4 }}px) scale({{ 1 - ($index * 0.05) }}) rotate({{ $index * 2 }}deg);
                             "
                             onload="this.classList.add('stack-img-{{ $index }}')">
                    @endif
                @endforeach
                <!-- Fallback CSS for hover spreading if inline styles conflict with tailwind arbitrary groups -->
                <style>
                    .group:hover .stack-img-0 { transform: translateX(-20px) scale(1) rotate(-4deg) !important; }
                    .group:hover .stack-img-1 { transform: translateX(20px) scale(0.95) rotate(4deg) !important; z-index: 12 !important; }
                    .group:hover .stack-img-2 { transform: translateX(50px) scale(0.9) rotate(8deg) !important; z-index: 13 !important; }
                </style>
            @else
                <div class="w-full h-full border border-dashed border-[rgba(10,10,10,0.2)] flex items-center justify-center font-mono text-[9px] text-[#909090]">
                    [ NO ASSET IMAGE ]
                </div>
            @endif
        </div>

        <!-- Info Grid -->
        <div class="flex-grow grid grid-cols-1 sm:grid-cols-2 gap-8 md:gap-12 w-full pt-4">
            
            <!-- Items List -->
            <div class="flex flex-col gap-2">
                <span class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase block">Assets Included</span>
                @foreach($order->items as $item)
                    <div class="font-h2 text-sm uppercase tracking-widest text-[#1A1A1A] truncate max-w-[200px]">
                        {{ $item->product->name }} <span class="text-[#909090] font-mono text-[10px]">x{{ $item->quantity }}</span>
                    </div>
                @endforeach
                @if($order->items->count() > 3)
                    <div class="font-mono text-[9px] text-[#909090] mt-1">+ {{ $order->items->count() - 3 }} more assets</div>
                @endif
            </div>

            <!-- Total & Status -->
            <div class="flex flex-col justify-between">
                <div>
                    <span class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase block mb-1">Status Code</span>
                    <div class="flex items-center gap-2">
                        @php
                            $dotColor = 'bg-[#1A1A1A]';
                            $pulse = 'animate-pulse';
                            if (in_array($order->status, ['completed', 'shipped'])) {
                                $pulse = '';
                            } elseif (in_array($order->status, ['cancelled', 'pending_cancel'])) {
                                $dotColor = 'bg-red-600';
                                $pulse = '';
                            }
                        @endphp
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }} {{ $pulse }}" style="animation-duration: 1.8s;"></span>
                        <span class="font-mono text-xs uppercase tracking-widest text-[#1A1A1A]">
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>
                    </div>
                </div>

                <div class="mt-4">
                    <span class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase block mb-1">Valuation</span>
                    <div class="font-mono text-lg tracking-widest text-[#1A1A1A] transition-colors group-hover:text-primary">
                        ${{ number_format($order->total, 0) }}
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Actions / Invoice Reveal -->
    <div class="absolute bottom-6 right-6 z-20">
        <button @click.prevent="showInvoice()" class="font-mono text-[9px] tracking-[0.2em] uppercase text-[#1A1A1A] flex items-center gap-2 border-b border-transparent hover:border-[#1A1A1A] transition-colors opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 duration-400 ease-[cubic-bezier(0.23,1,0.32,1)]">
            VIEW DOSSIER <span class="material-symbols-outlined text-[12px]">description</span>
        </button>
    </div>

    <!-- Hover Border Drawer Accent -->
    <div class="absolute top-0 left-0 h-full w-[1px] bg-[#1A1A1A] transform scale-y-0 origin-top group-hover:scale-y-100 transition-transform duration-500 ease-[cubic-bezier(0.23,1,0.32,1)]"></div>
</div>
