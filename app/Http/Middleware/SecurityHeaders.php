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
        
        // 1 & 4. CSP efektif & Trusted Types
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https:",
            "style-src 'self' 'unsafe-inline' https:",
            "img-src 'self' data: https:",
            "font-src 'self' data: https:",
            "connect-src 'self' wss: https:",
            "object-src 'none'",
            "base-uri 'self'",
            "require-trusted-types-for 'script'",
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
