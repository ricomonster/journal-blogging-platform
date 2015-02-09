<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'journal', 'middleware' => 'installation'], function() {
	Route::get('login', 'AuthController@login');
	Route::get('logout', 'AuthController@logout');

	Route::get('/', function() {
		return redirect('/journal/posts');
	});

	// must be logged in routes
	Route::group(['middleware' => 'auth'], function() {
		Route::get('settings', 'SettingsController@index');
		Route::get('appearance', 'SettingsController@appearance');
		Route::get('services', 'SettingsController@services');

		// post routes
		Route::group(['prefix' => 'posts'], function() {
			Route::get('/', 'PostsController@index');
			Route::get('editor', 'PostsController@editor');
			Route::get('editor/{id}', 'PostsController@editorWithId');
		});

		// user routes
		Route::group(['prefix' => 'users'], function() {
			Route::get('/', 'UsersController@index');
			Route::get('add', 'UsersController@addUser');
		});
	});
});

/*
|--------------------------------------------------------------------------
| Installer Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'installer'], function() {
	Route::get('/', 'InstallerController@index');
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'api/v1'], function() {
	Route::group(['prefix' => 'auth'], function() {
		Route::get('handshake', 'Api\ApiAuthController@handshake');

		Route::post('login', 'Api\ApiAuthController@login');
	});

	Route::group(['prefix' => 'posts'], function() {
		Route::post('save', 'Api\ApiPostsController@savePost');
	});

	Route::group(['prefix' => 'settings'], function() {
		Route::post('update_general_settings', 'Api\ApiSettingsController@updateGeneralSettings');
		Route::post('upload', 'Api\ApiSettingsController@uploader');
	});

	Route::group(['prefix' => 'users'], function() {
		Route::get('lists', 'Api\ApiUsersController@allUsers');

		Route::post('create', 'Api\ApiUsersController@createUser');
	});

	Route::group(['prefix' => 'tags'], function() {
		Route::get('all', 'Api\ApiTagsController@getAllTags');
	});
});

/*
|--------------------------------------------------------------------------
| Blog Routes
|--------------------------------------------------------------------------
*/
Route::get('/', 'BlogController@index');
Route::get('posts/{id}', 'BlogController@posts');
