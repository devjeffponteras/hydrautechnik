<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Helpers\Setting;

class ReorderPointNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reorder_point_notification:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notification to admin if there is an item inventory need to be updated.';

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
        if(Setting::belowReorderTotal() > 0){
            $admins = User::where('role_id', 1)->get();
            foreach($admins as $admin){
                $admin->reorder_point_email_notification();   
            } 
        }
    }
}
