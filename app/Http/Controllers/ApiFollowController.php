<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail\MailController;
use App\Jobs\SendEmailNotification;
use App\Models\Feed;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;

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
        $people = (new ApiFeedsController($this->user, $this->feed))->gatherPeopleToFollow($userId);
        return response()->json(['success' => true, 'people' => $people]);
    }

    public function toggleFollow($userId, $target)
    {

        $user = $this->user->find($userId);
        $user->toggleFollow($target);

        $targetUser = $this->user->find($target);
        $message = "You've stopped following {$targetUser->name}. You'll no longer receive updates from this user";

        if ($user->isFollowing($target)){
            UserNotification::create(['user_id' => $user->id, 'content' => "You are now following ({$targetUser->name})."]);
            $message = "You are now following {$targetUser->name}. You'll now receive updates from this user";
            $mailContent['message'] = $message;
            $mailContent['to'] = $user->email;
            $mailContent['subject'] = "New Follow Activity";
            dispatch(new SendEmailNotification($mailContent));

        }
        return response([
            'message' => $message,
            'people' => (new ApiFeedsController($this->user, $this->feed))->gatherPeopleToFollow($userId),
            'following' => $user->followings()->get(),
            'followers' => $user->followers()->get()
        ]);
    }

}
