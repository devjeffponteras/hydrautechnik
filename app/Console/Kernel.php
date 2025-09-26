<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CouponAvailability::class,
        Commands\CouponValidity::class,
        Commands\EventValidity::class,
        Commands\PromoValidity::class,
        Commands\ProductCartNotification::class,
        Commands\ProductCartRemoved::class,
        // Commands\DeleteCartItem::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Check Coupon validity every minute.
        $schedule->command('coupon_validity:cron')
                 ->everyMinute();

        // Check Promo validity every minute.
        $schedule->command('promo_validity:cron')
                 ->everyMinute();

        // Check Coupon Event validity every minute.
        $schedule->command('event_validity:cron')
                 ->everyMinute();

        // Check Coupon start date then set availabity value into 1 (active).
        $schedule->command('coupon_availability:cron')
                 ->everyMinute();

        // Check shopping cart and send email notification to customer once a day.
        $schedule->command('product-cart-notification:cron')
                 ->everyFiveMinutes();
                 //daily

        // Check shopping cartevery minute for inventory / auto delete product on cart.
        $schedule->command('product-cart-removed:cron')
        ->everyFiveMinutes();

        // Check shopping cartevery minute for inventory / auto delete product on cart.
        // $schedule->command('cart-item:cron')
        //          ->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
