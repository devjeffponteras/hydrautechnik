<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Models\Ecommerce\Coupon;
use \Carbon\Carbon;

class CouponAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupon_availability:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check coupons start date then update availability column value into 1 (active)';

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
        $coupons = Coupon::whereNotNull('start_date')->where('status','ACTIVE')->get();
        foreach($coupons as $coupon){

            if(isset($coupon->endtime)){
                $time = $coupon->end_time;
            } else {
                $time = '00:00:00';
            }

            $startdate = $coupon->start_date.' '.$time;

            if(Carbon::parse(now()->format('Y-m-d H:i:s')) >= Carbon::parse($startdate)){
                Coupon::find($coupon->id)->update(['availability' => 1]);
            }
        }
    }
}
