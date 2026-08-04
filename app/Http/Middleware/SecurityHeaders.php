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

        // Framing policy. By default the site may only be framed by itself.
        // If config/security.php lists frame_ancestors (via the FRAME_ANCESTORS
        // env var), allow those origins to embed the site instead. We express
        // that allowlist with CSP frame-ancestors and drop X-Frame-Options:
        // XFO has no reliable allowlist form, and browsers that see both may
        // still enforce the stricter XFO, which would defeat the allowlist.
        $ancestors = config('security.frame_ancestors', []);

        if (empty($ancestors)) {
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        } else {
            $response->headers->remove('X-Frame-Options');
            $response->headers->set(
                'Content-Security-Policy',
                "frame-ancestors 'self' ".implode(' ', $ancestors),
            );
        }

        return $response;
    }
}
