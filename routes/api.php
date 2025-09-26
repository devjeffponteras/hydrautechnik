<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//CUSTOMER LOGOUT==================================================================
Route::get('/logout',[
   'uses'=>'App\Http\Controllers\ApiController@doCheckLogin',
   'as'=> 'logout'
]);

//CUSTOMER LOGIN==================================================================
Route::post('/check-login',[
   'uses'=>'App\Http\Controllers\ApiController@doCheckLogin',
   'as'=> 'check-login'
]);

//REGISTER ACCOUNT=================================================================
Route::post('/register-account',[
   'uses'=>'App\Http\Controllers\ApiController@doRegisterCustomer',
   'as'=> 'register-account'
]);

//FORGOT PASSWORD==================================================================
Route::post('/change-password',[
   'uses'=>'App\Http\Controllers\ApiController@doChangePassword',
   'as'=> 'change-password'
]);

//VERIFY ACCOUNT===================================================================
Route::post('/forgot-password',[
   'uses'=>'App\Http\Controllers\ApiController@doForgotPassword',
   'as'=> 'forgot-password'
]);

//CHANGE PASSWORD==================================================================
Route::post('/change-password',[
   'uses'=>'App\Http\Controllers\ApiController@doChangePassword',
   'as'=> 'change-password'
]);

//VERIFY ACCOUNT===================================================================
Route::post('/verify-account',[
   'uses'=>'App\Http\Controllers\ApiController@doVerifyAccount',
   'as'=> 'verify-account'
]);

//DEACTIVATE ACCOUNT==================================================================
Route::post('/deactivate-my-account',[
   'uses'=>'App\Http\Controllers\ApiController@doDeactivateMyAccount',
   'as'=> 'deactivate-my-account'
]);

//RESEND VERIFICATION CODE==========================================================
Route::post('/resend-code',[
   'uses'=>'App\Http\Controllers\ApiController@doResendVerificationCode',
   'as'=> 'resend-code'
]);

//CUSTOMER ADDRESS==================================================================
Route::post('/update-city-address',[
   'uses'=>'App\Http\Controllers\ApiController@updateCityAddressLocation',
   'as'=> 'update-city-address'
]);

//GET CUSTOMER INFORMATION===========================================================
Route::post('/get-customer-info',[
   'uses'=>'App\Http\Controllers\ApiController@getCustomerInformation',
   'as'=> 'get-customer-info'
]);

Route::post('/get-customer-info-by-email',[
   'uses'=>'App\Http\Controllers\ApiController@getCustomerInformationByEmail',
   'as'=> 'get-customer-info-by-email'
]);

Route::post('/get-customer-info-primary-address',[
   'uses'=>'App\Http\Controllers\ApiController@getCustomerInformationWithPrimaryAddress',
   'as'=> 'get-customer-info-primary-address'
]);

//CUSTOMER PPROFILE==================================================================
Route::post('/update-customer-info',[
   'uses'=>'App\Http\Controllers\ApiController@doUpdateCustomerProfile',
   'as'=> 'update-customer-info'
]);

//CUSTOMER ADDRESS===================================================================
Route::post('/update-customer-address',[
   'uses'=>'App\Http\Controllers\ApiController@doUpdateCustomerAddress',
   'as'=> 'update-customer-address'
]);

Route::post('/upload-photo',[
   'uses'=>'App\Http\Controllers\ApiController@doUploadPhoto',
   'as'=> 'upload-photo'
]);

//CUSTOMER SUBSCRIBED TO NEWS LETTER==================================================================
Route::post('/subscribe-news-letter',[
   'uses'=>'App\Http\Controllers\ApiController@SubscribedToNewsLetter',
   'as'=> 'subscribe-news-letter'
]);

//BOOK LIST =========================================================================
Route::post('/get-all-book-category-list',[
   'uses'=>'App\Http\Controllers\ApiController@getAllBookCategoryList',
   'as'=> 'get-all-book-category-list'
]);

Route::post('/get-all-book-list',[
   'uses'=>'App\Http\Controllers\ApiController@getAllBookList',
   'as'=> 'get-all-book-list'
]);

Route::post('/search-book-list',[
   'uses'=>'App\Http\Controllers\ApiController@searchBookList',
   'as'=> 'search-book-list'
]);

Route::post('/get-featured-list',[
   'uses'=>'App\Http\Controllers\ApiController@getFeaturedBookList',
   'as'=> 'get-featured-list'
]);

Route::post('/get-best-seller-list',[
   'uses'=>'App\Http\Controllers\ApiController@getBestSellerBookList',
   'as'=> 'get-best-seller-list'
]);

Route::post('/get-new-release-list',[
   'uses'=>'App\Http\Controllers\ApiController@getNewReleaseBookList',
   'as'=> 'get-new-release-list'
]);

Route::post('/get-free-book-list',[
   'uses'=>'App\Http\Controllers\ApiController@getFreeBookList',
   'as'=> 'get-free-book-list'
]);

Route::post('/get-premium-list',[
   'uses'=>'App\Http\Controllers\ApiController@getPremiumBookList',
   'as'=> 'get-premium-list'
]);

//CATALOGUE=========================================================================
Route::post('/get-all-header-catalogue-list',[
   'uses'=>'App\Http\Controllers\ApiController@getAllBookHeaderCatalogueList',
   'as'=> 'get-all-header-catalogue-list'
]);

Route::post('/get-all-details-catalogue-list',[
   'uses'=>'App\Http\Controllers\ApiController@getAllBookDetailsCatalogueList',
   'as'=> 'get-all-details-catalogue-list'
]);

//CITY LIST =======================================================================
Route::post('/get-city-list',[
   'uses'=>'App\Http\Controllers\ApiController@getCityList',
   'as'=> 'get-city-list'
]);

//LIBRARY =========================================================================
Route::post('/get-library-list',[
   'uses'=>'App\Http\Controllers\ApiController@getCustomerLibraryList',
   'as'=> 'get-library-list'
]);

Route::post('/check-library-book',[
   'uses'=>'App\Http\Controllers\ApiController@checkCustomerLibraryBookExist',
   'as'=> 'check-library-book'
]);

Route::post('/check-book-allow-download',[
   'uses'=>'App\Http\Controllers\ApiController@checkCustomerLibraryDownloadBookExist',
   'as'=> 'check-book-allow-download'
]);

//FAVORITES ========================================================================
Route::post('/add-to-favorites',[
   'uses'=>'App\Http\Controllers\ApiController@addToFavorites',
   'as'=> 'add-to-favorites'
]);

//BOOKMARKS ========================================================================
Route::post('/check-has-book-mark',[
   'uses'=>'App\Http\Controllers\ApiController@checkBookHasBookMark',
   'as'=> 'check-has-book-mark'
]);

Route::post('/save-book-marks',[
   'uses'=>'App\Http\Controllers\ApiController@saveBookMarks',
   'as'=> 'save-book-marks'
]);

Route::post('/update-book-marks',[
   'uses'=>'App\Http\Controllers\ApiController@updateBookMarks',
   'as'=> '/update-book-marks'
]);

//FAVORITE===========================================================================
Route::post('/get-favorite-list',[
   'uses'=>'App\Http\Controllers\ApiController@getCustomerFavoriteList',
   'as'=> 'get-favorite-list'
]);

Route::post('/add-to-favorites',[
   'uses'=>'App\Http\Controllers\ApiController@addToFavorites',
   'as'=> 'add-to-favorites'
]);

//CART===============================================================================
Route::post('/get-cart-list',[
   'uses'=>'App\Http\Controllers\ApiController@getCustomerCartList',
   'as'=> 'get-cart-list'
]);

Route::post('/add-to-cart',[
   'uses'=>'App\Http\Controllers\ApiController@addToCart',
   'as'=> 'add-to-cart'
]);

Route::post('/remove-to-cart',[
   'uses'=>'App\Http\Controllers\ApiController@removeToCart',
   'as'=> 'remove-to-cart'
]);

//LIBRARY =============================================================================
Route::post('/get-customer-library-list',[
   'uses'=>'App\Http\Controllers\ApiController@getCustomerLibraryList',
   'as'=> 'get-customer-library-list'
]);

Route::post('/add-to-library',[
   'uses'=>'App\Http\Controllers\ApiController@addToLibrary',
   'as'=> 'add-to-library'
]);

//SUBSCRIBED OPEN READ BOOKS==========================================================
Route::post('/get-subscribed-read-books-list',[
   'uses'=>'App\Http\Controllers\ApiController@getSubscribedReadBooksList',
   'as'=> 'get-subscribed-read-books-list'
]);

Route::post('/save-read-books',[
   'uses'=>'App\Http\Controllers\ApiController@saveReadSubscribedBooks',
   'as'=> 'save-read-books'
]);

//DOWNLOADED BOOKS====================================================================
Route::post('/get-subscribed-downloaded-books-list',[
   'uses'=>'App\Http\Controllers\ApiController@getSubscribedDownloadedBooksList',
   'as'=> 'get-subscribed-downloaded-books-list'
]);

Route::post('/save-download-books',[
   'uses'=>'App\Http\Controllers\ApiController@saveDownloadedSubscribedBooks',
   'as'=> 'save-download-books'
]);

//CART TRANS CHECK OUT ===============================================================
Route::post('/proceed-to-checkout',[
   'uses'=>'App\Http\Controllers\ApiController@proceedToCheckOut',
   'as'=> 'proceed-to-checkout'
]);

//ORDER TRANSACTION ==================================================================
Route::post('/get-order-history-list',[
   'uses'=>'App\Http\Controllers\ApiController@getCustomerOrderHistory',
   'as'=> 'get-order-history-list'
]);

Route::post('/get-order-information',[
   'uses'=>'App\Http\Controllers\ApiController@getCustomerOrderInformation',
   'as'=> 'get-order-information'
]);

Route::post('/get-order-details',[
   'uses'=>'App\Http\Controllers\ApiController@getCustomerOrderDetails',
   'as'=> 'get-order-details'
]);

Route::post('/send-order-history-list',[
   'uses'=>'App\Http\Controllers\ApiController@sendCustomerOrderHistory',
   'as'=> 'send-order-history-list'
]);

// REVIEW & COMMENT===================================================================
Route::post('/get-review-list',[
   'uses'=>'App\Http\Controllers\ApiController@getBookReview',
   'as'=> 'get-review-list'
]);

Route::post('/post-comment-review',[
   'uses'=>'App\Http\Controllers\ApiController@doPostCommentReview',
   'as'=> 'post-comment-review'
]);

//BANNER ADS==========================================================================
Route::post('/get-home-slider-banner',[
   'uses'=>'App\Http\Controllers\ApiController@getHomeSliderBanner',
   'as'=> 'get-home-slider-banner'
]);

Route::post('/get-home-popup-banner',[
   'uses'=>'App\Http\Controllers\ApiController@getPopUpBanner',
   'as'=> 'get-home-popup-banner'
]);

//COUPON =============================================================================
Route::post('/get-available-coupon-list',[
   'uses'=>'App\Http\Controllers\ApiController@getAvailableCouponList',
   'as'=> 'get-available-coupon-list'
]);

Route::post('/validate-coupon-code',[
   'uses'=>'App\Http\Controllers\ApiController@validateCouponCode',
   'as'=> 'validate-coupon-code'
]);

//SUBSCRIPTION PLAN ===================================================================
Route::post('/get-subscription-plan-list',[
   'uses'=>'App\Http\Controllers\ApiController@getSubscriptionPlanList',
   'as'=> 'get-subscription-plan-list'
]);

Route::post('/proceed-to-subscribe',[
   'uses'=>'App\Http\Controllers\ApiController@proceedToSubscribe',
   'as'=> 'proceed-to-subscribe'
]);

Route::post('/check-subscription-status',[
   'uses'=>'App\Http\Controllers\ApiController@checkSubscriptionStatus',
   'as'=> 'check-subscription-status'
]);

Route::post('/cancel-subscription-plan',[
   'uses'=>'App\Http\Controllers\ApiController@cancelSubscriptionPlan',
   'as'=> 'cancel-subscription-plan'
]);

Route::post('/check-subscriber-status',[
   'uses'=>'App\Http\Controllers\ApiController@checkSubscriberStatus',
   'as'=> 'check-subscriber-status'
]);

//CONTACT US FORM========================================================================
Route::post('/send-inquiry',[
   'uses'=>'App\Http\Controllers\ApiController@doSendInquiry',
   'as'=> 'send-inquiry'
]);

//EWALLET CREDIT HISTORY=================================================================
Route::post('/get-ewallet-history',[
   'uses'=>'App\Http\Controllers\ApiController@getEWalletCreditsHistory',
   'as'=> 'get-ewallet-history'
]);

//MESSAGE NOTIFICATION ==================================================================
Route::post('/get-message-notification',[
   'uses'=>'App\Http\Controllers\ApiController@getMessageNotificationList',
   'as'=> 'get-message-notification'
]);

Route::post('/open-message-notification',[
   'uses'=>'App\Http\Controllers\ApiController@openSetReadMessageNotification',
   'as'=> 'open-message-notification'
]);

Route::post('/delete-message-notification',[
   'uses'=>'App\Http\Controllers\ApiController@deleteReadMessageNotification',
   'as'=> 'delete-message-notification'
]);

//UPLOAD IMAGE PAYMENT====================================================================
Route::post('/upload-image-payment',[
   'uses'=>'App\Http\Controllers\ApiController@uploadPaymentImage',
   'as'=> 'upload-image-payment'
]);

//COMPANY INFO===========================================================================
Route::get('/get-company-about-us',[
   'uses'=>'App\Http\Controllers\ApiController@getCompanyAboutUs',
   'as'=> 'get-company-about-us'
]);

Route::get('/get-company-faq',[
   'uses'=>'App\Http\Controllers\ApiController@getCompanyFAQ',
   'as'=> 'get-company-faq'
]);

Route::get('/get-company-privacy-policy',[
   'uses'=>'App\Http\Controllers\ApiController@getCompanyPrivacyPolicy',
   'as'=> 'get-company-privacy-policy'
]);

Route::get('/get-company-terms-condition',[
   'uses'=>'App\Http\Controllers\ApiController@getCompanyTermsCondition',
   'as'=> 'get-company-terms-condition'
]);

//EPUB VIEWER=============================================================================
Route::get('/show-viewer',[
   'uses'=>'App\Http\Controllers\ApiController@showViewerEpub',
   'as'=> 'show-viewer'
]);


