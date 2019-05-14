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
    Route::post('reset-password','ApiPasswordResetController@sendEmail');
    Route::post('change-password','ApiPasswordResetController@changePassword');

    //Industries
    Route::get('industries','ApiCommonController@industries');
    Route::get('all-industries','ApiCommonController@allIndustries');
    Route::get('single-industry/{slug}','ApiCommonController@singleIndustry');
});