<?php

use App\Providers\Features;
use Illuminate\Support\Facades\Route;

$langs = array_merge([''], config('app.supported_locales'));

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Place inside the foreach loop only SSRable routes you want to localize.
 * Have in mind that these routes won't trigger the SetWebLocale middleware
 * until the user goes to another, non-ssrable, route (triggers the /{any?} route).
 */
foreach ($langs as $lang) {
    if (Features::enabled(Features::auth())) {
        /**
         * Every SSRable route that requires auth goes here
         */
        if (Features::enabled(Features::posts())) {
            Route::get($lang . '/posts/{slug}', 'SsrController@ssrPostRead');
        }
    }
}

Route::get('/{any?}', 'SsrController@ssrIndex')->where('any', '^(?!api\/)[\/\w\.\,-]*');

Route::post('/locale', 'TranslationController')->name('localization.set');
