<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
         * If there is no COOKIE, an attempt is made through the Locale
         * https://www.php.net/manual/en/locale.getdefault.php
         */
        else if (array_key_exists(Locale::getDefault(), config('app.languages')))
        {
            app()->setLocale(Locale::getDefault());
        }
        /**
         * If none of the above options served then it will be set to the default value
         */
        else
        {
            app()->setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
