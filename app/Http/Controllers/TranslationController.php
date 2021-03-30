<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TranslationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // Idea taken from: https://glutendesign.com/posts/detect-and-change-language-on-the-fly-with-laravel
        $request->validate([
            'locale' => [
                'required',
                Rule::in(config('app.supported_locales', ['en', 'es'])),
            ]
        ]);
        $request->session()->put('locale', $request->locale);
    }
}
