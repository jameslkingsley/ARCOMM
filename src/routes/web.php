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
Route::get('/join', 'JoinController@index');
Route::post('/join', 'JoinController@submit');
