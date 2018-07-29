<?php

Route::get('/', function () {
    return redirect('/hub');
});

Route::get('/oauth', 'Auth\DiscordController@callback');
Route::get('/oauth/redirect', 'Auth\DiscordController@redirect');

// Catch all route for Vue Router
Route::get('/hub/{any?}', 'HubController@index')->where('any', '.*');
