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

//--- Home
Route::get('/', 'PageController@home');

//--- Join Requests
Route::resource('join', 'PublicJoinController', ['only' => ['index', 'store']]);
Route::group(['middleware' => 'admin'], function() {
    Route::get('/join-requests/viewItems', 'JoinController@viewItems');
    Route::get('/join-requests/showByInput', 'JoinController@showByInput');
    // Route::get('/join-requests/transfer', 'JoinController@transferOldRecords');
    Route::get('/join-requests/{status}', 'JoinController@index');
    Route::post('/join-requests/createStatus', 'JoinController@createStatus');
    Route::post('/join-requests/setStatus', 'JoinController@setStatus');
    Route::post('/join-requests/getStatusView', 'JoinController@getStatusView');

    Route::resource('join-requests', 'JoinController', [
        'as' => 'admin',
        'except' => [],
        'names' => []
    ]);
});
