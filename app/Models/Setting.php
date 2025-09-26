<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use SoftDeletes;

    protected $table = 'settings';
    protected $fillable = ['api_key', 'website_name', 'website_favicon', 'company_logo', 'company_favicon', 'company_name', 
                            'google_analytics', 'google_recaptcha_sitekey', 'google_recaptcha_secret', 'data_privacy_title', 
                            'data_privacy_popup_content', 'data_privacy_content', 'mobile_no', 'fax_no', 'tel_no', 'email', 
                            'company_about', 'company_address', 'google_map', 'social_media_accounts', 'copyright', 'user_id',
                            'pickup_is_allowed','delivery_note','review_is_allowed','promo_is_displayed','min_order','min_order_is_allowed','flatrate_is_allowed','delivery_collect_is_allowed', 'coupon_limit', 'coupon_discount_limit', 'contact_us_email_layout', 'cart_notification_duration', 'cart_product_duration', 
                            'accepted_payments',
                            'third_party_signin',
                            'modal_title',
                            'modal_content',
                            'modal_status'
                        ];

    public static function getWebsiteName()
    {
        $data = Setting::where('id',1)->first();

        return $data->website_name;
    }

    public static function getCopyright()
    {
        $data = Setting::where('id',1)->first();

        return $data->copyright;
    }






    // ******** AUDIT LOG ******** //
    // Need to change every model
    static $oldModel;
    static $tableTitle = 'settings';
    static $name = 'name';
    static $unrelatedFields = ['id', 'social_media_accounts', 'user_id', 'min_order', 'promo_is_displayed', 'review_is_allowed', 'pickup_is_allowed', 'delivery_note', 'min_order_is_allowed', 'flatrate_is_allowed', 'delivery_collect_is_allowed', 'accepted_payments', 'third_party_signin', 'created_at', 'updated_at', 'deleted_at'];
    static $logName = [
        'api_key' => 'api key',
        'website_name' => 'website name',
        'website_favicon' => 'website favicon',
        'company_logo' => 'company logo',
        'company_favicon' => 'company favicon',
        'company_name' => 'company name',
        'company_about' => 'company about',
        'company_address' => 'company address', 
        'google_analytics' => 'google analytics',
        'google_map' => 'google map',
        'google_recaptcha_sitekey' => 'google recaptcha site key',
        'google_recaptcha_secret' => 'google recaptcha secret',
        'data_privacy_title' => 'data pivacy title',
        'data_privacy_popup_content' => 'data privacy popup content',
        'data_privacy_content' => 'data privacy content',
        'mobile_no' => 'mobile number',
        'fax_no' => 'fax number',
        'tel_no' => 'telephone number',
        'email' => 'email',
        'copyright' => 'copyright',
        'contact_us_email_layout' => 'contact us email layout',
        'coupon_discount_limit' => 'coupon discount limit',
        'coupon_limit' => 'coupon limit',
        'cart_notification_duration' => 'cart notification duration',
        'cart_product_duration' => 'cart product duration',
        'modal_title' => 'modal title',
        'modal_content' => 'modal content',
        'modal_status' => 'modal status'

    ];
    // END Need to change every model

    public static function boot()
    {
        parent::boot();

        self::updating(function($model) {
            self::$oldModel = $model->fresh();
        });

        self::updated(function($model) {
            $name = $model[self::$name];
            $oldModel = self::$oldModel->toArray();
            foreach ($oldModel as $fieldName => $value) {
                if (in_array($fieldName, self::$unrelatedFields)) {
                    continue;
                }

                $oldValue = $model[$fieldName];
                if ($oldValue != $value) {
                    ActivityLog::create([
                        'log_by' => auth()->id(),
                        'activity_type' => 'update',
                        'dashboard_activity' => 'updated the '. self::$tableTitle .' '. self::$logName[$fieldName],
                        'activity_desc' => 'updated the '. self::$tableTitle .' '. self::$logName[$fieldName] .' of '. $name .' from '. $oldValue .' to '. $value,
                        'activity_date' => date("Y-m-d H:i:s"),
                        'db_table' => $model->getTable(),
                        'old_value' => $oldValue,
                        'new_value' => $value,
                        'reference' => $model->id
                    ]);
                }
            }
        });
    }
}
