@extends('layouts.app')

@section('title', 'Smart Watch Advisor - Clementine')

@section('content')

<div class="w-full bg-background border-b border-primary">
    
    <div class="w-full max-w-5xl mx-auto border-l border-r border-primary bg-surface-container-lowest min-h-screen flex flex-col">
        
        <div class="px-6 md:px-12 py-24 md:py-32">
            <div class="mb-20">
                <h1 class="font-h1 text-[50px] md:text-[80px] text-primary uppercase tracking-tighter leading-none mb-6">
                    SMART ADVISOR
                </h1>
                <p class="font-body-md text-lg text-primary uppercase tracking-widest max-w-2xl leading-relaxed">
                    Let our intelligent system find the perfect watch for you based on your unique preferences.
                </p>
            </div>

            <form action="{{ route('advisor.process') }}" method="GET" class="space-y-16" x-data="{
                budget: 1000
            }">
                <!-- 1. Budget -->
                <div class="border-t border-primary pt-8">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                        <label class="font-headline-md text-2xl md:text-3xl uppercase text-primary">1. Maximum Budget</label>
                        <div class="font-body-md text-2xl text-primary">
                            $<span x-text="budget"></span>
                        </div>
                    </div>
                    <input type="range" name="budget" min="100" max="10000" step="100" x-model="budget" class="w-full appearance-none h-1 bg-primary/20 accent-primary cursor-pointer">
                </div>

                <!-- 2. Gender -->
                <div class="border-t border-primary pt-8">
                    <label class="font-headline-md text-2xl md:text-3xl uppercase text-primary block mb-8">2. Who is this for?</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border border-primary">
                        @foreach(['men' => 'Men', 'women' => 'Women', 'unisex' => 'Unisex / Anyone'] as $val => $label)
                            <label class="relative cursor-pointer border-b border-primary md:border-b-0 md:border-r last:border-b-0 md:last:border-r-0 group">
                                <input type="radio" name="gender" value="{{ $val }}" class="peer sr-only" {{ $val == 'unisex' ? 'checked' : '' }}>
                                <div class="w-full py-6 px-4 text-center font-body-sm text-sm uppercase tracking-widest text-primary peer-checked:bg-primary peer-checked:text-on-primary hover:bg-primary/5 transition-colors">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Material -->
                <div class="border-t border-primary pt-8">
                    <label class="font-headline-md text-2xl md:text-3xl uppercase text-primary block mb-8">3. Preferred Material</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-0 border border-primary">
                        @foreach(['Stainless Steel', 'Leather', 'Rubber', 'Titanium'] as $mat)
                            <label class="relative cursor-pointer border-b border-primary md:border-b-0 md:border-r last:border-b-0 md:last:border-r-0 [&:nth-child(even)]:border-r-0 md:[&:nth-child(even)]:border-r">
                                <input type="radio" name="material" value="{{ $mat }}" class="peer sr-only">
                                <div class="w-full py-6 px-4 text-center font-body-sm text-sm uppercase tracking-widest text-primary peer-checked:bg-primary peer-checked:text-on-primary hover:bg-primary/5 transition-colors h-full flex items-center justify-center">
                                    {{ $mat }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 4. Movement -->
                <div class="border-t border-primary pt-8">
                    <label class="font-headline-md text-2xl md:text-3xl uppercase text-primary block mb-8">4. Movement Type</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border border-primary">
                        @foreach(['Automatic', 'Quartz'] as $mov)
                            <label class="relative cursor-pointer border-b border-primary md:border-b-0 md:border-r last:border-b-0 md:last:border-r-0 group">
                                <input type="radio" name="movement" value="{{ $mov }}" class="peer sr-only">
                                <div class="w-full py-6 px-4 text-center font-body-sm text-sm uppercase tracking-widest text-primary peer-checked:bg-primary peer-checked:text-on-primary hover:bg-primary/5 transition-colors">
                                    {{ $mov }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-primary pt-12">
                    <button type="submit" class="w-full bg-primary text-on-primary py-6 font-headline-md text-xl md:text-2xl uppercase tracking-widest hover:bg-background hover:text-primary border border-primary transition-colors flex items-center justify-between px-8 group">
                        <span>DISCOVER MY MATCH</span>
                        <span class="material-symbols-outlined group-hover:translate-x-2 transition-transform">arrow_forward</span>
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</div>

@endsection
