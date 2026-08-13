@if(config('services.google_analytics.id'))
    <!-- Google tag (gtag.js) Deferred -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('services.google_analytics.id') }}');

        const initGTM = () => {
            if (window.gtagLoaded) return;
            window.gtagLoaded = true;
            const script = document.createElement('script');
            script.src = 'https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics.id') }}';
            script.async = true;
            document.head.appendChild(script);
            
            ['scroll', 'mousemove', 'touchstart', 'click', 'keydown'].forEach(evt => {
                window.removeEventListener(evt, initGTM);
            });
        };

        ['scroll', 'mousemove', 'touchstart', 'click', 'keydown'].forEach(evt => {
            window.addEventListener(evt, initGTM, { once: true, passive: true });
        });
        setTimeout(initGTM, 3500);
    </script>
@endif
