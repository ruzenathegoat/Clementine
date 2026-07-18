<section id="magazine" class="w-full bg-[#0a0a0a] text-white pt-xl pb-3xl overflow-hidden font-sans border-t border-[#333]">
    <!-- Marquee -->
    <div class="w-full border-y border-[#333] py-2 overflow-hidden flex whitespace-nowrap bg-black text-[#00ff9d] text-sm uppercase tracking-widest font-bold">
        <marquee scrollamount="8">
            @for ($i = 0; $i < 10; $i++)
                // NO BULLSHIT HOROLOGY &nbsp;&nbsp;&nbsp;&nbsp; // THE REALEST WATCH NEWS &nbsp;&nbsp;&nbsp;&nbsp; // HYPE OR TRASH &nbsp;&nbsp;&nbsp;&nbsp; // UNCENSORED &nbsp;&nbsp;&nbsp;&nbsp; 
            @endfor
        </marquee>
    </div>

    <!-- Header -->
    <div class="px-sm md:px-lg mt-xl mb-2xl">
        <h2 class="font-serif italic text-2xl md:text-4xl text-gray-400">The Daily</h2>
        <h1 class="font-sans font-black text-6xl md:text-9xl uppercase tracking-tighter leading-[0.8] text-white mix-blend-difference">
            Drip & Ticks
        </h1>
    </div>

    <!-- Grid Layout -->
    <div class="px-sm md:px-lg grid grid-cols-1 md:grid-cols-12 gap-sm md:gap-md auto-rows-[250px] md:auto-rows-[300px]">
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
                $tags = ['HYPE', 'GRAIL', 'COP OR DROP', 'UNDERRATED', 'NEW DROP', 'TRASH?'];
                $tag = $tags[$index % count($tags)];
            @endphp
            
            <a href="{{ $magazine->link }}" target="_blank" rel="noopener noreferrer" 
               class="group relative block w-full h-full border border-[#333] overflow-hidden {{ $colSpan }} {{ $rowSpan }} bg-[#111]">
                
                @if($magazine->image_url)
                    <img src="{{ $magazine->image_url }}" alt="{{ $magazine->title }}" 
                         class="absolute inset-0 w-full h-full object-cover transition-all duration-700 filter grayscale contrast-125 group-hover:grayscale-0 group-hover:scale-105 opacity-60 group-hover:opacity-100" />
                    <!-- Subtle Noise Overlay -->
                    <div class="absolute inset-0 mix-blend-overlay opacity-20 pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 200 200\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.65\' numOctaves=\'3\' stitchTiles=\'stitch\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\'/%3E%3C/svg%3E');"></div>
                @else
                    <!-- Fallback placeholder -->
                    <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-[#111] to-[#222] flex items-center justify-center">
                        <span class="text-9xl font-black text-[#333] opacity-30 select-none uppercase tracking-tighter mix-blend-overlay transform -rotate-12">REDACTED</span>
                    </div>
                @endif
                
                <!-- Content Overlay -->
                <div class="absolute inset-0 p-md flex flex-col justify-between bg-gradient-to-t from-black/90 via-black/40 to-transparent">
                    <div class="flex justify-between items-start">
                        <span class="bg-[#00ff9d] text-black text-xs font-black px-2 py-1 uppercase tracking-widest shadow-[4px_4px_0px_#000] transform transition-transform group-hover:-translate-y-1 group-hover:-translate-x-1">
                            {{ $tag }}
                        </span>
                        <span class="text-xs font-mono text-gray-400 bg-black/50 px-2 py-1 backdrop-blur-sm">
                            {{ $magazine->pub_date ? $magazine->pub_date->diffForHumans() : 'Recently' }}
                        </span>
                    </div>
                    
                    <div class="mt-auto">
                        <p class="font-mono text-[10px] text-gray-400 uppercase tracking-widest mb-2">{{ $magazine->source ?? 'Google News' }}</p>
                        <h3 class="font-sans font-bold text-xl md:text-2xl leading-tight text-white group-hover:text-[#00ff9d] transition-colors">
                            {{ $magazine->title }}
                        </h3>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>