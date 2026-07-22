<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CLEMENTINE')</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@900,700,500,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

    <!-- GSAP, SplitType & Lenis -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/split-type"></script>
    <script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.39/dist/lenis.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Transparent Nav Overrides */
        .nav-transparent {
            background-color: transparent !important;
            border-color: transparent !important;
            color: white !important;
        }
        .nav-transparent .nav-item {
            color: white !important;
        }
        .nav-transparent .nav-item.border-primary {
            border-color: white !important;
            color: white !important;
        }
        .nav-transparent .nav-item:hover,
        .nav-transparent .icon-btn:hover {
            background-color: white !important;
            color: black !important;
            border-color: transparent !important;
        }
        .nav-transparent .text-primary {
            color: white !important;
        }
        .nav-transparent svg, .nav-transparent path {
            fill: white !important;
        }
        body { background-color: #ffffff; color: #000000; }
        .border-black { border-color: #000000; }
        input[type="checkbox"]:checked { background-color: #000000; border-color: #000000; }
        input[type="range"] { -webkit-appearance: none; width: 100%; background: transparent; }
        input[type="range"]::-webkit-slider-thumb { -webkit-appearance: none; height: 16px; width: 16px; background: #000000; cursor: pointer; margin-top: -8px; border-radius: 0; }
        input[type="range"]::-webkit-slider-runnable-track { width: 100%; height: 1px; cursor: pointer; background: #000000; }
        
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #ffffff; border-left: 1px solid #000000; }
        ::-webkit-scrollbar-thumb { background: #000000; }

        /* Alpine Cloak */
        [x-cloak] { display: none !important; }

        /* Lenis base styling */
        html.lenis { height: auto; }
        .lenis.lenis-smooth { scroll-behavior: auto !important; }
        .lenis.lenis-smooth [data-lenis-prevent] { overscroll-behavior: contain; }
        .lenis.lenis-stopped { overflow: hidden; }
        .lenis.lenis-scrolling iframe { pointer-events: none; }
    </style>
    
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col font-body-md relative" x-data="{ sidebarOpen: false, preloaderFinished: false, searchOpen: false, isScrolled: false }" @scroll.window="isScrolled = (window.pageYOffset > {{ request()->routeIs('home') ? '(window.innerHeight * 2.9)' : '50' }})">

    <!-- Preloader -->
    <div id="preloader" class="fixed inset-0 z-[100] bg-primary flex items-center justify-center pointer-events-none">
        <div class="font-body-md text-on-primary text-label-caps uppercase tracking-[0.2em] overflow-hidden">
            <div class="inline-flex items-center gap-4 translate-y-[100%] pb-1 preloader-text">
                <x-logo class="w-10 h-10" />
                <span>CLEMENTINE HOROLOGY</span>
            </div>
        </div>
    </div>

    <!-- Sidebar Overlay & Menu -->
    <div x-cloak x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] bg-primary/20 backdrop-blur-sm"
         @click="sidebarOpen = false"></div>
         
    <aside x-cloak 
           class="fixed top-0 right-0 h-full w-[85vw] max-w-[400px] bg-background border-l border-primary z-[70] flex flex-col transition-transform duration-500 ease-out"
           :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full'">
        <div class="flex justify-between items-center p-lg border-b border-primary bg-surface-container-lowest">
            <span class="font-headline-md text-xl uppercase">MENU</span>
            <button @click="sidebarOpen = false" class="hover:bg-primary hover:text-on-primary transition-colors p-sm border border-transparent hover:border-primary">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="flex flex-col flex-grow p-xl gap-lg font-headline-md text-3xl uppercase overflow-y-auto">
            <a href="{{ route('home') }}" class="hover:bg-primary hover:text-on-primary border border-transparent hover:border-primary px-4 py-2 w-max transition-colors">HOME</a>
            <a href="{{ route('products.index') }}" class="hover:bg-primary hover:text-on-primary border border-transparent hover:border-primary px-4 py-2 w-max transition-colors">SHOP</a>
            <a href="{{ route('collections.index') }}" class="hover:bg-primary hover:text-on-primary border border-transparent hover:border-primary px-4 py-2 w-max transition-colors">COLLECTIONS</a>
            <a href="{{ route('advisor.index') }}" class="hover:bg-primary hover:text-on-primary border border-transparent hover:border-primary px-4 py-2 w-max transition-colors">ADVISOR</a>
            <a href="{{ route('concierge.index') }}" class="hover:bg-primary hover:text-on-primary border border-transparent hover:border-primary px-4 py-2 w-max transition-colors">CONCIERGE</a>
            
            <div class="mt-auto flex flex-col gap-lg">
                @auth
                    <a href="{{ route('profile.index') }}" class="hover:bg-primary hover:text-on-primary border border-transparent hover:border-primary px-4 py-2 w-max transition-colors uppercase">PROFILE / ORDERS</a>
                    <a href="{{ route('clementpay.index') }}" class="hover:bg-primary hover:text-on-primary border border-transparent hover:border-primary px-4 py-2 w-max transition-colors uppercase">CLEMENTPAY (${{ number_format(auth()->user()->clementpay_balance, 2) }})</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hover:bg-primary hover:text-on-primary border border-transparent hover:border-primary px-4 py-2 w-max transition-colors text-secondary">ADMIN DASHBOARD</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:bg-primary hover:text-on-primary border border-transparent hover:border-primary px-4 py-2 w-max transition-colors text-left uppercase">LOGOUT ({{ auth()->user()->name }})</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:bg-primary hover:text-on-primary border border-transparent hover:border-primary px-4 py-2 w-max transition-colors">LOGIN</a>
                @endauth
            </div>
        </div>
        <div class="p-lg border-t border-primary bg-surface-container-lowest font-body-md uppercase text-sm">
            <div class="flex gap-4">
                <a href="https://www.instagram.com/naufalrahmaann/" target="_blank" class="hover:underline">INSTAGRAM</a>
                <a href="#" class="hover:underline">TWITTER</a>
            </div>
        </div>
    </aside>

    <!-- Main Navigation -->
    <nav :class="!isScrolled && {{ request()->routeIs('home') ? 'true' : 'false' }} ? 'nav-transparent' : 'bg-surface-container-lowest border-primary text-primary'"
         class="{{ request()->routeIs('home') ? 'fixed' : 'sticky' }} top-0 w-full z-50 flex justify-between items-center px-lg py-md border-b transition-colors duration-300" id="main-nav">
        <div class="flex gap-lg items-center">
            <a class="flex items-center gap-3 font-headline-md text-headline-md group" href="{{ route('home') }}">
                <x-logo class="w-8 h-8 group-hover:scale-95 transition-transform duration-300 ease-out" />
            </a>
            <div class="hidden md:flex gap-lg font-body-md text-body-md uppercase tracking-widest">
                <a class="nav-item {{ request()->routeIs('home') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:bg-primary hover:text-on-primary active:scale-95 transition-all duration-200 px-2 py-1' }}" href="{{ route('home') }}">HOME</a>
                <a class="nav-item {{ request()->routeIs('products.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:bg-primary hover:text-on-primary active:scale-95 transition-all duration-200 px-2 py-1' }}" href="{{ route('products.index') }}">SHOP</a>
                <a class="nav-item {{ request()->routeIs('collections.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:bg-primary hover:text-on-primary active:scale-95 transition-all duration-200 px-2 py-1' }}" href="{{ route('collections.index') }}">COLLECTIONS</a>
                <a class="nav-item {{ request()->routeIs('advisor.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:bg-primary hover:text-on-primary active:scale-95 transition-all duration-200 px-2 py-1' }}" href="{{ route('advisor.index') }}">ADVISOR</a>
                <a class="nav-item {{ request()->routeIs('concierge.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:bg-primary hover:text-on-primary active:scale-95 transition-all duration-200 px-2 py-1' }}" href="{{ route('concierge.index') }}">CONCIERGE</a>
            </div>
        </div>
        <div class="flex gap-md">
            @auth
                <a href="{{ route('profile.index') }}" class="icon-btn hidden md:flex hover:bg-primary hover:text-on-primary active:scale-95 transition-all duration-150 items-center justify-center p-sm border border-transparent hover:border-primary">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">person</span>
                </a>
            @endauth
            <button @click="searchOpen = true" class="icon-btn hover:bg-primary hover:text-on-primary active:scale-95 transition-all duration-150 flex items-center justify-center p-sm border border-transparent hover:border-primary">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">search</span>
            </button>
            <a href="{{ route('cart.index') }}" class="icon-btn hover:bg-primary hover:text-on-primary active:scale-95 transition-all duration-150 flex items-center justify-center p-sm border border-transparent hover:border-primary">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">shopping_bag</span>
            </a>
            <button @click="sidebarOpen = true" class="icon-btn hover:bg-primary hover:text-on-primary active:scale-95 transition-all duration-150 flex items-center justify-center p-sm border border-transparent hover:border-primary">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">menu</span>
            </button>
        </div>
    </nav>

    <main class="flex-grow flex flex-col items-center w-full" id="main-content">
        @yield('content')
    </main>



    <!-- Hypebizz-style Footer -->
    <footer class="w-full flex flex-col mt-auto border-t border-primary" x-data="{ activeModal: null }">
        @if(request()->routeIs('home'))
        <!-- Top Half (Black) -->
        <div class="w-full bg-primary text-on-primary py-[80px] md:py-[150px] flex items-center justify-center relative overflow-hidden footer-parallax-container">
            <h1 class="footer-logo font-h1 text-[clamp(3rem,13vw,20rem)] leading-[0.8] tracking-tighter uppercase text-center relative z-10 w-full px-4">
                CLEMENTINE
            </h1>
            <!-- Abstract decorative element -->
            <div class="absolute right-[-10%] top-1/2 -translate-y-1/2 opacity-5 pointer-events-none">
                <x-logo class="w-[400px] h-[400px] md:w-[800px] md:h-[800px]" />
            </div>
        </div>
        @endif
        
        <!-- Bottom Half (White) -->
        <div class="w-full bg-background text-primary p-lg md:p-xl flex flex-col">
            @if(request()->routeIs('home'))
            <div class="flex flex-col lg:flex-row justify-between items-start gap-xl pb-3xl border-b border-primary">
                
                <!-- Links Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-x-xl gap-y-4 font-body-md text-xs uppercase font-bold">
                    <div class="flex flex-col gap-4 items-start">
                        <button @click="activeModal = 'about'" class="hover:underline text-left">ABOUT US</button>
                        <button @click="activeModal = 'faq'" class="hover:underline text-left">FAQ</button>
                        <button @click="activeModal = 'contact'" class="hover:underline text-left">CONTACT US</button>
                        <button @click="activeModal = 'products'" class="hover:underline text-left">PRODUCTS</button>
                    </div>
                    <div class="flex flex-col gap-4 items-start">
                        <button @click="activeModal = 'privacy'" class="hover:underline text-left">PRIVACY POLICY</button>
                        <button @click="activeModal = 'refund'" class="hover:underline text-left">REFUND POLICY</button>
                        <button @click="activeModal = 'terms'" class="hover:underline text-left">TERMS OF SERVICE</button>
                        <button @click="activeModal = 'care'" class="hover:underline text-left">CUSTOMER CARE</button>
                    </div>
                    <div class="flex flex-col gap-4 items-start">
                        <button @click="activeModal = 'order'" class="hover:underline text-left">HOW TO ORDER</button>
                        <button @click="activeModal = 'how_refund'" class="hover:underline text-left">HOW TO REFUND</button>
                        <button @click="activeModal = 'track'" class="hover:underline text-left">TRACK YOUR ORDER</button>
                    </div>
                </div>
                
                <!-- Newsletter -->
                <div class="flex flex-col gap-4 w-full lg:w-[400px]">
                    <h3 class="font-h1 text-4xl uppercase tracking-tight">JOIN OUR COMMUNITY</h3>
                    <form class="flex w-full">
                        <input type="email" placeholder="ENTER YOUR EMAIL" class="w-full border-t border-b border-l border-primary p-3 font-body-md text-xs uppercase focus:outline-none bg-transparent rounded-none">
                        <button type="submit" class="border border-primary bg-primary text-on-primary px-4 py-3 font-body-md text-xs font-bold hover:bg-background hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </button>
                    </form>
                </div>
            </div>
            @endif
            
            <!-- Bottom Copyright & Socials -->
            <div class="flex flex-col md:flex-row justify-between items-center {{ request()->routeIs('home') ? 'pt-8' : '' }} font-body-md text-xs font-bold uppercase gap-6">
                <div>©2026 CLEMENTINE. ALL RIGHTS RESERVED.</div>
                <div class="flex gap-6">
                    <a href="https://www.instagram.com/naufalrahmaann/" target="_blank" class="hover:underline">INSTAGRAM</a>
                    <a href="#" class="hover:underline">TWITTER</a>
                    <a href="#" class="hover:underline">FACEBOOK</a>
                    <a href="https://www.linkedin.com/in/naufal-rahman-2182a1249/" target="_blank" class="hover:underline">LINKEDIN</a>
                </div>
            </div>
        </div>

        <!-- Footer Modals -->
        <div x-cloak x-show="activeModal !== null" class="fixed inset-0 z-[200] flex items-center justify-center"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
             <!-- Backdrop -->
             <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="activeModal = null"></div>
             
             <!-- Dialog Container (Shadcn style) -->
             <div class="relative bg-white text-black w-[90vw] md:w-[500px] rounded-xl shadow-2xl border border-gray-200 flex flex-col max-h-[85vh]"
                  @click.stop
                  x-transition:enter="transition ease-out duration-300 transform"
                  x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                  x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                  x-transition:leave="transition ease-in duration-200 transform"
                  x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                  x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                  
                  <!-- Header -->
                  <div class="flex items-center justify-between p-6 border-b border-gray-100">
                      <h2 class="font-h1 text-2xl uppercase tracking-tight" x-text="
                          activeModal === 'about' ? 'About Us' :
                          activeModal === 'faq' ? 'FAQ' :
                          activeModal === 'contact' ? 'Contact Us' :
                          activeModal === 'products' ? 'Products' :
                          activeModal === 'privacy' ? 'Privacy Policy' :
                          activeModal === 'refund' ? 'Refund Policy' :
                          activeModal === 'terms' ? 'Terms of Service' :
                          activeModal === 'care' ? 'Customer Care' :
                          activeModal === 'order' ? 'How to Order' :
                          activeModal === 'how_refund' ? 'How to Refund' :
                          activeModal === 'track' ? 'Track Your Order' : ''
                      "></h2>
                      <button @click="activeModal = null" class="text-gray-400 hover:text-black transition-colors rounded-sm focus:outline-none focus:ring-2 focus:ring-black">
                          <span class="material-symbols-outlined">close</span>
                      </button>
                  </div>
                  
                  <!-- Body -->
                  <div class="p-6 overflow-y-auto font-body-md text-sm leading-relaxed text-gray-700">
                      <template x-if="activeModal === 'about'">
                          <p>CLEMENTINE is a premium horology destination focusing on uncompromising mechanical perfection. We curate the best timepieces from around the world to satisfy your aesthetic and engineering needs.</p>
                      </template>
                      <template x-if="activeModal === 'faq'">
                          <div class="space-y-4">
                              <div>
                                  <strong class="text-black block mb-1">Do you ship internationally?</strong>
                                  <p>Yes, we ship globally via insured couriers.</p>
                              </div>
                              <div>
                                  <strong class="text-black block mb-1">Are the watches authentic?</strong>
                                  <p>Absolutely. Every timepiece is verified and comes with papers and our authenticity guarantee.</p>
                              </div>
                          </div>
                      </template>
                      <template x-if="activeModal === 'contact'">
                          <div class="flex flex-col gap-3">
                              <p class="mb-2">Connect with us on our platforms:</p>
                              <a href="https://www.instagram.com/naufalrahmaann/" target="_blank" class="hover:text-black hover:underline transition-colors flex items-center gap-2">
                                  Instagram: @naufalrahmaann
                              </a>
                              <a href="https://www.linkedin.com/in/naufal-rahman-2182a1249/" target="_blank" class="hover:text-black hover:underline transition-colors flex items-center gap-2">
                                  LinkedIn: Naufal Rahman
                              </a>
                              <a href="https://github.com/ruzenathegoat" target="_blank" class="hover:text-black hover:underline transition-colors flex items-center gap-2">
                                  GitHub: ruzenathegoat
                              </a>
                              <p class="mt-4 pt-4 border-t border-gray-100">
                                  <strong class="text-black">WhatsApp:</strong> 081398297976
                              </p>
                          </div>
                      </template>
                      <template x-if="activeModal === 'products'">
                          <div>
                              <p class="mb-4">Explore our wide range of premium mechanical timepieces. From classic calibers to modern complications, we got you covered.</p>
                              <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-black text-white font-medium hover:bg-gray-800 transition-colors rounded-md">Browse Shop</a>
                          </div>
                      </template>
                      <template x-if="activeModal === 'privacy'">
                          <p>Your privacy is important to us. We securely store your data and never sell it to third parties. All transactions are encrypted.</p>
                      </template>
                      <template x-if="activeModal === 'refund'">
                          <p>All purchases are final unless the product is proven defective upon arrival. In such cases, please contact us within 24 hours of delivery.</p>
                      </template>
                      <template x-if="activeModal === 'terms'">
                          <p>By using our services, you agree to our terms of service, which ensure a safe and premium experience for all our customers.</p>
                      </template>
                      <template x-if="activeModal === 'care'">
                          <p>Our concierge team is available 24/7 for VIP members. For standard inquiries, expect a reply within 24 hours.</p>
                      </template>
                      <template x-if="activeModal === 'order'">
                          <p>Simply browse our catalog, add your desired timepiece to the cart, and proceed to our secure checkout. We accept major credit cards and bank transfers.</p>
                      </template>
                      <template x-if="activeModal === 'how_refund'">
                          <p>If eligible, please contact our customer care with your Order ID and photo evidence of the defect to initiate a refund process.</p>
                      </template>
                      <template x-if="activeModal === 'track'">
                          <p>Once shipped, a tracking number will be provided in your account dashboard and sent via email.</p>
                      </template>
                  </div>
             </div>
        </div>
    </footer>

    <!-- Search Modal -->
    <div x-cloak x-show="searchOpen" class="fixed inset-0 z-[300] bg-background flex flex-col"
         x-transition:enter="transition-transform duration-500 ease-mechanical"
         x-transition:enter-start="-translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition-transform duration-500 ease-mechanical"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="-translate-y-full">
         
        <div class="flex justify-end p-lg border-b border-primary">
            <button @click="searchOpen = false" class="hover:bg-primary hover:text-on-primary transition-colors p-sm border border-transparent hover:border-primary">
                <span class="material-symbols-outlined text-4xl">close</span>
            </button>
        </div>
        
        <div class="flex-grow flex items-center justify-center p-xl">
            <form action="{{ route('products.index') }}" method="GET" class="w-full max-w-5xl relative group">
                <input type="text" name="search" placeholder="SEARCH ARCHIVES..." autofocus 
                       class="w-full font-h1 text-[40px] md:text-[80px] lg:text-[120px] tracking-tighter uppercase text-primary bg-transparent border-b-4 border-primary focus:outline-none placeholder-primary/20 pb-4" />
                <button type="submit" class="absolute right-0 bottom-6 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-[40px] md:text-[80px]">arrow_forward</span>
                </button>
            </form>
        </div>
        
        <div class="p-lg border-t border-primary font-body-md text-[#787774] text-xs uppercase tracking-widest text-center">
            PRESS ENTER TO SEARCH
        </div>
    </div>

    <!-- Initialized Lenis and GSAP animations -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            // Initialize Lenis
            const lenis = new Lenis({
                duration: 1.2,
                easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                direction: 'vertical',
                gestureDirection: 'vertical',
                smooth: true,
                mouseMultiplier: 1,
                smoothTouch: false,
                touchMultiplier: 2,
                infinite: false,
            });

            function raf(time) {
                lenis.raf(time);
                requestAnimationFrame(raf);
            }
            requestAnimationFrame(raf);
            
            lenis.on('scroll', ScrollTrigger.update);
            gsap.ticker.add((time)=>{
                lenis.raf(time * 1000);
            });
            gsap.ticker.lagSmoothing(0);

            // 1. Nav Item Staggered Entrance (Run only once on first load)
            if (!sessionStorage.getItem('navRevealed')) {
                gsap.from('.nav-item', {
                    y: -20,
                    opacity: 0,
                    stagger: 0.05,
                    duration: 0.8,
                    ease: 'power4.out',
                    delay: 0.2 // Small delay after page loader
                });
                sessionStorage.setItem('navRevealed', 'true');
            }

            // 2. Footer Logo Parallax
            if (document.querySelector('.footer-logo')) {
                gsap.fromTo('.footer-logo',
                    { y: -100, opacity: 0.5, scale: 0.9 },
                    {
                        y: 0,
                        opacity: 1,
                        scale: 1,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: '.footer-parallax-container',
                            start: 'top bottom', // Start when container enters viewport
                            end: 'bottom bottom', // End when container is fully in view
                            scrub: 0.5
                        }
                    }
                );
            }

            // Preloader Animation Logic
            const preloader = document.getElementById('preloader');
            
            if (sessionStorage.getItem('preloaderShown')) {
                // If already shown, hide immediately and fire event
                preloader.style.display = 'none';
                window.dispatchEvent(new Event('preloaderFinished'));
            } else {
                // Run animation on first load
                const tl = gsap.timeline({
                    onComplete: () => {
                        preloader.style.display = 'none';
                        sessionStorage.setItem('preloaderShown', 'true');
                        window.dispatchEvent(new Event('preloaderFinished'));
                    }
                });

                tl.to('.preloader-text', {
                    y: 0,
                    duration: 1,
                    ease: 'power4.out',
                    delay: 0.2
                })
                .to('.preloader-text', {
                    y: '-100%',
                    duration: 0.8,
                    ease: 'power4.in',
                    delay: 0.5
                })
                .to('#preloader', {
                    yPercent: -100,
                    duration: 1,
                    ease: 'expo.inOut'
                }, "-=0.3");
            }
            
            // Simple navbar hide/show on scroll
            let lastScroll = 0;
            const nav = document.getElementById('main-nav');
            lenis.on('scroll', (e) => {
                const currentScroll = e.animatedScroll;
                if (currentScroll > 100 && currentScroll > lastScroll) {
                    nav.style.transform = 'translateY(-100%)';
                } else {
                    nav.style.transform = 'translateY(0)';
                }
                lastScroll = currentScroll;
            });
        });
    </script>
    
    <!-- Alpine.js Brutalist Toast Notifications -->
    @php
        $initialToasts = [];
        if (session('success')) {
            $initialToasts[] = ['type' => 'success', 'message' => session('success')];
        }
        if (session('status')) {
            $initialToasts[] = ['type' => 'success', 'message' => session('status')];
        }
        if (session('error')) {
            $initialToasts[] = ['type' => 'error', 'message' => session('error')];
        }
        if (isset($errors) && $errors->any()) {
            foreach ($errors->all() as $error) {
                $initialToasts[] = ['type' => 'error', 'message' => $error];
            }
        }
    @endphp

    <div x-data="{ 
            toasts: @js($initialToasts),
            init() {
                this.toasts.forEach((_, i) => {
                    setTimeout(() => { this.toasts.shift() }, 4000 + (i * 200));
                });
            }
         }" 
         @notify.window="toasts.push($event.detail); setTimeout(() => toasts.shift(), 4000)"
         class="fixed bottom-lg right-lg z-[100] flex flex-col gap-sm pointer-events-none">
        <template x-for="(toast, index) in toasts" :key="index">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 class="p-md font-label-caps text-sm shadow-2xl flex items-center gap-md pointer-events-auto uppercase"
                 @php
                     $isAuth = request()->routeIs('login', 'register', 'password.*', 'verification.*');
                     $errorClasses = $isAuth ? 'bg-red-600 text-white border border-red-600' : 'bg-primary text-white border border-primary';
                 @endphp
                 :class="toast.type === 'error' ? '{{ $errorClasses }}' : 'bg-primary text-white border border-primary'">
                <span class="material-symbols-outlined text-[20px]" x-text="toast.type === 'error' ? 'warning' : 'check_circle'"></span>
                <span x-text="toast.message" class="tracking-widest"></span>
            </div>
        </template>
    </div>
    
    @if(session('just_upgraded_to_vip'))
    <!-- VIP Upgrade Modal -->
    <div x-data="{ showVipModal: true }"
         x-init="setTimeout(() => showVipModal = false, 10000)"
         x-cloak
         x-show="showVipModal"
         class="fixed inset-0 z-[500] flex items-center justify-center p-4 md:p-8"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
         <!-- Backdrop -->
         <div class="absolute inset-0 bg-primary/80 backdrop-blur-sm" @click="showVipModal = false"></div>
         
         <!-- Modal Content -->
         <div class="relative bg-background text-primary border-4 border-primary p-8 md:p-12 w-full max-w-[600px] flex flex-col items-center text-center transform"
              style="box-shadow: 16px 16px 0px 0px #111111;"
              x-transition:enter="transition ease-out duration-500"
              x-transition:enter-start="opacity-0 translate-y-12 scale-95"
              x-transition:enter-end="opacity-100 translate-y-0 scale-100"
              x-transition:leave="transition ease-in duration-300"
              x-transition:leave-start="opacity-100 translate-y-0 scale-100"
              x-transition:leave-end="opacity-0 translate-y-12 scale-95">
              
              <div class="font-h1 text-[60px] md:text-[80px] leading-none uppercase tracking-tighter mb-6">
                  VIP STATUS<br>UNLOCKED
              </div>
              
              <p class="font-body-md text-sm md:text-base uppercase tracking-widest max-w-[45ch] mb-8 font-bold">
                  Your lifetime value has exceeded $10,000. Welcome to the exclusive circle. You now have priority access to THE DROP.
              </p>
              
              <button @click="showVipModal = false" class="bg-primary text-on-primary border-2 border-primary font-h2 text-xl px-12 py-4 uppercase tracking-widest hover:bg-background hover:text-primary transition-colors w-full">
                  ACCEPT
              </button>
         </div>
    </div>
    @endif
    
    <!-- Global Page Loader (Motion.dev Powered - Pure Instant) -->
    <div id="global-page-loader" class="fixed inset-0 z-[9999] bg-background/80 backdrop-blur-sm flex flex-col items-center justify-center hidden opacity-0 pointer-events-none">
        <div class="w-36 h-4 bg-[#EAEAEA] overflow-hidden relative" style="-webkit-mask: linear-gradient(90deg, #000 70%, #0000 0) 0/20%; mask: linear-gradient(90deg, #000 70%, #0000 0) 0/20%;">
            <div id="page-loader-bar" class="absolute top-0 bottom-0 left-0 w-full bg-[#111111] origin-left h-full" style="transform: scaleX(0);"></div>
        </div>
        <p class="mt-4 font-label-caps uppercase tracking-widest text-primary text-xs animate-pulse">LOADING...</p>
    </div>

    <!-- Global Loader Script (Motion.dev) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loaderContainer = document.getElementById('global-page-loader');
            const loaderBar = document.getElementById('page-loader-bar');
            let activeAnimation = null;

            function startProgress() {
                loaderContainer.classList.remove('hidden', 'opacity-0');

                if (loaderBar) {
                    if (activeAnimation) activeAnimation.stop();
                    
                    if (window.animate) {
                        activeAnimation = window.animate(
                            loaderBar,
                            { scaleX: [0, 0.5, 0.85] },
                            { duration: 0.8, ease: [0.22, 1, 0.36, 1] }
                        );
                    } else {
                        loaderBar.style.transform = 'scaleX(0.85)';
                    }
                }
            }

            function hideLoader() {
                if (activeAnimation) activeAnimation.stop();
                loaderContainer.classList.add('hidden', 'opacity-0');
                if (loaderBar) loaderBar.style.transform = 'scaleX(0)';
            }

            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (
                        this.href && 
                        !this.href.startsWith('javascript:') && 
                        this.getAttribute('target') !== '_blank' &&
                        !this.href.includes('#') &&
                        this.host === window.location.host &&
                        !e.defaultPrevented &&
                        !this.hasAttribute('download')
                    ) {
                        startProgress();
                    }
                });
            });

            document.addEventListener('submit', function(e) {
                if (!e.defaultPrevented) {
                    startProgress();
                }
            });
            
            window.addEventListener('pageshow', function (event) {
                hideLoader();
            });
        });
    </script>
</body>
</html>
