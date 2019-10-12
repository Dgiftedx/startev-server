<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $mailContent;

    /**
     * MailNotify constructor.
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
        return $this->view('emails.confirm-success')
            ->subject($this->mailContent['subject']);
    }
}
