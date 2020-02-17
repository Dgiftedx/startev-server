<?php
/**
 * Created by PhpStorm.
 * User: kesty
 * Date: 12/8/17
 * Time: 1:05 PM
 */

namespace App\Providers;



use App\Repositories\Notification;

class PushNotification
{

    public static $NewsFeed = 1;
    public static $Messages = 2;


    /**
     * @var null
     */
    /**
     * @const api uri
     */
//    const API_URI = 'https://fcm.googleapis.com/fcm/send';
    const API_URI = 'https://onesignal.com/api/v1/notifications';


    private $to = null;
    private $registration_ids = null;
    /**
     * @var null
     */
    private $notification = null;
    /**
     * @var null
     */
    private $data = null;
    private $headers = null;
    private $payload = null;

    /**
     *
     * @var string
     */
    private $apiKey = '';
    private $apiID = '';

    public function __construct($payload)
    {
        $this->payload = $payload;
        $this->setContent();
    }


    /**
     * @param $title
     * @param $body
     * @return $this
     */
    public function setContent()
    {

//        $this->setApiKey(env('FCM_API_KEY', ""));
        $this->setApiKey(env('ONESIGNAL_API_KEY', ""));
        $this->setApiID(env('ONESIGNAL_API_ID', ""));
        $this->notification = [
            "headings" => array(
                "en" => $this->payload['title']
            ),
//            "body" => $this->payload['body'],
            "contents" => array(
                "en" => $this->payload['body']
            ),
            "ios_sound"=>"notification",
            "android_sound"=>"notification",
            'app_id' => $this->getApiID(),



//            "sound" => "assets/sounds/notification.mp3",
//            "click_action" => "FCM_PLUGIN_ACTIVITY",
//            "icon"=>"fcm_push_icon"
//            "icon" => "notification"
        ];
        if (isset($this->payload['data']))
            $this->setMeta($this->payload['data']);

        if (isset($this->payload['tokens']))
            $this->setRegis($this->payload['tokens']);
        if (isset($this->payload['topic']))
            $this->toTopic($this->payload['topic']);

        $this->setHeaders();

        return $this;
    }


    /**
     * @param $topic
     * @return $this|null
     */

    public function toTopic($topic)
    {
        if (is_array($topic)) {
            return null;
        } else {
            $this->to = '/topics/' . $topic;
        }
        return $this;
    }

    public function setRegis($registration_ids)
    {

            $this->registration_ids = $registration_ids;

        return $this;
    }


    /**
     * @param null $payload
     * @return $this
     */
    public function setMeta($payload = null)
    {
        $this->data = $payload;
        return $this;
    }

    /**
     * @param null $device_token
     * @return $this
     */
    public function setTo($device_token = null)
    {
        $this->to = $device_token;
        return $this;
    }

    /**
     * Send this message via the specified API key.
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey($apiKey = '')
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Send this message via the specified API ID.
     * @param string $apiID
     * @return $this
     */
    public function setApiID($apiID = '')
    {
        $this->apiID = $apiID;
        return $this;
    }

    /**
     * Get the API key for this message (if any)
     * @return string
     */
    public function getApiID()
    {
        return $this->apiID;
    }

    /**
     * Get the API key for this message (if any)
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set Email headers
     */
    private function setHeaders()
    {
        $this->headers = [
//            'Authorization' => 'key=' . $this->getApiKey(),
            'Authorization' => 'Basic ' . $this->getApiKey(),
            'Content-Type' => 'application/json; charset=utf-8',
        ];
    }

    public function getHeaders()
    {
        return $this->headers;
    }


    /**
     * @return string
     */
    public function serialize()
    {
//        $filtered = array_filter([
////            'to' => $this->to,
//            'registration_ids' => $this->registration_ids,
//            'notification' => $this->notification,
//            'data' => $this->data,
//        ]);
        $this->notification['include_player_ids'] = $this->registration_ids;
        $this->notification['data'] = $this->data;
//        $filtered = array_filter([
////            'to' => $this->to,
////            'registration_ids' => $this->registration_ids,
//            'notification' => $this->notification,
//            'data' => $this->data,
//        ]);
        return json_encode($this->notification);
    }


    public function sendPush()
    {
       return Notification::postPushNotification(PushNotification::API_URI,$this->serialize(),
            $this->getHeaders());
//        $this->client->post(PushNotification::API_URI, [
//            'headers' => ,
//            'body' => ,
//        ]);

    }

}