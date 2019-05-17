<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([

    'middleware' => ['api'],
    'prefix' => 'auth'

], function () {
    Route::post('register','ApiAuthController@register');
    Route::post('login', 'ApiAuthController@login');
    Route::post('logout', 'ApiAuthController@logout');
    Route::post('refresh', 'ApiAuthController@refresh');
    Route::post('me', 'ApiAuthController@me');

});


Route::group([
    'middleware' => ['api']
], function () {
    //Account
    Route::get('get-profile','ApiAccountController@profile');
    Route::post('update-user-avatar/{id}','ApiAccountController@updateAvatar');
    Route::post('update-user-data/{id}','ApiAccountController@updateProfile');
    Route::post('update-student-data/{id}','ApiAccountController@updateStudentData');
    Route::post('update-mentor-data/{id}','ApiAccountController@updateMentorData');
    Route::post('update-password-data/{id}','ApiAccountController@updatePasswordData');
    Route::post('update-industry-data/{id}','ApiAccountController@updateIndustryData');
    Route::post('update-business-data/{id}','ApiAccountController@updateBusinessData');
    Route::post('reset-password','ApiPasswordResetController@sendEmail');
    Route::post('change-password','ApiPasswordResetController@changePassword');
    Route::post('update-user-header-image/{id}','ApiAccountController@updateHeaderImage');

    //Industries
    Route::get('industries','ApiCommonController@industries');
    Route::get('all-industries','ApiCommonController@allIndustries');
    Route::get('single-industry/{slug}','ApiCommonController@singleIndustry');

    //Location
    Route::get('countries','ApiCommonController@countries');
    Route::get('states/{id}','ApiCommonController@states');
    Route::get('cities/{id}','ApiCommonController@cities');

    //Career Paths
    Route::get('career-paths','ApiCommonController@careerPaths');

});