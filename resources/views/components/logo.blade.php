<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" {{ $attributes->merge(['class' => '']) }}>
  <!-- Refined CH Monogram with higher legibility -->
  
  <!-- 'C' Structure (left side) -->
  <path d="M 180 100 L 80 100 L 80 300 L 180 300" 
        fill="none" 
        stroke="currentColor" 
        stroke-width="50" 
        stroke-linejoin="miter" 
        stroke-linecap="butt" />
        
  <!-- 'H' Structure (right side) -->
  <!-- Left vertical of H -->
  <path d="M 220 100 L 220 300" 
        fill="none" 
        stroke="currentColor" 
        stroke-width="50" 
        stroke-linecap="butt" />
  <!-- Right vertical of H -->
  <path d="M 320 100 L 320 300" 
        fill="none" 
        stroke="currentColor" 
        stroke-width="50" 
        stroke-linecap="butt" />
  <!-- Crossbar of H -->
  <path d="M 220 200 L 320 200" 
        fill="none" 
        stroke="currentColor" 
        stroke-width="50" 
        stroke-linecap="butt" />

  <!-- Technical Index Markers (4px hairline for scalability) for horological feel -->
  <g stroke="currentColor" stroke-width="4">
    <line x1="200" y1="60" x2="200" y2="100" />
    <line x1="200" y1="300" x2="200" y2="340" />
    <line x1="50" y1="200" x2="80" y2="200" />
    <line x1="320" y1="200" x2="350" y2="200" />
  </g>
</svg>
