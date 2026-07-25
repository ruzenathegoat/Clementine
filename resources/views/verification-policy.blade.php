@extends('layouts.app')

@section('title', 'Verification Policy — Clementine')

@section('content')
<div class="w-full bg-primary text-secondary min-h-screen pt-32 pb-24 px-6 md:px-12">
    <div class="max-w-4xl mx-auto">
        <!-- Eyebrow -->
        <div class="flex items-center gap-4 mb-12">
            <div class="h-[1px] w-12 bg-secondary/40"></div>
            <span class="font-mono text-[11px] tracking-[0.25em] uppercase text-secondary/60">Protocol 01 — Documentation</span>
        </div>

        <h1 class="font-h1 text-[clamp(2.5rem,6vw,5rem)] leading-none uppercase mb-16" style="font-weight: 500;">
            Verification Policy
        </h1>

        <div class="font-mono text-sm text-secondary/60 leading-relaxed space-y-12 max-w-3xl">
            
            <section>
                <h2 class="font-h1 text-2xl text-secondary mb-4 uppercase tracking-wide">1. The Clementine Standard</h2>
                <p class="mb-4">
                    Every mechanical timepiece in the Clementine catalog undergoes a rigorous authentication process before it is presented to our collectors. We do not rely on third-party assertions; every watch is physically inspected by our internal team of AWCI and WOSTEP certified watchmakers.
                </p>
                <p>
                    If a piece cannot be definitively authenticated down to the caliber components, it does not enter our vault.
                </p>
            </section>

            <section>
                <h2 class="font-h1 text-2xl text-secondary mb-4 uppercase tracking-wide">2. The Four-Stage Protocol</h2>
                <ul class="list-none space-y-6">
                    <li class="pl-4 border-l border-secondary/20">
                        <strong class="text-secondary block mb-1">I. Physical Inspection</strong>
                        The movement, case, dial, and hands are examined under high magnification. We cross-reference part numbers, finishing techniques, and serial date ranges against official manufacturer archives.
                    </li>
                    <li class="pl-4 border-l border-secondary/20">
                        <strong class="text-secondary block mb-1">II. Digital Certification</strong>
                        Upon successful inspection, a unique digital certificate is generated. This certificate is permanently linked to the movement's serial number and recorded in our secure, immutable ledger.
                    </li>
                    <li class="pl-4 border-l border-secondary/20">
                        <strong class="text-secondary block mb-1">III. Provenance Tagging</strong>
                        An encrypted NFC tag is embedded within the physical packaging of the timepiece. This allows the owner to instantly verify the watch's provenance using a mobile device at any time.
                    </li>
                    <li class="pl-4 border-l border-secondary/20">
                        <strong class="text-secondary block mb-1">IV. Insured Custody</strong>
                        From our vault to your hands, the timepiece remains under full insurance coverage via our partners at Lloyd's. Handover requires verifiable proof of identity.
                    </li>
                </ul>
            </section>

            <section>
                <h2 class="font-h1 text-2xl text-secondary mb-4 uppercase tracking-wide">3. Certificate Verification</h2>
                <p class="mb-4">
                    Each digital certificate is assigned a unique Serial Number (e.g., CLM-XXXX-XXXX). This serial number is provided on your physical provenance card and in your purchase confirmation email.
                </p>
                <p>
                    You may verify the authenticity and status of your certificate at any time via the verification portal on our homepage.
                </p>
            </section>

            <section>
                <h2 class="font-h1 text-2xl text-secondary mb-4 uppercase tracking-wide">4. Dispute & Return Policy</h2>
                <p>
                    In the highly unlikely event that a timepiece authenticated by Clementine is proven to contain non-original parts not disclosed at the time of sale, we offer a full buyback guarantee. This guarantee is valid for the lifetime of the original purchaser's ownership.
                </p>
            </section>

        </div>
        
        <div class="mt-24 pt-12 border-t border-secondary/20">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 font-mono text-[11px] tracking-[0.15em] uppercase text-secondary/60 no-underline transition-colors duration-300 hover:text-secondary">
                &larr; Return to Homepage
            </a>
        </div>
    </div>
</div>
@endsection
