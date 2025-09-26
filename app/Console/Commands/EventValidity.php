<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Models\Ecommerce\Coupon;
use \Carbon\Carbon;

class EventValidity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event_validity:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Coupon event validity every minute.';

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
        $coupons = Coupon::whereNotNull('event_date')->where('status','ACTIVE')->where('event_date','<',today())->update(['status' => 'INACTIVE']);
    }
}
