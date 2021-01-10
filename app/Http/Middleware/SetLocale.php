<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        if (!empty($request->input('locale')) && array_key_exists(strtolower($request->route('locale')), config('app.languages')))
        {
            session()->put('language', $request->input('locale'));
            $language = $request->input('locale');
        }
        else
        {
            $language = session('language') ?? config('app.locale');
        }

        if (isset($language) && config('app.languages.' . $language)) {
            app()->setLocale($language);
        }

        return $next($request);
    }
}
