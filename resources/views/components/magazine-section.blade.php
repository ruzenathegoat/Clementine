<section id="editorial-magazine" class="w-full bg-background text-primary pt-24 md:pt-40 pb-32 md:pb-48 overflow-hidden font-sans border-t border-primary/10">
    
    <!-- Header -->
    <div class="px-6 md:px-12 mb-16 md:mb-24 flex flex-col items-start mag-header-container">
        <div class="mag-header" style="opacity: 0.8; letter-spacing: 0.08em;">
            <h2 class="font-mono text-[10px] md:text-xs uppercase text-primary/60 tracking-widest mb-3">The Daily</h2>
            <h1 class="font-h1 font-medium text-5xl md:text-[7rem] uppercase tracking-tighter leading-[0.85] text-primary">
                DRIP & <span class="font-serif italic lowercase tracking-normal">ticks</span>
            </h1>
        </div>
    </div>

    <!-- Grid Layout -->
    <div class="px-6 md:px-12">
        <div class="grid grid-cols-1 md:grid-cols-12 relative mag-grid" x-data="{ hoveredIndex: null }">
            
            <!-- Global Top Border -->
            <div class="absolute top-0 left-0 w-full h-[1px] bg-primary/20 scale-x-0 origin-left mag-border-x z-20"></div>

            @foreach($magazines as $index => $magazine)
                @php
                    // Editorial asymmetrical grid spanning
                    $colSpan = 'md:col-span-4'; 
                    $rowSpan = 'md:row-span-1';
                    
                    if ($index === 0) {
                        $colSpan = 'md:col-span-8';
                        $rowSpan = 'md:row-span-2';
                    } elseif ($index === 3 || $index === 4) {
                        $colSpan = 'md:col-span-6';
                    } 
                    
                    // Tags based on index for demo purposes
                    $tags = ['Editorial', 'Review', 'Grail', 'New Drop', 'Underrated', 'Opinion'];
                    $tag = $tags[$index % count($tags)];
                @endphp
                
                <a href="{{ $magazine->link }}" target="_blank" rel="noopener noreferrer" 
                   class="mag-card group relative flex flex-col w-full h-full bg-background {{ $colSpan }} {{ $rowSpan }} p-6 md:p-10"
                   @mouseenter="hoveredIndex = {{ $index }}"
                   @mouseleave="hoveredIndex = null"
                   :class="{ 'opacity-35': hoveredIndex !== null && hoveredIndex !== {{ $index }}, 'opacity-100': hoveredIndex === null || hoveredIndex === {{ $index }} }"
                   style="transition: opacity 250ms cubic-bezier(0.25, 1, 0.5, 1);">
                    
                    <!-- Drawn Borders -->
                    <!-- Mobile bottom border -->
                    <div class="md:hidden absolute bottom-0 left-0 w-full h-[1px] bg-primary/20 scale-x-0 origin-left mag-border-x z-10"></div>

                    @if($index === 0)
                        <!-- Desktop: Right & Bottom border for featured -->
                        <div class="hidden md:block absolute top-0 right-0 w-[1px] h-full bg-primary/20 scale-y-0 origin-top mag-border-y z-10"></div>
                        <div class="hidden md:block absolute bottom-0 left-0 w-full h-[1px] bg-primary/20 scale-x-0 origin-left mag-border-x z-10"></div>
                    @elseif($index === 3)
                        <!-- Desktop: Right & Bottom border -->
                        <div class="hidden md:block absolute top-0 right-0 w-[1px] h-full bg-primary/20 scale-y-0 origin-top mag-border-y z-10"></div>
                        <div class="hidden md:block absolute bottom-0 left-0 w-full h-[1px] bg-primary/20 scale-x-0 origin-left mag-border-x z-10"></div>
                    @else
                        <!-- Desktop: Bottom border for all others -->
                        <div class="hidden md:block absolute bottom-0 left-0 w-full h-[1px] bg-primary/20 scale-x-0 origin-left mag-border-x z-10"></div>
                    @endif
                    
                    <!-- Image Container (Editorial focus) -->
                    @if($magazine->image_url)
                        <div class="w-full aspect-[4/3] mb-6 overflow-hidden bg-surface relative">
                            <img src="{{ $magazine->image_url }}" alt="{{ $magazine->title }}" 
                                 class="w-full h-full object-cover transition-all duration-350 ease-out" 
                                 style="filter: brightness(0.88) contrast(1);"
                                 x-bind:style="hoveredIndex === {{ $index }} ? 'filter: brightness(1) contrast(1.05);' : 'filter: brightness(0.88) contrast(1);'" />
                        </div>
                    @else
                        <!-- Fallback placeholder -->
                        <div class="w-full aspect-[4/3] mb-6 overflow-hidden bg-surface relative flex items-center justify-center border border-primary/5">
                            <span class="text-3xl font-serif italic text-primary/30">Clementine</span>
                        </div>
                    @endif
                    
                    <!-- Content -->
                    <div class="flex flex-col flex-grow justify-between">
                        <!-- Labels -->
                        <div class="flex justify-between items-start mb-6">
                            <span class="text-primary/60 text-[10px] uppercase tracking-[0.1em] font-mono">
                                {{ $tag }}
                            </span>
                            <span class="text-[10px] font-mono text-primary/40 uppercase tracking-[0.1em] transition-opacity duration-300"
                                  :class="hoveredIndex === {{ $index }} ? 'opacity-100' : 'opacity-60'">
                                {{ $magazine->pub_date ? $magazine->pub_date->format('M d, Y') : 'Recent' }}
                            </span>
                        </div>
                        
                        <!-- Headline -->
                        <div class="mt-auto">
                            <h3 class="mag-headline font-h1 font-medium text-2xl md:text-3xl leading-[1.1] uppercase transition-transform duration-300 ease-out"
                                style="color: #6d6d6d;"
                                :class="hoveredIndex === {{ $index }} ? 'translate-x-1' : 'translate-x-0'">
                                {{ $magazine->title }}
                            </h3>
                        </div>
                    </div>
                    
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- GSAP Script for Editorial Magazine Motion -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

        const section = document.getElementById('editorial-magazine');
        if (!section) return;

        // 1. Header Reveal (Very subtle)
        const header = section.querySelector('.mag-header');
        if (header) {
            gsap.to(header, {
                letterSpacing: 'normal',
                opacity: 1,
                duration: 0.7,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        }

        // 2. Grid Borders Reveal (Ink drawing effect)
        const borderX = section.querySelectorAll('.mag-border-x');
        const borderY = section.querySelectorAll('.mag-border-y');
        
        if (borderX.length) {
            gsap.to(borderX, {
                scaleX: 1,
                duration: 0.8,
                ease: 'power1.out',
                stagger: 0.1,
                scrollTrigger: {
                    trigger: '.mag-grid',
                    start: 'top 80%',
                }
            });
        }

        if (borderY.length) {
            gsap.to(borderY, {
                scaleY: 1,
                duration: 0.8,
                ease: 'power1.out',
                stagger: 0.1,
                scrollTrigger: {
                    trigger: '.mag-grid',
                    start: 'top 80%',
                }
            });
        }

        // 3. Reading Progress Headline Transition
        const headlines = section.querySelectorAll('.mag-headline');
        headlines.forEach(headline => {
            gsap.to(headline, {
                color: '#000000', // Full black when reading
                ease: 'none',
                scrollTrigger: {
                    trigger: headline,
                    start: 'top 90%', 
                    end: 'bottom 60%', 
                    scrub: true,
                }
            });
        });
    });
</script>