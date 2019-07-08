<?php

namespace App\Jobs;

use App\Mail\MailNotify;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEmailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mailDetails;

    /**
     * SendEmailNotification constructor.
     * @param $details
     */
    public function __construct($details)
    {
        $this->mailDetails = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $when = Carbon::now()->addMinutes(2);
        $mailContent = [];

        if (isset($this->mailDetails['subject'])) {
            $mailContent['subject'] = $this->mailDetails['subject'];
        }else{
            $mailContent['subject'] = "Notification from Startev Africa";
        }

        $mailContent['message'] = $this->mailDetails['message'];
        $mailContent['to'] = $this->mailDetails['to'];

        Mail::to($mailContent['to'])->send(new MailNotify($mailContent));
    }
}
