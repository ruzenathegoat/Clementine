const fs = require('fs');
const path = 'c:/laragon/www/clementine/resources/views/home.blade.php';
let content = fs.readFileSync(path, 'utf8');

// Replace colors
content = content.replace(/text-\[\#B99B5A\]/g, 'text-secondary');
content = content.replace(/bg-\[\#B99B5A\]/g, 'bg-secondary');
content = content.replace(/hover:bg-\[\#B99B5A\]/g, 'hover:bg-white');
content = content.replace(/border-\[\#B99B5A\]\/35/g, 'border-secondary/35');
content = content.replace(/transparent, #B99B5A, transparent/g, 'transparent, #FFFFFF, transparent');

// Replace text
content = content.replace(
    'Setiap jam yang masuk ke katalog Clementine melewati empat tahap verifikasi independen. Anda tidak perlu percaya pada kata-kata kami — Anda bisa memeriksa jejaknya sendiri.',
    'Every timepiece entering the Clementine catalog undergoes four independent stages of verification. You don\'t have to take our word for it—you can trace the lineage yourself.'
);
content = content.replace(
    'Movement, case, dan dial diperiksa langsung oleh watchmaker bersertifikat AWCI/WOSTEP, dibandingkan dengan spesifikasi pabrik asli.',
    'The movement, case, and dial are physically inspected by an AWCI/WOSTEP certified watchmaker and verified against original factory specifications.'
);
content = content.replace(
    'Hasil inspeksi dicatat sebagai sertifikat digital dengan nomor unik, tertaut permanen ke nomor seri movement.',
    'Inspection results are recorded as a digital certificate with a unique identifier, permanently linked to the movement\'s serial number.'
);
content = content.replace(
    'NFC tag tersembunyi ditanam pada kemasan, memungkinkan pemindaian riwayat kepemilikan kapan pun setelah pembelian.',
    'A hidden NFC tag is embedded in the packaging, enabling instant scanning of ownership history at any time after purchase.'
);
content = content.replace(
    'Pengiriman diasuransikan penuh oleh pihak ketiga dari brankas kami hingga ke tangan Anda, dengan bukti serah terima.',
    'Shipments are fully insured by a third-party from our vault directly to your hands, complete with proof of handover.'
);
content = content.replace(
    'Verifikasi keaslian unit Anda',
    'Verify your timepiece'
);
content = content.replace(
    'Masukkan nomor sertifikat yang tertera di kartu provenance atau dikirim melalui email konfirmasi pembelian.',
    'Enter the certificate number located on your provenance card or in your purchase confirmation email.'
);
content = content.replace(
    'Coba klik Verify untuk melihat pratinjau interaksinya.',
    'Click Verify to preview the interaction.'
);
content = content.replace(
    'Masukkan nomor sertifikat<br>untuk memulai verifikasi',
    'Enter your certificate number<br>to begin verification'
);
content = content.replace(
    '"Saya bisa memindai NFC di kotaknya sebelum transfer sepeser pun. Itu yang meyakinkan saya untuk membeli lintas negara."',
    '"I could scan the NFC tag on the box before transferring a single cent. That\'s what convinced me to purchase internationally."'
);
content = content.replace(
    '— R. Hartono, Kolektor sejak 2019',
    '— R. Hartono, Collector since 2019'
);

fs.writeFileSync(path, content);
console.log('Replacements completed successfully.');
