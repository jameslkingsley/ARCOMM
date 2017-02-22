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
Route::get('/', 'PageController@index');

//--- Steam Authentication
Route::get('/steamauth', 'AuthController@login');

//--- Join Requests
Route::resource('join', 'PublicJoinController', ['only' => ['index', 'store']]);

//--- Media
Route::resource('media', 'MediaController');
Route::post('/media/delete', 'MediaController@deletePhoto');

//--- Modset
Route::get('/modset', function() {
    return view('modset.index');
});

//--- Roster
Route::get('/roster', 'PageController@roster');

//--- Members
Route::group(['middleware' => 'member'], function() {
    Route::get('/guides', function() {
        return view('home.index');
    });
});

//--- Admins
Route::group(['middleware' => 'admin'], function() {
    // Route::get('/hub/applications/transfer', 'JoinController@transferOldRecords');
    Route::get('/hub/applications/viewItems', 'JoinController@viewItems');
    Route::get('/hub/applications/showByInput', 'JoinController@showByInput');
    Route::get('/hub/applications/{status}', 'JoinController@index');
    Route::post('/hub/applications/createStatus', 'JoinController@createStatus');
    Route::post('/hub/applications/setStatus', 'JoinController@setStatus');
    Route::post('/hub/applications/getStatusView', 'JoinController@getStatusView');

    Route::resource('/hub/applications', 'JoinController', [
        'as' => 'admin',
        'except' => [],
        'names' => []
    ]);
});

Route::group(['middleware' => 'member'], function() {
    Route::post('/hub/missions/show-panel', 'MissionController@showPanel');
    Route::post('/hub/missions/show-mission', 'MissionController@showMission');
    Route::get('/hub/missions/{panel}', 'MissionController@index');
    Route::resource('/hub/missions', 'MissionController');

    Route::resource('/hub', 'HubController');
});
