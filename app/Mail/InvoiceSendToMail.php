<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceSendToMail extends Mailable
{
    use Queueable, SerializesModels;
     protected $invoice_details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice_details)
    {
       $this->invoice_details=$invoice_details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $details = $this->invoice_details;

        return $this->view('email.invoiceSendMail', compact('details'));
    }
}
