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

Route::auth();

Route::any('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');

Route::group(['middleware' => 'auth'], function () {
	Route::get('/dashboard', 'DashboardController@index');

    //contacts
    Route::any('contacts', 'ContactController@index');
    Route::post('contacts/save', 'ContactController@save');
    Route::any('contacts/create', 'ContactController@create');
    Route::any('contacts/edit', 'ContactController@edit');
    Route::get('contacts/view/{id}', 'ContactController@view');
    Route::post('contacts/delete', 'ContactController@delete');
    Route::get('get-contact-numbers-via-contact-id', 'ContactController@getContactNumbersViaContactId');

	//users
	Route::any('users', 'UserController@index');
	Route::post('users/save', 'UserController@save');
	Route::any('users/create', 'UserController@create');
	Route::any('users/edit', 'UserController@edit');
	Route::get('users/view/{id}', 'UserController@view');
	Route::post('users/delete', 'UserController@delete');
	Route::any('profile', 'UserController@profile');

	//company
	Route::any('company', 'CompanyController@index');
	Route::post('company/save', 'CompanyController@save');
	Route::any('company/create', 'CompanyController@create');
	Route::any('company/edit', 'CompanyController@edit');
	Route::get('company/view/{id}', 'CompanyController@view');
	Route::post('company/delete', 'CompanyController@delete');

	//meeting
	Route::any('meeting', 'MeetingController@index');
	Route::post('meeting/save', 'MeetingController@save');
	Route::any('meeting/create', 'MeetingController@create');
	Route::any('meeting/edit', 'MeetingController@edit');
	Route::get('meeting/view/{id}', 'MeetingController@view');
	Route::post('meeting/delete', 'MeetingController@delete');
	Route::get('meeting/json', 'MeetingController@meetingJson');

	//reminders
	Route::any('reminders', 'ReminderController@index');
	Route::post('reminders/save', 'ReminderController@save');
	Route::any('reminders/create', 'ReminderController@create');
	Route::any('reminders/edit', 'ReminderController@edit');
	Route::get('reminders/view/{id}', 'ReminderController@view');
	Route::post('reminders/delete', 'ReminderController@delete');
});
