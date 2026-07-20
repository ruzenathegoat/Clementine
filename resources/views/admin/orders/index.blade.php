@extends('admin.layout')

@section('title', 'Orders')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header Area -->
    <div class="scroll-reveal flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <div class="inline-flex items-center admin-badge bg-[#EAEAEA] text-[#111111]">Operations</div>
            <h1 class="font-serif text-5xl md:text-6xl tracking-tight leading-none text-[#111111]">Orders</h1>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Search Form -->
            <form action="{{ route('admin.orders.index') }}" method="GET" class="relative w-full md:w-64">
                <i class="ph-light ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#787774]"></i>
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}"
                    placeholder="Search ID or Customer..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-[#EAEAEA] rounded-full text-sm focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow"
                >
            </form>
            
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-full border {{ !request('status') ? 'border-black text-black bg-white' : 'border-[#EAEAEA] text-[#787774] hover:border-black hover:text-black' }} text-sm transition-colors hidden sm:block">All</a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-full border {{ request('status') == 'pending' ? 'border-[#956400] text-[#956400] bg-[#FBF3DB]' : 'border-[#EAEAEA] text-[#787774] hover:border-[#956400] hover:text-[#956400]' }} text-sm transition-colors hidden lg:block">Pending</a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-4 py-2 rounded-full border {{ request('status') == 'processing' ? 'border-[#1F6C9F] text-[#1F6C9F] bg-[#E1F3FE]' : 'border-[#EAEAEA] text-[#787774] hover:border-[#1F6C9F] hover:text-[#1F6C9F]' }} text-sm transition-colors hidden lg:block">Processing</a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="px-4 py-2 rounded-full border {{ request('status') == 'shipped' ? 'border-[#346538] text-[#346538] bg-[#EDF3EC]' : 'border-[#EAEAEA] text-[#787774] hover:border-[#346538] hover:text-[#346538]' }} text-sm transition-colors hidden lg:block">Shipped</a>
        </div>
    </div>

    @if(session('success'))
        <div class="scroll-reveal p-4 bg-[#EDF3EC] text-[#346538] text-sm rounded-xl border border-[#EDF3EC]/50 flex items-center gap-3">
            <i class="ph-light ph-check-circle text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- BI Dashboard Section for Orders -->
    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-6">
        <div class="scroll-reveal admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-regions" style="width: 100%; height: 350px;"></div>
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
                            <th class="px-6 py-4 font-normal">Order ID & Date</th>
                            <th class="px-6 py-4 font-normal">Customer</th>
                            <th class="px-6 py-4 font-normal hidden sm:table-cell">Total Amount</th>
                            <th class="px-6 py-4 font-normal">Status</th>
                            <th class="px-6 py-4 font-normal text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAEAEA]">
                        @forelse($orders as $order)
                            <tr class="group hover:bg-[#F9F9F8]/50 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.orders.edit', $order->id) }}'">
                                <td class="px-6 py-4">
                                    <p class="font-mono text-sm text-[#111111] leading-tight font-medium">{{ substr($order->id, 0, 8) }}...</p>
                                    <p class="text-xs text-[#787774] mt-1">{{ $order->created_at->format('M d, Y') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($order->user)
                                            <img src="{{ $order->user->avatar_url }}" alt="{{ $order->user->name }}" class="w-8 h-8 rounded-full object-cover border border-[#EAEAEA]">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-[#EAEAEA] flex items-center justify-center font-serif text-sm text-[#111111]">
                                                ?
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-[#111111] text-sm">{{ $order->user->name ?? 'Unknown' }}</p>
                                            <p class="text-xs text-[#787774]">{{ $order->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    <p class="text-sm font-medium text-[#111111]">{{ \App\Services\CurrencyService::format($order->total) }}</p>
                                    <p class="text-xs text-[#787774] mt-1">{{ $order->items_sum_quantity ?? 0 }} items</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($order->status === 'pending')
                                        <span class="admin-badge bg-[#FBF3DB] text-[#956400]">Pending</span>
                                    @elseif($order->status === 'processing')
                                        <span class="admin-badge bg-[#E1F3FE] text-[#1F6C9F]">Processing</span>
                                    @elseif($order->status === 'verified')
                                        <span class="admin-badge bg-[#E1F3FE] text-[#1F6C9F]">Verified</span>
                                    @elseif($order->status === 'shipped')
                                        <span class="admin-badge bg-[#EDF3EC] text-[#346538]">Shipped</span>
                                    @elseif($order->status === 'completed')
                                        <span class="admin-badge bg-[#F9F9F8] border border-[#EAEAEA] text-[#111111]">Completed</span>
                                    @elseif($order->status === 'pending_cancel')
                                        <span class="admin-badge bg-[#FDEBEC] border border-[#9F2F2D]/20 text-[#9F2F2D]">Pending Cancel</span>
                                    @else
                                        <span class="admin-badge bg-[#FDEBEC] text-[#9F2F2D]">Cancelled</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <i class="ph-light ph-caret-right text-lg text-[#787774] group-hover:text-[#111111] transition-colors"></i>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <i class="ph-light ph-receipt text-4xl text-[#EAEAEA] mb-2"></i>
                                    <p class="text-sm text-[#787774]">No orders found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($orders->hasPages())
            <div class="border-t border-[#EAEAEA] p-4 bg-white">
                {{ $orders->links('pagination::tailwind') }}
            </div>
            @endif
        </div>
    </div>
</div>

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

    if (biData.regions && biData.regions.categories.length > 0) {
        Highcharts.chart('chart-regions', {
            chart: { type: 'column' },
            title: { text: 'Top Purchase Regions (Cities)' },
            xAxis: { categories: biData.regions.categories },
            yAxis: { title: { text: 'Orders' } },
            series: [{ name: 'Orders', data: biData.regions.data, color: '#111111' }]
        });
    }
});
</script>
@endsection
