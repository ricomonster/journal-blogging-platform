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
*/
Route::group(['middleware' => ['web'], 'namespace' => 'API', 'prefix' => 'api'], function () {
    // Installer Routes
    Route::group(['prefix' => 'installer'], function () {
        Route::post('database', 'ApiInstallerController@database');
        Route::post('setup', 'ApiInstallerController@setup');
    });

    // Post Routes
    Route::group(['prefix' => 'posts'], function () {
        Route::delete('delete', 'ApiPostsController@delete');

        Route::get('get', 'ApiPostsController@get');

        Route::post('generate_slug', 'ApiPostsController@generateSlug');
        Route::post('save', 'ApiPostsController@save');
    });

    // Setting Routes
    Route::group(['prefix' => 'settings'], function () {
        Route::get('get', 'ApiSettingsController@get');

        Route::post('save_settings', 'ApiSettingsController@saveSettings');
    });

    // User Routes
    Route::group(['prefix' => 'users'], function () {
        Route::post('create', 'ApiUsersController@create');
    });

    Route::post('upload', 'ApiUploadController@upload');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['web'], 'namespace' => 'Admin', 'prefix' => 'journal'], function () {
    // Auth Routes
    Route::get('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');

    Route::post('auth/authenticate', 'AuthController@authenticate');

    Route::group(['middleware' => ['auth']], function () {
        // Editor Routes
        Route::get('editor', 'EditorController@index');
        Route::get('editor/{postId}', 'EditorController@edit');

        // Post Routes
        Route::group(['prefix' => 'posts'], function () {
            Route::get('list', 'PostsController@lists');
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', 'SettingsController@index');
        });

        // User Routes
        Route::group(['prefix' => 'users'], function () {
            Route::get('create', 'UsersController@create');
            Route::get('lists', 'UsersController@lists');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Installer Routes
|--------------------------------------------------------------------------
*/
Route::group(['namespace' => 'Installer', 'prefix' => 'installer'], function () {
    Route::get('/', 'WelcomeController@page');
    Route::get('database', 'DatabaseController@page');
    Route::get('success', 'FinalController@page');
    Route::get('setup', 'SetupController@page');

    Route::post('setup_database', 'DatabaseController@setup');
});
