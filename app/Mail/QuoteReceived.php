<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $qsub;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $qsub)
    {
        $this->name = $name;
        $this->qsub = $qsub;
        $this->subject = 'Test';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.received.quote');
    }
}
