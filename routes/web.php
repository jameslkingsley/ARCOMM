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
Route::resource('join', 'PublicJoinController', [
    'only' => ['index', 'store']
]);

//--- Media
Route::resource('media', 'MediaController');
Route::post('/media/delete', 'MediaController@deletePhoto');

//--- Modset
Route::get('/modset', function() {
    return view('modset.index');
});

//--- Roster
Route::get('/roster', 'PageController@roster');

//--- Admins
Route::group(['middleware' => 'permission:apps:all'], function() {
    // Route::get('/hub/applications/transfer', 'JoinController@transferOldRecords');
    Route::get('/hub/applications/viewItems', 'JoinController@viewItems');
    Route::get('/hub/applications/showByInput', 'JoinController@showByInput');
    Route::get('/hub/applications/{status}', 'JoinController@index');
    Route::post('/hub/applications/createStatus', 'JoinController@createStatus');
    Route::post('/hub/applications/setStatus', 'JoinController@setStatus');
    Route::post('/hub/applications/getStatusView', 'JoinController@getStatusView');

    Route::resource('/hub/applications', 'JoinController', [
        'as' => 'admin'
    ]);
});

Route::group(['middleware' => 'permission:operations:all'], function() {
    Route::get('/hub/operations', 'Missions\OperationController@index');
});

Route::group(['middleware' => 'permission:operations:all'], function() {
    Route::resource('/api/operations', 'API\OperationController');
    Route::resource('/api/operations/missions', 'API\OperationMissionController');
});

Route::group(['middleware' => 'member'], function() {
    // Mission Media
    Route::post('/hub/missions/media/add-photo', 'Missions\MediaController@uploadPhoto');
    Route::post('/hub/missions/media/delete-photo', 'Missions\MediaController@deletePhoto');
    Route::post('/hub/missions/media/add-video', 'Missions\MediaController@addVideo');
    Route::post('/hub/missions/media/delete-video', 'Missions\MediaController@removeVideo');

    // Mission Operations
    Route::post('/hub/missions/operations/remove-mission', 'Missions\OperationController@removeMission');
    Route::post('/hub/missions/operations/add-mission', 'Missions\OperationController@addMission');
    Route::post('/hub/missions/operations/create-operation', 'Missions\OperationController@create');
    Route::post('/hub/missions/operations/delete-operation', 'Missions\OperationController@destroy');

    // Mission Comments
    Route::resource('/hub/missions/comments', 'Missions\CommentController', [
        'except' => ['create', 'show', 'update']
    ]);

    // Mission Briefings
    Route::post('/hub/missions/briefing', 'Missions\MissionController@briefing');
    Route::post('/hub/missions/briefing/update', 'Missions\MissionController@setBriefingLock');

    // Missions
    Route::get('/hub/missions/{mission}/delete', 'Missions\MissionController@destroy');
    Route::post('/hub/missions/{mission}/update', 'Missions\MissionController@update');
    Route::post('/hub/missions/{mission}/set-verification', 'Missions\MissionController@updateVerification');

    // Downlaod
    Route::get('/hub/missions/{mission}/download/{format}', 'Missions\MissionController@download');

    // Notes
    Route::get('/hub/missions/{mission}/notes/read-notifications', 'Missions\NoteController@readNotifications');
    Route::resource('/hub/missions/{mission}/notes', 'Missions\NoteController');

    // Panels
    Route::get('/hub/missions/{mission}/{panel}', 'Missions\MissionController@panel');

    Route::resource('/hub/missions', 'Missions\MissionController', [
        'except' => ['create', 'edit']
    ]);

    Route::get('/hub/settings/avatar-sync', 'Users\SettingsController@avatarSync');
    Route::resource('/hub/settings', 'Users\SettingsController');

    Route::get('/hub/guides', function() {
        return view('guides.index');
    });

    Route::get('/hub/notifications', 'Users\NotificationsController@index');

    // Hub Index
    Route::resource('/hub', 'HubController', [
        'only' => ['index']
    ]);
});

//--- Users
Route::group(['middleware' => 'permission:users:all'], function() {
    Route::resource('/hub/users', 'Users\UserController');
});
