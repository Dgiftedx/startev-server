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

//Route::get('send-mail','HelperController@sendVerificationMail');
Route::get('download-file', 'DownloadController@download');



Auth::routes();
Route::group(['middleware' => ['auth:admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');


    //Dashboard Routes
    Route::group(['prefix' => 'dashboard'], function() {
//        Route::get('get-stats', '');
    });

    //Users managements route
    Route::group(['prefix' => 'users'], function() {

    });


    //Feeds Routes
    Route::group(['prefix' => 'feeds'], function() {

    });


    //Verifications
    Route::group(['prefix' => 'verification'], function() {

    });


    //account
    Route::group(['prefix' => 'account'], function() {

    });
});
