<?php

Route::view('/', 'welcome');

Route::get('/oauth', 'Auth\DiscordController@callback');
Route::get('/oauth/redirect', 'Auth\DiscordController@redirect');

// Mission Download
Route::get('/api/mission/{mission}/download', 'API\MissionDownloadController@index')
    ->middleware('web');

// Catch all route for Vue Router
Route::get('/hub/{any?}', 'HubController@index')->where('any', '.*');
