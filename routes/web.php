<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialiteController;

// CMS Controllers
use App\Http\Controllers\{FileDownloadCategoryController, FileDownloadController, MemberController, PageModalController, SitemapController, FacebookDataDeletionController, GoogleDataDeletionController, FacebookController, QrCodeController, ResourceCategoryController, ResourceController};

use App\Http\Controllers\Cms4Controllers\{
    ArticleCategoryController, ArticleFrontController, ArticleController, AlbumController, MobileAlbumController, PageController, MenuController, FileManagerController
};

// Settings
use App\Http\Controllers\Settings\{
    PermissionController, AccountController, AccessController, UserController, LogsController, RoleController, WebController
};

// Ecommerce Controller
use App\Http\Controllers\Ecommerce\{
    CustomerController, CustomerFrontController, ProductCategoryController, ProductController, ProductFrontController, InventoryReceiverHeaderController, PromoController, DeliverablecitiesController, CouponController, CouponFrontController, CartController, MyAccountController, SalesController, ReportsController, BrandController, FormAttributeController, ProductReviewController, CustomerFavoriteController, CustomerWishlistController, BannerAdController, ProductCatalogHeaderController
};

use App\Http\Controllers\MailingList\{SubscriberController, GroupController, CampaignController, SubscriberFrontController};



//FOR STORAGE LINK
Route::get('/storagelink', function () {
    Artisan::call('storage:link');
});

//FOR PHPINFO
Route::get('/phpinfo', function () {
    phpinfo();
});


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

// CMS4 Front Pages
    Route::get('/', [FrontController::class, 'home'])->name('home');
    Route::get('/privacy-policy/', [FrontController::class, 'privacy_policy'])->name('privacy-policy');
    Route::post('/contact-us', [FrontController::class, 'contact_us'])->name('contact-us');

    Route::get('/search', [FrontController::class, 'search'])->name('search');

    //News Frontend
        Route::get('/news/', [ArticleFrontController::class, 'news_list'])->name('news.front.index');
        Route::get('/news/{slug}', [ArticleFrontController::class, 'news_view'])->name('news.front.show');
        Route::get('/news/{slug}/print', [ArticleFrontController::class, 'news_print'])->name('news.front.print');
        Route::post('/news/{slug}/share', [ArticleFrontController::class, 'news_share'])->name('news.front.share');

        Route::get('/albums/preview', [FrontController::class, 'test'])->name('albums.preview');
        Route::get('/search-result', [FrontController::class, 'seach_result'])->name('search.result');
    //

    // Sitemap
        Route::get('/sitemap', [FrontController::class, 'sitemap'])->name('sitemap');
        // Route::get('/sitemap', [SitemapController::class, 'index'])->name('sitemap');
    // 

    // Portfolio
    Route::get('/portfolio', [FrontController::class, 'portfolio'])->name('portfolio');


    /*Extra Pages */
    // products page
    Route::get('/products', [FrontController::class, 'products'])->name('products');
    Route::get('/equipments', [FrontController::class, 'equipments'])->name('equipments');
    Route::get('/services', [FrontController::class, 'services'])->name('services');

    // Resources
        Route::get('/case-details/{slug}', [FrontController::class, 'resource_details'])->name('resource-details.front.show');
        Route::get('/cases', [FrontController::class, 'resource_list'])->name('resource-list.front.show');
        Route::get('/cases/{slug}', [FrontController::class, 'resource_category_list'])->name('resource-category.list');


    Route::post('/subscribe', [SubscriberFrontController::class, 'subscribe'])->name('mailing-list.front.subscribe');
    Route::get('/unsubscribe/{subscriber}/{code}', [SubscriberFrontController::class, 'unsubscribe'])->name('mailing-list.front.unsubscribe');


    // Customer Signup - Signin
    Route::get('/login',                  [CustomerFrontController::class, 'login'])->name('customer-front.login');
    Route::post('/login',                 [CustomerFrontController::class, 'customer_login'])->name('customer-front.customer_login');
    Route::get('/customer-sign-up',       [CustomerFrontController::class, 'sign_up'])->name('customer-front.sign-up');
    Route::post('/customer-sign-up',      [CustomerFrontController::class, 'customer_sign_up'])->name('customer-front.customer-sign-up');
    Route::get('/forgot-password',        [CustomerFrontController::class, 'forgot_password'])->name('customer-front.forgot_password');
    Route::post('/forgot-password',       [CustomerFrontController::class, 'sendNewUserResetLinkEmail'])->name('customer-front.send_new_user_reset_link_email');
    Route::post('/forgot-password',       [CustomerFrontController::class, 'sendResetLinkEmail'])->name('customer-front.send_reset_link_email');
    Route::get('/reset-password/{token}', [CustomerFrontController::class, 'showResetForm'])->name('customer-front.reset_password');
    Route::post('/reset-password',        [CustomerFrontController::class, 'reset'])->name('customer-front.reset_password_post');

    //Socialite Signup -Signin
    Route::get('login/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('login.provider');
    Route::get('login/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);

    Route::post('facebook/data-deletion', [FacebookDataDeletionController::class, 'handle'])->name('facebook.data-deletion');
    Route::post('google/data-deletion', [GoogleDataDeletionController::class, 'handle'])->name('google.data-deletion');
    
    //Chat Plugin
    Route::post('/setup-chat-plugin', [FacebookController::class, 'setupChatPlugin']);

    // Ecommerce Pages
    
    Route::get('/brands', [ProductFrontController::class, 'brands'])->name('product.brands');
    Route::get('/brand-product-categories/{id}', [ProductFrontController::class, 'brand_product_categories'])->name('brand.product-category-list');
    Route::get('/product-sub-categories/{id}', [ProductFrontController::class, 'product_sub_categories'])->name('product.sub-categories');

    // Route::get('/brand-products/{id}', [ProductFrontController::class, 'brand_products'])->name('brand.product-list');
    Route::get('/category-products/{id}', [ProductFrontController::class, 'category_products'])->name('category.product-list');
    
    
    // Cart Management
    Route::get('/cart',                [CartController::class, 'cart'])->name('cart.front.show');
    Route::post('add-to-cart',         [CartController::class, 'add_to_cart'])->name('product.add-to-cart');
    Route::post('ebbok-add-to-cart',         [CartController::class, 'ebook_add_to_cart'])->name('ebook.add-to-cart');
    Route::post('buy-now',             [CartController::class, 'buy_now'])->name('cart.buy-now');
    Route::post('cart-update',         [CartController::class, 'cart_update'])->name('cart.update');
    Route::post('cart-remove-product', [CartController::class, 'remove_product'])->name('cart.remove_product');
    Route::post('proceed-checkout',    [CartController::class, 'proceed_checkout'])->name('cart.front.proceed_checkout');


    Route::post('/payment-notification', [CartController::class, 'receive_data_from_payment_gateway'])->name('cart.payment-notification');


// ADMIN ROUTES
Route::get('/admin-panel', [DashboardController::class, 'index'])->name('dashboard');
        
Route::group(['prefix' => 'admin-panel'], function (){
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('panel.login');

    Auth::routes();

    Route::group(['middleware' => 'admin'], function (){

        Route::get('/admin-panel', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/admin/ecommerce-dashboard', [DashboardController::class, 'ecommerce'])->name('ecom-dashboard');

        // Account
            Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
            Route::put('/account/update', [AccountController::class, 'update'])->name('account.update');
            Route::put('/account/update_email', [AccountController::class, 'update_email'])->name('account.update-email');
            Route::put('/account/update_password', [AccountController::class, 'update_password'])->name('account.update-password');
        //

        // Website
            Route::get('/website-settings/edit', [WebController::class, 'edit'])->name('website-settings.edit');
            Route::put('/website-settings/update', [WebController::class, 'update'])->name('website-settings.update');
            Route::post('/website-settings/update_contacts', [WebController::class, 'update_contacts'])->name('website-settings.update-contacts');
            Route::post('/website-settings/update-ecommerce', [WebController::class, 'update_ecommerce'])->name('website-settings.update-ecommerce');
            Route::post('/website-settings/update-paynamics', [WebController::class, 'update_paynamics'])->name('website-settings.update-paynamics');
            Route::post('/website-settings/update-signin', [WebController::class, 'update_signin'])->name('website-settings.update-signin');
            Route::post('/website-settings/update_media_accounts', [WebController::class, 'update_media_accounts'])->name('website-settings.update-media-accounts');
            Route::post('/website-settings/update_data_privacy', [WebController::class, 'update_data_privacy'])->name('website-settings.update-data-privacy');
            Route::post('/website-settings/remove_logo', [WebController::class, 'remove_logo'])->name('website-settings.remove-logo');
            Route::post('/website-settings/remove_icon', [WebController::class, 'remove_icon'])->name('website-settings.remove-icon');
            Route::post('/website-settings/remove_media', [WebController::class, 'remove_media'])->name('website-settings.remove-media');
            Route::post('update-coupons-settings', [WebController::class, 'update_coupon_settings'])->name('website-settings.update-coupont-settings');
        //

        // Audit
            Route::get('/audit-logs', [LogsController::class, 'index'])->name('audit-logs.index');
        //

        // Users
            Route::resource('/users', UserController::class);
            Route::post('/users/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
            Route::post('/users/activate', [UserController::class, 'activate'])->name('users.activate');
            Route::get('/user-search/', [UserController::class, 'search'])->name('user.search');
            Route::get('/profile-log-search/', [UserController::class, 'filter'])->name('user.activity.search');
        //

        // Roles
            Route::resource('/role', RoleController::class);
            Route::post('/role/delete',[RoleController::class, 'destroy'])->name('role.delete');
            Route::get('/role/restore/{id}',[RoleController::class, 'restore'])->name('role.restore');
        //

        // Access
            Route::resource('/access', AccessController::class);
            Route::post('/roles_and_permissions/update', [AccessController::class, 'update_roles_and_permissions'])->name('role-permission.update');

            if (env('APP_DEBUG') == "true") {
                // Permission Routes
                Route::resource('/permission', PermissionController::class)->except(['destroy']);
                Route::get('/permission-search/', [PermissionController::class, 'search'])->name('permission.search');
                Route::post('/permission/destroy', [PermissionController::class, 'destroy'])->name('permission.destroy');
                Route::get('/permission/restore/{id}', [PermissionController::class, 'restore'])->name('permission.restore');
                Route::post('permission/delete', [PermissionController::class, 'delete'])->name('permission.delete');

            }
        //



        ###### CMS4 Standard Routes ######
            //Pages
                Route::resource('/pages', PageController::class);
                Route::get('/pages-advance-search', [PageController::class, 'advance_index'])->name('pages.index.advance-search');
                Route::post('/pages/get-slug', [PageController::class, 'get_slug'])->name('pages.get_slug');
                Route::put('/pages/{page}/default', [PageController::class, 'update_default'])->name('pages.update-default');
                Route::put('/pages/{page}/customize', [PageController::class, 'update_customize'])->name('pages.update-customize');
                Route::put('/pages/{page}/contact-us', [PageController::class, 'update_contact_us'])->name('pages.update-contact-us');
                Route::post('/pages-change-status', [PageController::class, 'change_status'])->name('pages.change.status');
                Route::post('/pages-delete', [PageController::class, 'delete'])->name('pages.delete');
                Route::get('/page-restore/{page}', [PageController::class, 'restore'])->name('pages.restore');
            //

            // Albums
                Route::resource('/albums', AlbumController::class);
                Route::post('/albums/upload', [AlbumController::class, 'upload'])->name('albums.upload');
                Route::delete('/many/album', [AlbumController::class, 'destroy_many'])->name('albums.destroy_many');
                Route::put('/albums/quick/{album}', [AlbumController::class, 'quick_update'])->name('albums.quick_update');
                Route::post('/albums/{album}/restore', [AlbumController::class, 'restore'])->name('albums.restore');
                Route::post('/albums/banners/{album}', [AlbumController::class, 'get_album_details'])->name('albums.banners');
            //

            // Mobile Albums
                Route::resource('/mobile-albums', MobileAlbumController::class);
                Route::post('/mobile-albums/upload', [MobileAlbumController::class, 'upload'])->name('mobile-albums.upload');
                Route::delete('/many/mobile-album', [MobileAlbumController::class, 'destroy_many'])->name('mobile-albums.destroy_many');
                Route::put('/mobile-albums/quick/{mobile_album}', [MobileAlbumController::class, 'quick_update'])->name('mobile-albums.quick_update');
                Route::post('/mobile-albums/{mobile_album}/restore', [MobileAlbumController::class, 'restore'])->name('mobile-albums.restore');
                Route::post('/mobile-albums/banners/{mobile_album}', [MobileAlbumController::class, 'get_album_details'])->name('mobile-albums.banners');
                Route::get('/mobile-albums/change-status/{id}', [MobileAlbumController::class, 'change_status'])->name('mobile-albums.change-status');
            //

            // News
                Route::resource('/news', ArticleController::class)->except(['show', 'destroy']);
                Route::get('/news-advance-search', [ArticleController::class, 'advance_index'])->name('news.index.advance-search');
                Route::post('/news-get-slug', [ArticleController::class, 'get_slug'])->name('news.get-slug');
                Route::post('/news-change-status', [ArticleController::class, 'change_status'])->name('news.change.status');
                Route::post('/news-delete', [ArticleController::class, 'delete'])->name('news.delete');
                Route::get('/news-restore/{news}', [ArticleController::class, 'restore'])->name('news.restore');

                // News Category
                Route::resource('/news-categories', ArticleCategoryController::class)->except(['show']);;
                Route::post('/news-categories/get-slug', [ArticleCategoryController::class, 'get_slug'])->name('news-categories.get-slug');
                Route::post('/news-categories/delete', [ArticleCategoryController::class, 'delete'])->name('news-categories.delete');
                Route::get('/news-categories/restore/{id}', [ArticleCategoryController::class, 'restore'])->name('news-categories.restore');
            //

            // File Manager
                Route::get('laravel-filemanager', '\UniSharp\LaravelFilemanager\Controllers\LfmController@show')->name('file-manager.show');
                Route::post('laravel-filemanager/upload', '\UniSharp\LaravelFilemanager\Controllers\UploadController@upload')->name('unisharp.lfm.upload');
                Route::get('file-manager', [FileManagerController::class, 'index'])->name('file-manager.index');
            //

            // Menu
                Route::resource('/menus', MenuController::class);
                Route::delete('/many/menu', [MenuController::class, 'destroy_many'])->name('menus.destroy_many');
                Route::put('/menus/quick1/{menu}', [MenuController::class, 'quick_update'])->name('menus.quick_update');
                Route::get('/menu-restore/{menu}', [MenuController::class, 'restore'])->name('menus.restore');
            //

            // Resource Category
                Route::resource('resource-categories', ResourceCategoryController::class);
                Route::post('resource-category-delete', [ResourceCategoryController::class, 'single_delete'])->name('resource-category.single.delete');
                Route::get('resource-category-restore/{id}', [ResourceCategoryController::class, 'restore'])->name('resource-category.restore');
                Route::get('resource-category/{id}/{status}', [ResourceCategoryController::class, 'update_status'])->name('resource-category.change-status');
                Route::post('resource-categories-multiple-change-status',[ResourceCategoryController::class, 'multiple_change_status'])->name('resource-category.multiple.change.status');
                Route::post('resource-categories-multiple-delete',[ResourceCategoryController::class, 'multiple_delete'])->name('resource-category.multiple.delete');
            //

            // Resource List
                Route::resource('resources', ResourceController::class);
                Route::get('resource/{id}/{status}', [ResourceController::class, 'update_status'])->name('resources.change-status');
                Route::post('resource-delete', [ResourceController::class, 'single_delete'])->name('resources.single.delete');
                Route::get('resource-restore/{id}', [ResourceController::class, 'restore'])->name('resources.restore');
                Route::post('resources-multiple-change-status',[ResourceController::class, 'multiple_change_status'])->name('resources.multiple.change.status');
                Route::post('resources-multiple-delete',[ResourceController::class, 'multiple_delete'])->name('resources.multiple.delete');
                Route::post('resource-remove-file', [ResourceController::class, 'remove_file'])->name('resources.remove.file');
            //

        ###### CMS4 Standard Routes ######


        ###### Ecommerce Routes ######   
            // Page Modals
                Route::resource('page-modals', PageModalController::class);
                Route::get('modal/{id}/{status}', [PageModalController::class, 'update_status'])->name('modal.change-status');
                Route::post('modal-delete', [PageModalController::class, 'single_delete'])->name('modal.single.delete');
                Route::get('modal-restore/{id}', [PageModalController::class, 'restore'])->name('modal.restore');
                Route::post('modals-multiple-change-status',[PageModalController::class, 'multiple_change_status'])->name('modals.multiple.change.status');
                Route::post('modals-multiple-delete',[PageModalController::class, 'multiple_delete'])->name('modals.multiple.delete');
        ###### Ecommerce Routes ######
    });
});

// Pages Frontend
Route::get('/{any}', [FrontController::class, 'page'])->where('any', '.*');