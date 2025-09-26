<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Models\Ecommerce\Promo;
use \Carbon\Carbon;

class PromoValidity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo_validity:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Promo validity every minute.';

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
        Promo::where('status','ACTIVE')->where('promo_end','<',Carbon::parse(now()->format('Y-m-d H:i:s')))->update(['status' => 'INACTIVE']);
    }
}
