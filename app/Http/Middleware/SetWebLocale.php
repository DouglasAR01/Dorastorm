<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetWebLocale
{
    /**
     * This middleware looks if the request has a valid locale parameter and adds it to the session.
     * This only works with WEB based requests.
     */
    public function handle(Request $request, Closure $next)
    {
        $param = $request->route('any');
        $locale = explode('/', $param)[0];

        if (!empty($locale) &&  in_array($locale, config('app.supported_locales'))) {
            $request->session()->put('locale', $locale);
        } else {
            $request->session()->put('locale', config('app.locale'));
        }
        if ($request->session()->has('locale')) {
            $locale = $request->session()->get('locale', config('app.locale'));
            App::setLocale($locale);
        } 
        return $next($request);
    }
}
