<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Ecommerce\Cart;
use App\Models\Setting;
use Carbon\Carbon;

class ProductCartRemoved extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product-cart-removed:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove product on cart when certaing condition is meet.';

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

        $shoppingCarts = Cart::where('created_at', '<', now()->subHours($setting->cart_product_duration))->get();
        $arr_productCart = [];

        foreach ($shoppingCarts as $cart) {
            $pastHours = now()->diffInHours($cart->created_at);

            if ($pastHours >= $setting->cart_product_duration) {
                $arr_productCart[] = $cart->id;
            }
        }

        // $shoppingCart = Cart::where('created_at', '<', now()->subDays(4))->get();
        // $arr_productCart = [];
        // foreach($shoppingCart as $shcart){
        //     $pasthour = abs(strtotime(today().' 23:59:59.999') - strtotime($shcart->created_at))/(60*60);

        //     if($pasthour >= $setting->cart_product_duration){
        //         array_push($arr_productCart, $shcart->id);
        //     }
        // }

        Cart::whereIn('id', $arr_productCart)->delete();
    }
}
