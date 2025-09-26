<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Models\Ecommerce\Coupon;
use \Carbon\Carbon;

class CouponValidity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupon_validity:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Coupon validity every minute.';

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
     * @return mixed
     */
    public function handle()
    {
        $coupons = Coupon::whereNotNull('end_date')->where('status','ACTIVE')->get();
        foreach($coupons as $coupon){

            if(isset($coupon->endtime)){
                $time = $coupon->end_time;
            } else {
                $time = '00:00:00';
            }

            $end_datetime = $coupon->end_date.' '.$time;

            if(Carbon::parse(now()->format('Y-m-d H:i')) > Carbon::parse($end_datetime)){
                Coupon::find($coupon->id)->update(['status' => 'INACTIVE']);
            }
        }
    }
}
