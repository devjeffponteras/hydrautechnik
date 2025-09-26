<?php

namespace App\Mail\MailingList;

use App\Models\{Setting, Campaign};

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    public $campaign;
    public $subscriber;

    /**
     * Create a new message instance.
     *
     * @param Setting $setting
     * @param Campaign $campaign
     */
    public function __construct($setting, Campaign $campaign, $subscriber)
    {
        $this->setting = $setting;
        $this->campaign = $campaign;
        $this->subscriber = $subscriber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->campaign->subject)
            ->view('mail.mailing-list.campaign')
            ->text('mail.mailing-list.campaign_plain');
    }
}
