<?php

namespace App\Jobs;

use App\Models\User;
use App\Repositories\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $payload;
    public function __construct($payload)
    {
        $this->payload=$payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $token_ids = [];
        if (isset($this->payload['users'])){
            $ids = $this->payload['users'];
            if (count($ids)>0) {
                $temp = (new User)->whereIn('id', $ids)
//                ->whereHas('notification_settings', function ($query) {
//                $query->where('push_notification', 1);
//            })
                    ->where('push_token', '!=', NULL);
                foreach ($temp as $tem){
                    if ($tem->push_token) {
                        $temp = $tem->push_token;
                        if (!is_null($temp))
                            $token_ids = array_merge($token_ids, $temp->toArray());
                        if (count($token_ids) > 0) {
                            $this->payload['content']['tokens'] = array_unique($token_ids);
                            Notification::sendPushNotification($this->payload['content']);
                        }
                        Log::info("File push Done:  done", $this->payload);
                    }
                }
            }

        }
    }
}
