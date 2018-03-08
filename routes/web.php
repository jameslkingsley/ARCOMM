<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test/{file}', function (string $file) {
    $contents = file_get_contents(__DIR__ . "/../tests/ARC_COOP_Valid_K.Malden/$file");

    $data = new \App\Support\ArmaConfig($contents);

    return $data->toArray();
});

Route::get('/hub', 'HubController@index');
Route::get('/hub/{page}', 'HubController@index');
Route::get('/hub/{page}/{subpage}', 'HubController@index');

Route::post('/api/mission', 'API\MissionController@store');

Route::get('/auth', 'Auth\SteamController@handle');
