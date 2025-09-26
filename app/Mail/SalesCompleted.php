<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SalesCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $h;
    public $setting;

    public function __construct($sales, $setting)
    {
        $this->h = $sales;
        $this->setting  = $setting;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->view('mail.order-details')
            ->subject('Order # '.$this->h->order_number.' has been placed.');
    }
}
