<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::group([
    'middleware' => ['api'],
    'namespace' => 'API',
    'prefix' => 'api'], function () {
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
            Route::get('themes', 'ApiSettingsController@themes');

            Route::post('save_settings', 'ApiSettingsController@saveSettings');
            Route::post('sidebar', 'ApiSettingsController@sidebar');
        });

        // Tag Routes
        Route::group(['prefix' => 'tags'], function () {
            Route::delete('delete', 'ApiTagsController@delete');

            Route::get('get', 'ApiTagsController@get');

            Route::post('create', 'ApiTagsController@create');

            Route::put('update', 'ApiTagsController@update');
        });

        // User Routes
        Route::group(['prefix' => 'users'], function () {
            Route::get('get', 'ApiUsersController@get');

            // Route::post('create', 'ApiUsersController@create');

            Route::put('change-password', 'ApiUsersController@changePassword');
            Route::put('update', 'ApiUsersController@update');
        });

        Route::post('upload', 'ApiUploadController@upload');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::group([
    'middleware'    => ['web', 'installed'],
    'namespace'     => 'Admin',
    'prefix'        => 'journal'], function () {
        Route::get('/', function () {
            return redirect('journal/posts');
        });

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
                Route::get('/', 'PostsController@index');
            });

            // Settings Routes
            Route::get('settings', 'SettingsController@index');
            Route::get('navigation', 'SettingsController@navigation');

            // Tag Routes
            Route::group(['prefix' => 'tags'], function () {
                Route::get('/', 'TagsController@index');
                Route::get('{tagId}/edit', 'TagsController@edit');
            });

            // User Routes
            Route::group(['prefix' => 'users'], function () {
                Route::get('change-password', 'UsersController@changePassword');
                Route::get('create', 'UsersController@create');
                Route::get('/', 'UsersController@index');
                Route::get('{id}/profile', 'UsersController@profile');
            });
        });
});

/*
|--------------------------------------------------------------------------
| Installer Routes
|--------------------------------------------------------------------------
*/
Route::group([
    'namespace' => 'Installer',
    'middleware'    => ['web'],
    'prefix' => 'installer'], function () {
        Route::get('/', 'WelcomeController@page');
        Route::get('database', 'DatabaseController@page');
        Route::get('success', 'FinalController@page');
        Route::get('setup', 'SetupController@page');

        Route::post('setup_database', 'DatabaseController@setup');
});

/*
|--------------------------------------------------------------------------
| Blog Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['installed'], 'namespace' => 'Blog'], function () {
    Route::get('/', 'HomeController@page');
    Route::get('author/{slug}', 'AuthorController@page');
    Route::get('rss', 'RssController@page');
    Route::get('tag/{slug}', 'TagController@page');
    Route::get('{parameter}', 'PageController@page');
});
