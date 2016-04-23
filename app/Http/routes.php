<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::group([
    'middleware' => ['web'],
    'namespace' =>
    'API',
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
        });

        // Tag Routes
        Route::group(['prefix' => 'tags'], function () {
            Route::get('get', 'ApiTagsController@get');
        });

        // User Routes
        Route::group(['prefix' => 'users'], function () {
            Route::post('create', 'ApiUsersController@create');
            Route::post('update', 'ApiUsersController@update');

            Route::get('get', 'ApiUsersController@get');
        });

        Route::post('upload', 'ApiUploadController@upload');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::group([
    'middleware' => ['web', 'installed'],
    'namespace' => 'Admin',
    'prefix' => 'journal'], function () {
        Route::get('/', function () {
            return redirect('journal/posts/list');
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
                Route::get('list', 'PostsController@lists');
            });

            // Settings Routes
            Route::get('settings', 'SettingsController@index');

            // Tag Routes
            Route::group(['prefix' => 'tags'], function () {
                Route::get('list', 'TagsController@lists');
            });

            // User Routes
            Route::group(['prefix' => 'users'], function () {
                Route::get('create', 'UsersController@create');
                Route::get('list', 'UsersController@lists');
                Route::get('{id}', 'UsersController@profile');
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

/*
|--------------------------------------------------------------------------
| Blog Routes
|--------------------------------------------------------------------------
*/
Route::group(['namespace' => 'Blog'], function () {
    Route::get('/', 'HomeController@page');
    Route::get('author/{slug}', 'AuthorController@page');
    Route::get('tag/{slug}', 'TagController@page');
    Route::get('{parameter}', 'PageController@page');
});
