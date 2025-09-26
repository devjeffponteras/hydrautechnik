<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ecommerce\Cart;

class DeleteCartItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart-item:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete cart items that are older than 24 hours';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Logic to delete cart items older than 24 hours
        $fourDaysAgo = now()->subDays(4);

        $cartsToDelete = Cart::where('created_at', '<', $fourDaysAgo)->get();
        // $cartsToDelete = Cart::where('created_at', '<', now()->subDays(4))->get();
        
        foreach ($cartsToDelete as $cart) {
            $cart->delete();
        }
        
        $this->info('Cart items older than 24 hours have been deleted.');
    }
}
