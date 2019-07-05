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
Route::get('send-mail', 'TestController@sendMail');


Auth::routes();
Route::group(['middleware' => ['auth:admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/profile', 'HomeController@profile')->name('profile');
    Route::post('/update-profile/{id}', 'HomeController@updateProfile');
    Route::post('/update-profile-avatar/{id}', 'HomeController@updateProfileAvatar');
    Route::get('/get-user-profile', 'HomeController@getProfile');

    //Dashboard Routes
    Route::group(['prefix' => 'dashboard'], function() {
        Route::get('get-stats', 'Admin\DashboardController@getStats');
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
