@extends('admin.layout')

@section('title', 'Edit Product')

@section('content')
<div class="space-y-10 pb-12 max-w-4xl mx-auto">
    <!-- Header Area -->
    <div class="scroll-reveal flex items-center justify-between gap-6">
        <div class="space-y-2">
            <a href="{{ route('admin.inventory.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[#787774] hover:text-[#111111] transition-colors mb-2">
                <i class="ph-light ph-arrow-left"></i> Back to Inventory
            </a>
            <h1 class="font-serif text-4xl tracking-tight leading-none text-[#111111]">Edit Product</h1>
        </div>
        
        <div>
            @if(in_array($product->status, ['active', 'new', 'limited_edition']))
                <span class="admin-badge bg-[#EDF3EC] text-[#346538]">{{ ucwords(str_replace('_', ' ', $product->status)) }}</span>
            @else
                <span class="admin-badge bg-[#EAEAEA] text-[#787774]">{{ ucwords(str_replace('_', ' ', $product->status)) }}</span>
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

    <form action="{{ route('admin.inventory.update', $product->id) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="scroll-reveal admin-outer-shell">
            <div class="admin-inner-core p-8 md:p-10">
                <div class="flex flex-col md:flex-row gap-12">
                    
                    <!-- Left: Identity -->
                    <div class="w-full md:w-1/3 flex flex-col gap-6">
                        <div class="aspect-square bg-[#F9F9F8] border border-[#EAEAEA] rounded-xl overflow-hidden flex items-center justify-center">
                            @if($product->primaryImage)
                                <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <i class="ph-light ph-watch text-4xl text-[#EAEAEA]"></i>
                            @endif
                        </div>
                        
                        <div>
                            <p class="text-sm font-mono uppercase tracking-widest text-[#787774] mb-1">Product Name</p>
                            <p class="font-medium text-lg text-[#111111] leading-tight">{{ $product->name }}</p>
                            <p class="text-xs text-[#787774] mt-1 break-all">{{ $product->slug }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-mono uppercase tracking-widest text-[#787774] mb-1">Collection</p>
                            <p class="text-sm text-[#111111]">{{ $product->collection->name ?? 'Uncategorized' }}</p>
                        </div>
                    </div>

                    <!-- Right: Form Controls -->
                    <div class="w-full md:w-2/3 space-y-8">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Price -->
                            <div class="space-y-2">
                                <label for="price" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Retail Price ({{ \App\Services\CurrencyService::getCode() }})</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#787774] font-mono text-sm">{{ \App\Services\CurrencyService::getCode() == 'USD' ? '$' : 'Rp' }}</span>
                                    <input type="number" id="price" name="price" value="{{ old('price', (int)$product->price) }}" class="w-full pl-10 pr-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm font-mono focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow" required>
                                </div>
                            </div>
                            
                            <!-- COGS -->
                            <div class="space-y-2">
                                <label for="cogs" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">COGS / HPP ({{ \App\Services\CurrencyService::getCode() }})</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#787774] font-mono text-sm">{{ \App\Services\CurrencyService::getCode() == 'USD' ? '$' : 'Rp' }}</span>
                                    <input type="number" id="cogs" name="cogs" value="{{ old('cogs', (int)$product->cogs) }}" class="w-full pl-10 pr-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm font-mono focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow">
                                </div>
                                <p class="text-xs text-[#787774]">Cost of goods sold for profit calculation.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Stock -->
                            <div class="space-y-2">
                                <label for="stock" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Stock Quantity</label>
                                <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm font-mono focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow" required>
                            </div>
                            
                            <!-- Status -->
                            <div class="space-y-2">
                                <label for="status" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Visibility Status</label>
                                <div class="relative">
                                    <select id="status" name="status" class="w-full pl-4 pr-10 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow appearance-none">
                                        <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="new" {{ old('status', $product->status) === 'new' ? 'selected' : '' }}>New Arrival</option>
                                        <option value="limited_edition" {{ old('status', $product->status) === 'limited_edition' ? 'selected' : '' }}>Limited Edition</option>
                                        <option value="sold_out" {{ old('status', $product->status) === 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                                    </select>
                                    <i class="ph-light ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#787774] pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Scheduled Publish -->
                        <div class="space-y-2">
                            <label for="scheduled_publish_at" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Scheduled Publish (Optional)</label>
                            <input type="datetime-local" id="scheduled_publish_at" name="scheduled_publish_at" value="{{ old('scheduled_publish_at', $product->scheduled_publish_at ? $product->scheduled_publish_at->format('Y-m-d\TH:i') : '') }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow">
                            <p class="text-xs text-[#787774]">If set, a draft product will automatically become public after this time.</p>
                        </div>

                        <!-- Product Specifications -->
                        <div class="space-y-6 pt-6 border-t border-[#EAEAEA]">
                            <h3 class="text-sm font-mono uppercase tracking-widest text-[#111111]">Product Specifications</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="gender" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Gender</label>
                                    <select id="gender" name="gender" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111]">
                                        <option value="">Select Gender</option>
                                        <option value="men" {{ old('gender', strtolower($product->gender)) == 'men' ? 'selected' : '' }}>Men</option>
                                        <option value="women" {{ old('gender', strtolower($product->gender)) == 'women' ? 'selected' : '' }}>Women</option>
                                        <option value="unisex" {{ old('gender', strtolower($product->gender)) == 'unisex' ? 'selected' : '' }}>Unisex</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label for="diameter_mm" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Diameter (mm)</label>
                                    <input type="number" step="0.1" id="diameter_mm" name="diameter_mm" value="{{ old('diameter_mm', $product->diameter_mm) }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm font-mono focus:outline-none focus:ring-1 focus:ring-[#111111]">
                                </div>
                                <div class="space-y-2">
                                    <label for="movement" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Movement</label>
                                    <input type="text" id="movement" name="movement" value="{{ old('movement', $product->movement) }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111]">
                                </div>
                                <div class="space-y-2">
                                    <label for="case_material" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Case Material</label>
                                    <input type="text" id="case_material" name="case_material" value="{{ old('case_material', $product->case_material) }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111]">
                                </div>
                                <div class="space-y-2">
                                    <label for="material" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Strap Material</label>
                                    <input type="text" id="material" name="material" value="{{ old('material', $product->material) }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111]">
                                </div>
                                <div class="space-y-2">
                                    <label for="water_resistance" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Water Resistance</label>
                                    <input type="text" id="water_resistance" name="water_resistance" value="{{ old('water_resistance', $product->water_resistance) }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111]">
                                </div>
                                <div class="space-y-2">
                                    <label for="crystal" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Crystal</label>
                                    <input type="text" id="crystal" name="crystal" value="{{ old('crystal', $product->crystal) }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111]">
                                </div>
                                <div class="space-y-2">
                                    <label for="warranty_years" class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Warranty (Years)</label>
                                    <input type="number" step="1" id="warranty_years" name="warranty_years" value="{{ old('warranty_years', $product->warranty_years) }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm font-mono focus:outline-none focus:ring-1 focus:ring-[#111111]">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                
                <div class="mt-12 pt-8 border-t border-[#EAEAEA] flex items-center justify-between">
                    <button type="submit" form="delete-form" class="text-sm font-medium text-[#9F2F2D] hover:text-red-700 transition-colors px-4 py-2">
                        Archive Product
                    </button>
                    
                    <button type="submit" class="admin-button-island group hover:bg-[#333333] transition-haptic active:scale-95">
                        <span>Save Changes</span>
                        <div class="admin-button-island-icon group-hover:bg-white/20 transition-colors">
                            <i class="ph-light ph-check text-white"></i>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </form>
    
    <!-- Hidden Delete/Archive Form -->
    <form id="delete-form" action="{{ route('admin.inventory.destroy', $product->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection
