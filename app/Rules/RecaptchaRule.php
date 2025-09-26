<?php

namespace App\Rules;

use App\Helpers\Setting;

use Illuminate\Contracts\Validation\Rule;

class RecaptchaRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    
     public function passes($attribute, $value)
     {
         $response = Http::get("https://www.google.com/recaptcha/api/siteverify",[
             'secret' => env('RECAPTCHA_SECRET_KEY'),
             'response' => $value
         ]);
           
         return $response->json()["success"];
     }
     
    // public function passes($attribute, $value)
    // {
    //     $secret = Setting::info()->google_recaptcha_secret;
    //     $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$value);
    //     $response = json_decode($response, true);

    //     return isset($response['success']) && $response['success'] == true;
    // }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The Captcha has been expired.';
    }
}
