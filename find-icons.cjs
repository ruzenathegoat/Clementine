const fs = require('fs');
const path = require('path');
const https = require('https');

const viewsDir = path.join(__dirname, 'resources', 'views');
const svgDir = path.join(__dirname, 'resources', 'svg');

if (!fs.existsSync(svgDir)) {
    fs.mkdirSync(svgDir, { recursive: true });
}

function walk(dir, done) {
    let results = [];
    fs.readdir(dir, function(err, list) {
        if (err) return done(err);
        let i = 0;
        function next() {
            let file = list[i++];
            if (!file) return done(null, results);
            file = path.resolve(dir, file);
            fs.stat(file, function(err, stat) {
                if (stat && stat.isDirectory()) {
                    walk(file, function(err, res) {
                        results = results.concat(res);
                        next();
                    });
                } else {
                    if(file.endsWith('.blade.php')) results.push(file);
                    next();
                }
            });
        }
        next();
    });
}

walk(viewsDir, async function(err, files) {
    if (err) throw err;
    
    let uniqueIcons = new Set();
    const regex = /<span[^>]*class="[^"]*material-symbols-outlined[^"]*"[^>]*>([\w_]+)<\/span>/g;
    
    for (let file of files) {
        let content = fs.readFileSync(file, 'utf8');
        let match;
        while ((match = regex.exec(content)) !== null) {
            if (match[1].trim() !== '') {
                uniqueIcons.add(match[1].trim());
            }
        }
    }
    
    console.log("Unique icons found:", Array.from(uniqueIcons));
});
