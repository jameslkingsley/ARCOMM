<?php

Route::get('/', function () {
    return redirect('/hub');
});

Route::get('/api/mission', 'API\MissionController@index');
Route::get('/api/mission/{mission}', 'API\MissionController@show');
Route::post('/api/mission', 'API\MissionController@store');

// Mission Comments
Route::get('/api/mission/{mission}/comment', 'API\MissionCommentController@index');
Route::post('/api/mission/{mission}/comment', 'API\MissionCommentController@store');

// Mission Media
Route::get('/api/mission/{mission}/media', 'API\MissionMediaController@index');
Route::post('/api/mission/{mission}/media', 'API\MissionMediaController@store');
Route::post('/api/mission/{mission}/banner', 'API\MissionBannerController@store');

// Mission Briefing
Route::put('/api/mission/{mission}/briefing', 'API\MissionBriefingController@update');

Route::get('/auth', 'Auth\SteamController@handle');

// Catch all route for Vue Router
Route::get('/hub', 'HubController@index');
Route::get('/hub/{any}', 'HubController@index')->where('any', '.*');
