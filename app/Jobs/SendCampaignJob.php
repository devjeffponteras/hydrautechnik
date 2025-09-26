<?php

namespace App\Jobs;

use App\Helpers\Webfocus\Setting;
use App\Mail\MailingList\CampaignMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subscriber, $campaign, $campaignHistory;

    /**
     * Create a new job instance.
     *
     * @param $subscriber
     * @param $campaign
     * @param $campaignHistory
     */
    public function __construct($subscriber, $campaign, $campaignHistory)
    {
        $this->subscriber = $subscriber;
        $this->campaign = $campaign;
        $this->campaignHistory = $campaignHistory;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->subscriber->email)->send(new CampaignMail(Setting::info(), $this->campaign));

        if (!Mail::failures()) {
            $this->campaignHistory->update(['is_sent' => 1]);
        }
    }
}
