const fs = require('fs');
const path = 'c:/laragon/www/clementine/resources/views/home.blade.php';
let lines = fs.readFileSync(path, 'utf8').split('\n');

// Find Watchmaker's Notes boundaries
const watchmakerStart = lines.findIndex(l => l.includes("<!-- 3.5 Watchmaker's Notes -->"));
const newArrivalsStart = lines.findIndex(l => l.includes("<!-- 3. New Arrivals Section - Collector's Selection -->"));
const magazineStart = lines.findIndex(l => l.includes("<x-magazine-section />"));

if (watchmakerStart !== -1 && newArrivalsStart !== -1 && magazineStart !== -1) {
    // Determine the blank line just before magazine
    let newArrivalsEnd = magazineStart;
    while(newArrivalsEnd > newArrivalsStart && lines[newArrivalsEnd - 1].trim() === '') {
        newArrivalsEnd--;
    }

    const watchmakerLines = lines.slice(watchmakerStart, newArrivalsStart);
    const newArrivalsLines = lines.slice(newArrivalsStart, magazineStart);
    
    const before = lines.slice(0, watchmakerStart);
    const after = lines.slice(magazineStart);
    
    const newLines = [...before, ...newArrivalsLines, ...watchmakerLines, ...after];
    
    fs.writeFileSync(path, newLines.join('\n'));
    console.log('Swapped successfully!');
} else {
    console.log('Could not find boundaries.', { watchmakerStart, newArrivalsStart, magazineStart });
}
