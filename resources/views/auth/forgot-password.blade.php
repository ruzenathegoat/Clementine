@extends('layouts.app')

@section('title', 'Forgot Password - Clementine')

@section('content')
<div class="px-lg py-xl max-w-6xl mx-auto w-full min-h-[80vh] flex items-center justify-center">
    <div class="w-full flex flex-col md:flex-row border border-primary bg-background">
        
        <!-- Left Side: Branding -->
        <div class="w-full md:w-1/2 bg-primary text-on-primary p-xl md:p-[60px] flex flex-col justify-between relative overflow-hidden min-h-[400px]">
            <div class="relative z-10 leading-none">
                <x-logo class="w-16 h-16" />
            </div>
            <div class="relative z-10 mt-auto pt-[100px]">
                <p class="font-label-caps text-xs md:text-sm mb-4 tracking-widest ">RECOVER ACCESS</p>
                <h2 class="font-h1 text-[40px] md:text-[60px] leading-[0.9] tracking-tighter uppercase">
                    Regain entry to your collection.
                </h2>
            </div>
            <!-- Decorative element -->
            <div class="absolute -right-20 -top-20 opacity-10 rotate-12 pointer-events-none select-none">
                <x-logo class="w-[350px] h-[350px]" />
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full md:w-1/2 p-xl md:p-[60px] flex flex-col justify-center">
            <h1 class="font-h1 text-4xl mb-2 uppercase tracking-tight">Forgot Password?</h1>
            <p class="font-body-md text-sm text-secondary mb-xl">No problem. Just let us know your email address and we will email you a password reset link.</p>

            <form method="POST" action="{{ route('password.email') }}" class="w-full flex flex-col gap-lg">
                @csrf
                
                <div class="flex flex-col gap-xs">
                    <label for="email" class="font-label-caps font-bold text-xs uppercase">Your Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="collector@example.com" class="w-full border border-primary p-4 font-body-md text-sm focus:outline-none bg-transparent focus:ring-1 focus:ring-primary rounded-none transition-colors hover:bg-surface-container-lowest">
                </div>

                <button type="submit" class="w-full bg-primary text-on-primary font-h2 text-xl py-4 px-xl border border-primary hover:bg-background hover:text-primary transition-colors uppercase mt-2">
                    SEND RESET LINK
                </button>

                <div class="mt-lg text-center font-label-caps text-xs tracking-wider uppercase">
                    <a href="{{ route('login') }}" class=" hover:text-primary hover:underline font-bold transition-colors">Return to login</a>
                </div>
            </form>
        </div>
        
    </div>
</div>
@endsection
