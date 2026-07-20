@extends('admin.layout')

@section('title', 'Inventory')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header Area -->
    <div class="scroll-reveal flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <div class="inline-flex items-center admin-badge bg-[#EAEAEA] text-[#111111]">Inventory</div>
            <h1 class="font-serif text-5xl md:text-6xl tracking-tight leading-none text-[#111111]">Products</h1>
        </div>
        
        <div class="flex items-center gap-4 w-full md:w-auto">
            <!-- Search Form -->
            <form action="{{ route('admin.inventory.index') }}" method="GET" class="relative w-full md:w-64">
                <i class="ph-light ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#787774]"></i>
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}"
                    placeholder="Search name or SKU..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-[#EAEAEA] rounded-full text-sm focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow"
                >
            </form>
            
            <a href="{{ route('admin.inventory.index', ['status' => 'active']) }}" class="hidden sm:flex items-center justify-center w-10 h-10 rounded-full border border-[#EAEAEA] bg-white text-[#787774] hover:text-black hover:border-black transition-colors" title="Filter Active">
                <i class="ph-light ph-funnel"></i>
            </a>

            <button type="button" id="open-add-product" class="hidden sm:flex items-center justify-center h-10 px-4 rounded-full bg-[#111111] text-white hover:bg-black transition-colors" title="Add Product">
                <i class="ph-light ph-plus mr-2"></i> Add Product
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="scroll-reveal p-4 bg-[#EDF3EC] text-[#346538] text-sm rounded-xl border border-[#EDF3EC]/50 flex items-center gap-3">
            <i class="ph-light ph-check-circle text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- BI Dashboard Section for Inventory -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Top Products -->
        <div class="scroll-reveal admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-top-products" style="width: 100%; height: 350px;"></div>
            </div>
        </div>
        
        <!-- Stock vs Predicted Demand -->
        <div class="scroll-reveal admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-stock-prediction" style="width: 100%; height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Data List -->
    <div class="scroll-reveal admin-outer-shell">
        <div class="admin-inner-core">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAEAEA] text-xs font-mono uppercase tracking-widest text-[#787774] bg-[#F9F9F8]">
                            <th class="px-6 py-4 font-normal">Product</th>
                            <th class="px-6 py-4 font-normal hidden md:table-cell">SKU / ID</th>
                            <th class="px-6 py-4 font-normal">Stock</th>
                            <th class="px-6 py-4 font-normal hidden sm:table-cell">Price</th>
                            <th class="px-6 py-4 font-normal">Status</th>
                            <th class="px-6 py-4 font-normal text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAEAEA]">
                        @forelse($products as $product)
                            <tr class="group hover:bg-[#F9F9F8]/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <!-- Primary Image Thumbnail -->
                                        <div class="w-12 h-12 rounded-lg bg-[#EAEAEA] overflow-hidden flex-shrink-0 flex items-center justify-center">
                                            @if($product->primaryImage)
                                                <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="ph-light ph-image text-gray-400"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-[#111111] leading-tight">{{ $product->name }}</p>
                                            <p class="text-xs text-[#787774] mt-0.5">{{ $product->collection->name ?? 'No Collection' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 hidden md:table-cell">
                                    <p class="font-mono text-xs text-[#787774]">{{ $product->slug }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full {{ $product->stock > 10 ? 'bg-[#346538]' : ($product->stock > 0 ? 'bg-[#956400]' : 'bg-[#9F2F2D]') }}"></div>
                                        <span class="font-mono text-sm {{ $product->stock == 0 ? 'text-[#9F2F2D] font-medium' : 'text-[#111111]' }}">{{ $product->stock }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    <p class="text-sm font-medium text-[#111111]">{{ \App\Services\CurrencyService::format($product->price) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if(in_array($product->status, ['active', 'new', 'limited_edition']))
                                        <span class="admin-badge bg-[#EDF3EC] text-[#346538]">{{ ucwords(str_replace('_', ' ', $product->status)) }}</span>
                                    @else
                                        <span class="admin-badge bg-[#EAEAEA] text-[#787774]">{{ ucwords(str_replace('_', ' ', $product->status)) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.inventory.edit', $product->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-black hover:text-white transition-colors text-[#787774]">
                                        <i class="ph-light ph-pencil-simple text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <i class="ph-light ph-package text-4xl text-[#EAEAEA] mb-2"></i>
                                    <p class="text-sm text-[#787774]">No products found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($products->hasPages())
            <div class="border-t border-[#EAEAEA] p-4 bg-white">
                {{ $products->links('pagination::tailwind') }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div id="add-product-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#111111]/40 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl w-full max-w-xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden m-4">
        <!-- Header -->
        <div class="p-6 border-b border-[#EAEAEA] flex justify-between items-center bg-[#F9F9F8]">
            <h2 class="font-serif text-2xl text-[#111111]">Add New Product</h2>
            <button type="button" id="close-add-product" class="text-[#787774] hover:text-[#111111]">
                <i class="ph-light ph-x text-2xl"></i>
            </button>
        </div>
        
        <!-- Body -->
        <form action="{{ route('admin.inventory.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden max-h-full">
            @csrf
            <div class="p-8 overflow-y-auto space-y-6">
                @if($errors->any())
                    <div class="p-4 bg-[#FDEBEC] text-[#9F2F2D] text-sm rounded-xl border border-[#FDEBEC]/50 flex flex-col gap-1">
                        @foreach($errors->all() as $error)
                            <div class="flex items-center gap-2"><i class="ph-light ph-warning-circle"></i> {{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                
                <!-- Name -->
                <div>
                    <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow">
                </div>

                <!-- Image & Collection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Primary Image</label>
                        <input type="file" name="primary_image" accept="image/*" class="w-full px-4 py-2 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:ring-1 focus:ring-[#111111] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-[#111111] file:text-white hover:file:bg-[#333333] transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Collection</label>
                        <div class="relative">
                            <select name="collection_id" class="w-full pl-4 pr-10 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow appearance-none">
                                <option value="">No Collection</option>
                                @foreach($collections as $collection)
                                    <option value="{{ $collection->id }}" {{ old('collection_id') == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                                @endforeach
                            </select>
                            <i class="ph-light ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#787774] pointer-events-none"></i>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-6">
                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Price (USD) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow">
                    </div>
                    <!-- COGS -->
                    <div>
                        <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">COGS (USD) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="cogs" value="{{ old('cogs') }}" required class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Stock -->
                    <div>
                        <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Initial Stock <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" required class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow">
                    </div>
                    <!-- Gender -->
                    <div>
                        <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Gender <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="gender" required class="w-full pl-4 pr-10 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow appearance-none">
                                <option value="unisex" {{ old('gender') === 'unisex' ? 'selected' : '' }}>Unisex</option>
                                <option value="men" {{ old('gender') === 'men' ? 'selected' : '' }}>Men</option>
                                <option value="women" {{ old('gender') === 'women' ? 'selected' : '' }}>Women</option>
                            </select>
                            <i class="ph-light ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#787774] pointer-events-none"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Status <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="status" required class="w-full pl-4 pr-10 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow appearance-none">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="new" {{ old('status') === 'new' ? 'selected' : '' }}>New</option>
                            <option value="limited_edition" {{ old('status') === 'limited_edition' ? 'selected' : '' }}>Limited Edition</option>
                            <option value="sold_out" {{ old('status') === 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                        </select>
                        <i class="ph-light ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#787774] pointer-events-none"></i>
                    </div>
                </div>

                <!-- Scheduled Publish -->
                <div class="space-y-2">
                    <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Scheduled Publish (Optional)</label>
                    <input type="datetime-local" name="scheduled_publish_at" value="{{ old('scheduled_publish_at') }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow">
                </div>

                <!-- Product Specifications -->
                <div class="space-y-6 pt-6 border-t border-[#EAEAEA]">
                    <h3 class="text-sm font-mono uppercase tracking-widest text-[#111111]">Product Specifications</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Diameter (mm)</label>
                            <input type="number" step="0.1" name="diameter_mm" value="{{ old('diameter_mm') }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm font-mono focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Movement</label>
                            <input type="text" name="movement" value="{{ old('movement') }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Case Material</label>
                            <input type="text" name="case_material" value="{{ old('case_material') }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Strap Material</label>
                            <input type="text" name="material" value="{{ old('material') }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Water Resistance</label>
                            <input type="text" name="water_resistance" value="{{ old('water_resistance') }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Crystal</label>
                            <input type="text" name="crystal" value="{{ old('crystal') }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-mono uppercase tracking-widest text-[#787774]">Warranty (Years)</label>
                            <input type="number" step="1" name="warranty_years" value="{{ old('warranty_years') }}" class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm font-mono focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="p-6 border-t border-[#EAEAEA] bg-[#F9F9F8] flex justify-end gap-4 shrink-0">
                <button type="button" id="close-add-product-btn" class="px-6 py-2.5 rounded-lg text-sm font-medium text-[#787774] hover:text-[#111111] transition-colors">
                    Cancel
                </button>
                <button type="submit" class="admin-button-island group bg-[#111111] text-white hover:bg-[#333333] transition-haptic active:scale-95">
                    <span>Create Product</span>
                    <div class="admin-button-island-icon bg-white/10 group-hover:bg-white/20 transition-colors">
                        <i class="ph-light ph-plus text-white"></i>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('add-product-modal');
        const openBtn = document.getElementById('open-add-product');
        const closeBtns = [document.getElementById('close-add-product'), document.getElementById('close-add-product-btn')];
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
        
        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        if(openBtn) {
            openBtn.addEventListener('click', (e) => {
                e.preventDefault();
                openModal();
            });
        }
        
        closeBtns.forEach(btn => {
            if(btn) {
                btn.addEventListener('click', closeModal);
            }
        });

        if (hasErrors) {
            openModal();
        }
    });
</script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const biData = @json($biData ?? []);

    if (!biData || Object.keys(biData).length === 0) return;

    Highcharts.setOptions({
        chart: {
            style: { fontFamily: '"Plus Jakarta Sans", sans-serif' },
            backgroundColor: 'transparent'
        },
        title: {
            style: { color: '#111111', fontWeight: 'bold', fontSize: '16px' }
        },
        credits: { enabled: false }
    });

    // Top Products
    if (biData.top_products && biData.top_products.categories.length > 0) {
        Highcharts.chart('chart-top-products', {
            chart: { type: 'bar' },
            title: { text: 'Top Selling Products' },
            xAxis: { categories: biData.top_products.categories },
            yAxis: { title: { text: 'Units Sold' } },
            series: [{ name: 'Units', data: biData.top_products.data, color: '#1F6C9F' }]
        });
    }

    // Stock Prediction
    if (biData.stock_prediction && biData.stock_prediction.categories.length > 0) {
        Highcharts.chart('chart-stock-prediction', {
            chart: { type: 'column' },
            title: { text: 'Stock vs 30-Day Predicted Demand' },
            xAxis: { categories: biData.stock_prediction.categories },
            yAxis: [{ title: { text: 'Units' } }],
            series: [
                { name: 'Current Stock', data: biData.stock_prediction.stock, color: '#111111' },
                { name: 'Predicted 30-Day Demand', data: biData.stock_prediction.predicted, color: '#C62828' }
            ]
        });
    }
});
</script>
@endsection
