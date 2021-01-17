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
        $language = isset($_COOKIE['locale']) ? str_replace('-', '_', $_COOKIE['locale']) : '';
        if (array_key_exists($language, config('app.languages')))
        {
            app()->setLocale($language);
        }
        else if (array_key_exists(Locale::getDefault(), config('app.languages')))
        {
            app()->setLocale(Locale::getDefault());
        }
        else
        {
            app()->setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
