<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail\MailController;
use App\Jobs\SendEmailNotification;
use App\Models\Feed;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use App\Providers\PushNotification;
use App\Repositories\Notification;
use Illuminate\Support\Facades\Log;

class ApiFollowController extends Controller
{
    protected $user;

    protected $feed;

    protected $url;


    /**
     * ApiAccountController constructor.
     * @param User $userModel
     * @param Feed $feedModel
     */
    public function __construct( User $userModel, Feed $feedModel )
    {
        $this->middleware('auth:api');
        $this->user = $userModel;
        $this->feed = $feedModel;
        $this->url = url('/');
    }

    public function follow($userId, $target)
    {
        $user = $this->user->find($userId);
        $user->follow($target);
        $targetUser = $this->user->find($target);

        $notyTitle = "You have a new follower";
        $notyContent = "{$user->name} is following you.";
        UserNotification::create(['user_id' => $userId, 'target_id' => $target, 'title' => $notyTitle, 'content' => $notyContent]);

        $people = (new ApiFeedsController($this->user, $this->feed))->gatherPeopleToFollow($userId);


//        dd($userId, $target);
        //send push notification to target user if offline
        $this->handleOfflineFollowNotification($userId, $target);
        Log::info("Rrecipients ".$user);

        return response()->json(['success' => true, 'people' => $people]);
    }

    //send push notification to target user if offline
    private  function handleOfflineFollowNotification($sender, $recipient)
    {
        $user = User::find($recipient);
        $senderuser = User::find($sender);

        if (!$user->isOnline()) {
        }
        $pushData['content'] = [
            'data' => ['type'=>PushNotification::$Messages],
            'title'=>'You have a new follower',
            'body'=>"{$senderuser->name} is following you."
        ];
        $pushData['users'][] = $recipient;

        (new Notification)->sendPush($pushData);

//        dd($pushData);
    }



    public function toggleFollow($userId, $target)
    {

        $user = $this->user->find($userId);
        $user->toggleFollow($target);

        $targetUser = $this->user->find($target);
        $message = "You just stopped following {$targetUser->name}. You'll no longer receive updates from this user";

        if ($user->isFollowing($target)){
            $notyTitle = "You have a new follower";
            $notyContent = "{$user->name} is following you.";

            UserNotification::create(['user_id' => $userId, 'target_id' => $target, 'title' => $notyTitle, 'content' => $notyContent]);

            $message = "You are now following {$targetUser->name}. You'll now receive updates from this user";

            $mailContent['message'] = $message;
            $mailContent['to'] = $targetUser->email;
            $mailContent['subject'] = "You have a new follower";

            dispatch(new SendEmailNotification($mailContent));


            $this->handleOfflineFollowNotification($userId, $target);

        }
        return response([
            'message' => $message,
            'people' => (new ApiFeedsController($this->user, $this->feed))->gatherPeopleToFollow($userId),
            'following' => $user->followings()->get(),
            'followers' => $user->followers()->get()
        ]);
    }

}
