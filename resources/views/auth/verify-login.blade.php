@extends('layouts.app')

@section('title', 'Verify Login - Clementine')

@section('content')
<div class="px-lg py-xl max-w-6xl mx-auto w-full min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-2xl border border-primary bg-background p-xl md:p-[60px] text-center">
        
        <h1 class="font-h1 text-4xl md:text-5xl mb-4 uppercase tracking-tight">Security Check</h1>
        
        <p class="font-body-md text-sm text-secondary mb-xl leading-relaxed">
            We detected a login attempt from a new device or location. For your protection, we have temporarily blocked this access.
        </p>

        <div class="bg-surface-container-lowest p-md border border-primary mb-xl">
            <p class="font-label-caps text-xs tracking-wider uppercase font-bold text-primary mb-2">Action Required</p>
            <p class="font-body-md text-sm text-secondary">
                Please check your email inbox. We have sent a secure verification link to confirm this login attempt.
                The link will expire in 15 minutes.
            </p>
        </div>

        <a href="{{ route('login') }}" class="inline-block bg-primary text-on-primary font-h2 text-xl py-4 px-xl border border-primary hover:bg-background hover:text-primary transition-colors uppercase">
            Return to Login
        </a>
        
    </div>
</div>
@endsection
