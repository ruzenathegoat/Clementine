<header class="sticky top-0 w-full z-50 bg-background border-b border-border h-20 flex justify-between items-center px-lg">
    <div class="flex items-center gap-xl">
        <a href="{{ route('home') }}" class="font-display text-2xl uppercase tracking-tighter text-text-primary">
            CLEMENTINE
        </a>
        <nav class="hidden md:flex gap-lg items-center">
            <a href="{{ route('products.index', ['gender' => 'men']) }}" class="font-mono text-xs uppercase tracking-widest text-text-secondary hover:text-text-primary transition-colors">MEN</a>
            <a href="{{ route('products.index', ['gender' => 'women']) }}" class="font-mono text-xs uppercase tracking-widest text-text-secondary hover:text-text-primary transition-colors">WOMEN</a>
            <a href="{{ route('products.index', ['collection' => 'classic']) }}" class="font-mono text-xs uppercase tracking-widest text-text-secondary hover:text-text-primary transition-colors">CLASSIC</a>
            <a href="{{ route('products.index', ['collection' => 'chrono']) }}" class="font-mono text-xs uppercase tracking-widest text-text-secondary hover:text-text-primary transition-colors">CHRONO</a>
            <a href="{{ route('products.index') }}" class="font-mono text-xs uppercase tracking-widest text-text-secondary hover:text-text-primary transition-colors">NEW ARRIVALS</a>
        </nav>
    </div>
    <div class="flex items-center gap-md">
        <div class="relative hidden sm:block">
            <input type="text" placeholder="SEARCH"
                   class="bg-surface border border-border px-md py-xs text-xs font-mono focus:outline-none w-48" />
        </div>
        {{-- Auth session bridging (Supabase Auth <-> Laravel) is handled in Phase 5; static link for now --}}
        <a href="{{ route('cart.index') }}" class="p-sm hover:bg-primary hover:text-text-inverse transition-colors cursor-pointer" aria-label="Cart">
            <span class="material-symbols-outlined">shopping_bag</span>
        </a>
        <a href="{{ route('login') }}" class="p-sm hover:bg-primary hover:text-text-inverse transition-colors cursor-pointer" aria-label="Account">
            <span class="material-symbols-outlined">person</span>
        </a>
    </div>
</header>