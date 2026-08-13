@extends('layouts.app')

@section('title', 'Clementine Documentation')
@section('meta_description', 'Everything you need to know about Clementine — authentication process, ordering, shipping, returns, warranty, membership tiers, privacy policy, and frequently asked questions.')

@section('content')
<div class="w-full bg-primary text-secondary min-h-screen pt-24 pb-24">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 flex flex-col md:flex-row gap-12 relative" x-data="{ mobileNavOpen: false }">
        
        <!-- Nav Toggle (Mobile ONLY) -->
        <button @click="mobileNavOpen = !mobileNavOpen" class="md:hidden w-full flex items-center justify-between border-b border-secondary/20 pb-4 mb-4 font-mono text-xs tracking-[0.2em] uppercase text-secondary">
            <span>Documentation Menu</span>
            <span class="material-symbols-outlined text-[16px]" x-text="mobileNavOpen ? 'close' : 'menu'">menu</span>
        </button>

        <!-- Sidebar Navigation -->
        <aside class="fixed inset-y-0 left-0 z-[100] w-[95%] sm:w-96 bg-primary border-r border-secondary/20 p-8 transform transition-transform duration-300 -translate-x-full overflow-y-auto hidden-scrollbar md:!relative md:!translate-x-0 md:!w-80 md:!border-none md:!p-0 md:!bg-transparent md:!flex-shrink-0 md:!sticky md:!top-32 md:!max-h-[calc(100vh-8rem)] md:!overflow-y-auto md:!z-auto md:!inset-auto"
               :class="mobileNavOpen ? '!translate-x-0' : ''"
               data-lenis-prevent>
            
            <div class="flex justify-between items-start mb-12 border-b border-secondary/20 pb-6">
                <div>
                    <span class="font-mono text-[10px] tracking-[0.25em] uppercase text-secondary/40 block mb-2">Protocol 01</span>
                    <h1 class="font-h1 text-2xl uppercase tracking-wide">Documentation</h1>
                </div>
                <button @click="mobileNavOpen = false" class="text-secondary hover:text-white p-2 -mr-2 md:hidden">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            
            <nav class="flex flex-col gap-6 font-mono text-[11px] tracking-[0.1em] uppercase" @click="mobileNavOpen = false">
                <div>
                    <a href="#authentication" class="text-secondary/70 hover:text-secondary block mb-3 transition-colors">Authentication & Provenance</a>
                    <div class="flex flex-col gap-2 pl-4 border-l border-secondary/15 text-[10px] text-secondary/40">
                        <a href="#auth-process" class="hover:text-secondary transition-colors">How We Verify</a>
                        <a href="#auth-certificate" class="hover:text-secondary transition-colors">Reading Your Certificate</a>
                        <a href="#auth-lookup" class="hover:text-secondary transition-colors">Certificate Lookup</a>
                        <a href="#auth-report" class="hover:text-secondary transition-colors">Reporting Counterfeits</a>
                    </div>
                </div>
                
                <div>
                    <a href="#ordering" class="text-secondary/70 hover:text-secondary block mb-3 transition-colors">Ordering & The Drop</a>
                    <div class="flex flex-col gap-2 pl-4 border-l border-secondary/15 text-[10px] text-secondary/40">
                        <a href="#order-allocation" class="hover:text-secondary transition-colors">Allocation & VIP Window</a>
                        <a href="#order-payment" class="hover:text-secondary transition-colors">Accepted Payments</a>
                        <a href="#order-taxes" class="hover:text-secondary transition-colors">Taxes & Duties</a>
                    </div>
                </div>

                <div>
                    <a href="#shipping" class="text-secondary/70 hover:text-secondary block mb-3 transition-colors">Shipping & Insurance</a>
                    <div class="flex flex-col gap-2 pl-4 border-l border-secondary/15 text-[10px] text-secondary/40">
                        <a href="#ship-times" class="hover:text-secondary transition-colors">Delivery Estimates</a>
                        <a href="#ship-insurance" class="hover:text-secondary transition-colors">Insurance Partners</a>
                        <a href="#ship-handover" class="hover:text-secondary transition-colors">Handover Process</a>
                    </div>
                </div>

                <div>
                    <a href="#returns" class="text-secondary/70 hover:text-secondary block mb-3 transition-colors">Returns & Warranty</a>
                    <div class="flex flex-col gap-2 pl-4 border-l border-secondary/15 text-[10px] text-secondary/40">
                        <a href="#return-window" class="hover:text-secondary transition-colors">Window & Conditions</a>
                        <a href="#return-warranty" class="hover:text-secondary transition-colors">Warranty Coverage</a>
                        <a href="#return-claim" class="hover:text-secondary transition-colors">Claim Process</a>
                    </div>
                </div>

                <div>
                    <a href="#membership" class="text-secondary/70 hover:text-secondary block mb-3 transition-colors">Membership / The Club</a>
                    <div class="flex flex-col gap-2 pl-4 border-l border-secondary/15 text-[10px] text-secondary/40">
                        <a href="#club-tiers" class="hover:text-secondary transition-colors">Tiers & Benefits</a>
                    </div>
                </div>
                
                <a href="#privacy" class="text-secondary/70 hover:text-secondary block transition-colors">Privacy & Data</a>
                <a href="#terms" class="text-secondary/70 hover:text-secondary block transition-colors">Terms of Service</a>
                <a href="#faq" class="text-secondary/70 hover:text-secondary block transition-colors">FAQ</a>
            </nav>
        </aside>

        <!-- Nav Backdrop -->
        <div x-cloak x-show="mobileNavOpen" x-transition.opacity class="fixed inset-0 z-[90] bg-black/80 backdrop-blur-sm md:hidden" @click="mobileNavOpen = false"></div>

        <!-- Main Content Area -->
        <main class="flex-1 font-body-md text-sm md:text-[15px] leading-relaxed text-secondary/70 space-y-32">
            
            <!-- SECTION 1: Authentication & Provenance -->
            <section id="authentication" class="scroll-mt-32">
                <div class="border-b border-secondary/15 pb-8 mb-12">
                    <h2 class="font-h1 text-4xl text-secondary uppercase tracking-wide">Authentication & Provenance</h2>
                </div>

                <div id="auth-process" class="mb-16">
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">How We Verify Every Unit</h3>
                    <p class="mb-4">Every timepiece entering the Clementine catalog undergoes a four-stage independent verification protocol. We do not rely on third-party assertions or box-and-papers alone; every watch is physically inspected by our internal team of AWCI and WOSTEP certified watchmakers.</p>
                    <p class="mb-4">The movement, case, dial, and hands are examined under high magnification. We cross-reference part numbers, finishing techniques, and serial date ranges against official manufacturer archives. If a piece cannot be definitively authenticated down to the caliber components, it does not enter our vault.</p>
                </div>

                <div id="auth-certificate" class="mb-16">
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">Reading Your Certificate & NFC Tag</h3>
                    <p class="mb-4">Upon successful inspection, a unique digital certificate is generated and permanently linked to the movement's serial number. This creates an immutable digital ledger of provenance.</p>
                    <p class="mb-4">Additionally, a hidden encrypted NFC tag is embedded within the physical packaging of your timepiece. By tapping your smartphone against the designated area on the presentation box, you can instantly load your digital certificate, verify ownership history, and view the original inspection notes.</p>
                </div>

                <!-- Functional Certificate Lookup Tool -->
                <div id="auth-lookup" class="mb-16 p-8 md:p-12 border border-secondary/20 bg-white/[0.02]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                        <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest">Certificate Lookup Tool</h3>
                    </div>
                    <p class="font-mono text-[11px] uppercase tracking-widest text-secondary/50 mb-8">Official Database Query</p>
                    
                    <form id="certLookupForm" class="flex flex-col sm:flex-row gap-4 mb-8">
                        <input type="text" id="cert_sn" name="certificate_sn" placeholder="e.g. CLM-26X1Y2Z3A" value="{{ $searched_sn ?? '' }}" required class="flex-1 bg-transparent border border-secondary/30 py-4 px-5 font-mono text-sm tracking-widest text-secondary outline-none uppercase placeholder-secondary/30 focus:border-secondary transition-colors">
                        <button type="submit" id="certSubmitBtn" class="bg-secondary text-primary px-8 py-4 font-mono text-[11px] tracking-[0.2em] uppercase transition-colors duration-300 hover:bg-white hover:text-black whitespace-nowrap">
                            Verify Ledger
                        </button>
                    </form>

                    <!-- Result Container -->
                    <div id="certResultContainer" class="hidden border-t border-secondary/15 pt-8 mt-4">
                        <div id="certLoading" class="hidden text-center py-8">
                            <span class="font-mono text-[11px] tracking-[0.2em] uppercase text-secondary/50 animate-pulse">Querying Immutable Ledger...</span>
                        </div>
                        
                        <div id="certSuccess" class="hidden">
                            <div class="flex items-center gap-3 mb-6">
                                <span class="text-green-500 text-xl">✓</span>
                                <span class="font-mono text-[11px] tracking-[0.2em] uppercase text-green-500">Verified Authentic</span>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-12 font-mono text-xs">
                                <div>
                                    <div class="text-secondary/40 uppercase tracking-widest mb-1">Serial Number</div>
                                    <div id="res-sn" class="text-secondary tracking-widest"></div>
                                </div>
                                <div>
                                    <div class="text-secondary/40 uppercase tracking-widest mb-1">Timepiece</div>
                                    <div id="res-product" class="text-secondary tracking-widest"></div>
                                </div>
                                <div>
                                    <div class="text-secondary/40 uppercase tracking-widest mb-1">Date of Issue</div>
                                    <div id="res-date" class="text-secondary tracking-widest"></div>
                                </div>
                                <div>
                                    <div class="text-secondary/40 uppercase tracking-widest mb-1">Strap Spec</div>
                                    <div id="res-strap" class="text-secondary tracking-widest"></div>
                                </div>
                            </div>
                        </div>

                        <div id="certError" class="hidden">
                            <div class="flex items-center gap-3">
                                <span class="text-red-500 text-xl">✕</span>
                                <span class="font-mono text-[11px] tracking-[0.2em] uppercase text-red-500">Certificate Not Found</span>
                            </div>
                            <p class="mt-4 font-mono text-xs text-secondary/50">The serial number provided does not exist in our active ledger. Please check the spelling or contact concierge.</p>
                        </div>
                    </div>
                </div>

                <div id="auth-report">
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">Reporting Suspected Counterfeits</h3>
                    <p>In the highly unlikely event that a timepiece authenticated by Clementine is proven to contain non-original parts not disclosed at the time of sale, we offer a full buyback guarantee valid for the lifetime of the original purchaser's ownership. Please contact our concierge immediately with your certificate serial number to initiate an independent third-party review.</p>
                </div>
            </section>

            <!-- SECTION 2: Ordering & The Drop -->
            <section id="ordering" class="scroll-mt-32">
                <div class="border-b border-secondary/15 pb-8 mb-12">
                    <h2 class="font-h1 text-4xl text-secondary uppercase tracking-wide">Ordering & The Drop</h2>
                </div>

                <div id="order-allocation" class="mb-16">
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">Allocation System & VIP Window</h3>
                    <p class="mb-4">Due to the extreme scarcity of the timepieces we curate, our acquisitions operate on a "Drop" model. Collections are announced in advance but are strictly first-come, first-served upon release.</p>
                    <p>Members holding VIP status (accumulated over $10,000 in acquisitions) are granted a private 24-hour Early Access window before public drops. During this window, allocation is guaranteed upon successful checkout, though highly limited pieces may still sell out within minutes.</p>
                </div>

                <div id="order-payment" class="mb-16">
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">Accepted Payment Methods</h3>
                    <p>We accept major Credit Cards (Visa, MasterCard, Amex via secure Stripe integration), Direct Bank Transfers (Virtual Account), and our proprietary secure wallet, Clementpay. Clementpay guarantees instant checkout, which is critical during high-demand Drop events where milliseconds matter.</p>
                </div>

                <div id="order-taxes">
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">Taxes & International Duties</h3>
                    <p>All prices listed are exclusive of destination-specific import duties and local taxes. For international shipments, the buyer is solely responsible for any customs fees, VAT, or import tariffs levied by their respective country's border authority. We declare the exact purchase value for insurance purposes; we do not under-declare values under any circumstances.</p>
                </div>
            </section>

            <!-- SECTION 3: Shipping & Insurance -->
            <section id="shipping" class="scroll-mt-32">
                <div class="border-b border-secondary/15 pb-8 mb-12">
                    <h2 class="font-h1 text-4xl text-secondary uppercase tracking-wide">Shipping & Insurance</h2>
                </div>

                <div id="ship-times" class="mb-16">
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">Delivery Estimates by Region</h3>
                    <p class="mb-4">Upon successful payment verification, your timepiece enters final preparation and staging. Dispatch typically occurs within 48 hours.</p>
                    <ul class="list-disc pl-6 space-y-2 mt-4 text-secondary/60">
                        <li><strong>Domestic (ID):</strong> 1-2 Business Days via Armored Courier or Next-Day Air.</li>
                        <li><strong>Asia-Pacific:</strong> 3-5 Business Days.</li>
                        <li><strong>Europe & Americas:</strong> 5-7 Business Days.</li>
                    </ul>
                </div>

                <div id="ship-insurance" class="mb-16">
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">Insurance Partners & Claims</h3>
                    <p>From the moment it leaves our vault until it is signed for by you, your timepiece remains under full comprehensive insurance coverage via our partners at Lloyd's of London. In the event of loss or severe transit damage, Clementine handles the claim entirely and guarantees a full refund or replacement allocation.</p>
                </div>

                <div id="ship-handover">
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">The Handover Process</h3>
                    <p>Delivery of any Clementine timepiece requires verifiable proof of identity and a direct signature from the named purchaser. We do not allow packages to be dropped off without signature or rerouted to alternate addresses once dispatched. Please ensure you are available at the delivery location or schedule a pickup at an authorized secure facility.</p>
                </div>
            </section>

            <!-- SECTION 4: Returns & Warranty -->
            <section id="returns" class="scroll-mt-32">
                <div class="border-b border-secondary/15 pb-8 mb-12">
                    <h2 class="font-h1 text-4xl text-secondary uppercase tracking-wide">Returns & Warranty</h2>
                </div>

                <div id="return-window" class="mb-16">
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">Return Window & Conditions</h3>
                    <p>We offer a strict 7-day return window from the date of delivery. The timepiece must remain unworn, with all tamper-evident seals intact, and include all original packaging, tags, and digital certificates. Due to the high value and logistical complexity of these items, a restocking fee may apply unless the return is due to a misrepresentation in our catalog.</p>
                </div>

                <div id="return-warranty" class="mb-16">
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">Warranty: Clementine vs Manufacturer</h3>
                    <p>For modern timepieces, the original manufacturer's warranty applies (often 2-5 years). For vintage or discontinued pieces, Clementine provides a complimentary 1-year mechanical warranty covering the movement's functionality under normal use. This warranty does not cover water damage, magnetism, or shock resulting from drops.</p>
                </div>

                <div id="return-claim">
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">Warranty Claim Process</h3>
                    <p>To initiate a warranty claim, contact the concierge desk with your Certificate Serial Number and a description of the issue. We will arrange secure, insured transit for the timepiece back to our Swiss-certified workshop for diagnosis and repair.</p>
                </div>
            </section>

            <!-- SECTION 5: Membership / The Club -->
            <section id="membership" class="scroll-mt-32">
                <div class="border-b border-secondary/15 pb-8 mb-12">
                    <h2 class="font-h1 text-4xl text-secondary uppercase tracking-wide">Membership / The Club</h2>
                </div>

                <div id="club-tiers">
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">Membership Tiers & Benefits</h3>
                    <p class="mb-4">Clementine operates a closed-loop membership ecosystem. Creating an account grants you Standard access to purchase during public drops.</p>
                    <p>Reaching a cumulative spend of $10,000 elevates you to <strong>VIP Status</strong>. VIPs receive:</p>
                    <ul class="list-disc pl-6 space-y-2 mt-4 text-secondary/60">
                        <li>24-hour Early Access to all Drops.</li>
                        <li>Private sourcing requests (we will hunt specific references for you).</li>
                        <li>Invitations to private horology events and manufacture tours.</li>
                        <li>Complimentary insured shipping globally.</li>
                    </ul>
                </div>
            </section>

            <!-- SECTION 6: Privacy & Data -->
            <section id="privacy" class="scroll-mt-32">
                <div class="border-b border-secondary/15 pb-8 mb-12">
                    <h2 class="font-h1 text-4xl text-secondary uppercase tracking-wide">Privacy & Data</h2>
                </div>

                <div>
                    <h3 class="font-h1 text-xl text-secondary uppercase tracking-widest mb-6">What We Store & Why</h3>
                    <p class="mb-4">We maintain strict confidentiality regarding our client list. We store only the data necessary to fulfill orders, maintain warranty records, and comply with international anti-money laundering (AML) regulations.</p>
                    <p>Your payment details are never stored on our servers; they are vaulted by our PCI-DSS compliant payment gateways. The immutable ledger powering our certificates stores only the serial numbers and associated metadata, never personally identifiable information linking a specific individual to a timepiece publicly.</p>
                </div>
            </section>

            <!-- SECTION 7: Terms of Service -->
            <section id="terms" class="scroll-mt-32">
                <div class="border-b border-secondary/15 pb-8 mb-12">
                    <h2 class="font-h1 text-4xl text-secondary uppercase tracking-wide">Terms of Service</h2>
                </div>

                <div>
                    <p class="mb-4">By accessing the Clementine platform and participating in our Drops, you agree to abide by our terms of service. We reserve the right to refuse service, cancel orders, or revoke VIP status if we detect bot usage, fraudulent payment attempts, or attempts to instantly flip allocations on gray market platforms.</p>
                    <p>All content, photography, and intellectual property on this site is owned by Clementine and may not be reproduced without explicit written consent.</p>
                </div>
            </section>

            <!-- SECTION 8: FAQ -->
            <section id="faq" class="scroll-mt-32">
                <div class="border-b border-secondary/15 pb-8 mb-12">
                    <h2 class="font-h1 text-4xl text-secondary uppercase tracking-wide">Frequently Asked Questions</h2>
                </div>

                <div class="space-y-8">
                    <div>
                        <h4 class="font-h1 text-lg text-secondary uppercase mb-2">Can I reserve a watch before a drop?</h4>
                        <p>No. To maintain fairness, we do not accept reservations or pre-payments. VIPs gain early access, but it remains first-come, first-served during that window.</p>
                    </div>
                    <div>
                        <h4 class="font-h1 text-lg text-secondary uppercase mb-2">Do you accept crypto?</h4>
                        <p>Currently, we do not directly accept cryptocurrency. However, we are exploring secure stablecoin integrations for future drops.</p>
                    </div>
                    <div>
                        <h4 class="font-h1 text-lg text-secondary uppercase mb-2">Can I sell my watch back to Clementine?</h4>
                        <p>Yes. If you purchased a timepiece from us and wish to sell it, we offer a streamlined consignment or direct buyback process for VIP members.</p>
                    </div>
                </div>
            </section>
            
        </main>
    </div>
</div>

<!-- AJAX Logic for Certificate Lookup -->
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // =========================================================
        // 1. AJAX CERTIFICATE LOOKUP (kode lama kamu, tidak berubah)
        // =========================================================
        const form = document.getElementById('certLookupForm');
        const submitBtn = document.getElementById('certSubmitBtn');
        const resContainer = document.getElementById('certResultContainer');
        const loading = document.getElementById('certLoading');
        const success = document.getElementById('certSuccess');
        const error = document.getElementById('certError');
        if (form) {
            @if(isset($searched_sn) && $searched_sn)
                setTimeout(() => {
                    form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                    document.getElementById('authentication').scrollIntoView({ behavior: 'smooth' });
                }, 500);
            @endif

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const sn = document.getElementById('cert_sn').value;

                resContainer.classList.remove('hidden');
                loading.classList.remove('hidden');
                success.classList.add('hidden');
                error.classList.add('hidden');
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';

                try {
                    const response = await fetch(`/docs/verify?certificate_sn=${encodeURIComponent(sn)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    loading.classList.add('hidden');

                    if (data.success) {
                        document.getElementById('res-sn').textContent = data.certificate.sn;
                        document.getElementById('res-product').textContent = data.certificate.product_name;
                        document.getElementById('res-date').textContent = data.certificate.date;
                        document.getElementById('res-strap').textContent = data.certificate.strap;
                        success.classList.remove('hidden');
                    } else {
                        error.classList.remove('hidden');
                    }
                } catch (err) {
                    loading.classList.add('hidden');
                    error.classList.remove('hidden');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                }
            });
        }

        // =========================================================
        // 2. SMOOTH SCROLL SAAT LINK SIDEBAR DIKLIK
        // =========================================================
        const navLinks = document.querySelectorAll('nav a[href^="#"]');

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const targetId = link.getAttribute('href').slice(1);
                const targetEl = document.getElementById(targetId);
                if (!targetEl) return;

                e.preventDefault();

                // Gunakan lenis.scrollTo agar kompatibel dengan Lenis smooth scroll
                // offset -128 = kompensasi sticky nav (~8rem)
                if (window.lenis) {
                    window.lenis.scrollTo(targetEl, { offset: -128, duration: 1.2 });
                } else {
                    targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                // update URL hash tanpa jump instan
                history.pushState(null, '', `#${targetId}`);
            });
        });

        // =========================================================
        // 3. SCROLL-SPY: highlight otomatis sub-bab sesuai posisi scroll
        // =========================================================
        // Ambil SEMUA elemen yang punya id dan dituju oleh link sidebar
        const sectionIds = Array.from(navLinks).map(a => a.getAttribute('href').slice(1));
        const sections = sectionIds
            .map(id => document.getElementById(id))
            .filter(Boolean);

        // Map id -> link, supaya lookup cepat
        const linkMap = {};
        navLinks.forEach(link => {
            const id = link.getAttribute('href').slice(1);
            linkMap[id] = link;
        });

        function setActiveLink(id) {
            navLinks.forEach(link => {
                link.classList.remove('text-white', 'font-bold');
                link.classList.add('text-secondary/40');
            });

            const activeLink = linkMap[id];
            if (activeLink) {
                activeLink.classList.remove('text-secondary/40');
                activeLink.classList.add('text-white', 'font-bold');

                // auto-scroll sidebar itu sendiri supaya link aktif selalu terlihat
                activeLink.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'nearest'
                });
            }
        }

        // rootMargin: trigger saat section berada di ~20% teratas viewport,
        // supaya highlight berpindah tepat saat section itu jadi fokus utama
        const observer = new IntersectionObserver((entries) => {
            // Cari entry yang paling "terlihat" (intersectionRatio terbesar) di antara yang isIntersecting
            const visibleEntries = entries.filter(e => e.isIntersecting);

            if (visibleEntries.length > 0) {
                // Ambil yang paling atas (paling dekat ke top viewport)
                const topMost = visibleEntries.reduce((a, b) => {
                    return a.boundingClientRect.top < b.boundingClientRect.top ? a : b;
                });
                setActiveLink(topMost.target.id);
            }
        }, {
            root: null,
            rootMargin: '-15% 0px -70% 0px', // zona "aktif" ada di 15%-30% dari atas viewport
            threshold: 0
        });

        sections.forEach(section => observer.observe(section));

        // Set active link awal berdasarkan hash URL saat page load (kalau ada)
        if (window.location.hash) {
            const initialId = window.location.hash.slice(1);
            if (linkMap[initialId]) setActiveLink(initialId);
        }
    });
</script>
@endsection
