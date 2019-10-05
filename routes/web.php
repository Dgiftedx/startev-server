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
Route::get('pay-business','TestController@payBusiness');
Route::get('pay-stores','TestController@payStores');

//Download schedule
Route::get('download-broadcast-schedules/{id}', 'OpenApiController@downloadScheduleReport');

Route::get('/video/{file}', function ( $file ) {
    $path = base_path("public/uploads/{$file}");

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With, X-Auth-Token');
    header('Access-Control-Allow-Credentials: true');

        return response()->file($path, array('Content-Type' => 'video/mp4'));
});


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


    //Transactions
    Route::get('view-transactions', 'Admin\ViewsController@transactions')->name('all transactions');
   Route::get('/transaction/get-index', 'Admin\TransactionsController@index');


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


    //Orders Management
    Route::get('new-orders', 'Admin\ViewsController@newOrders')->name('admin new orders');
    Route::get('confirmed-orders', 'Admin\ViewsController@confirmedOrders')->name('admin confirmed orders');
    Route::get('cancelled-orders', 'Admin\ViewsController@cancelledOrders')->name('admin cancelled orders');
    Route::get('delivered-orders', 'Admin\ViewsController@deliveredOrders')->name('admin delivered orders');

    //Ajax calls
    Route::get('admin/order/get-orders-index/{type}','Admin\OrdersController@index');
    Route::get('admin/confirm-order/{id}','Admin\OrdersController@confirmOrder');
    Route::get('admin/order/conclude-order/{id}', 'Admin\OrdersController@final');


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
    Route::get('platform-vocals/settle/{id}','VocalsController@resetVocal');
    Route::get('platform-vocals/delete-vocal/{id}','VocalsController@deleteVocal');
    Route::get('platform/vocals-view', 'Admin\ViewsController@vocalsView')->name('vocals view');
    Route::get('platform/create-vocal-view', 'Admin\ViewsController@createVocalProfileView')->name('create vocal profile');

    //Mail Manager
    Route::post('mail-manager/check-users','MailManagerController@checkUsers');
    Route::post('mail-manager/send-mail','MailManagerController@sendMail');
    Route::get('mail-manager/compose-view','Admin\ViewsController@compose')->name('compose');

    //Content Management
    Route::get('manage-contents/get-adverts', 'ContentsManagerController@advertBanners');
    Route::get('manage-contents/delete-advert/{id}', 'ContentsManagerController@removeAdvert');
    Route::get('manage-contents/update-advert/{id}/{status}', 'ContentsManagerController@updateAdvert');
    Route::post('manage-contents/upload-banner','ContentsManagerController@uploadBanner');
    Route::get('manage-contents/get-help-tips','ContentsManagerController@getHelpTips');
    Route::post('manage-contents/store-help-tip','ContentsManagerController@storeHelpTip');
    Route::get('manage-contents/delete-help-tip/{id}','ContentsManagerController@deleteHelpTip');
    Route::post('manage-contents/update-help-tip/{id}','ContentsManagerController@updateHelpTip');
    Route::get('manage-contents/help-tips-view','Admin\ViewsController@helpTips')->name('help tips');
    Route::get('manage-contents/get-advert-banners','Admin\ViewsController@adverts')->name('adverts');


    //site Data
    Route::get('/general-settings/reload-banks','Admin\SettingsController@reloadBanks');
    Route::get('/general-settings/fetch-site-data','Admin\SettingsController@siteData');
    Route::get('general-settings/site-data-view', 'Admin\ViewsController@sideData')->name('site data');


    //Payout Overview
    Route::get('/payouts/index','Admin\PayoutsController@index');
    Route::get('/payouts/items','Admin\PayoutsController@items');
    Route::get('/payouts/store-view', 'Admin\ViewsController@storePayoutView')->name('store payout');
    Route::get('/payouts/delivery-view', 'Admin\ViewsController@deliveryPayoutView')->name('delivery payout');
    Route::get('/payouts/business-view', 'Admin\ViewsController@businessPayoutView')->name('business payout');

});
