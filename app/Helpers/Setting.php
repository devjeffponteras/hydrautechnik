<?php

namespace App\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


use App\Models\Ecommerce\{
    Cart, ProductCategory, Product
};

use App\Models\{
    Option, PageModal
};

class Setting {

    public static function info() {

        $setting = DB::table('settings')->first();
        $setting->menu = DB::table('menus')->where('is_active', 1)->first();
        return $setting;

	}

	public static function getFaviconLogo()
    {
        $settings = DB::table('settings')->where('id',1)->first();

        return $settings;
    }

    public static function social_account($sm)
    {
        $account = DB::table('social_media')->where('name','=',$sm)->first();

        if($account === null){
            return false;
        }
        else{
            return $account;
        }
    }

    public static function getTopBar()
    {
        $top_bar = DB::table('pages')->where('slug', 'top-bar')->where('name', 'Top Bar')->first();

        return $top_bar;
    }

    public static function getAds()
    {
        $ads = DB::table('pages')->where('slug', 'ads')->where('name', 'Ads')->first();

        return $ads;
    }

    public static function getFooter()
    {
        $footer = DB::table('pages')->where('slug', 'footer')->where('name', 'footer')->first();

        return $footer;
    }

    public static function productCategories()
    {
        $categories = ProductCategory::where('status','PUBLISHED')->orderBy('name','asc')->get();

        return $categories;
    }

    public static function is_promo_product($productID)
    {
        $check_promo = DB::table('promos')->join('promo_products','promos.id','=','promo_products.promo_id')->where('promos.status','ACTIVE')->where('promos.is_expire',0)->where('promo_products.product_id',$productID)->count();

        return $check_promo;
    }

    public static function datetimeFormat($datetime)
    {
        return Carbon::parse($datetime)->format('m/d/Y H:i:s');
    }

    public static function datetimeFormat2($datetime)
    {
        return Carbon::parse($datetime)->format('m/d/Y h:i A');
    }

    public static function news_date_format($date)
    {
        return Carbon::parse($date)->format('d M Y');
    }

    public function delete_old_records(){

    }

    public static function date_for_listing($date) {
        if ($date == null || trim($date) == '') {
            return "-";
        }
        else if ($date != null && strtotime($date) < strtotime('-1 day')) {
            return Carbon::parse($date)->isoFormat('lll');
        }

        return Carbon::parse($date)->diffForHumans();
	}

    public static function date_for_news_list($date) {
        if ($date != null && strtotime($date) > strtotime('-1 day')) {
            return Carbon::parse($date)->toFormattedDateString();
        } else {
            return date('F d, Y', strtotime($date));
        }
    }

    public static function get_company_logo_storage_path()
    {
        $settings = DB::table('settings')->where('id',1)->first();

        return asset('storage').'/logos/'.$settings->company_logo;
    }

    public static function get_company_favicon_storage_path()
    {
        $settings = DB::table('settings')->where('id',1)->first();

        return asset('storage').'/icons/'.$settings->website_favicon;
    }

    public static function EcommerceCartTotalItems()
    {
        if (\Auth::check()) {
            return Cart::total_items_of_auth_cart();
        } else {
            return Cart::total_items_of_guest_cart();
        }
    }

    public static function hasItemsLeftOnCart(){

        $setting = \App\Models\Setting::first();

        $hoursAgo = now()->subHours($setting->cart_notification_duration);
    
        $isLeftOnCart = Cart::where('created_at', '<', $hoursAgo )->where('user_id', auth()->user()->id ?? -1)->exists();

        return $isLeftOnCart;
    }

    public static function hasItemThreeDaysOnCart(){
        $threeDaysAgo = now()->subDays(3);
    
        $isThreeDaysOnCart = Cart::where('created_at', '<', $threeDaysAgo)->where('user_id', auth()->user()->id ?? -1)->exists();
        return $isThreeDaysOnCart;
    }

    public static function isThreeDaysOnCart($id){
        $threeDaysAgo = now()->subDays(3);
    
        $isThreeDaysOnCart = Cart::where('id', $id)->where('created_at', '<', $threeDaysAgo)->where('user_id', auth()->user()->id)->exists();
        return $isThreeDaysOnCart;
    }
    

    public static function bannerTransition($id)
    {
        $transition = Option::find($id);

        return $transition->value;
    }

    public static function paynamics_merchant()
    {
        // if (env('APP_ENV') == 'production') {
           
        //     return [
        //         'id' => '00000019121943FC3BD7',
        //         'key' => '6B1198B811715D83148DB4E7FC981A54',
        //         'url' => 'https://testpti.payserv.net/webpayment/Default.aspx'
        //     ];
            
            
        // } else {
            return [
                'id' => '00000019121943FC3BD7',
                'key' => '6B1198B811715D83148DB4E7FC981A54',
                'url' => 'https://testpti.payserv.net/webpayment/Default.aspx'
            ];
        //}
    }

    public static function modals($pageName)
    {
        $pages = PageModal::where('status', 'Active')->where('pages', 'like', '%'.$pageName.'%')->first();

        return $pages;
    }
}
