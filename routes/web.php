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
    Route::post('platform-create-student', 'Admin\PlatformUsersController@platformCreateStudent');
    Route::post('platform-update-student/{id}', 'Admin\PlatformUsersController@platformUpdateStudent');
    Route::get('platform-delete-student/{id}', 'Admin\PlatformUsersController@deleteStudentAccount');

    Route::get('get-platform-graduates', 'Admin\PlatformUsersController@platformGraduates');
    Route::post('platform-create-graduate', 'Admin\PlatformUsersController@platformCreateGraduate');
    Route::post('platform-update-graduate/{id}', 'Admin\PlatformUsersController@platformUpdateGraduate');
    Route::get('platform-delete-graduate/{id}', 'Admin\PlatformUsersController@deleteGraduateAccount');


    Route::get('get-platform-mentors', 'Admin\PlatformUsersController@platformMentors');
    Route::post('platform-create-mentor', 'Admin\PlatformUsersController@platformCreateMentor');
    Route::post('platform-update-mentor/{id}', 'Admin\PlatformUsersController@platformUpdateMentor');
    Route::get('platform-delete-mentor/{id}', 'Admin\PlatformUsersController@deleteMentorAccount');


    Route::get('get-platform-businesses', 'Admin\PlatformUsersController@platformBusinesses');
    Route::post('platform-create-business', 'Admin\PlatformUsersController@platformCreateBusiness');
    Route::post('platform-update-business/{id}', 'Admin\PlatformUsersController@platformUpdateBusiness');
    Route::get('platform-delete-business/{id}', 'Admin\PlatformUsersController@deleteBusinessAccount');

    //Verifications
    Route::get('verification/get-requests','Admin\VerificationController@allRequests');
    Route::get('account-verification/verify/{request_id}','Admin\VerificationController@verify');
    Route::get('account-verification/reject/{request_id}','Admin\VerificationController@reject');
    Route::get('/download-verification-file/{file}', 'Admin\VerificationController@downloadDocument');
    Route::get('verification/requests', 'Admin\ViewsController@verificationRequests')->name('verification requests');

    //Platform Vocals
    Route::post('platform-vocals/store','VocalsController@store');
    Route::get('platform-vocals/get-all','VocalsController@allVocals');
    Route::get('platform-vocals/delete-vocal/{id}','VocalsController@deleteVocal');
    Route::get('platform/vocals-view', 'Admin\ViewsController@vocalsView')->name('vocals view');
    Route::get('platform/create-vocal-view', 'Admin\ViewsController@createVocalProfileView')->name('create vocal profile');

});
