<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // 2. Menggunakan kebijakan HSTS yang kuat
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        
        // 3. Memastikan isolasi origin yang tepat dengan COOP
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        // Framing policy. By default the site may only be framed by itself.
        $ancestors = config('security.frame_ancestors', []);
        $csp = [
            "default-src 'self'",

            // Scripts: only the CDNs we actually load from
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'"
                . " https://cdnjs.cloudflare.com"       // GSAP
                . " https://cdn.jsdelivr.net"            // Alpine.js, Lenis
                . " https://unpkg.com"                   // SplitType, Phosphor Icons
                . " https://code.highcharts.com"         // Highcharts (admin)
                . " https://www.googletagmanager.com"    // GA gtag loader
                . " https://www.google-analytics.com"    // GA beacons
                . " https://static.cloudflareinsights.com", // Cloudflare Web Analytics

            // Styles: font providers + inline styles (Tailwind, blade)
            "style-src 'self' 'unsafe-inline'"
                . " https://fonts.googleapis.com"
                . " https://api.fontshare.com",

            // Images: own domain, data URIs, Supabase bucket, QR generator, magazine covers
            "img-src 'self' data: blob:"
                . " https://qcrmvarkayzimbjyolum.supabase.co"
                . " https://api.qrserver.com"
                . " https://www.googletagmanager.com"
                . " https://monochrome-watches.com"
                . " https://cdn.clementine.my.id",

            // Fonts: Google Fonts files + Fontshare CDN
            "font-src 'self' data:"
                . " https://fonts.gstatic.com"
                . " https://api.fontshare.com"
                . " https://cdn.fontshare.com",

            // XHR / Fetch / WebSocket connections
            "connect-src 'self'"
                . " wss://ws-ap1.pusher.com"             // Pusher WebSocket
                . " https://sockjs-ap1.pusher.com"       // Pusher fallback
                . " https://stats.pusher.com"            // Pusher stats
                . " https://www.google-analytics.com"    // GA events
                . " https://www.googletagmanager.com"    // GA config
                . " https://analytics.google.com"        // GA4 events
                . " https://qcrmvarkayzimbjyolum.supabase.co"  // Supabase API
                . " https://qcrmvarkayzimbjyolum.storage.supabase.co" // Supabase S3
                . " https://cdn.clementine.my.id",

            "object-src 'none'",
            "base-uri 'self'",
        ];

        if (empty($ancestors)) {
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        } else {
            $response->headers->remove('X-Frame-Options');
            $csp[] = "frame-ancestors 'self' " . implode(' ', $ancestors);
        }

        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        return $response;
    }
}
