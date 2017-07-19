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

Route::get('/', function () {
    return view('auth.login');
});

//Route::auth();

Route::any('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');

Route::group(['middleware' => 'auth'], function () {
	Route::get('/dashboard', 'DashboardController@index');

	//users
	Route::any('users', 'UserController@index');
	Route::post('users/save', 'UserController@save');
	Route::any('users/create', 'UserController@create');
	Route::any('users/edit', 'UserController@edit');
	Route::get('users/view/{id}', 'UserController@view');
	Route::post('users/delete', 'UserController@delete');
	Route::any('profile', 'UserController@profile');
});
