<?php

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user/me', 'Auth\UserController@me');

    Route::get('/mission', 'API\MissionController@index');
    Route::get('/mission/{mission}', 'API\MissionController@show');
    Route::post('/mission', 'API\MissionController@store');

    // Mission Comments
    Route::get('/mission/{mission}/comment', 'API\MissionCommentController@index');
    Route::post('/mission/{mission}/comment', 'API\MissionCommentController@store');
    Route::post('/mission/{mission}/comment/{comment}', 'API\MissionCommentController@update');

    // Mission Media
    Route::get('/mission/{mission}/media', 'API\MissionMediaController@index');
    Route::post('/mission/{mission}/media', 'API\MissionMediaController@store');
    Route::post('/mission/{mission}/banner', 'API\MissionBannerController@store');

    // Mission Briefing
    Route::put('/mission/{mission}/briefing', 'API\MissionBriefingController@update');
});
