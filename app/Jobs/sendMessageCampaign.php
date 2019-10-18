<?php

namespace App\Jobs;

use App\Mail\CampaignMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class sendMessageCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $mailDetails;

    /**
     * sendMessageCampaign constructor.
     * @param $details
     */
    public function __construct( $details )
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
        foreach ($this->mailDetails['recipients'] as $recipient) {
            Mail::to($recipient['email'])->send(new CampaignMail($this->mailDetails));
        }
    }
}
