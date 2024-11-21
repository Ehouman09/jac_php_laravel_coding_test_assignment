<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeadersMiddleware
{
    /**
     * This middleware will handle the incoming request and set security headers.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $this->setSecurityHeaders($response);

        return $response;
    }

    /**
     *  To secure our application, this middleware will set various security headers on the response.
     * 
     * Headers set:
     * - X-Content-Type-Options: nosniff
     * - X-Frame-Options: DENY
     * - X-XSS-Protection: 1; mode=block
     * - Referrer-Policy: strict-origin-when-cross-origin
     */
    protected function setSecurityHeaders($response): void
    {
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
    }
}
