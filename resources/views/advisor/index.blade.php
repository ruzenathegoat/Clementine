@extends('layouts.app')

@section('title', 'Smart Watch Advisor - Clementine')

@section('content')

<div class="w-full bg-background min-h-screen flex flex-col pt-[80px]">
    
    <!-- Header Section -->
    <header class="w-full px-lg md:px-2xl py-2xl md:py-4xl border-b border-primary bg-background flex flex-col md:flex-row md:items-end justify-between gap-xl relative overflow-hidden" id="advisor-header">
        <div class="relative z-10 w-full md:w-3/4">
            <h1 class="advisor-headline font-h1 text-[clamp(4rem,8vw,6rem)] text-primary m-0 p-0 leading-[0.85] tracking-tight uppercase" style="font-weight: 400; text-wrap: balance;">
                <span class="advisor-word inline-block" style="opacity: 0; letter-spacing: 0.15em;">SMART</span>
                <span class="advisor-word inline-block" style="opacity: 0; letter-spacing: 0.15em;">ADVISOR</span>
            </h1>
            <div class="advisor-subhead mt-lg overflow-hidden">
                <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-primary/60 m-0" style="opacity: 0; transform: translateY(100%);">
                    Let our intelligent algorithm compute the perfect timepiece match based on your precise constraints.
                </p>
            </div>
        </div>
    </header>

    <div class="w-full flex-1 flex justify-center py-2xl px-lg md:px-2xl bg-background advisor-fade-up" style="opacity: 0; transform: translateY(20px);">
        
        <form action="{{ route('advisor.process') }}" method="GET" class="w-full max-w-4xl space-y-2xl" x-data="{
            budget: 1000
        }">
            <!-- 1. Budget -->
            <div class="border-t border-primary pt-xl flex flex-col md:flex-row gap-xl items-start md:items-center justify-between">
                <div class="flex flex-col gap-2 w-full md:w-1/3">
                    <span class="font-mono text-[10px] uppercase tracking-[0.2em] text-primary/50">PARAMETER 01</span>
                    <label class="font-h2 text-2xl md:text-3xl uppercase text-primary leading-none">MAXIMUM BUDGET</label>
                </div>
                
                <div class="w-full md:w-2/3 flex flex-col gap-lg">
                    <div class="font-mono text-[24px] text-primary tracking-wide text-right">
                        $<span x-text="budget.toLocaleString()"></span>
                    </div>
                    <div class="relative w-full h-[2px] bg-primary/20">
                        <!-- Custom styled range input -->
                        <input type="range" name="budget" min="100" max="10000" step="100" x-model="budget" 
                               class="absolute inset-0 w-full appearance-none bg-transparent opacity-0 cursor-pointer z-20"
                               style="height: 24px; top: -11px;">
                        
                        <!-- Visual Track -->
                        <div class="absolute left-0 top-0 h-full bg-primary z-0" :style="`width: ${(budget - 100) / (10000 - 100) * 100}%`"></div>
                        <!-- Visual Thumb -->
                        <div class="absolute top-1/2 -translate-y-1/2 w-[4px] h-[24px] bg-primary z-10 transition-transform pointer-events-none" :style="`left: ${(budget - 100) / (10000 - 100) * 100}%`"></div>
                    </div>
                    <div class="flex justify-between font-mono text-[10px] uppercase tracking-[0.2em] text-primary/40 mt-1">
                        <span>$100</span>
                        <span>$10,000+</span>
                    </div>
                </div>
            </div>

            <!-- 2. Gender -->
            <div class="border-t border-primary pt-xl flex flex-col md:flex-row gap-xl items-start md:items-center justify-between">
                <div class="flex flex-col gap-2 w-full md:w-1/3 shrink-0">
                    <span class="font-mono text-[10px] uppercase tracking-[0.2em] text-primary/50">PARAMETER 02</span>
                    <label class="font-h2 text-2xl md:text-3xl uppercase text-primary leading-none">INTENDED WRIST</label>
                </div>
                
                <div class="w-full md:w-2/3 flex flex-wrap gap-md">
                    @foreach(['men' => 'Men', 'women' => 'Women', 'unisex' => 'Any'] as $val => $label)
                        <label class="relative cursor-pointer group flex-1 min-w-[120px]">
                            <input type="radio" name="gender" value="{{ $val }}" class="peer sr-only" {{ $val == 'unisex' ? 'checked' : '' }}>
                            <div class="w-full py-4 px-6 border border-primary bg-background text-center font-mono text-[11px] uppercase tracking-[0.1em] text-primary peer-checked:bg-primary peer-checked:text-background hover:bg-primary/5 transition-colors active:scale-[0.98] duration-150">
                                {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 3. Material -->
            <div class="border-t border-primary pt-xl flex flex-col md:flex-row gap-xl items-start md:items-center justify-between">
                <div class="flex flex-col gap-2 w-full md:w-1/3 shrink-0">
                    <span class="font-mono text-[10px] uppercase tracking-[0.2em] text-primary/50">PARAMETER 03</span>
                    <label class="font-h2 text-2xl md:text-3xl uppercase text-primary leading-none">CASE MATERIAL</label>
                </div>
                
                <div class="w-full md:w-2/3 grid grid-cols-2 gap-md">
                    @foreach(['Stainless Steel', 'Leather', 'Rubber', 'Titanium'] as $mat)
                        <label class="relative cursor-pointer group w-full">
                            <input type="radio" name="material" value="{{ $mat }}" class="peer sr-only">
                            <div class="w-full py-4 px-4 border border-primary bg-background text-center font-mono text-[11px] uppercase tracking-[0.1em] text-primary peer-checked:bg-primary peer-checked:text-background hover:bg-primary/5 transition-colors active:scale-[0.98] duration-150">
                                {{ $mat }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 4. Movement -->
            <div class="border-t border-primary pt-xl flex flex-col md:flex-row gap-xl items-start md:items-center justify-between">
                <div class="flex flex-col gap-2 w-full md:w-1/3 shrink-0">
                    <span class="font-mono text-[10px] uppercase tracking-[0.2em] text-primary/50">PARAMETER 04</span>
                    <label class="font-h2 text-2xl md:text-3xl uppercase text-primary leading-none">CALIBER TYPE</label>
                </div>
                
                <div class="w-full md:w-2/3 flex flex-wrap gap-md">
                    @foreach(['Automatic', 'Quartz'] as $mov)
                        <label class="relative cursor-pointer group flex-1 min-w-[150px]">
                            <input type="radio" name="movement" value="{{ $mov }}" class="peer sr-only">
                            <div class="w-full py-4 px-6 border border-primary bg-background text-center font-mono text-[11px] uppercase tracking-[0.1em] text-primary peer-checked:bg-primary peer-checked:text-background hover:bg-primary/5 transition-colors active:scale-[0.98] duration-150">
                                {{ $mov }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-primary pt-xl mt-xl">
                <button type="submit" class="w-full bg-primary text-background hover:bg-background hover:text-primary border border-primary py-lg font-mono text-[12px] uppercase tracking-[0.2em] transition-colors flex items-center justify-center gap-4 group active:scale-[0.99] duration-150">
                    <span>EXECUTE ALGORITHM</span>
                    <div class="w-2 h-2 bg-background group-hover:bg-primary transition-colors animate-pulse"></div>
                </button>
            </div>
        </form>
        
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Check if GSAP is available (it should be loaded in app.blade.php)
    if (typeof gsap !== 'undefined') {
        const tl = gsap.timeline();
        
        tl.fromTo('.advisor-word', 
            { opacity: 0, y: 40, scale: 0.95 },
            { opacity: 1, y: 0, scale: 1, duration: 0.8, stagger: 0.05, ease: "power3.out" }
        )
        .to('.advisor-word', 
            { letterSpacing: "0em", duration: 1.2, ease: "power2.inOut" }, 
            "-=0.6"
        )
        .fromTo('.advisor-subhead p',
            { opacity: 0, y: '100%' },
            { opacity: 1, y: '0%', duration: 0.6, ease: "power2.out" },
            "-=0.8"
        )
        .fromTo('.advisor-fade-up',
            { opacity: 0, y: 20 },
            { opacity: 1, y: 0, duration: 0.6, ease: "power2.out" },
            "-=0.4"
        );
    }
});
</script>

@endsection
