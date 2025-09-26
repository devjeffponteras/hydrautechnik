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



    //Products/Books
    Route::get('books/{category?}', [ProductFrontController::class, 'product_list'])->name('product.front.list');
    Route::get('/book-details/{slug}', [ProductFrontController::class, 'product_details'])->name('product.details');
    // Route::get('/ebook-details/{slug}', [ProductFrontController::class, 'ebook_details'])->name('ebook.details');
    Route::get('/search-products', [ProductFrontController::class, 'search_product'])->name('search-product');
    Route::get('/search-contents', [ProductFrontController::class, 'search_content'])->name('search-content');

    Route::get('/generate-book-qr-code', [QrCodeController::class, 'generate_product_qr'])->name('generate.product.qr');
    Route::get('/book/series', [QrCodeController::class, 'product_series'])->name('product.series');



    // ECOMMERCE CUSTOMER AUTH ROUTES
        Route::group(['middleware' => ['authenticated']], function () {
            // MEMBER
            Route::get('/member/file-downloads', [MemberController::class, 'file_download'])->name('member.file-download');
            Route::get('/member/manage-account', [MemberController::class, 'manage_account'])->name('member.manage-account');
            Route::get('/member/change-password', [MemberController::class, 'change_password'])->name('member.change-password');
            Route::get('/member-logout', [MemberController::class, 'logout'])->name('member.logout');


            Route::post('/add-manual-coupon', [CouponFrontController::class, 'add_manual_coupon'])->name('add-manual-coupon');
            Route::get('/show-coupons', [CouponFrontController::class, 'collectibles'])->name('show-coupons');


            Route::get('/customer/dashboard', [MyAccountController::class, 'dashboard'])->name('customer.dashboard');
            Route::get('/manage-account', [MyAccountController::class, 'manage_account'])->name('customer.manage-account');
            Route::get('/library', [MyAccountController::class, 'library'])->name('customer.library');
            Route::get('/wishlist', [MyAccountController::class, 'wishlist'])->name('customer.wishlist');
            Route::get('/favorites', [MyAccountController::class, 'favorites'])->name('customer.favorites');
            Route::get('/free-ebooks', [MyAccountController::class, 'free_ebooks'])->name('customer.free-ebooks');
            Route::get('/ecredits', [MyAccountController::class, 'ecredits'])->name('customer.ecredits');
            Route::post('/account-update', [MyAccountController::class, 'update_personal_info'])->name('my-account.update-personal-info');
            Route::get('/account/change-password', [MyAccountController::class, 'change_password'])->name('my-account.change-password');
            Route::post('/account/change-password', [MyAccountController::class, 'update_password'])->name('my-account.update-password');
            Route::get('/account-logout', [CustomerFrontController::class, 'logout'])->name('account.logout');
            
            //DEACTIVATE SOCIAL LOGIN 
            Route::post('/deactivate-social-login', [MyAccountController::class, 'deactivate_social_login'])->name('customer.deactivate-social-login');

            Route::get('/my-orders', [MyAccountController::class, 'orders'])->name('profile.sales');
            Route::get('/account/pay/{id}', [MyAccountController::class, 'pay_again'])->name('my-account.pay-again');
            Route::post('/account/cancel/order', [MyAccountController::class, 'cancel_order'])->name('my-account.cancel-order');


            Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.front.checkout');
            Route::post('/temp_save',[CartController::class, 'save_sales'])->name('cart.temp_sales');
            Route::get('/success',[CartController::class, 'success'])->name('cart.success');

            Route::get('/get-lbc-city-list', [CartController::class, 'lbc_cities'])->name('checkout.get-lbc-city-list');
            Route::get('/get-lbc-brgy-list', [CartController::class, 'lbc_barangays'])->name('checkout.get-lbc-brgy-list');

            
            //PRODUCT REVIEW
            Route::resource('/product-review', ProductReviewController::class)->except(['destroy']);
            Route::post('/product-review/single-approve', [ProductReviewController::class, 'single_approve'])->name('product-review.single-approve');
            Route::post('/product-review/single-delete', [ProductReviewController::class, 'single_delete'])->name('product-review.single-delete');
            Route::get('/product-review/restore/{id}', [ProductReviewController::class, 'restore'])->name('product-review.restore');
            Route::post('/product-review-multiple-delete',[ProductReviewController::class, 'multiple_delete'])->name('product-review.multiple.delete');
            Route::post('/product-review-multiple-approve',[ProductReviewController::class, 'multiple_approve'])->name('product-review.multiple-approve');
            Route::post('/product-review-update-review',[ProductReviewController::class, 'update_review'])->name('product-review.update-review');

            //PRODUCT CATALOG
            Route::resource('/product-catalog', ProductCatalogHeaderController::class)->except(['destroy']);
            Route::get('/product-catalog/restore/{id}', [ProductCatalogHeaderController::class, 'restore'])->name('product-catalog.restore');
            Route::get('/product-catalog/{id}/{status}', [ProductCatalogHeaderController::class, 'update_status'])->name('product-catalog.change-status');
            Route::post('/product-catalog/single-delete', [ProductCatalogHeaderController::class, 'single_delete'])->name('product-catalog.single.delete');
            Route::post('/product-catalog/multiple-change-status',[ProductCatalogHeaderController::class, 'multiple_change_status'])->name('product-catalog.multiple.change.status');
            Route::post('/product-catalog/multiple-delete',[ProductCatalogHeaderController::class, 'multiple_delete'])->name('product-catalog.multiple.delete');

            //CUSTOMER FAVORITES
            Route::resource('/customer_favorite', CustomerFavoriteController::class)->except(['destroy']);
            Route::get('/customer_favorite/add-to-favorites/{prd_id}', [CustomerFavoriteController::class, 'add_to_favorites'])->name('add-to-favorites');
            
            //CUSTOMER WISHLIST
            Route::resource('/customer_wishlist', CustomerWishlistController::class)->except(['destroy']);
            Route::get('/customer_wishlist/add-to-wishlist/{prd_id}', [CustomerWishlistController::class, 'add_to_wishlist'])->name('add-to-wishlist');

        });
    //

    //BANNER ADS
    
        Route::get('/ads/click_count/{id}',[BannerAdController::class, 'click_count'])->name('ads.click.count');



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
            // Members
                Route::get('members', [UserController::class, 'members'])->name('members.index');
                Route::get('members/create', [UserController::class, 'member_create'])->name('members.create');
                Route::post('store-member', [UserController::class, 'member_store'])->name('members.store');

                Route::get('members/edit/{id}', [UserController::class, 'member_edit'])->name('members.edit');
                Route::post('members-update', [UserController::class, 'member_update'])->name('members.update');

                Route::post('member-multiple-change-status',[MemberController::class, 'multiple_change_status'])->name('member.multiple.change.status');
                Route::get('/member/{id}/{status}', [MemberController::class, 'update_status'])->name('member.change-status');
                Route::post('member-multiple-delete',[MemberController::class, 'multiple_delete'])->name('member.multiple.delete');
                Route::post('member-single-delete', [MemberController::class, 'single_delete'])->name('member.single.delete');
                Route::get('member-restore/{id}', [MemberController::class, 'restore'])->name('member.restore');
            //

            // Customers
                Route::resource('/admin/customers', CustomerController::class);
                Route::post('/customer/deactivate', [CustomerController::class, 'deactivate'])->name('customer.deactivate');
                Route::post('/customer/activate', [CustomerController::class, 'activate'])->name('customer.activate');
                Route::post('/customer/update', [CustomerController::class, 'update'])->name('customer.update');
            //

            // Product Categories
                Route::resource('/admin/product-categories',ProductCategoryController::class);
                Route::post('/admin/product-category-get-slug', [ProductCategoryController::class, 'get_slug'])->name('product.category.get-slug');
                Route::post('/admin/product-categories-single-delete', [ProductCategoryController::class, 'single_delete'])->name('product.category.single.delete');
                Route::get('/admin/product-category/search', [ProductCategoryController::class, 'search'])->name('product.category.search');
                Route::get('/admin/product-category/restore/{id}', [ProductCategoryController::class, 'restore'])->name('product.category.restore');
                Route::get('/admin/product-category/{id}/{status}', [ProductCategoryController::class, 'update_status'])->name('product.category.change-status');
                Route::post('/admin/product-categories-multiple-change-status',[ProductCategoryController::class, 'multiple_change_status'])->name('product.category.multiple.change.status');
                Route::post('/admin/product-category-multiple-delete',[ProductCategoryController::class, 'multiple_delete'])->name('product.category.multiple.delete');

                Route::post('reorder-category', [ProductCategoryController::class, 'reorder_category'])->name('reorder-product-category');
            //
            

            //Product Bundles
                Route::get('/admin/products/create-bundle', [ProductController::class, 'create_bundle'])->name('product.create.bundle');
                Route::get('/admin/products/edit-bundle/{id}', [ProductController::class, 'edit_bundle'])->name('product.edit.bundle');
            // 

            // Products
                Route::resource('/admin/products', ProductController::class);
                Route::get('/products-advance-search', [ProductController::class, 'advance_index'])->name('product.index.advance-search');
                Route::post('/admin/product-get-slug', [ProductController::class, 'get_slug'])->name('product.get-slug');
                Route::post('/admin/products/upload', [ProductController::class, 'upload'])->name('products.upload');

                Route::get('/admin/product-change-status/{id}/{status}', [ProductController::class, 'change_status'])->name('product.single-change-status');
                Route::post('/admin/product-single-delete', [ProductController::class, 'single_delete'])->name('product.single.delete');
                Route::get('/admin/product/restore/{id}', [ProductController::class, 'restore'])->name('product.restore');
                Route::post('/admin/product-multiple-change-status', [ProductController::class, 'multiple_change_status'])->name('product.multiple.change.status');
                Route::post('/admin/product-multiple-delete', [ProductController::class, 'multiple_delete'])->name('products.multiple.delete');

                Route::post('/product-add-inventory',[ ProductController::class, 'add_inventory'])->name('products.add-inventory');
                Route::post('/product-deduct-inventory',[ ProductController::class, 'deduct_inventory'])->name('products.deduct-inventory');


                Route::get('/product-download-template',[ProductController::class, 'download_template'])->name('product.download.template');
                Route::post('/product-upload-template',[ProductController::class, 'upload_template'])->name('product.upload.template');

                Route::get('/generate-file-qr-code', [QrCodeController::class, 'generate_file_qr'])->name('generate.file.qr');

                Route::get('/ebook-customer-assignment/{id}', [ProductController::class, 'ebook_customer_assignment'])->name('product.ebook-customer-assignment');
                Route::put('/ebook-customer-assignment-update/{id}', [ProductController::class, 'ebook_customer_assignment_update'])->name('product.ebook-customer-assignment-update');


            //

            // Brands
                Route::resource('brands', BrandController::class);
                Route::get('brand/{id}/{status}', [BrandController::class, 'update_status'])->name('brand.change-status');
                Route::post('brand-single-delete', [BrandController::class, 'single_delete'])->name('brand.single.delete');
                Route::get('/admin/brand/restore/{id}', [BrandController::class, 'restore'])->name('brand.restore');
                Route::post('brand-multiple-change-status',[BrandController::class, 'multiple_change_status'])->name('brand.multiple.change.status');
                Route::post('brand-multiple-delete',[BrandController::class, 'multiple_delete'])->name('brand.multiple.delete');

                Route::get('brand-menu-order', [BrandController::class, 'menu_order'])->name('brand.menu-order');
                Route::post('brand-update-nestable-menu', [BrandController::class, 'update_nestable_menu'])->name('brand.update-nestable-menu');

                Route::post('reorder-brand', [BrandController::class, 'reorder_brand'])->name('reorder-brand');


            //Inventory
                Route::resource('/inventory',InventoryReceiverHeaderController::class);
                Route::get('/inventory-download-template',[InventoryReceiverHeaderController::class, 'download_template'])->name('inventory.download.template');
                Route::post('/inventory-upload-template',[InventoryReceiverHeaderController::class, 'upload_template'])->name('inventory.upload.template');
                Route::get('/inventory-post/{id}',[InventoryReceiverHeaderController::class, 'post'])->name('inventory.post');
                Route::get('/inventory-cancel/{id}',[InventoryReceiverHeaderController::class, 'cancel'])->name('inventory.cancel');
                Route::get('/inventory-view/{id}',[InventoryReceiverHeaderController::class, 'view'])->name('inventory.view');
            //

            // Promos
                Route::resource('/admin/promos', PromoController::class);
                Route::get('/admin/promo/{id}/{status}', [PromoController::class, 'update_status'])->name('promo.change-status');
                Route::post('/admin/promo-single-delete', [PromoController::class, 'single_delete'])->name('promo.single.delete');
                Route::post('/admin/promo-multiple-change-status',[PromoController::class, 'multiple_change_status'])->name('promo.multiple.change.status');
                Route::post('/admin/promo-multiple-delete',[PromoController::class, 'multiple_delete'])->name('promo.multiple.delete');
                Route::get('/admin/promo-restore/{id}', [PromoController::class, 'restore'])->name('promo.restore');
            //

            // Delivery Rates
                Route::resource('/locations', DeliverablecitiesController::class);
                Route::get('/admin/location/{id}/{status}', [DeliverablecitiesController::class, 'update_status'])->name('location.change-status');
                Route::post('/admin/location-single-delete', [DeliverablecitiesController::class, 'single_delete'])->name('location.single.delete');
                Route::post('/admin/location-multiple-change-status',[DeliverablecitiesController::class, 'multiple_change_status'])->name('location.multiple.change.status');
                Route::post('/admin/location-multiple-delete',[DeliverablecitiesController::class, 'multiple_delete'])->name('location.multiple.delete');
                Route::get('/restore-rate/{id}', [DeliverablecitiesController::class, 'restore'])->name('location.restore');
            //

            // Coupon
                Route::resource('/coupons',CouponController::class);
                Route::get('/coupon/{id}/{status}', [CouponController::class, 'update_status'])->name('coupon.change-status');
                Route::post('/coupon-single-delete', [CouponController::class, 'single_delete'])->name('coupon.single.delete');
                Route::get('/coupon-restore/{id}', [CouponController::class, 'restore'])->name('coupon.restore');
                Route::post('/coupon-multiple-change-status',[CouponController::class, 'multiple_change_status'])->name('coupon.multiple.change.status');
                Route::post('/coupon-multiple-delete',[CouponController::class, 'multiple_delete'])->name('coupon.multiple.delete');

                // Route::get('/get-product-brands', [CouponFrontController::class, 'get_brands'])->name('display.product-brands');
                Route::get('/coupon-download-template', [CouponController::class, 'download_coupon_template'])->name('coupon.download.template');
            //

            // BannerAds
                Route::resource('/ads',BannerAdController::class);
                Route::post('/ads/delete/{id}',[BannerAdController::class, 'delete'])->name('ads.delete');
                Route::post('/ads/restore/{id}',[BannerAdController::class, 'restore'])->name('ads.restore');
            //

            // Sales Transaction
                Route::resource('/admin/sales-transaction', SalesController::class);
                Route::post('/admin/sales-transaction/change-status', [SalesController::class, 'change_status'])->name('sales-transaction.change.status');
                Route::post('/admin/sales-transaction/{sales}', [SalesController::class, 'quick_update'])->name('sales-transaction.quick_update');
                Route::get('/admin/sales-transaction/view/{sales}', [SalesController::class, 'show'])->name('sales-transaction.view');
                Route::post('/admin/change-delivery-status', [SalesController::class, 'delivery_status'])->name('sales-transaction.delivery_status');
                Route::get('/admin/sales-transaction/print/{sales}', [SalesController::class, 'print'])->name('sales-transaction.print');


                Route::get('/admin/sales-transaction/view-payment/{sales}', [SalesController::class, 'view_payment'])->name('sales-transaction.view_payment');
                Route::post('/admin/sales-transaction/cancel-product', [SalesController::class, 'cancel_product'])->name('sales-transaction.cancel_product');
                Route::get('/sales-advance-search/', [SalesController::class, 'advance_index'])->name('admin.sales.list.advance-search');


                

                Route::get('/admin/sales-transaction/view-payment/{sales}', [SalesController::class, 'view_payment'])->name('sales-transaction.view_payment');
                Route::post('/admin/sales-transaction/cancel-product', [SalesController::class, 'cancel_product'])->name('sales-transaction.cancel_product');
                
                Route::get('/display-added-payments', [SalesController::class, 'display_payments'])->name('display.added-payments');
                Route::get('/display-delivery-history', [SalesController::class, 'display_delivery'])->name('display.delivery-history');

                Route::get('/sales/update-payment/{id}','EcommerceControllers\JoborderController@staff_edit_payment')->name('staff-edit-payment');
                Route::post('/sales/update-payment','EcommerceControllers\JoborderController@staff_update_payment')->name('staff-update-payment');
            //

            // Form Attributes
                Route::resource('product-attributes', FormAttributeController::class);
                Route::post('product-attribute-delete', [FormAttributeController::class, 'single_delete'])->name('product-attribute.single.delete');
                Route::get('/attribute-restore/{id}', [FormAttributeController::class, 'restore'])->name('product-attribute.restore');

            // Mailing List
                Route::resource('mailing-list/subscribers', SubscriberController::class, ['as' => 'mailing-list']);
                Route::get('mailing-list/cancelled-subscribers', [SubscriberController::class, 'unsubscribe'])->name('mailing-list.subscribers.unsubscribe');
                Route::post('mailing-list/subscribers-change-status', [SubscriberController::class, 'change_status'])->name('mailing-list.subscribers.change-status');

                Route::resource('mailing-list/groups', GroupController::class, ['as' => 'mailing-list']);
                Route::delete('delete/mailing-list/groups', [GroupController::class, 'destroy_many'])->name('mailing-list.groups.destroy_many');
                Route::post('mailing-list-groups/{id}/restore', [GroupController::class, 'restore'])->name('mailing-list.groups.restore');

                Route::resource('mailing-list/campaigns', CampaignController::class, ['as' => 'mailing-list']);
                Route::get('mailing-list/forward-campaign/{id}', [CampaignController::class, 'forward_campaign'])->name('mailing-list.forward-campaign');
                Route::get('mailing-list/sent-campaigns', [CampaignController::class, 'sent_campaigns'])->name('mailing-list.campaigns.sent-campaigns');
                Route::delete('delete/mailing-list/campaign', [CampaignController::class, 'destroy_many'])->name('mailing-list.campaigns.destroy_many');
                Route::post('campaigns/{id}/restore', [CampaignController::class, 'restore'])->name('mailing-list.campaigns.restore');

                Route::post('sent-campaigns/{id}/delete', [CampaignController::class, 'delete_sent_campaign'])->name('mailing-list.sent-campaigns.delete');

            //

            // Page Modals
                Route::resource('page-modals', PageModalController::class);
                Route::get('modal/{id}/{status}', [PageModalController::class, 'update_status'])->name('modal.change-status');
                Route::post('modal-delete', [PageModalController::class, 'single_delete'])->name('modal.single.delete');
                Route::get('modal-restore/{id}', [PageModalController::class, 'restore'])->name('modal.restore');
                Route::post('modals-multiple-change-status',[PageModalController::class, 'multiple_change_status'])->name('modals.multiple.change.status');
                Route::post('modals-multiple-delete',[PageModalController::class, 'multiple_delete'])->name('modals.multiple.delete');


            // Reports
                Route::get('/report/best-sellers', [ReportsController::class, 'best_sellers'])->name('report.best-sellers');
                Route::get('/report/sales-transaction', [ReportsController::class, 'sales_list'])->name('report.sales-transaction');
                Route::get('/report/top-buyers', [ReportsController::class, 'top_buyers'])->name('report.top-buyers');
                Route::get('/report/top-products', [ReportsController::class, 'top_products'])->name('report.top-products');

                Route::get('/admin/report/sales_summary', [ReportsController::class, 'sales_summary'])->name('report.sales.summary');
                Route::get('/admin/report/delivery_status', [ReportsController::class, 'delivery_status'])->name('admin.report.delivery_status');
                Route::get('/admin/report/delivery_report/{id}', [ReportsController::class, 'delivery_report'])->name('admin.report.delivery_report');

                Route::get('/report/inventory_reorder_point', [ReportsController::class, 'inventory_reorder_point'])->name('report.inventory.reorder_point');
                Route::get('/report/coupon_list', [ReportsController::class, 'coupon_list'])->name('report.coupon.list');
                Route::get('/report/product-list', [ReportsController::class, 'product_list'])->name('report.product-list');
                Route::get('/report/customer-list', [ReportsController::class, 'customer_list'])->name('report.customer-list');

                Route::get('/report/promo-list', [ReportsController::class, 'promo_list'])->name('report.promo-list');
                Route::get('/report/payment-list', [ReportsController::class, 'payment_list'])->name('report.payment-list');

                Route::get('/report/wishlist', [ReportsController::class, 'wishlist'])->name('report.wishlist');
                Route::get('/report/favorites', [ReportsController::class, 'favorites'])->name('report.favorites');

            // Mobile Reports
                Route::get('/report/best-sellers/mobile', [ReportsController::class, 'best_sellers_mobile'])->name('report.best-sellers.mobile');
                Route::get('/report/sales-transaction/mobile', [ReportsController::class, 'sales_list_mobile'])->name('report.sales-transaction.mobile');
                Route::get('/report/top-buyers/mobile', [ReportsController::class, 'top_buyers_mobile'])->name('report.top-buyers.mobile');
                Route::get('/report/top-products/mobile', [ReportsController::class, 'top_products_mobile'])->name('report.top-products.mobile');
                Route::get('/report/subscribers/mobile', [ReportsController::class, 'subscribers_mobile'])->name('report.subscribers.mobile');
            //
        ###### Ecommerce Routes ######
    });
});

// Pages Frontend
Route::get('/{any}', [FrontController::class, 'page'])->where('any', '.*');