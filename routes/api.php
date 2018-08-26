<?php

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user/me', 'Auth\UserController@me');

    // Mission
    Route::get('/mission', 'API\MissionController@index');
    Route::post('/mission', 'API\MissionController@store');
    Route::get('/mission/{mission}', 'API\MissionController@show');
    Route::post('/mission/{mission}', 'API\MissionController@update');
    Route::delete('/mission/{mission}', 'API\MissionController@destroy');

    // Mission Verification
    Route::post('/mission/{mission}/verification', 'API\MissionVerificationController@store');
    Route::delete('/mission/{mission}/verification', 'API\MissionVerificationController@destroy');

    // Comments
    Route::get('/comment', 'API\CommentController@index');
    Route::post('/comment', 'API\CommentController@store');
    Route::post('/comment/{comment}', 'API\CommentController@update');
    Route::delete('/comment/{comment}', 'API\CommentController@destroy');

    // Mission Media
    Route::get('/mission/{mission}/media', 'API\MissionMediaController@index');
    Route::post('/mission/{mission}/media', 'API\MissionMediaController@store');
    Route::post('/mission/{mission}/banner', 'API\MissionBannerController@store');

    // Mission Briefing
    Route::put('/mission/{mission}/briefing', 'API\MissionBriefingController@update');

    // Absences
    Route::get('/absence', 'API\AbsenceController@index');
    Route::post('/absence', 'API\AbsenceController@store');
    Route::post('/absence/{absence}', 'API\AbsenceController@update');
    Route::delete('/absence/{absence}', 'API\AbsenceController@destroy');
});
