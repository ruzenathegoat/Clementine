<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHTML
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya cache GET request dan jika user tidak login (guest)
        if ($request->isMethod('GET') && auth()->guest() && $response->isSuccessful()) {
            // Cache selama 10 menit (600 detik) untuk anonim user di CDN (seperti Cloudflare)
            $response->headers->set('Cache-Control', 'public, max-age=600, s-maxage=600');
        }

        return $response;
    }
}
