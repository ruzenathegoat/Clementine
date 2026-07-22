<footer class="w-full flex flex-col bg-background text-primary overflow-hidden relative z-50 border-t border-primary/20" id="editorial-footer">
    
    <!-- Hero Footer (Visual Closing Statement) -->
    <div class="w-full min-h-[50vh] md:min-h-[70vh] flex items-center justify-center relative overflow-hidden px-4 py-24" id="footer-hero">
        <!-- Subtle vertical grid lines -->
        <div class="absolute inset-0 pointer-events-none flex justify-between px-8 md:px-24 opacity-0" id="footer-grid">
            <div class="w-[1px] h-full bg-primary/10"></div>
            <div class="w-[1px] h-full bg-primary/10 hidden md:block"></div>
            <div class="w-[1px] h-full bg-primary/10"></div>
        </div>

        <!-- Background Monogram -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden z-0" id="footer-monogram-container">
            <div class="font-h1 text-[150vw] md:text-[80vw] leading-none text-primary" id="footer-monogram" style="opacity: 0; transform: translateY(12px);">
                CH
            </div>
        </div>
        
        <!-- Large Typography -->
        <div class="relative z-10 w-full overflow-hidden flex justify-center" id="footer-title-container">
            <h1 class="font-h1 text-[clamp(4rem,15vw,22rem)] leading-[0.8] tracking-tighter uppercase text-center text-primary" id="footer-title" style="transform: translateY(100%);">
                CLEMENTINE
            </h1>
        </div>
        
        <!-- Footer Arrival Background -->
        <div class="absolute inset-0 bg-background z-20 pointer-events-none" id="footer-arrival-bg"></div>
    </div>
    
    <!-- Footer Navigation & Newsletter -->
    <div class="w-full px-lg md:px-3xl py-3xl bg-background relative z-10" id="footer-nav-area" style="opacity: 0;">
        <div class="max-w-[1600px] mx-auto flex flex-col lg:flex-row justify-between items-start gap-24">
            
            <!-- Links Grid -->
            <div class="flex-1 grid grid-cols-2 md:grid-cols-3 gap-x-12 gap-y-16 font-body-md text-sm uppercase tracking-widest footer-nav-col">
                <div class="flex flex-col gap-8 items-start footer-col">
                    <button data-modal-target="about" class="footer-link relative group text-left block w-fit">
                        <span class="relative z-10 block transition-all duration-300 origin-left">ABOUT</span>
                        <span class="absolute bottom-[-4px] left-0 w-full h-[1px] bg-primary scale-x-0 origin-left"></span>
                    </button>
                    <button data-modal-target="faq" class="footer-link relative group text-left block w-fit">
                        <span class="relative z-10 block transition-all duration-300 origin-left">FAQ</span>
                        <span class="absolute bottom-[-4px] left-0 w-full h-[1px] bg-primary scale-x-0 origin-left"></span>
                    </button>
                    <button data-modal-target="contact" class="footer-link relative group text-left block w-fit">
                        <span class="relative z-10 block transition-all duration-300 origin-left">CONTACT</span>
                        <span class="absolute bottom-[-4px] left-0 w-full h-[1px] bg-primary scale-x-0 origin-left"></span>
                    </button>
                </div>
                <div class="flex flex-col gap-8 items-start footer-col">
                    <button data-modal-target="products" class="footer-link relative group text-left block w-fit">
                        <span class="relative z-10 block transition-all duration-300 origin-left">PRODUCTS</span>
                        <span class="absolute bottom-[-4px] left-0 w-full h-[1px] bg-primary scale-x-0 origin-left"></span>
                    </button>
                    <button data-modal-target="privacy" class="footer-link relative group text-left block w-fit">
                        <span class="relative z-10 block transition-all duration-300 origin-left">PRIVACY</span>
                        <span class="absolute bottom-[-4px] left-0 w-full h-[1px] bg-primary scale-x-0 origin-left"></span>
                    </button>
                    <button data-modal-target="refund" class="footer-link relative group text-left block w-fit">
                        <span class="relative z-10 block transition-all duration-300 origin-left">REFUND</span>
                        <span class="absolute bottom-[-4px] left-0 w-full h-[1px] bg-primary scale-x-0 origin-left"></span>
                    </button>
                </div>
                <div class="flex flex-col gap-8 items-start footer-col">
                    <button data-modal-target="support" class="footer-link relative group text-left block w-fit">
                        <span class="relative z-10 block transition-all duration-300 origin-left">SUPPORT</span>
                        <span class="absolute bottom-[-4px] left-0 w-full h-[1px] bg-primary scale-x-0 origin-left"></span>
                    </button>
                    <button data-modal-target="order" class="footer-link relative group text-left block w-fit">
                        <span class="relative z-10 block transition-all duration-300 origin-left">ORDER</span>
                        <span class="absolute bottom-[-4px] left-0 w-full h-[1px] bg-primary scale-x-0 origin-left"></span>
                    </button>
                    <button data-modal-target="tracking" class="footer-link relative group text-left block w-fit">
                        <span class="relative z-10 block transition-all duration-300 origin-left">TRACKING</span>
                        <span class="absolute bottom-[-4px] left-0 w-full h-[1px] bg-primary scale-x-0 origin-left"></span>
                    </button>
                </div>
            </div>
            
            <!-- Newsletter -->
            <div class="flex-1 w-full max-w-[500px] flex flex-col gap-10 opacity-0" id="footer-newsletter">
                <h3 class="font-h1 text-[32px] md:text-[40px] uppercase tracking-tight leading-none">JOIN THE CLEMENTINE LETTER</h3>
                <p class="font-body-md text-sm uppercase tracking-widest text-primary/70 leading-relaxed">
                    Monthly editorials.<br>
                    Mechanical stories.<br>
                    Rare releases.
                </p>
                
                <form id="newsletter-form" class="relative mt-8 group" method="POST" action="{{ route('newsletter.store') }}">
                    @csrf
                    <!-- Input Wrapper -->
                    <div class="relative w-full overflow-hidden">
                        <!-- Scanning line border -->
                        <div class="absolute bottom-0 left-0 w-full h-[1px] bg-primary/20"></div>
                        <div class="absolute bottom-0 left-0 w-full h-[1px] bg-primary scale-x-0 origin-left" id="nl-border"></div>
                        
                        <!-- Floating Label -->
                        <label for="nl-email" class="absolute left-0 top-6 font-body-md text-xs uppercase tracking-widest text-primary/60 transition-all duration-300 pointer-events-none origin-left" id="nl-label">ENTER YOUR EMAIL</label>
                        
                        <!-- Input -->
                        <input type="email" id="nl-email" name="email" required class="w-full bg-transparent pt-8 pb-3 font-body-md text-sm uppercase tracking-widest focus:outline-none text-primary caret-transparent" autocomplete="off" />
                        
                        <!-- Mechanical Pulse Caret -->
                        <div class="absolute bottom-4 left-0 w-[8px] h-[2px] bg-primary opacity-0 pointer-events-none" id="nl-caret"></div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="absolute right-0 bottom-2 bg-transparent text-primary flex items-center justify-center p-2" id="nl-submit">
                        <span class="material-symbols-outlined text-[20px] transition-transform duration-300" id="nl-arrow">arrow_forward</span>
                    </button>
                    
                    <!-- Loading & Success -->
                    <div class="absolute right-4 bottom-4 w-12 h-[1px] bg-primary scale-x-0 origin-right" id="nl-loading"></div>
                    <div class="absolute right-0 bottom-3 text-primary opacity-0 flex items-center gap-2" id="nl-success">
                        <span class="material-symbols-outlined text-[16px]">check</span>
                        <span class="font-mono text-[10px] uppercase tracking-widest">THANK YOU</span>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Bottom Divider -->
        <div class="w-full h-[1px] bg-primary mt-32 mb-8 scale-x-0 origin-left" id="footer-bottom-divider"></div>
        
        <!-- Bottom Copyright & Socials -->
        <div class="flex flex-col md:flex-row justify-between items-center font-body-md text-[10px] md:text-xs font-bold uppercase tracking-widest gap-8 opacity-0" id="footer-bottom-area">
            <div class="text-primary/60">©2026 CLEMENTINE. ALL RIGHTS RESERVED.</div>
            <div class="flex gap-12">
                <a href="https://www.instagram.com" target="_blank" class="social-link block origin-center">INSTAGRAM</a>
                <a href="#" class="social-link block origin-center">TWITTER</a>
                <a href="#" class="social-link block origin-center">FACEBOOK</a>
                <a href="#" class="social-link block origin-center">LINKEDIN</a>
            </div>
        </div>
    </div>

    <!-- Editorial System Modals -->
    <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 md:p-8 pointer-events-none opacity-0" id="editorial-modal">
        <!-- Blur Backdrop -->
        <div class="absolute inset-0 bg-background/5" style="backdrop-filter: blur(0px);" id="modal-backdrop"></div>
        <div class="absolute inset-0 bg-primary/0 pointer-events-none" id="modal-overlay"></div>
        
        <!-- Panel -->
        <div class="relative bg-background text-primary border border-primary w-full max-w-[700px] max-h-[85vh] overflow-y-auto flex flex-col p-8 md:p-16 opacity-0 translate-y-5" id="modal-panel">
            
            <button id="modal-close" class="absolute top-8 right-8 text-primary transition-transform duration-300 flex items-center justify-center p-2 group">
                <span class="material-symbols-outlined font-light text-3xl group-hover:rotate-45 transition-transform duration-300">close</span>
            </button>
            
            <div class="font-h1 text-[32px] md:text-[48px] leading-none uppercase tracking-tighter mb-12 opacity-0" id="modal-header"></div>
            <div class="font-body-md text-sm uppercase tracking-widest leading-relaxed opacity-0" id="modal-body"></div>
        </div>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Reduced Motion Check
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // 1. Footer Arrival
        const footer = document.getElementById('editorial-footer');
        if (footer) {
            const tlFooter = gsap.timeline({
                scrollTrigger: {
                    trigger: footer,
                    start: 'top 75%',
                    once: true
                }
            });
            
            // bg fades 0 -> 100% 250ms
            tlFooter.to('#footer-arrival-bg', { opacity: 0, duration: 0.25, ease: 'none' });
            
            if (!prefersReducedMotion) {
                // CLEMENTINE reveals using vertical mask bottom -> top (1.1s, power4.out)
                tlFooter.to('#footer-title', { y: 0, duration: 1.1, ease: 'power4.out' }, '+=0');
                
                // 180ms delay -> background monogram slides upward 12px, opacity 0->12%, 1.5s
                tlFooter.to('#footer-monogram', { y: 0, opacity: 0.08, duration: 1.5, ease: 'power2.out' }, '+=0.18');
                
                // Show grid
                tlFooter.to('#footer-grid', { opacity: 1, duration: 1 }, '<');
            } else {
                tlFooter.set('#footer-title', { y: 0 });
                tlFooter.set('#footer-monogram', { y: 0, opacity: 0.08 });
            }

            // navigation appears column by column, 40ms stagger
            tlFooter.to('#footer-nav-area', { opacity: 1, duration: 0.5 }, '-=1');
            
            if (!prefersReducedMotion) {
                tlFooter.from('.footer-col', { opacity: 0, y: 10, duration: 0.6, stagger: 0.04, ease: 'power2.out' }, '-=0.5');
            }
            
            // Newsletter appears last
            tlFooter.to('#footer-newsletter', { opacity: 1, duration: 0.6, ease: 'power2.out' }, '-=0.2');
            
            // Bottom Divider draws left -> right 900ms
            tlFooter.to('#footer-bottom-divider', { scaleX: 1, duration: 0.9, ease: 'power3.out' }, '-=0.4');
            
            // Bottom Area
            tlFooter.to('#footer-bottom-area', { opacity: 1, duration: 0.5 }, '-=0.5');
            
            
            // 2. Huge Typography Interaction (Parallax)
            if (!prefersReducedMotion) {
                const title = document.getElementById('footer-title');
                const monogram = document.getElementById('footer-monogram');
                
                footer.addEventListener('mousemove', (e) => {
                    const rect = footer.getBoundingClientRect();
                    const centerX = rect.left + rect.width / 2;
                    const centerY = rect.top + rect.height / 2;
                    const moveX = (e.clientX - centerX) / rect.width;
                    const moveY = (e.clientY - centerY) / rect.height;
                    
                    gsap.to(title, { x: moveX * 4, y: moveY * 4, duration: 1, ease: 'power2.out' });
                    gsap.to(monogram, { x: moveX * 8, y: moveY * 8, duration: 1.2, ease: 'power2.out' });
                });
                
                // 8. Background Monogram Scroll drift
                gsap.to(monogram, {
                    y: -15,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: footer,
                        start: 'top bottom',
                        end: 'bottom top',
                        scrub: true
                    }
                });
            }
            
            // 3. Newsletter Input
            const nlInput = document.getElementById('nl-email');
            const nlLabel = document.getElementById('nl-label');
            const nlBorder = document.getElementById('nl-border');
            const nlForm = document.getElementById('newsletter-form');
            const nlArrow = document.getElementById('nl-arrow');
            const nlSubmit = document.getElementById('nl-submit');
            const nlLoading = document.getElementById('nl-loading');
            const nlSuccess = document.getElementById('nl-success');
            const nlCaret = document.getElementById('nl-caret');
            
            let caretAnim;
            
            nlForm.addEventListener('mouseenter', () => {
                if(document.activeElement !== nlInput) {
                    gsap.to(nlBorder, { scaleX: 1, duration: 0.35, ease: 'power2.out' });
                    gsap.to(nlArrow, { x: 4, duration: 0.2, ease: 'power2.out', yoyo: true, repeat: 1 });
                }
            });
            
            nlForm.addEventListener('mouseleave', () => {
                if(document.activeElement !== nlInput) {
                    gsap.to(nlBorder, { scaleX: 0, duration: 0.35, ease: 'power2.out' });
                }
            });
            
            nlInput.addEventListener('focus', () => {
                gsap.to(nlBorder, { scaleX: 1, duration: 0.35, ease: 'power2.out' });
                gsap.to(nlLabel, { y: -16, scale: 0.7, opacity: 0.4, duration: 0.3, ease: 'power2.out' });
                // Mechanical pulse caret
                caretAnim = gsap.fromTo(nlCaret, 
                    { opacity: 1 }, 
                    { opacity: 0, duration: 0.1, delay: 0.9, repeat: -1, repeatDelay: 0.9, ease: 'steps(1)' }
                );
            });
            
            nlInput.addEventListener('blur', () => {
                if(nlInput.value === '') {
                    gsap.to(nlLabel, { y: 0, scale: 1, opacity: 0.6, duration: 0.3, ease: 'power2.out' });
                    gsap.to(nlBorder, { scaleX: 0, duration: 0.35, ease: 'power2.out' });
                }
                if(caretAnim) caretAnim.kill();
                gsap.set(nlCaret, { opacity: 0 });
            });
            
            nlInput.addEventListener('input', () => {
                // simple caret tracking hack (approximate)
                const charWidth = 8.5; // px per char approx
                gsap.set(nlCaret, { x: nlInput.value.length * charWidth });
                
                // reset blink on type
                if(caretAnim) caretAnim.restart();
            });
            
            // 4. Submit Interaction
            nlForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                const tlSubmit = gsap.timeline();
                
                // Arrow moves right 18px -> disappears
                tlSubmit.to(nlArrow, { x: 18, opacity: 0, duration: 0.3, ease: 'power2.in' })
                .set(nlSubmit, { display: 'none' })
                // becomes loading line -> line grows
                .to(nlLoading, { scaleX: 1, duration: 0.6, ease: 'power2.inOut' })
                // success checkmark -> line retracts -> Thank You appears
                .to(nlLoading, { scaleX: 0, transformOrigin: 'left', duration: 0.4, ease: 'power2.inOut' })
                .to(nlSuccess, { opacity: 1, x: -10, duration: 0.4, ease: 'power2.out' }, '-=0.2');
                
            });
            
            // 5. Footer Links
            const footerLinks = document.querySelectorAll('.footer-link');
            footerLinks.forEach(link => {
                const line = link.querySelector('span:nth-child(2)');
                const text = link.querySelector('span:nth-child(1)');
                
                link.addEventListener('mouseenter', () => {
                    gsap.to(line, { scaleX: 1, duration: 0.3, ease: 'power2.out' });
                    gsap.to(text, { y: -1, letterSpacing: '0.3px', duration: 0.3, ease: 'power2.out' });
                });
                link.addEventListener('mouseleave', () => {
                    gsap.to(line, { scaleX: 0, duration: 0.3, ease: 'power2.out' });
                    gsap.to(text, { y: 0, letterSpacing: 'normal', duration: 0.3, ease: 'power2.out' });
                });
            });
            
            // 6. Social Links
            const socialLinks = document.querySelectorAll('.social-link');
            socialLinks.forEach(link => {
                link.addEventListener('mouseenter', () => {
                    gsap.to(link, { letterSpacing: '1px', opacity: 0.7, duration: 0.2, ease: 'power1.out', onComplete: () => {
                        gsap.to(link, { opacity: 1, duration: 0.2, ease: 'power1.in' });
                    }});
                });
                link.addEventListener('mouseleave', () => {
                    gsap.to(link, { letterSpacing: 'normal', opacity: 1, duration: 0.3, ease: 'power2.out' });
                });
            });

            // 9. Modal Interaction
            const modalContents = {
                about: { title: 'ABOUT', body: 'CLEMENTINE is a premium horology destination focusing on uncompromising mechanical perfection. We curate the best timepieces from around the world to satisfy your aesthetic and engineering needs.' },
                faq: { title: 'FAQ', body: 'Common questions regarding our authentication protocol and shipping timelines.' },
                contact: { title: 'CONTACT', body: 'For direct inquiries, our concierge is available via encrypted channel. Expect a reply within 24 hours.' },
                products: { title: 'PRODUCTS', body: 'All available mechanical timepieces, verified for absolute authenticity.' },
                privacy: { title: 'PRIVACY', body: 'Your privacy is absolute. Zero external telemetry. We securely store your data and never sell it.' },
                refund: { title: 'REFUND', body: 'All acquisitions are final unless proven defective upon arrival. Contact us within 24 hours of delivery.' },
                support: { title: 'SUPPORT', body: 'Our technicians and concierge team are on standby. 24/7 access for VIP members.' },
                order: { title: 'ORDER', body: 'Select your preferred configuration and proceed through checkout. We accept major credit cards and bank transfers.' },
                tracking: { title: 'TRACKING', body: 'Shipment coordinates will be provided post-acquisition via email and dashboard.' }
            };

            const modalWrapper = document.getElementById('editorial-modal');
            const mBackdrop = document.getElementById('modal-backdrop');
            const mOverlay = document.getElementById('modal-overlay');
            const mPanel = document.getElementById('modal-panel');
            const mHeader = document.getElementById('modal-header');
            const mBody = document.getElementById('modal-body');
            const mClose = document.getElementById('modal-close');
            let isModalOpen = false;
            let modalTl = gsap.timeline({ paused: true, reversed: true });

            // Build Timeline (500-700ms total)
            modalTl.to(modalWrapper, { autoAlpha: 1, duration: 0.1, pointerEvents: 'auto' })
                   .to(mBackdrop, { backdropFilter: 'blur(8px)', duration: 0.4, ease: 'power2.out' }, 0)
                   .to(mOverlay, { backgroundColor: 'rgba(17, 17, 17, 0.55)', duration: 0.4, ease: 'power2.out' }, 0)
                   .to(mPanel, { y: 0, opacity: 1, duration: 0.5, ease: 'power3.out' }, 0.1)
                   .to(mHeader, { opacity: 1, duration: 0.4, ease: 'power2.out' }, 0.2)
                   .to(mBody, { opacity: 1, duration: 0.4, ease: 'power2.out' }, 0.26);

            const openEditorialModal = (key) => {
                if(!modalContents[key]) return;
                mHeader.innerHTML = modalContents[key].title;
                mBody.innerHTML = modalContents[key].body;
                
                isModalOpen = true;
                modalTl.timeScale(1).play();
                // Prevent body scroll
                document.body.style.overflow = 'hidden';
                if(window.lenis) window.lenis.stop();
            };

            const closeEditorialModal = () => {
                if(!isModalOpen) return;
                isModalOpen = false;
                modalTl.timeScale(1.5).reverse();
                // Restore body scroll
                setTimeout(() => {
                    document.body.style.overflow = '';
                    if(window.lenis) window.lenis.start();
                }, 400);
            };

            document.querySelectorAll('[data-modal-target]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openEditorialModal(btn.getAttribute('data-modal-target'));
                });
            });

            mClose.addEventListener('click', closeEditorialModal);
            mBackdrop.addEventListener('click', closeEditorialModal);
            
        }
    });
</script>