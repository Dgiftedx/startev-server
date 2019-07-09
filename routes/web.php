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


    //users management
    Route::get('get-admin-users', 'Admin\AdminUsersController@all');
    Route::get('delete-admin/{id}', 'Admin\AdminUsersController@destroy');
    Route::post('admin-create-user', 'Admin\AdminUsersController@storeAdmin');
    Route::post('admin-update-user/{id}', 'Admin\AdminUsersController@updateAdmin');
    Route::get('all-admin-users', 'Admin\ViewsController@allAdminUsersView')->name('admin users');
    Route::get('platform-mentors', 'Admin\ViewsController@platformMentors')->name('platform mentors');
    Route::get('platform-students', 'Admin\ViewsController@platformStudents')->name('platform students');
    Route::get('platform-graduates', 'Admin\ViewsController@platformGraduates')->name('platform graduates');
    Route::get('platform-businesses', 'Admin\ViewsController@platformBusinesses')->name('platform businesses');

    Route::get('get-platform-students', 'Admin\PlatformUsersController@platformStudents');
    Route::get('platform-delete-student/{id}', 'Admin\PlatformUsersController@deleteStudentAccount');

    //Verifications
    Route::group(['prefix' => 'verification'], function() {

    });

});
