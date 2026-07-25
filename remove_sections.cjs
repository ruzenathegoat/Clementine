const fs = require('fs');
const path = 'c:/laragon/www/clementine/resources/views/home.blade.php';
let lines = fs.readFileSync(path, 'utf8').split('\n');

let newLines = lines.filter((line, idx) => {
    let lineNum = idx + 1;
    if (lineNum >= 267 && lineNum <= 773) return false;
    if (lineNum >= 1612 && lineNum <= 1840) return false;
    return true;
});

fs.writeFileSync(path, newLines.join('\n'));
console.log('Done!');
