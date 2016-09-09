<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
 */

// Home
Route::get('/', 'PageController@home');

// Join Requests
// Route::get('/join', 'JoinController@index');
// Route::post('/join', 'JoinController@submit');
// Route::get('/join/list', 'JoinController@listRequests');
// Route::get('/join/items', 'JoinController@items');
// Route::get('/join/show/{joinRequest}', 'JoinController@show');
// Route::get('/join/approve/{joinRequest}', 'JoinController@approve');

Route::resource('join', 'PublicJoinController', ['only' => ['index', 'store']]);

Route::group(['middleware' => 'admin'], function() {
    Route::get('/join-requests/items', 'JoinController@items');
    Route::get('/join-requests/showByInput', 'JoinController@showByInput');
    Route::resource('join-requests', 'JoinController', [
        'as' => 'admin',
        'names' => []
    ]);
});