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
        try {
            $token_ids = [];
            if (isset($this->payload['users'])) {
                $ids = $this->payload['users'];
//                dd($ids);
                $temp = (new User)->whereIn('id', $ids)
//                ->whereHas('notification_settings', function ($query) {
//                $query->where('push_notification', 1);
//            })
                    ->where('push_token', '!=', NULL)
                    ->pluck('push_token');
                if (!is_null($temp))
                    $token_ids = array_merge($token_ids, $temp->toArray());

            }
            if (count($token_ids) > 0) {
                $this->payload['content']['tokens'] = array_unique($token_ids);
                Notification::sendPushNotification($this->payload['content']);
                Log::info("File push Done:  done", $this->payload);
            }

        }
        catch (\Exception $e){
            Log::info("File push Done:  Errror: ".$e->getMessage());
        }
    }
}
