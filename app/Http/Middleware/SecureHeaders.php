<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Tweak HTTP headers for better security.
 * See <https://www.danieldusek.com/blog/2/enabling-security-headers-for-your-website-with-php-and-laravel>.
 */
class SecureHeaders
{
    /**
     * The headers that should be added.
     */
    public const WANTED_HEADERS = [
        'Referrer-Policy' => 'no-referrer-when-downgrade',
        'X-Content-Type-Options' => 'nosniff',
        'X-XSS-Protection' => '1; mode=block',
        'X-Frame-Options' => 'DENY',
        'Strict-Transport-Security' => 'max-age=31536000',
        // 'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src * data:; font-src 'self' data:",
    ];

    /**
     * Handle the request.
     *
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // having nonce active will prevent inline javascript
        Vite::useCspNonce();
        $nonce = "'nonce-".Vite::cspNonce()."'";

        $response = $next($request);

        $response->withHeaders(self::WANTED_HEADERS);
        // add csp if debugbar is not active -> https://github.com/barryvdh/laravel-debugbar/issues/1317
        if (!env('APP_DEBUG')) {
            $response->withHeaders([
                'Content-Security-Policy' => "default-src 'self'; script-src 'self' ".$nonce."; style-src 'self' ".$nonce."; img-src * data:; font-src 'self' data: "
            ]);
        }

        return $response;
    }
}
