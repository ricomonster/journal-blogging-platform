<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'api/v1.0'], function() {
    // Authentication Routes
    Route::group(['prefix' => 'auth'], function() {
        Route::get('check', 'Api\ApiAuthController@checkAuthentication');
        Route::get('check_installation', 'Api\ApiAuthController@checkInstallation');

        Route::post('authenticate', 'Api\ApiAuthController@authenticate');
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

    Route::post('installer/create_account', 'Api\ApiInstallerController@createAccount');
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
Route::get('/', 'BlogController@index');
Route::get('author/{slug}', 'BlogController@author');
Route::get('tag/{slug}', 'BlogController@tags');
Route::get('{parameter}', 'BlogController@post');
