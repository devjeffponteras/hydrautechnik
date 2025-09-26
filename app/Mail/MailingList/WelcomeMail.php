<?php

namespace App\Mail\MailingList;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    public $subscriber;

    /**
     * Create a new message instance.
     *
     * @param Setting $setting
     * @param $subscriber
     */
    public function __construct($setting, $subscriber)
    {
        $this->setting = $setting;
        $this->subscriber = $subscriber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to our mailing list')
            ->view('mail.mailing-list.welcome')
            ->text('mail.mailing-list.welcome_plain');
    }
}

