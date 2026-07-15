@extends('layouts.app')

@section('title', 'Login - Clementine')

@section('content')
<div class="px-lg py-xl max-w-6xl mx-auto w-full min-h-[80vh] flex items-center justify-center">
    <div class="w-full flex flex-col md:flex-row border border-primary bg-background">
        
        <!-- Left Side: Branding -->
        <div class="w-full md:w-1/2 bg-primary text-on-primary p-xl md:p-[60px] flex flex-col justify-between relative overflow-hidden min-h-[400px]">
            <div class="font-h1 text-5xl uppercase relative z-10 leading-none">
                *
            </div>
            <div class="relative z-10 mt-auto pt-[100px]">
                <p class="font-label-caps text-xs md:text-sm mb-4 tracking-widest text-copper">ACCESS CLEMENTINE</p>
                <h2 class="font-h1 text-[40px] md:text-[60px] leading-[0.9] tracking-tighter uppercase">
                    Enter your personal hub for high horology.
                </h2>
            </div>
            <!-- Decorative element -->
            <div class="absolute -right-20 -top-20 text-[350px] opacity-10 rotate-12 pointer-events-none leading-none select-none">
                ⚙️
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full md:w-1/2 p-xl md:p-[60px] flex flex-col justify-center">
            <h1 class="font-h1 text-4xl mb-2 uppercase tracking-tight">Welcome Back</h1>
            <p class="font-body-md text-sm text-secondary mb-xl">Access your collection, orders, and exclusive releases anytime, anywhere.</p>

            <form method="POST" action="{{ route('login') }}" class="w-full flex flex-col gap-lg">
                @csrf
                <div style="display:none;" aria-hidden="true">
                    <label for="company_website">Company Website</label>
                    <input type="text" name="company_website" id="company_website" tabindex="-1" autocomplete="off">
                </div>
                <div class="flex flex-col gap-xs">
                    <label for="email" class="font-label-caps font-bold text-xs uppercase">Your Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="collector@example.com" class="w-full border border-primary p-4 font-body-md text-sm focus:outline-none bg-transparent focus:ring-1 focus:ring-primary rounded-none transition-colors hover:bg-surface-container-lowest">
                </div>

                <div class="flex flex-col gap-xs" x-data="{ show: false }">
                    <label for="password" class="font-label-caps font-bold text-xs uppercase">Password</label>
                    <div class="relative w-full">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required placeholder="••••••••••••" class="w-full border border-primary p-4 pr-12 font-body-md text-sm focus:outline-none bg-transparent focus:ring-1 focus:ring-primary rounded-none transition-colors hover:bg-surface-container-lowest">
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-primary hover:opacity-50 transition-opacity flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <input id="remember" type="checkbox" name="remember" class="w-4 h-4 border border-primary text-primary focus:ring-primary rounded-none bg-transparent">
                        <label for="remember" class="font-label-caps text-[10px] uppercase tracking-wider">Keep me signed in</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="font-label-caps text-[10px] uppercase text-copper hover:text-primary hover:underline transition-colors font-bold tracking-wider">Forgot Password?</a>
                </div>

                <button type="submit" class="w-full bg-primary text-on-primary font-h2 text-xl py-4 px-xl border border-primary hover:bg-background hover:text-primary transition-colors uppercase mt-2">
                    LOGIN
                </button>

                <!-- Divider -->
                <div class="flex items-center gap-4 my-2 opacity-40">
                    <div class="flex-grow border-t border-primary"></div>
                    <span class="font-label-caps text-[10px] uppercase tracking-widest">OR CONTINUE WITH</span>
                    <div class="flex-grow border-t border-primary"></div>
                </div>

                <!-- Social Buttons Placeholder -->
                <div class="flex gap-4 w-full">
                    <button type="button" class="flex-1 border border-primary p-3 flex justify-center hover:bg-primary hover:text-on-primary transition-colors text-sm font-bold font-body-md">Google</button>
                    <button type="button" class="flex-1 border border-primary p-3 flex justify-center hover:bg-primary hover:text-on-primary transition-colors text-sm font-bold font-body-md">Apple</button>
                </div>

                <div class="mt-lg text-center font-label-caps text-xs tracking-wider uppercase">
                    Don't have an account? <a href="{{ route('register') }}" class="text-copper hover:text-primary hover:underline font-bold transition-colors">Sign up</a>
                </div>
            </form>
        </div>
        
    </div>
</div>
@endsection
