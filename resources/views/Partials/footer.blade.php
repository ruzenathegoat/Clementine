<footer class="w-full border-t border-border mt-3xl">
    <div class="bg-primary p-lg py-2xl text-center">
        <h1 class="font-display text-[14vw] sm:text-[10vw] leading-none text-text-inverse uppercase tracking-tighter select-none">
            CLEMENTINE
        </h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 bg-background border-t border-border">
        <div class="p-lg border-r border-border">
            <h4 class="font-mono text-xs font-bold uppercase mb-md">EXPLORE</h4>
            <div class="flex flex-col gap-sm">
                <a href="#" class="font-mono text-xs text-text-secondary hover:text-text-primary">COLLECTIONS</a>
                <a href="#" class="font-mono text-xs text-text-secondary hover:text-text-primary">STORES</a>
                <a href="#" class="font-mono text-xs text-text-secondary hover:text-text-primary">HERITAGE</a>
                <a href="#" class="font-mono text-xs text-text-secondary hover:text-text-primary">MAGAZINE</a>
            </div>
        </div>
        <div class="p-lg border-r border-border">
            <h4 class="font-mono text-xs font-bold uppercase mb-md">SUPPORT</h4>
            <div class="flex flex-col gap-sm">
                <a href="#" class="font-mono text-xs text-text-secondary hover:text-text-primary">WARRANTY</a>
                <a href="#" class="font-mono text-xs text-text-secondary hover:text-text-primary">SHIPPING</a>
                <a href="#" class="font-mono text-xs text-text-secondary hover:text-text-primary">CONTACT</a>
                <a href="#" class="font-mono text-xs text-text-secondary hover:text-text-primary">RETURNS</a>
            </div>
        </div>
        <div class="p-lg border-r border-border">
            <h4 class="font-mono text-xs font-bold uppercase mb-md">LEGAL</h4>
            <div class="flex flex-col gap-sm">
                <a href="#" class="font-mono text-xs text-text-secondary hover:text-text-primary">PRIVACY</a>
                <a href="#" class="font-mono text-xs text-text-secondary hover:text-text-primary">TERMS</a>
                <a href="#" class="font-mono text-xs text-text-secondary hover:text-text-primary">ACCESSIBILITY</a>
                <a href="#" class="font-mono text-xs text-text-secondary hover:text-text-primary">COOKIES</a>
            </div>
        </div>
        <div class="p-lg bg-surface">
            <h4 class="font-mono text-xs font-bold uppercase mb-md">JOIN OUR COMMUNITY</h4>
            <p class="font-mono text-xs mb-md text-text-secondary">Be the first to hear about new limited edition releases.</p>

            @if (session('status'))
                <p class="font-mono text-xs mb-sm text-text-primary">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('newsletter.store') }}" class="flex gap-0 border border-border">
                @csrf
                <input type="email" name="email" required placeholder="EMAIL ADDRESS"
                       class="flex-grow bg-transparent border-none px-md py-xs font-mono text-xs focus:ring-0" />
                <button type="submit" class="bg-primary text-text-inverse px-lg font-mono text-xs uppercase hover:opacity-80 transition-opacity">
                    JOIN
                </button>
            </form>
            @error('email')
                <p class="font-mono text-xs mt-sm text-text-primary">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="bg-background p-lg border-t border-border flex flex-col md:flex-row justify-between items-center gap-md">
        <span class="font-mono text-xs opacity-50">© {{ date('Y') }} CLEMENTINE HOROLOGY. ALL RIGHTS RESERVED.</span>
        <div class="flex gap-lg">
            <span class="material-symbols-outlined cursor-pointer hover:opacity-60">public</span>
            <span class="material-symbols-outlined cursor-pointer hover:opacity-60">chat</span>
            <span class="material-symbols-outlined cursor-pointer hover:opacity-60">camera</span>
        </div>
    </div>
</footer>