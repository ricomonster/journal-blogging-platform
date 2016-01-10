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

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'api/v1.0', 'middleware' => 'cors'], function() {
    // Authentication Routes
    Route::group(['prefix' => 'auth'], function() {
        Route::get('check', 'Api\ApiAuthController@checkAuthentication');
        Route::get('check_installation', 'Api\ApiAuthController@checkInstallation');

        Route::post('authenticate', 'Api\ApiAuthController@authenticate');
    });

    // Installer Routes
    Route::group(['prefix' => 'installer'], function() {
        Route::get('install', 'Api\ApiInstallerController@install');

        Route::post('create_account', 'Api\ApiInstallerController@createAccount');
    });

    // Post Routes
    Route::group(['prefix' => 'posts'], function() {
        Route::delete('delete', 'Api\ApiPostsController@deletePost');

        Route::get('all', 'Api\ApiPostsController@all');
        Route::get('check_slug', 'Api\ApiPostsController@checkSlug');
        Route::get('get_post', 'Api\ApiPostsController@getPost');

        Route::post('save', 'Api\ApiPostsController@save');
    });

    // Role Routes
    Route::group(['prefix' => 'roles'], function() {
        Route::get('all', 'Api\ApiRolesController@all');
    });

    // Setting Routes
    Route::group(['prefix' => 'settings'], function() {
        Route::get('get', 'Api\ApiSettingsController@getSettings');
        Route::get('themes', 'Api\ApiSettingsController@themes');

        Route::post('save', 'Api\ApiSettingsController@saveSettings');
    });

    // Tag Routes
    Route::group(['prefix' => 'tags'], function() {
        Route::delete('delete_tag', 'Api\ApiTagsController@deleteTag');

        Route::get('all', 'Api\ApiTagsController@all');
        Route::get('get_tag', 'Api\ApiTagsController@getTag');

        Route::post('create', 'Api\ApiTagsController@createTag');

        Route::put('update', 'Api\ApiTagsController@update');
    });

    // User Routes
    Route::group(['prefix' => 'users'], function() {
        Route::get('all', 'Api\ApiUsersController@all');
        Route::get('get_user', 'Api\ApiUsersController@getUser');

        Route::post('create', 'Api\ApiUsersController@create');
        Route::post('update_profile', 'Api\ApiUsersController@updateDetails');
        Route::post('change_password', 'Api\ApiUsersController@changePassword');
    });

    // Upload Endpoint
    Route::post('upload', 'Api\ApiUploadController@upload');
});

Route::get('journal', function() {
    return view('journal');
});

/*
|--------------------------------------------------------------------------
| Blog Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'installation.not'], function() {
    Route::get('/', 'BlogController@index');
    Route::get('author/{slug}', 'BlogController@author');
    Route::get('tag/{slug}', 'BlogController@tags');
    Route::get('rss', 'BlogController@rss');
    Route::get('{parameter}', 'BlogController@post');
});
