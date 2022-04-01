<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Gmail extends Mailable
{
    use Queueable, SerializesModels;

    public $packingBaju;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($packingBaju)
    {
        $this->packingBaju = $packingBaju;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Laporan Packing Harian')
            ->view('emails.gmail')
            ->from('Kingsconsult001@gmail.com');
    }
}
