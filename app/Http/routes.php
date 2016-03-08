<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/
Route::group(['namespace' => 'API', 'prefix' => 'api'], function () {
    // Auth Routes
    Route::group(['prefix' => 'auth'], function () {
        Route::post('authorize', 'ApiAuthController@authorize');
    });

    // User Routes
    Route::group(['prefix' => 'users'], function () {
        Route::post('create', 'ApiUsersController@create');
    });
});
