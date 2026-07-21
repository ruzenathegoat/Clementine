@extends('layouts.app')

@section('title', 'Smart Watch Advisor - Clementine')

@section('content')

<div class="min-h-screen bg-background border-b border-primary relative overflow-hidden flex items-center justify-center py-24 px-4">
    <!-- Decorative Lines -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 left-1/4 w-[1px] h-full bg-primary/10"></div>
        <div class="absolute top-0 left-1/2 w-[1px] h-full bg-primary/10"></div>
        <div class="absolute top-0 left-3/4 w-[1px] h-full bg-primary/10"></div>
    </div>

    <div class="w-full max-w-3xl bg-surface-container-lowest border border-primary relative z-10 p-8 md:p-12 shadow-2xl">
        
        <div class="text-center mb-12">
            <h1 class="font-h1 text-[40px] md:text-[60px] text-primary uppercase tracking-tighter leading-none mb-4">
                SMART ADVISOR
            </h1>
            <p class="font-body-md text-base text-[#787774] uppercase tracking-widest max-w-lg mx-auto">
                Let our intelligent system find the perfect watch for you based on your unique preferences.
            </p>
        </div>

        <form action="{{ route('advisor.process') }}" method="GET" class="space-y-10" x-data="{
            budget: 1000,
            updateBudget(val) {
                this.budget = val;
            }
        }">
            <!-- Budget -->
            <div>
                <div class="flex justify-between items-end mb-4">
                    <label class="font-headline-md text-2xl uppercase text-primary">1. Maximum Budget</label>
                    <div class="font-body-md text-xl text-primary font-bold">
                        $<span x-text="budget"></span>
                    </div>
                </div>
                <input type="range" name="budget" min="100" max="10000" step="100" x-model="budget" class="w-full">
            </div>

            <!-- Gender -->
            <div>
                <label class="font-headline-md text-2xl uppercase text-primary block mb-4">2. Who is this for?</label>
                <div class="grid grid-cols-3 gap-4">
                    @foreach(['men' => 'Men', 'women' => 'Women', 'unisex' => 'Unisex / Anyone'] as $val => $label)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="gender" value="{{ $val }}" class="peer sr-only" {{ $val == 'unisex' ? 'checked' : '' }}>
                            <div class="w-full border-2 border-primary py-3 px-4 text-center font-body-sm uppercase tracking-widest text-primary peer-checked:bg-primary peer-checked:text-on-primary hover:bg-surface-container-highest transition-colors">
                                {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Material -->
            <div>
                <label class="font-headline-md text-2xl uppercase text-primary block mb-4">3. Preferred Material</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach(['Stainless Steel', 'Leather', 'Rubber', 'Titanium'] as $mat)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="material" value="{{ $mat }}" class="peer sr-only">
                            <div class="w-full border-2 border-primary py-3 px-4 text-center font-body-sm uppercase tracking-widest text-primary peer-checked:bg-primary peer-checked:text-on-primary hover:bg-surface-container-highest transition-colors">
                                {{ $mat }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Movement -->
            <div>
                <label class="font-headline-md text-2xl uppercase text-primary block mb-4">4. Movement Type</label>
                <div class="grid grid-cols-2 gap-4">
                    @foreach(['Automatic', 'Quartz'] as $mov)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="movement" value="{{ $mov }}" class="peer sr-only">
                            <div class="w-full border-2 border-primary py-3 px-4 text-center font-body-sm uppercase tracking-widest text-primary peer-checked:bg-primary peer-checked:text-on-primary hover:bg-surface-container-highest transition-colors">
                                {{ $mov }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-8">
                <button type="submit" class="w-full bg-primary text-on-primary py-5 font-h2 text-2xl uppercase tracking-widest hover:bg-background hover:text-primary border-2 border-primary transition-colors flex items-center justify-center gap-4 group">
                    <span>DISCOVER MY MATCH</span>
                    <span class="material-symbols-outlined group-hover:translate-x-2 transition-transform">arrow_forward</span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
