<?php

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
        Route::get('all', 'Api\ApiPostsController@all');
        Route::get('check_slug', 'Api\ApiPostsController@checkSlug');
        Route::get('get_post', 'Api\ApiPostsController@getPost');

        Route::post('delete', 'Api\ApiPostsController@deletePosts');
        Route::post('save', 'Api\ApiPostsController@save');
    });

    // Setting Routes
    Route::group(['prefix' => 'settings'], function() {
        Route::get('get', 'Api\ApiSettingsController@getSettings');
        Route::get('themes', 'Api\ApiSettingsController@themes');

        Route::post('save', 'Api\ApiSettingsController@saveSettings');
    });

    // Tag Routes
    Route::group(['prefix' => 'tags'], function() {
        Route::get('all', 'Api\ApiTagsController@all');

        Route::post('create_tag', 'Api\ApiTagsController@createTag');
    });

    // User Routes
    Route::group(['prefix' => 'users'], function() {
        Route::get('all', 'Api\ApiUsersController@all');
        Route::get('get_user', 'Api\ApiUsersController@getUser');

        Route::post('create', 'Api\ApiUsersController@create');
        Route::post('update_details', 'Api\ApiUsersController@updateDetails');
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
