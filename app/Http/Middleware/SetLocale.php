<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Locale;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * Checks if the language supplied through COOKIE is valid
         */
        $language = isset($_COOKIE['locale']) ? str_replace('-', '_', $_COOKIE['locale']) : '';
        if (array_key_exists($language, config('app.languages')))
        {
            app()->setLocale($language);
        }
        /**
         * If the language in $_COOKIE['locale'] is not valid, the value will be set as the default
         */
        else
        {
            app()->setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
