<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailContent;

    /**
     * CampaignMail constructor.
     * @param $mailContent
     */
    public function __construct( $mailContent )
    {
        $this->mailContent = $mailContent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.campaign')
            ->subject($this->mailContent['subject']);
    }
}
