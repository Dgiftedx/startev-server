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


Route::group(['prefix' => 'store'], function () {
    Route::get('/get-local-product','Store\MainStoreController@getLocalProduct');

    ////////////////////////////////////////////////////////////////////////////////////////////
    /// Main Store routes starts
    Route::get('main-store-products', 'Store\FrontController@all');
    Route::get('main-store-new-products', 'Store\FrontController@newProducts');

    Route::get('main-store-get-cart', 'Store\MainStoreController@getCart');
    Route::post('main-store-add-to-cart', 'Store\MainStoreController@addToCart');
    Route::post('main-store-place-order', 'Store\MainStoreController@placeOrder');
    Route::get('main-store-get-products/{identifier}', 'Store\MainStoreController@index');
    Route::get('main-store-remove-from-cart/{item_id}', 'Store\MainStoreController@removeFromCart');
    Route::get('main-store-get-single-product/{product}', 'Store\MainStoreController@singleProduct');
    Route::get('main-store-get-products-with-query/{identifier}', 'Store\MainStoreController@byFilter');

    Route::post('validate-referral-code', 'Store\FrontController@validateCode');
    Route::post('place-new-order', 'Store\FrontController@newMainStorePlaceOrder');
});

Route::group([

    'middleware' => ['api'],
    'prefix' => 'auth'

], function () {
    Route::post('verify','ApiAuthController@verify');
    Route::post('register','ApiAuthController@register');
    Route::post('login', 'ApiAuthController@login');
    Route::post('logout', 'ApiAuthController@logout');
    Route::post('refresh', 'ApiAuthController@refresh');
    Route::post('me', 'ApiAuthController@me');
    Route::post('remove-user-account', 'ApiAuthController@removeAccount');
});


Route::group([
    'middleware' => ['api']
], function () {
    //Account
    Route::get('get-banks','OpenApiController@banks');
    Route::post('verify-account-number','ApiAccountController@verifyAccountNumber');
    Route::post('add-account-details','ApiAccountController@addAccountDetails');
    Route::post('add-account-details-biz','ApiAccountController@addAccountDetailsBiz');
    Route::post('add-business-account-details','ApiAccountController@addBusinessAccountDetails');
    Route::get('get-landing-stats', 'OpenApiController@landingStats');
    Route::get('get-profile','ApiAccountController@profile');
    Route::get('get-my-feeds/{id}','ApiFeedsController@myFeeds');
    Route::get('get-new-sign-ups','ApiAccountController@newSignUps');
    Route::get('get-top-profiles','ApiAccountController@topProfiles');
    Route::get('get-my-partners/{id}','ApiAccountController@myPartners');
    Route::get('get-suggestions/{id}','ApiAccountController@suggestions');
    Route::get('get-featured-mentors','ApiAccountController@featuredMentors');
    Route::get('get-general-profile/{slug}','ApiAccountController@generalProfile');
    Route::post('update-user-avatar/{id}','ApiAccountController@updateAvatar');
    Route::post('update-user-data/{id}','ApiAccountController@updateProfile');
    Route::get('get-business-partners/{id}','ApiAccountController@businessPartners');
    Route::post('update-student-data/{type}/{id}','ApiAccountController@updateStudentData');
    Route::post('update-mentor-data/{id}','ApiAccountController@updateMentorData');
    Route::post('update-password-data/{id}','ApiAccountController@updatePasswordData');
    Route::post('update-industry-data/{id}','ApiAccountController@updateIndustryData');
    Route::post('update-business-data/{id}','ApiAccountController@updateBusinessData');
    Route::post('reset-password','ApiPasswordResetController@sendEmail');
    Route::post('resend-email-confirmation','ApiPasswordResetController@ResendConfirmEmail');
    Route::post('change-password','ApiPasswordResetController@changePassword');
    Route::get('remove-header-image/{id}', 'ApiAccountController@removeBgImage');
    Route::post('update-user-header-image/{id}','ApiAccountController@updateHeaderImage');
    Route::post('submit-verification','ApiAccountController@submitVerification');
    Route::post('address-search-places','OpenApiController@searchPlacesByAddress');
    Route::post('main-store-calculate-delivery','OpenApiController@calculateDelivery');
    Route::post('generate-code', 'ApiAccountController@generateCode');
    Route::post('generate-store-url', 'ApiAccountController@generateStoreUrl');

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
    Route::post('hide-feed', 'ApiFeedsController@hideFeed');
    Route::post('upload-video', 'ApiFeedsController@uploadVideo');
    Route::post('feed-post-article','ApiFeedsController@post');
    Route::post('delete-feed','ApiFeedsController@deleteFeed');
    Route::post('api-update-feed','ApiFeedsController@updateFeed');
    Route::get('single-feed/{feed_id}','ApiFeedsController@showSingle');
    Route::get('increase-feed-view/{id}','ApiFeedsController@increaseFeedViews');

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
    Route::post('search-ventures','ApiVentureController@searchVenture');
    Route::get('business-ventures/{id}', 'ApiVentureController@ventureByBusiness');
    Route::get('venture-by-business/{id}', 'ApiVentureController@ventureBusiness');
    Route::get('remove-venture/{business_id}/{id}', 'ApiVentureController@removeVenture');

    //Partner
    Route::get('get-partners','ApiVentureController@getPartners');
    Route::get('single-venture/{identifier}','ApiVentureController@singleVenture');
    Route::post('apply-to-partner','ApiVentureController@applyToPartner');
    Route::post('accept-partnership','ApiVentureController@acceptPartnership');
    Route::post('reject-partnership','ApiVentureController@rejectPartnership');


    //Publication
    Route::get('get-publications','ApiPublicationController@index');
    Route::get('get-my-publications/{id}','ApiPublicationController@myPublications');
    Route::post('publish-publication','ApiPublicationController@store');
    Route::post('update-publication/{id}','ApiPublicationController@updatePublication');
    Route::get('get-hub-materials','ApiPublicationController@hubMaterials');
    Route::get('get-pub-cat','ApiPublicationController@getPubCategories');
    Route::get('delete-publication/{id}','ApiPublicationController@delete');
    Route::get('single-publication/{publication_id}','ApiPublicationController@show');
    Route::get('toggle-publication-like/{userId}/{publication}', 'ApiPublicationController@toggleLike');

    // Connect
    Route::get('toggle-connection/{user}/{mentor}','ApiAccountController@toggleConnection');

    //Help Tips
    Route::get('get-help-tips/{userId}','ApiHelpTipsController@index');

    //Messaging
    Route::post('send-message','Chat\MessagingController@sendMessage');
    Route::post('send-typing-event','Chat\MessagingController@typingEvent');
    Route::get('/chat-get-contacts/{user}','Chat\MessagingController@getContacts');
    Route::get('/chat-get-messages/{user}','Chat\MessagingController@getMessages');
    Route::post('mark-messages-as-read', 'Chat\MessagingController@markAllAsRead');

    //Search
    Route::post('get-search-results', 'ApiSearchController@search');


    //Notification
    Route::get('mark-all-notifications-as-read/{id}', 'ApiUserNotificationController@markAsRead');
    Route::get('get-widget-notifications/{user}','ApiUserNotificationController@widgetNotifications');

    //Get open Api Feeds
    Route::get('open-api-get-feed/{id}', 'OpenApiController@getSingleFeed');

    Route::get('get-adverts','OpenApiController@getAdverts');

    //Contact List
    Route::get('get-user-contact-list/{id}', 'ContactListController@index');
    Route::post('add-to-contact-list', 'ContactListController@addToContact');
    Route::post('remove-from-contact-list', 'ContactListController@removeFromContact');

    //Broadcast
    Route::post('log-live-session', 'BroadcastManagerController@logSession');
    Route::post('remove-live-session', 'BroadcastManagerController@removeSession');
    Route::post('submit-schedule', 'BroadcastManagerController@submitSchedule');
    Route::post('update-schedule', 'BroadcastManagerController@updateSchedule');
    Route::post('send-schedule-notice', 'BroadcastManagerController@sendReminder');
    Route::get('clear-broadcast-schedules/{id}', 'BroadcastManagerController@clearSchedules');
    Route::get('delete-schedule/{schedule_id}', 'BroadcastManagerController@deleteSchedule');
    Route::get('get-broadcast-schedules/{user_id}', 'BroadcastManagerController@schedules');
    Route::get('get-mentor-trainees/{user_id}', 'BroadcastManagerController@getTrainees');
    Route::get('fetch-schedule-participants/{schedule_id}', 'BroadcastManagerController@getParticipants');

    Route::get('get-broadcast-messages', 'BroadcastManagerController@getBroadcastMessages');
    Route::get('get-live-sessions/{user_id}', 'BroadcastManagerController@getLiveSessions');
    Route::post('submit-broadcast-message', 'BroadcastManagerController@submitBroadcastMessage');
    Route::get('get-upcoming-meetings/{user_id}', 'BroadcastManagerController@getUpcomingMeetings');
    Route::get('remove-broadcast-messages/{schedule_id}', 'BroadcastManagerController@removeBroadcastMessages');


    //Audio Session
    Route::post('log-audio-session', 'Chat\AudioSessionController@logSession');
    Route::get('delete-audio-session/{id}', 'Chat\AudioSessionController@deleteSession');
    Route::post('end-receiver-audio-session', 'Chat\AudioSessionController@endReceiverSession');
    Route::get('get-audio-session/{target}', 'Chat\AudioSessionController@getSession');
    Route::get('pick-audio-session/{id}', 'Chat\AudioSessionController@pickSession');
    Route::get('get-audio-host-session/{host}', 'Chat\AudioSessionController@getHostSession');

});


Route::group([
    'middleware' => ['api'],
    'prefix' => 'store'
], function () {

    //Settlements
    Route::get('payout/get-business-settlements/{id}','ApiCommonController@businessSettlements');
    Route::get('payout/get-business-settlement-items/{id}', 'ApiCommonController@settlementItems');
    Route::get('payout/get-store-settlements/{id}','ApiCommonController@storeSettlements');
    Route::get('payout/get-store-settlement-items/{id}', 'ApiCommonController@storeSettlementItems');


    //////////////////////////////////////////////////////////////////////////////
    /// User store routes starts
    Route::get('get-reviews/{user}','Store\UserStoreController@reviews');
    Route::post('forward-order','Store\UserStoreController@forwardOrder');
    Route::get('get-ventures/{user}','Store\UserStoreController@ventureList');
    Route::get('check-has-store/{user}','Store\UserStoreController@hasStore');
    Route::get('track-order/{Id}/{user}','Store\UserStoreController@trackOrder');
    Route::get('get-store-orders/{user}','Store\UserStoreController@storeOrders');
    Route::get('store-get-transactions/{user_id}','Store\UserStoreController@transactions');
    Route::get('get-dashboard-data/{user}','Store\UserStoreController@dashboard');
    Route::get('get-store-settings/{user}','Store\UserStoreController@storeSettings');
    Route::get('get-single-order/{orderId}', 'Store\UserStoreController@singleOrder');
    Route::post('save-store-settings/{user}','Store\UserStoreController@saveStoreSettings');
    Route::get('sync-venture-products/{user}/{venture}','Store\UserStoreController@syncVentureProducts');
    Route::post('import-venture-products','Store\UserStoreController@importVentureProducts');
    Route::get('fetch-venture-products/{user}/{venture}','Store\UserStoreController@fetchVentureProducts');
    Route::get('detach-venture-products/{user}/{venture}','Store\UserStoreController@detachVentureProducts');
    Route::get('get-single-product/{Id}','Store\UserStoreController@singleProduct');
    Route::post('edit-product/{product}/{userId}','Store\UserStoreController@editProduct');
    Route::get('get-store-products/{userId}','Store\UserStoreController@storeProducts');
    Route::get('delete-single-product/{Id}','Store\UserStoreController@deleteProduct');

    //////////////////////////////////////////////////////////////////////////////
    /// Business store routes starts
    Route::get('store-manager/confirm-order/{id}','Store\BusinessStoreController@orderAction');
    Route::post('store-manager/add-product/{ventureId}/{userId}','Store\BusinessStoreController@addProduct');
    Route::get('store-manager/get-ventures/{userId}','Store\BusinessStoreController@getVentures');
    Route::post('store-manager/add-venture/{businessId}','Store\BusinessStoreController@addVenture');
    Route::get('store-manager/get-settings/{businessId}','Store\BusinessStoreController@getSettings');
    Route::get('store-manager/get-dashboard-data/{ventureId}/{userId}','Store\BusinessStoreController@dashboard');
    Route::get('store-manager/get-venture-data/{ventureId}/{userId}','Store\BusinessStoreController@venturedata');
    Route::get('store-manager/get-store-orders/{ventureId}/{userId}','Store\BusinessStoreController@storeOrders');
    Route::get('store-manager/get-single-order/{orderId}', 'Store\BusinessStoreController@singleOrder');
    Route::get('store-manager/get-single-product/{Id}','Store\BusinessStoreController@singleProduct');
    Route::get('store-manager/track-order/{Id}/{business}','Store\BusinessStoreController@trackOrder');
    Route::get('store-manager/delete-single-product/{Id}','Store\BusinessStoreController@deleteProduct');
    Route::get('store-manager/get-store-products/{ventureId}/{userId}','Store\BusinessStoreController@storeProducts');
    Route::post('store-manager/edit-product/{product}/{userId}','Store\BusinessStoreController@editProduct');
    Route::post('store-manager/update-settings/{businessId}','Store\BusinessStoreController@updateSettings');
    Route::post('store-manager/update-venture/{venture}/{businessId}','Store\BusinessStoreController@updateVenture');
    Route::post('store-manager/attach-products-to-venture/{venture}','Store\BusinessStoreController@attachProductVenture');
    Route::get('store-manager/detach-products-from-venture/{venture}','Store\BusinessStoreController@detachProductVenture');


});
