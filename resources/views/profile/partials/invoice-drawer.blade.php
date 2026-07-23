<!-- Invoice Drawer Component -->
<div x-cloak x-show="invoiceOpen" class="fixed inset-0 z-[100] flex justify-end invoice-drawer-container" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    
    <!-- Background Overlay -->
    <div x-show="invoiceOpen" 
         x-transition:enter="transition-opacity ease-out duration-400" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-in duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="absolute inset-0 bg-[#0A0A0A]/40 backdrop-blur-sm print:hidden" 
         @click="closeInvoice()"></div>

    <!-- Drawer Panel -->
    <div x-show="invoiceOpen" 
         x-transition:enter="transform transition ease-[cubic-bezier(0.23,1,0.32,1)] duration-500" 
         x-transition:enter-start="translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transform transition ease-in duration-300" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="translate-x-full" 
         class="relative w-full max-w-full md:max-w-[40%] bg-[#FAFAFA] border-l border-[rgba(10,10,10,0.15)] shadow-2xl h-full flex flex-col pointer-events-auto invoice-print-area">
        
        <!-- Header -->
        <div class="px-8 md:px-12 py-8 border-b border-[rgba(10,10,10,0.15)] flex justify-between items-start bg-white">
            <div>
                <span class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase mb-1 block">Acquisition Dossier</span>
                <h2 class="font-h1 text-3xl uppercase tracking-widest text-[#1A1A1A] m-0" id="slide-over-title" x-text="selectedOrder ? selectedOrder.ref : ''"></h2>
            </div>
            
            <button @click="closeInvoice()" class="w-10 h-10 border border-[rgba(10,10,10,0.15)] flex items-center justify-center text-[#1A1A1A] hover:bg-[#1A1A1A] hover:text-white transition-colors duration-300">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-grow overflow-y-auto overflow-x-hidden p-8 md:px-12 py-12 scrollbar-hide">
            
            <div class="flex justify-between items-end border-b border-[rgba(10,10,10,0.15)] pb-6 mb-12">
                <div>
                    <span class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase block mb-2">Entity</span>
                    <span class="font-h2 text-sm uppercase tracking-widest text-[#1A1A1A]">{{ $user->name }}</span>
                    <br>
                    <span class="font-mono text-xs text-[#555]">{{ $user->email }}</span>
                </div>
                <div class="text-right">
                    <span class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase block mb-2">Date Recorded</span>
                    <span class="font-mono text-sm tracking-widest text-[#1A1A1A]" x-text="selectedOrder ? selectedOrder.date : ''"></span>
                </div>
            </div>

            <div class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase border-b border-[rgba(10,10,10,0.15)] pb-2 mb-6 flex justify-between">
                <span>Asset Description</span>
                <span>Valuation</span>
            </div>

            <!-- Items Loop via Alpine -->
            <template x-if="selectedOrder">
                <div class="flex flex-col gap-6 mb-12">
                    <template x-for="item in selectedOrder.items" :key="item.name">
                        <div class="flex justify-between items-start border-b border-[rgba(10,10,10,0.05)] pb-4">
                            <div class="max-w-[70%]">
                                <span class="font-h2 text-sm uppercase tracking-widest text-[#1A1A1A] block mb-1" x-text="item.name"></span>
                                <span class="font-mono text-[10px] text-[#909090]" x-text="'Qty: ' + item.qty"></span>
                            </div>
                            <span class="font-mono text-sm tracking-widest text-[#1A1A1A]" x-text="'$' + item.price"></span>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Totals -->
            <div class="border-t border-[rgba(10,10,10,0.15)] pt-6 flex flex-col gap-4">
                <div class="flex justify-between font-mono text-[10px] tracking-widest text-[#909090] uppercase">
                    <span>Subtotal</span>
                    <span x-text="selectedOrder ? '$' + selectedOrder.total : ''"></span>
                </div>
                <div class="flex justify-between font-mono text-[10px] tracking-widest text-[#909090] uppercase">
                    <span>Logistics & Handling</span>
                    <span>$0</span>
                </div>
                <div class="flex justify-between items-end mt-4 pt-4 border-t border-[rgba(10,10,10,0.15)]">
                    <span class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase">Total Valuation</span>
                    <span class="font-mono text-2xl tracking-widest text-[#1A1A1A]" x-text="selectedOrder ? '$' + selectedOrder.total : ''"></span>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="p-8 md:p-12 border-t border-[rgba(10,10,10,0.15)] bg-white flex justify-between items-center print:hidden">
            <span class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase" x-text="selectedOrder ? 'STATUS: ' + selectedOrder.status : ''"></span>
            
            <button @click="window.print()" class="font-mono text-[10px] tracking-[0.2em] uppercase text-[#1A1A1A] border-b border-[#1A1A1A] pb-1 hover:text-[#909090] hover:border-[#909090] transition-colors">
                DOWNLOAD PDF
            </button>
        </div>
        
    </div>
</div>

<style>
@media print {
    body > *:not(.invoice-drawer-container) {
        display: none !important;
    }
    .invoice-drawer-container {
        display: block !important;
        position: static !important;
    }
    .invoice-print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        border: none !important;
        box-shadow: none !important;
        background: white !important;
    }
}
</style>
