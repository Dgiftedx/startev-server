<?php

namespace App\Jobs;

use App\Mail\OrderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendOrderNotification implements ShouldQueue
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
        Mail::to($this->mailDetails['email'])->send(new OrderNotification($this->mailDetails));
    }
}
