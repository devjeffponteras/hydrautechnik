<?php

namespace App\Mail;

use App\Helpers\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    public $clientInfo;
    public $emailContent;
    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $clientInfo
     */
    public function __construct($setting, $clientInfo)
    {
        $this->setting = $setting;
        $this->clientInfo = $clientInfo;
    }

    /**
     * pwede
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.inquiry')
            ->subject('Inquiry Received');
    }
}
