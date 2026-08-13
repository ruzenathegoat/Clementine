const https = require('https');
const fs = require('fs');
const path = require('path');

const icons = [
  'arrow_forward', 'send', 'close', 'arrow_back', 'check', 'expand_more', 'verified', 
  'security', 'local_shipping', 'menu', 'gpp_bad', 'person', 'search', 'shopping_bag', 
  'content_copy', 'developer_mode', 'qr_code_scanner', 'precision_manufacturing', 
  'diamond', 'water_drop', 'branding_watermark', 'add_shopping_cart', 'terminal', 
  'keyboard_arrow_right', 'contactless', 'logout', 'upload', 'history', 'lock', 
  'warning', 'description', 'tune', 'visibility', 'visibility_off', 'check_circle', 'error'
].join('');

// Note: text parameter needs URL encoding, but actually we can just pass the string of all distinct characters?
// Wait, Google fonts text parameter expects the exact characters! For ligatures like Material Symbols, the text parameter must contain the literal strings. 
// "If you want to use ligatures, you must URL-encode the entire word."
// But wait, the URL encoding of comma separated? No, just all words separated by nothing or url encoded.
// e.g. text=arrow_forwardsendclose
const textParam = encodeURIComponent(icons);
const url = `https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&text=${textParam}`;

https.get(url, { headers: { 'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36' } }, (res) => {
    let data = '';
    res.on('data', chunk => data += chunk);
    res.on('end', () => {
        const regex = /url\((https:\/\/[^)]+)\)/;
        const match = regex.exec(data);
        if (match) {
            const fontUrl = match[1];
            const dest = fs.createWriteStream(path.join(__dirname, 'public', 'fonts', 'material-symbols-subset.woff2'));
            https.get(fontUrl, (fontRes) => {
                fontRes.pipe(dest);
                dest.on('finish', () => {
                    console.log('Subset font downloaded!');
                });
            });
        } else {
            console.log('No URL found in CSS:', data);
        }
    });
});
