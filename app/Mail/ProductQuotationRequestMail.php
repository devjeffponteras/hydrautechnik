<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductQuotationRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $setting;

    public function __construct($product, $setting)
    {
        $this->product  = $product;
        $this->setting = $setting;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->view('mail.product-quotation-request')
            ->subject('Product Quotation Request');
    }
}
