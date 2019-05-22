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

Route::get('/build-countries', 'TestQueryController@buildCountries');
Route::get('/build-states', 'TestQueryController@buildStates');
Route::get('/build-cities', 'TestQueryController@buildCities');

Route::get('/test-carbon','TestController@testCarbon');
