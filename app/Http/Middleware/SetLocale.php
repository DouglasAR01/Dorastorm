<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    // This middleware will set the locale to the users native language.
    // Idea taken from: https://glutendesign.com/posts/detect-and-change-language-on-the-fly-with-laravel

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('locale')) {
            $locale = $request->session()->get('locale', config('app.locale'));
        } else {
            $locale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

            if (!in_array($locale, config('app.supported_locales'))) {
                $locale = 'en';
            }
        }

        App::setLocale($locale);
        return $next($request);
    }
}
