const fs = require('fs');
const path = 'c:/laragon/www/clementine/resources/views/home.blade.php';
let lines = fs.readFileSync(path, 'utf8').split('\n');

const html = `
    <!-- 2.75 PROVENANCE & VERIFICATION -->
    <section class="w-full bg-primary text-secondary py-32 md:py-40 relative overflow-hidden border-b border-secondary/15" id="provenance-section">
        <!-- Grid Background -->
        <div class="absolute inset-0 opacity-40 pointer-events-none" style="background-image: linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px); background-size: 64px 64px;"></div>
        
        <div class="w-full max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            
            <!-- Eyebrow -->
            <div class="flex items-center gap-4 mb-8">
                <div class="h-[1px] w-12 bg-secondary/40"></div>
                <span class="font-mono text-[11px] tracking-[0.25em] uppercase text-secondary/60">Provenance &amp; Verification — Protocol 01</span>
            </div>

            <!-- Headline -->
            <h2 class="font-h1 text-[clamp(2.2rem,5vw,4.2rem)] leading-[1.05] tracking-tight uppercase max-w-[820px] mb-8" style="font-weight: 500;">
                Every timepiece is <em class="not-italic text-[#B99B5A]">authenticated</em><br>before it reaches your wrist.
            </h2>
            <p class="font-mono text-sm leading-[1.8] text-secondary/60 max-w-[520px] mb-20 md:mb-24">
                Setiap jam yang masuk ke katalog Clementine melewati empat tahap verifikasi independen. Anda tidak perlu percaya pada kata-kata kami — Anda bisa memeriksa jejaknya sendiri.
            </p>

            <!-- 4-step process -->
            <div class="grid grid-cols-1 md:grid-cols-4 border-t border-b border-secondary/15 mb-24 md:mb-32">
                <div class="p-8 md:p-10 border-b md:border-b-0 md:border-r border-secondary/15 hover:bg-white/5 transition-colors duration-300">
                    <div class="flex items-center justify-between font-mono text-[11px] text-secondary/40 tracking-[0.2em] mb-6">
                        <span>01</span><span class="w-[5px] h-[5px] rounded-full bg-[#B99B5A]"></span>
                    </div>
                    <div class="font-h1 text-[15px] tracking-[0.02em] uppercase mb-4 leading-[1.3]" style="font-weight: 500;">Physical Inspection</div>
                    <div class="font-mono text-xs leading-[1.7] text-secondary/60">Movement, case, dan dial diperiksa langsung oleh watchmaker bersertifikat AWCI/WOSTEP, dibandingkan dengan spesifikasi pabrik asli.</div>
                </div>
                <div class="p-8 md:p-10 border-b md:border-b-0 md:border-r border-secondary/15 hover:bg-white/5 transition-colors duration-300">
                    <div class="flex items-center justify-between font-mono text-[11px] text-secondary/40 tracking-[0.2em] mb-6">
                        <span>02</span><span class="w-[5px] h-[5px] rounded-full bg-[#B99B5A]"></span>
                    </div>
                    <div class="font-h1 text-[15px] tracking-[0.02em] uppercase mb-4 leading-[1.3]" style="font-weight: 500;">Digital Certification</div>
                    <div class="font-mono text-xs leading-[1.7] text-secondary/60">Hasil inspeksi dicatat sebagai sertifikat digital dengan nomor unik, tertaut permanen ke nomor seri movement.</div>
                </div>
                <div class="p-8 md:p-10 border-b md:border-b-0 md:border-r border-secondary/15 hover:bg-white/5 transition-colors duration-300">
                    <div class="flex items-center justify-between font-mono text-[11px] text-secondary/40 tracking-[0.2em] mb-6">
                        <span>03</span><span class="w-[5px] h-[5px] rounded-full bg-[#B99B5A]"></span>
                    </div>
                    <div class="font-h1 text-[15px] tracking-[0.02em] uppercase mb-4 leading-[1.3]" style="font-weight: 500;">Provenance Tagging</div>
                    <div class="font-mono text-xs leading-[1.7] text-secondary/60">NFC tag tersembunyi ditanam pada kemasan, memungkinkan pemindaian riwayat kepemilikan kapan pun setelah pembelian.</div>
                </div>
                <div class="p-8 md:p-10 hover:bg-white/5 transition-colors duration-300">
                    <div class="flex items-center justify-between font-mono text-[11px] text-secondary/40 tracking-[0.2em] mb-6">
                        <span>04</span><span class="w-[5px] h-[5px] rounded-full bg-[#B99B5A]"></span>
                    </div>
                    <div class="font-h1 text-[15px] tracking-[0.02em] uppercase mb-4 leading-[1.3]" style="font-weight: 500;">Insured Custody</div>
                    <div class="font-mono text-xs leading-[1.7] text-secondary/60">Pengiriman diasuransikan penuh oleh pihak ketiga dari brankas kami hingga ke tangan Anda, dengan bukti serah terima.</div>
                </div>
            </div>

            <!-- Signature element: Certificate lookup + poinçon stamp -->
            <div class="grid grid-cols-1 lg:grid-cols-2 border border-secondary/15 mb-24 md:mb-32">
                <!-- Left -->
                <div class="p-12 md:p-16 border-b lg:border-b-0 lg:border-r border-secondary/15 flex flex-col justify-center">
                    <span class="font-mono text-[10px] tracking-[0.25em] text-secondary/40 uppercase mb-5">Check a certificate</span>
                    <h3 class="font-h1 text-[clamp(1.4rem,2.4vw,2rem)] uppercase leading-[1.15] mb-5" style="font-weight: 500;">Verifikasi keaslian unit Anda</h3>
                    <p class="font-mono text-[13px] leading-[1.8] text-secondary/60 mb-10 max-w-[400px]">Masukkan nomor sertifikat yang tertera di kartu provenance atau dikirim melalui email konfirmasi pembelian.</p>
                    
                    <div class="flex border border-secondary/15">
                        <input id="serialInput" type="text" placeholder="e.g. CLM-2044-0071" value="CLM-2044-0071" class="flex-1 bg-transparent border-none py-4 px-5 font-mono text-[13px] tracking-[0.05em] text-secondary outline-none uppercase placeholder-secondary/40">
                        <button id="verifyBtn" class="bg-secondary text-primary px-6 font-mono text-[11px] tracking-[0.2em] uppercase cursor-pointer transition-colors duration-300 hover:bg-[#B99B5A] hover:text-white whitespace-nowrap">Verify</button>
                    </div>
                    <p class="font-mono text-[10px] text-secondary/40 mt-4 tracking-[0.05em]">Coba klik Verify untuk melihat pratinjau interaksinya.</p>
                </div>
                
                <!-- Right -->
                <div class="p-12 md:p-16 relative min-h-[380px] flex items-center justify-center overflow-hidden">
                    <div id="scanBeam" class="absolute left-0 right-0 h-[1px] top-[110%] opacity-0 pointer-events-none" style="background: linear-gradient(to right, transparent, #B99B5A, transparent);"></div>
                    
                    <div class="relative w-[220px] h-[220px] flex items-center justify-center">
                        <div id="stampEl" class="w-full h-full rounded-full border-[1.5px] border-[#B99B5A]/35 flex items-center justify-center opacity-0 transition-all duration-500 relative" style="transform: scale(0.6) rotate(-18deg); transition-timing-function: cubic-bezier(0.22, 1, 0.36, 1);">
                            <div class="absolute inset-[14px] rounded-full border border-[#B99B5A]/35"></div>
                            <div class="text-center font-h1">
                                <div class="text-[34px] text-[#B99B5A] leading-none mb-2">✓</div>
                                <div class="font-mono text-[9px] tracking-[0.2em] text-secondary uppercase">Authentic</div>
                                <div id="stampSerial" class="font-mono text-[9px] tracking-[0.1em] text-secondary/60 mt-2">CLM-2044-0071</div>
                            </div>
                        </div>
                        <p id="idleCopy" class="absolute text-center font-mono text-[11px] text-secondary/40 tracking-[0.1em] uppercase max-w-[220px] leading-[1.8] transition-opacity duration-300">Masukkan nomor sertifikat<br>untuk memulai verifikasi</p>
                    </div>
                </div>
            </div>

            <!-- Trust bar -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 pt-16 border-t border-secondary/15">
                <div>
                    <div class="font-mono text-[10px] tracking-[0.2em] text-secondary/40 uppercase mb-6">Verified in partnership with</div>
                    <div class="flex flex-wrap gap-x-10 gap-y-4 items-center">
                        <span class="font-h1 text-sm text-secondary/60 tracking-[0.02em] uppercase" style="font-weight: 500;">Swiss Assay Bureau</span>
                        <span class="font-h1 text-sm text-secondary/60 tracking-[0.02em] uppercase" style="font-weight: 500;">WOSTEP Certified</span>
                        <span class="font-h1 text-sm text-secondary/60 tracking-[0.02em] uppercase" style="font-weight: 500;">Lloyd's Insured</span>
                    </div>
                </div>
                <div class="border-l border-[#B99B5A]/35 pl-6">
                    <p class="font-h1 text-base leading-[1.6] text-secondary mb-4">"Saya bisa memindai NFC di kotaknya sebelum transfer sepeser pun. Itu yang meyakinkan saya untuk membeli lintas negara."</p>
                    <div class="font-mono text-[11px] text-secondary/40 tracking-[0.05em] uppercase">— R. Hartono, Kolektor sejak 2019</div>
                </div>
            </div>

            <a href="#" class="inline-flex items-center gap-3 mt-16 font-mono text-[11px] tracking-[0.15em] uppercase text-secondary/60 no-underline border-b border-secondary/15 pb-1 transition-colors duration-300 hover:text-secondary hover:border-secondary/40">Read the full verification policy &rarr;</a>

        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('verifyBtn');
            const input = document.getElementById('serialInput');
            const stamp = document.getElementById('stampEl');
            const beam = document.getElementById('scanBeam');
            const idle = document.getElementById('idleCopy');
            const stampSerial = document.getElementById('stampSerial');

            if (btn && input && stamp && beam && idle && stampSerial) {
                btn.addEventListener('click', () => {
                    const val = input.value.trim() || 'CLM-2044-0071';
                    stampSerial.textContent = val.toUpperCase();
                    
                    stamp.style.opacity = '0';
                    stamp.style.transform = 'scale(0.6) rotate(-18deg)';
                    idle.style.opacity = '0';

                    beam.style.transition = 'none';
                    beam.style.top = '-10%';
                    beam.style.opacity = '0.9';
                    
                    requestAnimationFrame(() => {
                        beam.style.transition = 'top 0.9s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease';
                        beam.style.top = '110%';
                    });

                    setTimeout(() => {
                        beam.style.opacity = '0';
                        stamp.style.opacity = '1';
                        stamp.style.transform = 'scale(1) rotate(0deg)';
                    }, 950);
                });
            }
        });
    </script>
`;

let targetIdx = lines.findIndex(l => l.includes('@if(isset($theDrop) && $theDrop->isNotEmpty())'));
if (targetIdx !== -1) {
    lines.splice(targetIdx, 0, html);
    fs.writeFileSync(path, lines.join('\n'));
    console.log('Inserted successfully at line ' + (targetIdx + 1));
} else {
    console.log('Target line not found');
}
