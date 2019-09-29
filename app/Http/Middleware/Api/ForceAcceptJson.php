<?php

namespace App\Http\Middleware\Api;

use Closure;

/**
 * Force incoming API requests to be treated as JSON.
 * This way, a JSON response is always returned by Laravel
 * (including validation errors).
 */
class ForceAcceptJson
{
    /**
     * Handle an incoming request.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
