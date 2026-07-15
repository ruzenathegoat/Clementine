@extends('admin.layout')

@section('title', 'System Settings')

@section('content')
<div class="space-y-10 pb-12 max-w-4xl mx-auto">
    <!-- Header Area -->
    <div class="scroll-reveal flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <div class="inline-flex items-center admin-badge bg-[#EAEAEA] text-[#787774]">Configuration</div>
            <h1 class="font-serif text-5xl md:text-6xl tracking-tight leading-none text-[#111111]">Settings</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-[#EDF3EC] border border-[#346538]/20 text-[#346538] text-sm font-medium rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Currency Preferences -->
    <div class="scroll-reveal admin-outer-shell">
        <div class="admin-inner-core p-8">
            <h2 class="font-serif text-2xl tracking-tighter text-[#111111] mb-2">Display Preferences</h2>
            <p class="text-sm text-[#787774] mb-8">Customize how data is presented in your admin dashboard. This only affects your view, not the database or storefront.</p>

            <form action="{{ route('admin.settings.currency.update') }}" method="POST">
                @csrf
                <div class="space-y-4 max-w-md">
                    <label class="block text-sm font-medium text-[#111111] mb-2">Primary Currency</label>
                    <div class="relative">
                        <select name="currency" class="w-full pl-4 pr-10 py-3 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:border-[#111111] appearance-none cursor-pointer transition-colors">
                            <option value="USD" {{ $currentCurrency === 'USD' ? 'selected' : '' }}>USD ($) - US Dollar (Default)</option>
                            <option value="IDR" {{ $currentCurrency === 'IDR' ? 'selected' : '' }}>IDR (Rp) - Indonesian Rupiah</option>
                        </select>
                        <i class="ph-light ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-[#787774] pointer-events-none"></i>
                    </div>
                    <p class="text-xs text-[#787774] mt-2">
                        Values in IDR are automatically converted from the USD base price using a static rate (1 USD = 16,000 IDR) for reporting purposes.
                    </p>
                </div>

                <div class="mt-8 pt-6 border-t border-[#EAEAEA]">
                    <button type="submit" class="admin-button-island group hover:bg-[#333333] transition-haptic active:scale-95">
                        <span>Save Settings</span>
                        <div class="admin-button-island-icon group-hover:bg-white/20 transition-colors">
                            <i class="ph-light ph-check text-white"></i>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
