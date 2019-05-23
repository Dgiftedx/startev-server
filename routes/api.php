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

    //Mentor
    Route::get('single-mentor-profile/{slug}','ApiCommonController@singleMentor');

    //Feeds
    Route::get('get-feeds','ApiFeedsController@index');
    Route::get('get-people','ApiFeedsController@people'); //Get people to follow
    Route::post('feed-post-article','ApiFeedsController@post');

    //Follow & Un-follow
    Route::get('follow/{userId}/{target}', 'ApiFollowController@follow');
    Route::get('toggle-follow/{userId}/{target}', 'ApiFollowController@toggleFollow');

    //Like & Un-Like
    Route::get('toggle-like/{userId}/{feed}', 'ApiFeedsController@toggleLike');

    //Comments on Feeds
    Route::post('post-comment/{user_id}','ApiFeedsController@postComment');

    //Venture
    Route::get('all-ventures','ApiVentureController@index');
    Route::post('new-venture','ApiVentureController@store');
    Route::get('/all-business','ApiVentureController@allBusiness');
    Route::post('update-venture/{id}','ApiVentureController@update');
    Route::get('business-ventures/{id}', 'ApiVentureController@ventureByBusiness');
    Route::get('venture-by-business/{id}', 'ApiVentureController@ventureBusiness');
    Route::get('remove-venture/{business_id}/{id}', 'ApiVentureController@removeVenture');

    //Partner
    Route::get('single-venture/{identifier}','ApiVentureController@singleVenture');
    Route::get('apply-to-partner/{venture}/{user_id}','ApiVentureController@applyToPartner');
    Route::get('accept-partnership/{partnership_id}/{user_id}','ApiVentureController@acceptPartnership');
});