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

    // Installer Routes
    Route::group(['prefix' => 'installer'], function () {
        Route::post('save_setup', 'ApiInstallerController@saveSetup');
    });

    // User Routes
    Route::group(['prefix' => 'users'], function () {
        Route::post('create', 'ApiUsersController@create');
    });
});

/*
|--------------------------------------------------------------------------
| Installer Routes
|--------------------------------------------------------------------------
|
*/
Route::group(['namespace' => 'Installer', 'prefix' => 'installer'], function () {
    Route::get('/', 'WelcomeController@page');
    Route::get('database', 'DatabaseController@page');
    Route::get('success', 'FinalController@page');
    Route::get('setup', 'SetupController@page');

    Route::post('setup_database', 'DatabaseController@setup');
});
