<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') — Clementine</title>

    <!-- Fonts: Geist Sans & Editorial Serif -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons (Light) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/admin.css', 'resources/js/app.js'])
    
    <style>
        /* Map Plus Jakarta Sans to our admin-sans variable if needed */
        :root {
            --font-admin-sans: "Plus Jakarta Sans", "SF Pro Display", "Inter", sans-serif;
            --font-admin-serif: "Instrument Serif", "Playfair Display", serif;
        }
    </style>
</head>
<body class="admin-dashboard bg-[#F9F9F8] text-[#111111] min-h-[100dvh] flex flex-col md:flex-row overflow-x-hidden selection:bg-[#111111] selection:text-white">

    <!-- Mobile Header -->
    <div class="md:hidden flex items-center justify-between p-4 border-b border-[#EAEAEA] bg-white sticky top-0 z-50">
        <div class="font-serif text-2xl italic tracking-tight">Clementine</div>
        <button x-data @click="$dispatch('toggle-sidebar')" class="p-2 -mr-2 text-gray-500 hover:text-black transition-colors">
            <i class="ph-light ph-list text-2xl"></i>
        </button>
    </div>

    <!-- Sidebar -->
    <aside 
        x-data="{ open: false }" 
        @toggle-sidebar.window="open = !open"
        :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-[#F9F9F8] md:bg-transparent md:translate-x-0 transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] flex flex-col pt-8 pb-8 px-6 border-r border-[#EAEAEA]/50 md:border-none"
    >
        <!-- Logo -->
        <div class="mb-16 hidden md:block">
            <h1 class="font-serif text-3xl italic tracking-tight text-[#111111]">Clementine</h1>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-white shadow-[0_2px_8px_rgba(0,0,0,0.04)] text-black font-medium' : 'text-[#787774] hover:text-black hover:bg-white/50' }} transition-haptic">
                <i class="ph-light ph-squares-four text-xl {{ request()->routeIs('admin.dashboard') ? 'text-black' : 'text-gray-400 group-hover:text-black' }} transition-colors"></i>
                <span class="text-[0.9rem]">Overview</span>
            </a>
            
            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'inventory_manager')
            <a href="{{ route('admin.inventory.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.inventory.*') ? 'bg-white shadow-[0_2px_8px_rgba(0,0,0,0.04)] text-black font-medium' : 'text-[#787774] hover:text-black hover:bg-white/50' }} transition-haptic mt-2">
                <i class="ph-light ph-package text-xl {{ request()->routeIs('admin.inventory.*') ? 'text-black' : 'text-gray-400 group-hover:text-black' }} transition-colors"></i>
                <span class="text-[0.9rem]">Inventory</span>
            </a>
            <a href="{{ route('admin.collections.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.collections.*') ? 'bg-white shadow-[0_2px_8px_rgba(0,0,0,0.04)] text-black font-medium' : 'text-[#787774] hover:text-black hover:bg-white/50' }} transition-haptic mt-1">
                <i class="ph-light ph-folders text-xl {{ request()->routeIs('admin.collections.*') ? 'text-black' : 'text-gray-400 group-hover:text-black' }} transition-colors"></i>
                <span class="text-[0.9rem]">Collections</span>
            </a>
            @endif

            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'ops_staff' || auth()->user()->role === 'finance_manager')
            <a href="{{ route('admin.orders.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-white shadow-[0_2px_8px_rgba(0,0,0,0.04)] text-black font-medium' : 'text-[#787774] hover:text-black hover:bg-white/50' }} transition-haptic mt-2">
                <i class="ph-light ph-receipt text-xl {{ request()->routeIs('admin.orders.*') ? 'text-black' : 'text-gray-400 group-hover:text-black' }} transition-colors"></i>
                <span class="text-[0.9rem]">Orders</span>
            </a>
            @endif

            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'customer_success')
            <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-white shadow-[0_2px_8px_rgba(0,0,0,0.04)] text-black font-medium' : 'text-[#787774] hover:text-black hover:bg-white/50' }} transition-haptic mt-2">
                <i class="ph-light ph-users text-xl {{ request()->routeIs('admin.users.*') ? 'text-black' : 'text-gray-400 group-hover:text-black' }} transition-colors"></i>
                <span class="text-[0.9rem]">Customers</span>
            </a>
            @endif

            <!-- Reports -->
            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'finance_manager')
            <div class="mt-8">
                <h3 class="text-[10px] font-mono text-[#A8A7A4] uppercase tracking-[0.2em] mb-3 px-4">Reports</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.financials.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[0.9rem] transition-all duration-300
                           {{ request()->routeIs('admin.financials.*') ? 'bg-white shadow-[0_2px_8px_rgba(0,0,0,0.04)] text-black font-medium' : 'text-[#787774] hover:bg-white/50 hover:text-[#111111]' }}">
                            <i class="ph-light ph-chart-line-up text-xl {{ request()->routeIs('admin.financials.*') ? 'text-black' : 'text-gray-400 group-hover:text-black' }}"></i>
                            <span>Financials</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endif
            
            <!-- System -->
            @if(auth()->user()->role === 'super_admin')
            <div class="mt-8">
                <h3 class="text-[10px] font-mono text-[#A8A7A4] uppercase tracking-[0.2em] mb-3 px-4">System</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.settings.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[0.9rem] transition-all duration-300
                           {{ request()->routeIs('admin.settings.*') ? 'bg-white shadow-[0_2px_8px_rgba(0,0,0,0.04)] text-black font-medium' : 'text-[#787774] hover:bg-white/50 hover:text-[#111111]' }}">
                            <i class="ph-light ph-gear text-xl {{ request()->routeIs('admin.settings.*') ? 'text-black' : 'text-gray-400 group-hover:text-black' }}"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endif
        </nav>

        <!-- User Profile & Logout -->
        <div class="mt-auto pt-8">
            <div class="flex items-center gap-3 px-3 py-2 mb-4">
                <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center font-serif text-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-black truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-[#787774] truncate capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full group flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#787774] hover:text-[#9F2F2D] hover:bg-[#FDEBEC]/50 transition-haptic">
                    <i class="ph-light ph-sign-out text-xl group-hover:text-[#9F2F2D] transition-colors"></i>
                    <span class="text-[0.9rem]">Sign out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay for mobile sidebar -->
    <div x-data="{ open: false }" 
         @toggle-sidebar.window="open = !open" 
         x-show="open" 
         @click="$dispatch('toggle-sidebar')"
         class="fixed inset-0 bg-black/20 backdrop-blur-sm z-30 md:hidden" 
         style="display: none;"
         x-transition.opacity.duration.500ms>
    </div>

    <!-- Main Content -->
    <main class="flex-1 min-w-0 flex flex-col md:pl-0 pl-0">
        <div class="flex-1 w-full max-w-6xl mx-auto px-4 sm:px-6 md:px-12 py-8 md:py-16">
            @yield('content')
        </div>
    </main>

    <!-- Scroll entry animation script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0) scale(1)';
                        entry.target.style.filter = 'blur(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                root: null,
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            const elements = document.querySelectorAll('.scroll-reveal');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(16px) scale(0.99)';
                el.style.filter = 'blur(4px)';
                el.style.transition = `all 800ms cubic-bezier(0.16, 1, 0.3, 1) ${index * 80}ms`;
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
