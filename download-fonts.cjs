const https = require('https');
const fs = require('fs');
const path = require('path');

const url = 'https://api.fontshare.com/v2/css?f[]=satoshi@900,700,500,400&display=swap';

https.get(url, (res) => {
    let data = '';
    res.on('data', chunk => { data += chunk; });
    res.on('end', () => {
        // Find all woff2 urls
        const regex = /url\('?(https:\/\/cdn\.fontshare\.com\/[^)']+)'?\)/g;
        let match;
        const fontDir = path.join(__dirname, 'public', 'fonts', 'satoshi');
        fs.mkdirSync(fontDir, { recursive: true });
        
        let newCss = data;
        let downloads = [];
        
        while ((match = regex.exec(data)) !== null) {
            const fontUrl = match[1];
            const fileName = path.basename(fontUrl).split('?')[0];
            const localPath = `/fonts/satoshi/${fileName}`;
            
            newCss = newCss.replace(fontUrl, localPath);
            
            downloads.push(new Promise((resolve) => {
                https.get(fontUrl, (fontRes) => {
                    const dest = fs.createWriteStream(path.join(fontDir, fileName));
                    fontRes.pipe(dest);
                    dest.on('finish', () => { dest.close(); resolve(); });
                });
            }));
        }
        
        Promise.all(downloads).then(() => {
            fs.writeFileSync(path.join(__dirname, 'resources', 'css', 'satoshi.css'), newCss);
            console.log('Fonts downloaded and satoshi.css created.');
        });
    });
});
