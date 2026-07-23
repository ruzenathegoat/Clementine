@extends('layouts.app')

@section('title', 'Private Archive - ' . $user->name)

@push('styles')
<style>
    :root {
        --ease-out: cubic-bezier(0.23, 1, 0.32, 1);
        --bg-color: #FAFAFA;
        --ink-color: #0A0A0A;
        --border-color: rgba(10, 10, 10, 0.15);
    }
    body {
        background-color: var(--bg-color);
        color: var(--ink-color);
    }
    .archive-grid {
        border-top: 1px solid var(--border-color);
        border-left: 1px solid var(--border-color);
    }
    .archive-cell {
        border-right: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
    }
    .title-display {
        letter-spacing: -0.04em;
        text-wrap: balance;
    }
    
    /* Input Underline */
    .input-wrapper {
        position: relative;
    }
    .input-wrapper::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 1px;
        background-color: var(--ink-color);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 300ms var(--ease-out);
        z-index: 10;
    }
    .input-wrapper:focus-within::after {
        transform: scaleX(1);
    }
    
    input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 1000px var(--bg-color) inset !important;
        -webkit-text-fill-color: var(--ink-color) !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    /* Tab Transitions */
    .tab-panel {
        /* Handled via alpine transitions now, but ensuring absolute stack during transition */
    }

    /* Button Draw */
    .btn-draw {
        position: relative;
        overflow: hidden;
    }
    .btn-draw::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        border: 1px solid var(--ink-color);
        clip-path: inset(0 100% 0 0);
        transition: clip-path 400ms var(--ease-out);
    }
    .btn-draw:hover::before {
        clip-path: inset(0 0 0 0);
    }
    
    /* Danger Zone Modal */
    .danger-modal-bg {
        background-image: repeating-linear-gradient(45deg, #000 0, #000 10px, #111 10px, #111 20px);
    }
</style>
@endpush

@section('content')
<div x-data="archiveProfile()" class="w-full relative pb-24" id="archive-container" x-init="initGSAP()">

    <!-- HERO SECTION -->
    <header class="w-full min-h-[60vh] md:min-h-[80vh] flex flex-col justify-end px-6 md:px-12 py-16 relative overflow-hidden archive-hero">
        <!-- Giant Background Typography -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0 overflow-hidden mix-blend-multiply" id="hero-bg-text-container">
            <span class="font-h1 text-[18vw] uppercase text-[#1A1A1A] opacity-[0.02] whitespace-nowrap title-display" id="hero-bg-text">
                IDENTITY
            </span>
        </div>

        <div class="relative z-10 w-full max-w-[1600px] mx-auto flex flex-col justify-end h-full">
            <div class="archive-grid grid grid-cols-1 md:grid-cols-4 w-full" id="hero-grid">
                <!-- Name & ID -->
                <div class="archive-cell p-8 md:col-span-2 flex flex-col justify-end overflow-hidden">
                    <span class="font-mono text-[10px] tracking-[0.2em] text-[#909090] uppercase mb-4 hero-reveal">
                        Member ID: {{ strtoupper(substr(md5($user->id), 0, 12)) }}
                    </span>
                    <h1 class="font-h1 text-4xl md:text-7xl uppercase title-display m-0 leading-none hero-reveal hero-title-text" id="hero-name">
                        {{ $user->name }}
                    </h1>
                </div>

                <!-- Status & Level -->
                <div class="archive-cell p-8 flex flex-col justify-between overflow-hidden">
                    <div class="font-mono text-[10px] tracking-[0.2em] text-[#909090] uppercase hero-reveal">
                        Verification Status
                    </div>
                    <div class="mt-8 hero-reveal">
                        <span class="flex items-center gap-2 font-mono text-xs uppercase tracking-[0.2em]">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#1A1A1A] animate-pulse"></span>
                            AUTHENTICATED
                        </span>
                        @if($user->is_vip)
                            <div class="mt-4 text-[9px] bg-[#1A1A1A] text-white px-3 py-1.5 inline-block tracking-[0.2em] shadow-[0_0_15px_rgba(0,0,0,0.1)]">VIP ACCESS</div>
                        @endif
                    </div>
                </div>

                <!-- Stats -->
                <div class="archive-cell p-8 flex flex-col justify-between overflow-hidden">
                    <div class="font-mono text-[10px] tracking-[0.2em] text-[#909090] uppercase hero-reveal">
                        Archive Value
                    </div>
                    <div class="mt-8 font-mono text-xl tracking-widest hero-reveal">
                        ${{ number_format($orders->sum('total_amount'), 0) }}
                    </div>
                    <div class="font-mono text-[10px] tracking-[0.2em] text-[#909090] uppercase mt-4 hero-reveal">
                        {{ str_pad($orders->count(), 2, '0', STR_PAD_LEFT) }} ACQUISITIONS
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENT LAYOUT -->
    <div class="w-full max-w-[1600px] mx-auto px-6 md:px-12 flex flex-col lg:flex-row gap-0 mt-12 relative" id="content-layout">
        
        <!-- SIDEBAR ARCHIVE NAVIGATION -->
        <div class="w-full lg:w-[280px] flex-shrink-0 lg:sticky lg:top-32 h-max mb-12 lg:mb-0 z-20">
            <div class="font-mono text-[10px] tracking-[0.2em] text-[#909090] uppercase border-b border-[rgba(10,10,10,0.15)] pb-4 mb-4">
                [ ARCHIVE NAVIGATION ]
            </div>
            
            <nav class="flex flex-col gap-1 font-h2 text-xs md:text-sm uppercase tracking-widest relative">
                <!-- GSAP Line Indicator (Desktop only) -->
                <div id="nav-indicator" class="absolute left-0 w-4 h-[1px] bg-[#1A1A1A] tab-indicator hidden lg:block" style="top: 24px;"></div>

                <button @click="switchTab('identity', $event)" class="nav-btn text-left py-4 transition-colors duration-300 flex items-center gap-4 lg:pl-8 group" :class="activeTab === 'identity' ? 'text-[#1A1A1A]' : 'text-[#909090] hover:text-[#555]'">
                    <span class="block lg:hidden w-4 h-[1px] transition-colors" :class="activeTab === 'identity' ? 'bg-[#1A1A1A]' : 'bg-transparent group-hover:bg-[#909090]'"></span>
                    Identity
                </button>
                <button @click="switchTab('active', $event)" class="nav-btn text-left py-4 transition-colors duration-300 flex items-center gap-4 lg:pl-8 group" :class="activeTab === 'active' ? 'text-[#1A1A1A]' : 'text-[#909090] hover:text-[#555]'">
                    <span class="block lg:hidden w-4 h-[1px] transition-colors" :class="activeTab === 'active' ? 'bg-[#1A1A1A]' : 'bg-transparent group-hover:bg-[#909090]'"></span>
                    Active Acquisitions
                </button>
                <button @click="switchTab('history', $event)" class="nav-btn text-left py-4 transition-colors duration-300 flex items-center gap-4 lg:pl-8 group" :class="activeTab === 'history' ? 'text-[#1A1A1A]' : 'text-[#909090] hover:text-[#555]'">
                    <span class="block lg:hidden w-4 h-[1px] transition-colors" :class="activeTab === 'history' ? 'bg-[#1A1A1A]' : 'bg-transparent group-hover:bg-[#909090]'"></span>
                    Archive History
                </button>
                <button @click="switchTab('security', $event)" class="nav-btn text-left py-4 transition-colors duration-300 flex items-center gap-4 lg:pl-8 group" :class="activeTab === 'security' ? 'text-[#1A1A1A]' : 'text-[#909090] hover:text-[#555]'">
                    <span class="block lg:hidden w-4 h-[1px] transition-colors" :class="activeTab === 'security' ? 'bg-[#1A1A1A]' : 'bg-transparent group-hover:bg-[#909090]'"></span>
                    Security Protocol
                </button>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-8 border-t border-[rgba(10,10,10,0.15)] pt-4">
                    @csrf
                    <button type="submit" class="text-left py-4 text-[#909090] hover:text-[#1A1A1A] transition-colors duration-300 font-h2 text-sm uppercase tracking-widest flex items-center gap-2 group lg:pl-8">
                        <span class="material-symbols-outlined text-[14px] transform group-hover:-translate-x-1 transition-transform">logout</span>
                        Log Out
                    </button>
                </form>
            </nav>
        </div>

        <!-- MAIN PANELS -->
        <div class="flex-grow w-full lg:w-auto lg:flex-1 min-w-0 lg:pl-16 lg:border-l border-[rgba(10,10,10,0.15)] relative min-h-[60vh] grid grid-cols-1 grid-rows-1">
            
            <!-- IDENTITY TAB -->
            <div x-show="activeTab === 'identity'" 
                 x-transition:enter="transition ease-out duration-500 delay-100" 
                 x-transition:enter-start="opacity-0 translate-y-4" 
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="tab-panel w-full col-start-1 row-start-1"
                 style="display: none;">
                 
                <div class="font-mono text-[10px] tracking-[0.2em] text-[#909090] uppercase border-b border-[rgba(10,10,10,0.15)] pb-4 mb-12">
                    [ IDENTIFICATION RECORD ]
                </div>
                
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-16">
                    @csrf
                    @method('PUT')
                    
                    <!-- Avatar / ID Card -->
                    <div class="archive-grid grid grid-cols-1 md:grid-cols-3 max-w-2xl">
                        <div class="archive-cell p-4 relative group cursor-crosshair">
                            <div class="w-full aspect-square bg-[#EFEFEF] relative overflow-hidden">
                                <img id="avatar-preview" src="{{ $user->avatar_url }}" alt="Avatar" 
             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=000000&background=F3F4F6&size=256'">
        <div class="absolute inset-0 bg-[#1A1A1A]/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="absolute inset-0 bg-[#0A0A0A]/80 flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" onclick="document.getElementById('avatar-input').click()">
                                    <span class="material-symbols-outlined text-[24px] mb-2">upload</span>
                                    <span class="font-mono text-[9px] tracking-[0.2em] uppercase">Update Record</span>
                                </div>
                            </div>
                            <!-- Corner Accents -->
                            <div class="absolute top-0 left-0 w-2 h-2 border-t border-l border-[#1A1A1A] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 right-0 w-2 h-2 border-b border-r border-[#1A1A1A] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <input type="file" id="avatar-input" name="avatar" class="hidden" accept="image/*" onchange="document.getElementById('avatar-preview').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        
                        <div class="archive-cell md:col-span-2 p-6 flex flex-col justify-center gap-4">
                            <div>
                                <span class="font-mono text-[9px] tracking-[0.2em] text-[#909090] block uppercase mb-1">Status</span>
                                <span class="font-mono text-xs uppercase tracking-widest text-[#1A1A1A]">Verified Entity</span>
                            </div>
                            <div>
                                <span class="font-mono text-[9px] tracking-[0.2em] text-[#909090] block uppercase mb-1">Registered</span>
                                <span class="font-mono text-xs uppercase tracking-widest text-[#1A1A1A]">{{ $user->created_at->format('Y.m.d') }}</span>
                            </div>
                            <div>
                                <span class="font-mono text-[9px] tracking-[0.2em] text-[#909090] block uppercase mb-1">Clearance</span>
                                <span class="font-mono text-xs uppercase tracking-widest text-[#1A1A1A]">{{ $user->is_vip ? 'VIP ACCESS' : 'STANDARD' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Editorial Form Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-24 max-w-4xl">
                        <div class="flex flex-col gap-2 relative input-wrapper border-b border-[rgba(10,10,10,0.15)] pb-2">
                            <div class="flex justify-between">
                                <label for="name" class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase">Legal Name</label>
                                <span class="font-mono text-[9px] tracking-[0.2em] text-[#1A1A1A]">[ EDITABLE ]</span>
                            </div>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-transparent border-none focus:ring-0 p-0 font-h2 text-xl md:text-2xl uppercase tracking-widest text-[#1A1A1A]" required>
                        </div>
                        
                        <div class="flex flex-col gap-2 relative input-wrapper border-b border-[rgba(10,10,10,0.15)] pb-2">
                            <div class="flex justify-between">
                                <label for="email" class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase">Comms Channel (Email)</label>
                                <span class="font-mono text-[9px] tracking-[0.2em] text-[#1A1A1A]">[ VERIFIED ]</span>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-transparent border-none focus:ring-0 p-0 font-h2 text-xl md:text-2xl uppercase tracking-widest text-[#1A1A1A]" required>
                        </div>
                    </div>

                    <div class="pt-8">
                        <button type="submit" class="font-mono text-xs tracking-[0.2em] uppercase text-[#1A1A1A] px-8 py-4 border border-[#1A1A1A] hover:bg-[#1A1A1A] hover:text-white transition-colors duration-300 flex items-center gap-4 group">
                            UPDATE RECORD
                            <span class="material-symbols-outlined text-[14px] transform group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- ACTIVE ACQUISITIONS TAB -->
            <div x-show="activeTab === 'active'" 
                 x-transition:enter="transition ease-out duration-500 delay-100" 
                 x-transition:enter-start="opacity-0 translate-y-4" 
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="tab-panel w-full col-start-1 row-start-1"
                 style="display: none;">
                 
                <div class="font-mono text-[10px] tracking-[0.2em] text-[#909090] uppercase border-b border-[rgba(10,10,10,0.15)] pb-4 mb-12">
                    [ ACTIVE ACQUISITIONS ]
                </div>
                
                @if($activeOrders->isEmpty())
                    <div class="border border-[rgba(10,10,10,0.15)] p-16 md:p-24 flex flex-col max-w-2xl">
                        <h3 class="font-h1 text-4xl uppercase title-display text-[#1A1A1A] mb-4">No Active Records</h3>
                        <p class="font-body-md text-sm text-[#555] max-w-[40ch] leading-relaxed mb-12">
                            Your active acquisition queue is empty. Every archive begins with a first discovery.
                        </p>
                        <a href="{{ route('products.index') }}" class="w-max font-mono text-[10px] tracking-[0.2em] uppercase text-[#1A1A1A] border-b border-[#1A1A1A] pb-1 hover:text-[#909090] hover:border-[#909090] transition-colors">
                            Explore Collections
                        </a>
                    </div>
                @else
                    <div class="flex flex-col gap-12">
                        @foreach($activeOrders as $order)
                            @include('profile.partials.order-card', ['order' => $order])
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- ARCHIVE HISTORY TAB -->
            <div x-show="activeTab === 'history'" 
                 x-transition:enter="transition ease-out duration-500 delay-100" 
                 x-transition:enter-start="opacity-0 translate-y-4" 
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="tab-panel w-full col-start-1 row-start-1"
                 style="display: none;">
                 
                <div class="font-mono text-[10px] tracking-[0.2em] text-[#909090] uppercase border-b border-[rgba(10,10,10,0.15)] pb-4 mb-12">
                    [ ARCHIVE HISTORY ]
                </div>
                
                @if($pastOrders->isEmpty())
                    <div class="border border-[rgba(10,10,10,0.15)] p-16 md:p-24 flex flex-col max-w-2xl">
                        <h3 class="font-h1 text-4xl uppercase title-display text-[#1A1A1A] mb-4">Empty History</h3>
                        <p class="font-body-md text-sm text-[#555] max-w-[40ch] leading-relaxed">
                            No past acquisitions found in this archive. Completed or cancelled records will appear here as a timeline.
                        </p>
                    </div>
                @else
                    @php
                        $groupedOrders = $pastOrders->groupBy(function($order) {
                            return \Carbon\Carbon::parse($order->created_at)->format('Y');
                        });
                    @endphp
                    
                    <div class="flex flex-col">
                        @foreach($groupedOrders as $year => $ordersInYear)
                            <div class="mb-16">
                                <!-- Year Divider -->
                                <div class="flex items-center gap-6 mb-8">
                                    <span class="font-h1 text-5xl text-[#1A1A1A] opacity-20">{{ $year }}</span>
                                    <div class="h-[1px] bg-[rgba(10,10,10,0.15)] flex-grow"></div>
                                </div>
                                
                                <div class="w-full">
                                    <!-- Ledger Header -->
                                    <div class="hidden md:grid grid-cols-12 gap-4 pb-4 border-b border-[rgba(10,10,10,0.15)] font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase mb-4">
                                        <div class="col-span-3">Reference</div>
                                        <div class="col-span-2">Date</div>
                                        <div class="col-span-4">Asset</div>
                                        <div class="col-span-2 text-right">Valuation</div>
                                        <div class="col-span-1 text-right">Status</div>
                                    </div>
                                    
                                    <!-- Ledger Rows -->
                                    <div class="flex flex-col">
                                        @foreach($ordersInYear as $order)
                                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 py-6 border-b border-[rgba(10,10,10,0.08)] items-center hover:bg-[#FAFAFA] transition-colors group cursor-crosshair"
                                                 @click="openInvoice({
                                                     id: '{{ $order->id }}',
                                                     ref: '{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}',
                                                     date: '{{ \Carbon\Carbon::parse($order->created_at)->format('Y.m.d') }}',
                                                     total: '{{ number_format($order->total, 0) }}',
                                                     status: '{{ $order->status }}',
                                                     items: {{ json_encode($order->items->map(function($item) {
                                                         return [
                                                             'name' => $item->product->name,
                                                             'price' => number_format($item->price_at_purchase, 0),
                                                             'qty' => $item->quantity
                                                         ];
                                                     })) }}
                                                 })">
                                                 
                                                <div class="col-span-12 md:col-span-3">
                                                    <span class="md:hidden font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase mr-2">Ref:</span>
                                                    <span class="font-mono text-sm md:text-xs uppercase tracking-widest text-[#1A1A1A]">{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}</span>
                                                </div>
                                                
                                                <div class="col-span-12 md:col-span-2">
                                                    <span class="md:hidden font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase mr-2">Date:</span>
                                                    <span class="font-mono text-sm md:text-xs uppercase tracking-widest text-[#555]">{{ \Carbon\Carbon::parse($order->created_at)->format('Y.m.d') }}</span>
                                                </div>
                                                
                                                <div class="col-span-12 md:col-span-4">
                                                    <span class="md:hidden font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase mr-2">Asset:</span>
                                                    <div class="font-h2 text-sm uppercase tracking-widest text-[#1A1A1A] truncate group-hover:text-primary transition-colors">
                                                        {{ $order->items->first()?->product->name ?? 'UNKNOWN ASSET' }}
                                                        @if($order->items->count() > 1)
                                                            <span class="text-[#909090] font-mono text-[9px] ml-1">[+{{ $order->items->count() - 1 }}]</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="col-span-12 md:col-span-2 md:text-right">
                                                    <span class="md:hidden font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase mr-2">Valuation:</span>
                                                    <span class="font-mono text-sm md:text-xs tracking-widest text-[#1A1A1A]">${{ number_format($order->total, 0) }}</span>
                                                </div>
                                                
                                                <div class="col-span-12 md:col-span-1 flex items-center md:justify-end gap-2">
                                                    @php
                                                        $dotColor = 'bg-[#1A1A1A]';
                                                        if (in_array($order->status, ['cancelled', 'pending_cancel'])) {
                                                            $dotColor = 'bg-red-600';
                                                        }
                                                    @endphp
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                                </div>
                                                
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- SECURITY PROTOCOL TAB -->
            <div x-show="activeTab === 'security'" 
                 x-transition:enter="transition ease-out duration-500 delay-100" 
                 x-transition:enter-start="opacity-0 translate-y-4" 
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="tab-panel w-full col-start-1 row-start-1"
                 style="display: none;">
                 
                <div class="font-mono text-[10px] tracking-[0.2em] text-[#909090] uppercase border-b border-[rgba(10,10,10,0.15)] pb-4 mb-12">
                    [ SECURITY PROTOCOL ]
                </div>
                
                <form action="{{ route('profile.update') }}" method="POST" class="w-full flex flex-col gap-12 max-w-4xl">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-24">
                        <div class="flex flex-col gap-2 relative input-wrapper border-b border-[rgba(10,10,10,0.15)] pb-2">
                            <div class="flex justify-between">
                                <label for="password" class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase">New Access Key (Password)</label>
                            </div>
                            <input type="password" id="password" name="password" class="w-full bg-transparent border-none focus:ring-0 p-0 font-h2 text-xl md:text-2xl tracking-widest text-[#1A1A1A] py-1">
                        </div>
                        
                        <div class="flex flex-col gap-2 relative input-wrapper border-b border-[rgba(10,10,10,0.15)] pb-2">
                            <div class="flex justify-between">
                                <label for="password_confirmation" class="font-mono text-[9px] tracking-[0.2em] text-[#909090] uppercase">Verify Access Key</label>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full bg-transparent border-none focus:ring-0 p-0 font-h2 text-xl md:text-2xl tracking-widest text-[#1A1A1A] py-1">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="font-mono text-xs tracking-[0.2em] uppercase text-[#1A1A1A] px-8 py-4 border border-[#1A1A1A] hover:bg-[#1A1A1A] hover:text-white transition-colors duration-300 flex items-center gap-4 group">
                            UPDATE SECURITY
                            <span class="material-symbols-outlined text-[14px] transform group-hover:translate-x-1 transition-transform">lock</span>
                        </button>
                    </div>
                </form>

                <!-- DANGER ZONE -->
                <div class="w-full mt-32 border-t border-[rgba(10,10,10,0.15)] pt-16 max-w-2xl" x-data="{ termModalOpen: false }">
                    <h3 class="font-h1 text-2xl uppercase title-display text-[#1A1A1A] mb-2">Archive Termination</h3>
                    <p class="font-body-md text-sm text-[#555] max-w-[50ch] mb-8">
                        Permanently sever your connection to the Clementine archive. This action destroys all associated identity records and cannot be reversed.
                    </p>

                    @error('delete_account')
                        <div class="border border-red-900 bg-[#111] p-4 font-mono text-[10px] tracking-widest uppercase text-red-500 mb-8">
                            [ SYSTEM ERROR ] {{ $message }}
                        </div>
                    @enderror

                    @error('password')
                        <div class="border border-red-900 bg-[#111] p-4 font-mono text-[10px] tracking-widest uppercase text-red-500 mb-8">
                            [ VERIFICATION FAILED ] {{ $message }}
                        </div>
                    @enderror

                    <button type="button" @click="termModalOpen = true" class="font-mono text-xs tracking-[0.2em] uppercase text-red-600 px-8 py-4 border border-red-600 hover:bg-red-600 hover:text-white transition-colors duration-300">
                        INITIATE TERMINATION
                    </button>

                    <!-- Termination Modal (High-end Warning System) -->
                    <div x-cloak x-show="termModalOpen" class="fixed inset-0 z-[100] flex justify-center items-center p-4">
                        <div x-show="termModalOpen" x-transition.opacity class="absolute inset-0 bg-[#0A0A0A]/95 backdrop-blur-md danger-modal-bg" @click="termModalOpen = false"></div>
                        <div x-show="termModalOpen" 
                             x-transition:enter="transition ease-out duration-400"
                             x-transition:enter-start="opacity-0 scale-[0.98] translate-y-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-[0.98] translate-y-4"
                             class="relative bg-[#050505] w-full max-w-[600px] border border-red-900 p-12 flex flex-col gap-8 text-white z-10 shadow-[0_0_50px_rgba(220,38,38,0.15)]">
                            
                            <div class="flex items-center gap-4 text-red-500 border-b border-red-900/50 pb-6">
                                <span class="material-symbols-outlined text-[32px]">warning</span>
                                <h3 class="font-h1 text-3xl uppercase tracking-widest m-0 leading-none">Warning</h3>
                            </div>

                            <p class="text-sm font-mono tracking-widest text-[#909090] leading-relaxed uppercase">
                                You are about to permanently delete this archive. All records, VIP access, and identity data will be wiped from the system. This cannot be undone.
                            </p>

                            <form action="{{ route('profile.destroy') }}" method="POST" class="flex flex-col gap-8">
                                @csrf
                                @method('DELETE')

                                @if(auth()->user()->password)
                                <div class="flex flex-col gap-2 relative">
                                    <label for="delete_password" class="font-mono text-[9px] tracking-[0.2em] text-red-500 uppercase">Verify Identity (Password)</label>
                                    <input type="password" id="delete_password" name="password" class="w-full bg-transparent border-b border-red-900/50 focus:border-red-500 focus:ring-0 p-2 font-h2 text-xl tracking-widest text-white" required>
                                </div>
                                @endif

                                <div class="flex gap-4 mt-4">
                                    <button type="button" @click="termModalOpen = false" class="flex-1 px-6 py-4 border border-[#333] text-xs font-mono font-bold uppercase tracking-widest hover:bg-[#111] transition-colors">
                                        ABORT
                                    </button>
                                    <button type="submit" class="flex-1 px-6 py-4 bg-red-700 text-white text-xs font-mono font-bold uppercase tracking-widest hover:bg-red-600 transition-colors border border-red-700 shadow-[0_0_20px_rgba(220,38,38,0.3)]">
                                        CONFIRM TERMINATION
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    <!-- Invoice Drawer (Will be rendered dynamically, passing open logic via Alpine event) -->
    <template x-teleport="body">
        @include('profile.partials.invoice-drawer')
    </template>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('archiveProfile', () => ({
            activeTab: 'identity',
            invoiceOpen: false,
            selectedOrder: null,
            
            switchTab(tab, event) {
                if (this.activeTab === tab) return;
                this.activeTab = tab;
                
                // Move GSAP Indicator if on desktop
                if(window.innerWidth >= 1024) {
                    const btn = event.currentTarget;
                    const navContainer = btn.closest('nav');
                    const indicator = document.getElementById('nav-indicator');
                    
                    // Calculate relative Y position
                    const btnRect = btn.getBoundingClientRect();
                    const navRect = navContainer.getBoundingClientRect();
                    const relativeY = btnRect.top - navRect.top + (btnRect.height / 2);
                    
                    gsap.to(indicator, {
                        y: relativeY - 24, // 24 is the initial top offset in style
                        duration: 0.4,
                        ease: 'power3.out'
                    });
                }
            },
            
            openInvoice(orderData) {
                this.selectedOrder = orderData;
                this.invoiceOpen = true;
                
                // Freeze body scroll (Lenis handling if global, otherwise fallback to overflow-hidden)
                document.documentElement.classList.add('overflow-hidden');
            },
            
            closeInvoice() {
                this.invoiceOpen = false;
                setTimeout(() => {
                    this.selectedOrder = null;
                    document.documentElement.classList.remove('overflow-hidden');
                }, 400); // Wait for transition
            },
            
            initGSAP() {
                if (typeof gsap === 'undefined') return;
                
                // Initial Entry Timeline
                const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
                
                // Draw hero grid lines (simulated by revealing the grid container)
                gsap.set('#hero-grid', { opacity: 0 });
                gsap.set('.hero-reveal', { opacity: 0, y: 10, clipPath: 'inset(100% 0 0 0)' });
                
                tl.to('#hero-grid', { opacity: 1, duration: 0.1 })
                  .to('#hero-bg-text', { opacity: 0.02, duration: 1.5, ease: 'power2.inOut' }, 0)
                  .to('.hero-reveal', { 
                      opacity: 1, 
                      y: 0, 
                      clipPath: 'inset(0% 0 0 0)',
                      duration: 0.6, 
                      stagger: 0.05 
                  }, 0.2);
                  
                // Hero Parallax
                gsap.registerPlugin(ScrollTrigger);
                gsap.to('#hero-bg-text', {
                    yPercent: -30,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: '.archive-hero',
                        start: 'top top',
                        end: 'bottom top',
                        scrub: true
                    }
                });
                
                // Shrinking Name on scroll
                gsap.to('#hero-name', {
                    scale: 0.85,
                    transformOrigin: 'left bottom',
                    opacity: 0.5,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: '.archive-hero',
                        start: 'top top',
                        end: 'bottom top',
                        scrub: true
                    }
                });
            }
        }));
    });
</script>

@endsection
