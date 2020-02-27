<?php
/**
 * Created by PhpStorm.
 * User: kesty
 * Date: 6/15/17
 * Time: 4:30 AM
 */

namespace App\Repositories;

use App\Jobs\SendPushNotification;
;
use App\Providers\PushNotification;
use GuzzleHttp\Client;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

class Notification
{
    use DispatchesJobs;


    /**
     * Send Push notification
     *
     * @param $payload array parameters e.g [to, subject, message, cc, bcc]
     * @return bool
     */
    public static function sendPushNotification($payload)
    {
        return (new PushNotification($payload))->sendPush();
    }

    public function sendPush($payload)
    {
        return  $this->dispatch(new SendPushNotification($payload));
    }

    public static function postPushNotification($uri, $body, $headers)
    {
        $client = new Client();
//        dd($uri,$body,$headers);
        try {
            $method = 'POST';
            $res = $client->request($method, $uri, [
                'headers' => $headers,
                'body' => $body
            ]);
//            Log::info("success");
            return $res->getBody()->getContents();
//            return '';
        } catch (\Exception $e) {
//            Log::info("Error");
//            dd($e);
            return response()->json(['error' => 'Network Error']);
        }

    }



}