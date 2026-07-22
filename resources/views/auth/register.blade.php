@extends('layouts.app')

@section('title', 'Register - Clementine')

@section('content')
<div class="px-lg py-xl max-w-6xl mx-auto w-full min-h-[80vh] flex items-center justify-center">
    <div class="w-full flex flex-col md:flex-row border border-primary bg-background">
        
        <!-- Left Side: Branding -->
        <div class="w-full md:w-1/2 bg-primary text-on-primary p-xl md:p-[60px] flex flex-col justify-between relative overflow-hidden min-h-[400px]">
            <div class="relative z-10 leading-none">
                <x-logo class="w-16 h-16" />
            </div>
            <div class="relative z-10 mt-auto pt-[100px]">
                <p class="font-label-caps text-xs md:text-sm mb-4 tracking-widest ">JOIN CLEMENTINE</p>
                <h2 class="font-h1 text-[40px] md:text-[60px] leading-[0.9] tracking-tighter uppercase">
                    Begin your journey into <span class="font-serif italic  lowercase tracking-normal">high horology</span>.
                </h2>
            </div>
            <!-- Decorative element -->
            <div class="absolute -right-20 -top-20 opacity-10 rotate-12 pointer-events-none select-none">
                <x-logo class="w-[350px] h-[350px]" />
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full md:w-1/2 p-xl md:p-[60px] flex flex-col justify-center">
            <h1 class="font-h1 text-4xl mb-2 uppercase tracking-tight">Create an account</h1>
            <p class="font-body-md text-sm text-secondary mb-xl">Gain access to exclusive collections, track your orders, and join the community.</p>

            <form method="POST" action="{{ route('register') }}" class="w-full flex flex-col gap-lg">
                @csrf
                <div style="display:none;" aria-hidden="true">
                    <label for="company_website">Company Website</label>
                    <input type="text" name="company_website" id="company_website" tabindex="-1" autocomplete="off">
                </div>
                
                <div class="flex flex-col gap-xs">
                    <label for="name" class="font-label-caps font-bold text-xs uppercase">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe" class="w-full border border-primary p-4 font-body-md text-sm focus:outline-none bg-transparent focus:ring-1 focus:ring-primary rounded-none transition-colors hover:bg-surface-container-lowest">
                </div>

                <div class="flex flex-col gap-xs">
                    <label for="email" class="font-label-caps font-bold text-xs uppercase">Your Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="collector@example.com" class="w-full border border-primary p-4 font-body-md text-sm focus:outline-none bg-transparent focus:ring-1 focus:ring-primary rounded-none transition-colors hover:bg-surface-container-lowest">
                </div>

                <div class="flex flex-col gap-xs" x-data="{ 
                    password: '',
                    showPassword: false,
                    hasLength() { return this.password.length >= 8 && this.password.length <= 12; }, 
                    hasCapital() { return /^[A-Z]/.test(this.password); }, 
                    hasNumber() { return /[0-9]/.test(this.password); },
                    hasSpecial() { return /[\W_]/.test(this.password); },
                    strength() { 
                        return (this.hasLength() ? 1 : 0) + 
                               (this.hasCapital() ? 1 : 0) + 
                               (this.hasNumber() ? 1 : 0) + 
                               (this.hasSpecial() ? 1 : 0); 
                    }
                }">
                    <label for="password" class="font-label-caps font-bold text-xs uppercase">Password</label>
                    <div class="relative w-full">
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" x-model="password" required placeholder="••••••••••••" class="w-full border border-primary p-4 pr-12 font-body-md text-sm focus:outline-none bg-transparent focus:ring-1 focus:ring-primary rounded-none transition-colors hover:bg-surface-container-lowest">
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-primary hover:opacity-50 transition-opacity flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]" x-text="showPassword ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                    
                    <!-- Password Strength Bar -->
                    <div class="w-full h-1 mt-1 bg-surface-container-lowest border border-primary flex">
                        <div class="h-full transition-all duration-300" 
                             :style="`width: ${strength() * 25}%; background-color: ${strength() === 1 ? '#ff0000' : strength() === 2 || strength() === 3 ? '#ffcc00' : strength() === 4 ? '#00cc00' : 'transparent'};`">
                        </div>
                    </div>

                    <!-- Password Strength Indicators -->
                    <div class="grid grid-cols-2 gap-3 mt-3">
                        <!-- 8-12 chars -->
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 border border-primary flex items-center justify-center bg-transparent">
                                <svg class="w-3 h-3 text-primary transition-opacity duration-200" :class="hasLength() ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-label-caps text-[10px] tracking-wider uppercase text-primary transition-all duration-200" :class="hasLength() ? 'font-bold' : 'opacity-70'">8-12 chars</span>
                        </div>
                        <!-- First capital -->
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 border border-primary flex items-center justify-center bg-transparent">
                                <svg class="w-3 h-3 text-primary transition-opacity duration-200" :class="hasCapital() ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-label-caps text-[10px] tracking-wider uppercase text-primary transition-all duration-200" :class="hasCapital() ? 'font-bold' : 'opacity-70'">First capital</span>
                        </div>
                        <!-- Number -->
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 border border-primary flex items-center justify-center bg-transparent">
                                <svg class="w-3 h-3 text-primary transition-opacity duration-200" :class="hasNumber() ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-label-caps text-[10px] tracking-wider uppercase text-primary transition-all duration-200" :class="hasNumber() ? 'font-bold' : 'opacity-70'">Use number</span>
                        </div>
                        <!-- Special symbol -->
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 border border-primary flex items-center justify-center bg-transparent">
                                <svg class="w-3 h-3 text-primary transition-opacity duration-200" :class="hasSpecial() ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-label-caps text-[10px] tracking-wider uppercase text-primary transition-all duration-200" :class="hasSpecial() ? 'font-bold' : 'opacity-70'">Special symbol</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-xs" x-data="{ show: false }">
                    <label for="password_confirmation" class="font-label-caps font-bold text-xs uppercase">Confirm Password</label>
                    <div class="relative w-full">
                        <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required placeholder="••••••••••••" class="w-full border border-primary p-4 pr-12 font-body-md text-sm focus:outline-none bg-transparent focus:ring-1 focus:ring-primary rounded-none transition-colors hover:bg-surface-container-lowest">
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-primary hover:opacity-50 transition-opacity flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-on-primary font-h2 text-xl py-4 px-xl border border-primary hover:bg-background hover:text-primary transition-colors uppercase mt-2">
                    Create Account
                </button>

                <!-- Divider -->
                <div class="flex items-center gap-4 my-2 opacity-40">
                    <div class="flex-grow border-t border-primary"></div>
                    <span class="font-label-caps text-[10px] uppercase tracking-widest">OR CONTINUE WITH</span>
                    <div class="flex-grow border-t border-primary"></div>
                </div>

                <!-- Social Buttons Placeholder -->
                <div class="flex gap-4 w-full">
                    <a href="{{ route('google.login') }}" class="flex-1 border border-primary p-3 flex items-center justify-center hover:bg-primary hover:text-on-primary transition-colors text-sm font-bold font-body-md">
                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor"><path d="M12.545 10.239v3.821h5.445c-.712 2.315-2.647 3.972-5.445 3.972-3.332 0-6.033-2.701-6.033-6.032s2.701-6.032 6.033-6.032c1.498 0 2.866.549 3.921 1.453l2.814-2.814C17.503 2.988 15.139 2 12.545 2 7.021 2 2.543 6.477 2.543 12s4.478 10 10.002 10c8.396 0 10.249-7.85 9.426-11.761h-9.426z"/></svg>
                        Google
                    </a>
                    <a href="{{ route('twitter.login') }}" class="flex-1 border border-primary p-3 flex items-center justify-center hover:bg-primary hover:text-on-primary transition-colors text-sm font-bold font-body-md">
                        <svg class="w-4 h-4 mr-2" viewBox="0 0 512 512" fill="currentColor"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.6 318.1 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>
                        Twitter / X
                    </a>
                </div>

                <div class="mt-lg text-center font-label-caps text-xs tracking-wider uppercase">
                    Already have an account? <a href="{{ route('login') }}" class=" hover:text-primary hover:underline font-bold transition-colors">Log in</a>
                </div>
            </form>
        </div>
        
    </div>
</div>
@endsection
