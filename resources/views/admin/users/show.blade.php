@extends('admin.layout')

@section('title', 'Customer Profile')

@section('content')
<div class="space-y-10 pb-12 max-w-6xl mx-auto">
    <!-- Header Area -->
    <div class="scroll-reveal flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[#787774] hover:text-[#111111] transition-colors mb-2">
                <i class="ph-light ph-arrow-left"></i> Back to Customers
            </a>
            <div class="flex items-center gap-4">
                @if($user->avatar)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-8 h-8 md:w-12 md:h-12 rounded-full object-cover shrink-0">
                @else
                    <div class="w-8 h-8 md:w-12 md:h-12 rounded-full bg-[#EAEAEA] text-[#787774] flex items-center justify-center font-serif text-lg md:text-xl shrink-0">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
                <h1 class="font-serif text-3xl md:text-4xl tracking-tight leading-none text-[#111111]">
                    {{ $user->name }}
                </h1>
            </div>
        </div>
        
        <div>
            @if($user->is_vip)
                <span class="admin-badge bg-[#111111] text-white px-4 py-1.5 flex items-center gap-2">
                    <i class="ph-fill ph-star"></i> VIP Customer
                </span>
            @else
                <span class="admin-badge bg-[#EAEAEA] text-[#787774] px-4 py-1.5">
                    Standard Customer
                </span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="scroll-reveal p-4 bg-[#EDF3EC] text-[#346538] text-sm rounded-xl border border-[#EDF3EC]/50 flex items-center gap-3">
            <i class="ph-light ph-check-circle text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Left: Order History (col-span-8) -->
        <div class="w-full lg:w-2/3 space-y-8">
            <div class="scroll-reveal admin-outer-shell">
                <div class="admin-inner-core">
                    <div class="p-6 md:p-8 border-b border-[#EAEAEA] flex items-center justify-between">
                        <h2 class="text-sm font-mono uppercase tracking-widest text-[#787774]">Order History</h2>
                        <span class="text-sm font-medium text-[#111111]">{{ $user->orders->count() }} orders</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-[#EAEAEA] text-xs font-mono uppercase tracking-widest text-[#787774] bg-[#F9F9F8]">
                                    <th class="px-6 py-4 font-normal">Order ID</th>
                                    <th class="px-6 py-4 font-normal">Date</th>
                                    <th class="px-6 py-4 font-normal">Status</th>
                                    <th class="px-6 py-4 font-normal text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#EAEAEA]">
                                @forelse($user->orders as $order)
                                    <tr class="group hover:bg-[#F9F9F8]/50 transition-colors {{ auth()->user()->role === 'super_admin' || auth()->user()->role === 'ops_staff' || auth()->user()->role === 'finance_manager' ? 'cursor-pointer' : '' }}" 
                                        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'ops_staff' || auth()->user()->role === 'finance_manager')
                                        onclick="window.location='{{ route('admin.orders.edit', $order->id) }}'"
                                        @endif
                                    >
                                        <td class="px-6 py-4">
                                            <p class="font-mono text-xs text-[#111111] group-hover:underline">{{ substr($order->id, 0, 8) }}...</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-[#787774]">{{ $order->created_at->format('M d, Y') }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($order->status === 'pending')
                                                <span class="admin-badge bg-[#FBF3DB] text-[#956400]">Pending</span>
                                            @elseif($order->status === 'processing')
                                                <span class="admin-badge bg-[#E1F3FE] text-[#1F6C9F]">Processing</span>
                                            @elseif($order->status === 'shipped')
                                                <span class="admin-badge bg-[#EDF3EC] text-[#346538]">Shipped</span>
                                            @elseif($order->status === 'completed')
                                                <span class="admin-badge bg-[#F9F9F8] border border-[#EAEAEA] text-[#111111]">Completed</span>
                                            @else
                                                <span class="admin-badge bg-[#FDEBEC] text-[#9F2F2D]">Cancelled</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <p class="text-sm font-medium text-[#111111]">{{ \App\Services\CurrencyService::format($order->total) }}</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 text-center">
                                            <i class="ph-light ph-receipt text-4xl text-[#EAEAEA] mb-2"></i>
                                            <p class="text-sm text-[#787774]">No orders yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Profile & VIP Toggle (col-span-4) -->
        <div class="w-full lg:w-1/3 space-y-8">
            
            <div class="scroll-reveal admin-outer-shell">
                <div class="admin-inner-core p-6 md:p-8">
                    <h2 class="text-sm font-mono uppercase tracking-widest text-[#787774] mb-6">Customer Analytics</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <p class="text-xs font-mono uppercase tracking-widest text-[#787774] mb-1">Lifetime Value (LTV)</p>
                            <h3 class="font-serif text-3xl tracking-tight text-[#111111]">{{ \App\Services\CurrencyService::format($ltv) }}</h3>
                            <p class="text-xs text-[#787774] mt-1">From successful orders only.</p>
                        </div>
                        
                        <div class="pt-4 border-t border-[#EAEAEA]">
                            <p class="text-xs font-mono uppercase tracking-widest text-[#787774] mb-1">Registered On</p>
                            <p class="text-sm text-[#111111]">{{ $user->created_at->format('F j, Y') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs font-mono uppercase tracking-widest text-[#787774] mb-1">Email Address</p>
                            <p class="text-sm text-[#111111] break-all">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIP Control Form -->
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="is_vip" value="{{ $user->is_vip ? '0' : '1' }}">
                
                <div class="scroll-reveal admin-outer-shell">
                    <div class="admin-inner-core p-6 md:p-8 bg-white">
                        <h2 class="text-sm font-mono uppercase tracking-widest text-[#787774] mb-4">VIP Status Control</h2>
                        
                        <p class="text-sm text-[#111111] mb-6 leading-relaxed">
                            {{ $user->is_vip ? 'This customer is currently marked as a VIP. They may receive priority support or exclusive offers.' : 'Mark this customer as a VIP to grant them priority support and highlight them in the dashboard.' }}
                        </p>
                        
                        <button type="submit" class="w-full admin-button-island group {{ $user->is_vip ? 'bg-[#FDEBEC] text-[#9F2F2D] hover:bg-red-200' : 'bg-[#111111] text-white hover:bg-[#333333]' }} transition-haptic active:scale-95 justify-center">
                            @if($user->is_vip)
                                <span>Revoke VIP Status</span>
                                <div class="admin-button-island-icon bg-[#9F2F2D]/10 group-hover:bg-[#9F2F2D]/20 transition-colors">
                                    <i class="ph-light ph-x text-[#9F2F2D]"></i>
                                </div>
                            @else
                                <span>Grant VIP Status</span>
                                <div class="admin-button-island-icon bg-black/5 group-hover:bg-black/10 transition-colors">
                                    <i class="ph-fill ph-star text-black"></i>
                                </div>
                            @endif
                        </button>
                    </div>
                </div>
            </form>
            
        </div>

    </div>
</div>
@endsection
