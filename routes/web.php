<?php


// Route::view('/', 'welcome');

Route::get('/oauth', 'Auth\DiscordController@callback');
Route::get('/oauth/redirect', 'Auth\DiscordController@redirect');

Route::get('/complete', function () {
    return view('complete');
});

Route::get('/{any?}', function () {
    return view('login');
})->where('any', '.*');

// Mission Download
Route::get('/api/mission/{mission}/download', 'API\MissionDownloadController@index')
    ->middleware('web');

// Catch all route for Vue Router
Route::get('/hub/{any?}', 'HubController@index')->where('any', '.*');
