<?php

use App\Providers\Features;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
| The Features class is only used by Dorastorm, but you could use it
| if some parts of your app belongs to a specific section.
*/

Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('route:cache');
});
//Route::get('/test/testd/{nombre}', 'FileController@testDownload');

/**
 * This routes are enabled only if the auth feature is enabled.
 * Make sure to check the app features config.
 */
if (Features::enabled(Features::auth())) {
    Route::get('/session', 'SessionController@check')->name('session.check');

    if (Features::enabled(Features::posts())) {
        Route::get('/posts', 'PostController@index')->name('posts.index');
        Route::get('/tags/posts', 'PostController@getAllTags')->name('posts.tags');
        Route::get('/posts/{post_slug}', 'PostController@show')->name('posts.show');
    }

    if (Features::enabled(Features::quotes())) {
        Route::post('/quotes', 'QuoteController@store')->name('quotes.store');
    }

    /**
     * The protected routes goes inside the group.
     */
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/upload/image', 'FileController@uploadImage')->name('upload.image');
        Route::post('/upload/document', 'FileController@uploadDocument')->name('upload.document');

        if (Features::enabled(Features::userManagement())) {
            Route::get('/user', 'UserController@showMe')->name('user.data');
            Route::get('/user/roles-below', 'UserController@rolesBelow')->name('user.roles-below');
            Route::patch('/users/{user}/password', 'UserController@updatePassword')->name('users.password');
            Route::resource('/users', 'UserController')->except(['create', 'edit']);
            Route::resource('/roles', 'RoleController')->except(['create', 'edit']);
            Route::resource('/posts', 'PostController')->except(['index', 'show', 'create']);
        }

        if (Features::enabled(Features::quotes())) {
            Route::resource('/quotes', 'QuoteController')->only(['index', 'show', 'destroy']);
        }
    });
}
