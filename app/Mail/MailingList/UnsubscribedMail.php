<?php

namespace App\Mail\MailingList;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnsubscribedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    /**
     * Create a new message instance.
     *
     * @param Setting $setting
     */
    public function __construct($setting)
    {
        $this->setting = $setting;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Unsubscribed to our mailing list')
            ->view('mail.mailing-list.unsubscribed')
            ->text('mail.mailing-list.unsubscribed_plain');
    }
}
