<section id="magazine" class="w-full bg-background text-primary pt-xl pb-3xl overflow-hidden font-sans border-t border-border">
    <!-- Marquee -->
    <div class="w-full border-y border-border py-2 overflow-hidden flex whitespace-nowrap bg-background text-primary text-sm uppercase tracking-widest font-bold">
        <marquee scrollamount="8">
            @for ($i = 0; $i < 10; $i++)
                // THE REALEST WATCH NEWS &nbsp;&nbsp;&nbsp;&nbsp; // UNCENSORED &nbsp;&nbsp;&nbsp;&nbsp; // HYPE OR TRASH &nbsp;&nbsp;&nbsp;&nbsp; // EDITORIAL &nbsp;&nbsp;&nbsp;&nbsp; 
            @endfor
        </marquee>
    </div>

    <!-- Header -->
    <div class="px-sm md:px-lg mt-xl mb-xl">
        <h2 class="font-h1 font-medium text-2xl md:text-4xl text-primary uppercase">The Daily</h2>
        <h1 class="font-h1 font-medium text-6xl md:text-9xl uppercase tracking-tighter leading-[0.8] text-primary mt-2">
            DRIP & <span class="font-serif italic text-copper lowercase tracking-normal">ticks</span>
        </h1>
    </div>

    <!-- Grid Layout using 1px gap for brutalist borders -->
    <div class="px-sm md:px-lg">
        <div class="grid grid-cols-1 md:grid-cols-12 bg-border gap-[1px] border border-border">
            @foreach($magazines as $index => $magazine)
                @php
                    // Assign different spans for asymmetrical look
                    $colSpan = 'md:col-span-4'; // Default
                    $rowSpan = 'md:row-span-1';
                    
                    if ($index === 0) {
                        $colSpan = 'md:col-span-8';
                        $rowSpan = 'md:row-span-2';
                    } elseif ($index === 3) {
                        $colSpan = 'md:col-span-6';
                        $rowSpan = 'md:row-span-1';
                    } elseif ($index === 4) {
                        $colSpan = 'md:col-span-6';
                        $rowSpan = 'md:row-span-1';
                    }
                    
                    // Tags based on index for demo purposes
                    $tags = ['EDITORIAL', 'GRAIL', 'COP OR DROP', 'UNDERRATED', 'NEW DROP', 'OPINION'];
                    $tag = $tags[$index % count($tags)];
                @endphp
                
                <a href="{{ $magazine->link }}" target="_blank" rel="noopener noreferrer" 
                   class="group relative flex flex-col w-full h-full bg-background hover:bg-surface transition-colors {{ $colSpan }} {{ $rowSpan }} min-h-[300px]">
                    
                    <!-- Content Top -->
                    <div class="p-md flex flex-col flex-grow z-10 relative bg-background/90 group-hover:bg-transparent transition-colors">
                        <div class="flex justify-between items-start mb-auto">
                            <span class="bg-primary text-secondary text-xs font-mono px-2 py-1 uppercase tracking-widest border border-primary">
                                {{ $tag }}
                            </span>
                            <span class="text-xs font-mono text-primary px-2 py-1 bg-background border border-border">
                                {{ $magazine->pub_date ? $magazine->pub_date->diffForHumans() : 'RECENTLY' }}
                            </span>
                        </div>
                        
                        <div class="mt-8">
                            <p class="font-mono text-xs text-text-secondary uppercase tracking-widest mb-2">{{ $magazine->source ?? 'Google News' }}</p>
                            <h3 class="font-h1 font-medium text-2xl md:text-4xl leading-none text-primary uppercase group-hover:underline decoration-4 underline-offset-4">
                                {{ $magazine->title }}
                            </h3>
                        </div>
                    </div>
                    
                    <!-- Image Background (Grayscale Brutalist) -->
                    @if($magazine->image_url)
                        <div class="absolute inset-0 w-full h-full p-[1px]">
                            <img src="{{ $magazine->image_url }}" alt="{{ $magazine->title }}" 
                                 class="w-full h-full object-cover filter grayscale contrast-125 opacity-40 group-hover:opacity-100 transition-opacity duration-0" />
                        </div>
                    @else
                        <!-- Fallback placeholder -->
                        <div class="absolute inset-0 w-full h-full flex items-center justify-center overflow-hidden">
                            <span class="text-[12rem] font-sans font-black text-surface opacity-50 select-none uppercase tracking-tighter leading-none transform -rotate-12">NEWS</span>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>