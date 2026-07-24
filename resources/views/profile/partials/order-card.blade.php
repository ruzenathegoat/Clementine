<!-- Acquisition Record (Order Card) -->
<div class="relative group cursor-crosshair border-b border-[rgba(10,10,10,0.15)] pl-6 pb-12 transition-colors duration-300 hover:bg-[#FDFDFD]" 
     x-data="{ 
         isHovered: false,
         showInvoice() { 
             openInvoice({
                 id: '{{ $order->id }}',
                 ref: '{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}',
                 date: '{{ \Carbon\Carbon::parse($order->created_at)->format('Y.m.d') }}',
                 subtotal: '{{ number_format($order->subtotal, 0) }}',
                 tax: '{{ number_format($order->tax ?? 0, 0) }}',
                 shipping: '{{ number_format($order->shipping_fee ?? 0, 0) }}',
                 discount: '{{ number_format($order->discount_amount ?? 0, 0) }}',
                 total: '{{ number_format($order->total, 0) }}',
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
     }"
     @mouseenter="isHovered = true"
     @mouseleave="isHovered = false">
     
    <!-- Top Meta -->
    <div class="flex justify-between items-start mb-8 relative z-20">
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
        <div class="relative w-full max-w-[200px] h-[160px] overflow-hidden flex flex-shrink-0 items-center justify-center pointer-events-none">
            @if($order->items->count() > 0)
                @foreach($order->items->take(3) as $index => $item)
                    @if($item->product->primaryImage)
                        @php
                            $baseTransform = "translateX(" . ($index * 4) . "px) scale(" . (1 - ($index * 0.05)) . ") rotate(" . ($index * 2) . "deg)";
                            
                            $hoverX = $index == 0 ? '-20px' : ($index == 1 ? '20px' : '50px');
                            $hoverScale = $index == 0 ? '1' : ($index == 1 ? '0.95' : '0.9');
                            $hoverRotate = $index == 0 ? '-4deg' : ($index == 1 ? '4deg' : '8deg');
                            $hoverZ = $index == 0 ? 10 : ($index == 1 ? 12 : 3);
                            
                            $hoverTransform = "translateX({$hoverX}) scale({$hoverScale}) rotate({$hoverRotate})";
                        @endphp
                        <img src="{{ $item->product->primaryImage->url }}" 
                             alt="{{ $item->product->name }}" 
                             class="absolute w-[120px] h-auto object-contain grayscale-[20%] brightness-95 contrast-110 transition-all duration-400 ease-[cubic-bezier(0.23,1,0.32,1)] origin-center"
                             :style="isHovered ? 'z-index: {{ $hoverZ }}; transform: {{ $hoverTransform }};' : 'z-index: {{ 10 - $index }}; transform: {{ $baseTransform }};'">
                    @endif
                @endforeach
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
        <button @click.prevent="showInvoice()" class="font-mono text-[9px] tracking-[0.2em] uppercase text-[#1A1A1A] flex items-center gap-2 border-b border-transparent hover:border-[#1A1A1A] transition-all opacity-100 translate-y-0 md:opacity-0 md:group-hover:opacity-100 transform md:translate-y-4 md:group-hover:translate-y-0 duration-400 ease-[cubic-bezier(0.23,1,0.32,1)]">
            VIEW DOSSIER <span class="material-symbols-outlined text-[12px]">description</span>
        </button>
    </div>

    <!-- Hover Border Drawer Accent -->
    <div class="absolute top-0 left-0 h-full w-[2px] bg-[#1A1A1A] transform scale-y-0 origin-top group-hover:scale-y-100 transition-transform duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] z-0 pointer-events-none"></div>
</div>
