<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = [
            'id' => 1,
            'api_key' => '',
            'website_name' => 'PRECIOUS PAGES CORP',
            'website_favicon' => 'favicon.ico',
            'company_logo' => 'logo-transparent.png',
            'company_favicon' => '',
            'company_name' => 'PRECIOUS PAGES CORP',
            'company_about' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'company_address' => 'Engineering & Sales: 181-C Sunset Drive, Brookside Hills, Brgy. San Isidro, Cainta, Rizal 1900, Philippines',
            'google_map' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1930.6086602520882!2d121.125328!3d14.586689000000002!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x7d895c3d922926bc!2sExponent%20Controls%20and%20Electrical%20Corporation%20-%20Engineering%20Office!5e0!3m2!1sen!2sph!4v1642996547399!5m2!1sen!2sph',
            'google_recaptcha_sitekey' => '6Lfgj7cUAAAAAJfCgUcLg4pjlAOddrmRPt86tkQK',
            'google_recaptcha_secret' => '6Lfgj7cUAAAAALOaFTbSFgCXpJldFkG8nFET9eRx',
            'data_privacy_title' => 'Privacy-Policy',
            'data_privacy_popup_content' => 'This website uses cookies to ensure you get the best experience.',
            'data_privacy_content' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
            'mobile_no' => '+63 8542-4121',
            'fax_no' => '13232107114',
            'tel_no' => '1800-547-2145',
            'email' => 'ebookstore@phr.com.ph',
            'social_media_accounts' => '',
            'copyright' => '2023-2024',
            'user_id' => '1',

        ];

        DB::table('settings')->insert($setting);
    }
}
