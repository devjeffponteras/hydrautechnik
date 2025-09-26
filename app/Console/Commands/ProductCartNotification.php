<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Ecommerce\Cart;
use App\Models\Setting;
use Carbon\Carbon;

use Illuminate\Support\Facades\Mail;

class ProductCartNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product-cart-notification:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify customer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info('checking cart......');

        $setting = Setting::first();

        $shoppingCarts = Cart::where('created_at', '<', now()->subHours($setting->cart_notification_duration))->get();

        foreach ($shoppingCarts as $cart) {
            $email = $cart->user->email;
            $input['firstname'] = $cart->user->firstname;
            $input['customerId'] = $cart->user_id;
            $input['companyName'] = $setting->company_name;

            Mail::send("mail.cart-notification", $input, function($message) use($email) {
                $message->to($email)
                        ->subject("Shopping Cart Reminder");
            });
        }


        // $shoppingCart = Cart::where('created_at', '<', now())->get();
        // foreach($shoppingCart as $shcart){
        //     $pasthour = abs(strtotime(today().' 23:59:59.999') - strtotime($shcart->created_at))/(60*60);

        //     if($pasthour >= $setting->cart_notification_duration){
        //         $email = $shcart->user->email;
        //         $input['firstname'] = $shcart->user->firstname;
        //         $input['customerId'] = $shcart->user_id;
        //         $input['companyName'] = $setting->company_name;

        //         Mail::send("mail.cart-notification",$input,function($message) use($email){
        //             $message->to($email)
        //             ->subject("Shopping Cart Reminder");
        //         });
        //     }
        // }
    }
}
