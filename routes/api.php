<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/posts','PostController@index')->name('posts.index');
Route::get('/posts/{post}','PostController@show')->name('posts.show');

Route::middleware('auth:sanctum')->group(function () {
    // Your protected routes goes here
    Route::get('/user', 'UserController@showMe');
    Route::resource('/roles','RoleController')->except(['create', 'edit']);
    Route::resource('/posts','PostController')->only(['store','update','destroy']);
});

